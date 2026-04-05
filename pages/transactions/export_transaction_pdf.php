<?php
session_start();
require_once '../../vendor/autoload.php';
include 'query_transaction.php';
include '../customers/query.php';

use Mpdf\Mpdf;

// بررسی ورودی‌ها
$customer_id = $_POST['customer_id'] ?? '';
$from_date   = $_POST['from_date'] ?? '';
$to_date     = $_POST['to_date'] ?? '';

$query_customer    = new Query_Customer();
$query_transaction = new Query_Transaction();

// بررسی صحت مشتری
$customer = $query_customer->get_customer_by_id($customer_id);
if (!$customer) {
    die("مشتری یافت نشد!");
}

// دریافت تراکنش‌ها
$transactions = $query_transaction->get_transactions_by_customer($customer_id, $from_date, $to_date);

// محاسبه مجموع‌ها
$total_deposit = 0;
$total_withdraw = 0;
foreach ($transactions as $t) {
    if ($t['transaction_type'] === 'واریز') $total_deposit += $t['amount'];
    elseif ($t['transaction_type'] === 'برداشت') $total_withdraw += $t['amount'];
}
$balance = $total_deposit - $total_withdraw;

// تعیین وضعیت مانده حساب
if ($balance > 0) {
    $balance_status = 'طلبکار';
    $balance_color = 'green';
} elseif ($balance < 0) {
    $balance_status = 'بدهکار';
    $balance_color = 'red';
} else {
    $balance_status = 'تسویه شده';
    $balance_color = 'gray';
}

// ایجاد PDF
$mpdf = new Mpdf([
    'mode' => 'utf-8',
    'format' => 'A4',
    'default_font' => 'dejavusans',
    'margin_top' => 15,
    'margin_bottom' => 15,
]);

// استایل‌ها
$stylesheet = "
body { direction: rtl; font-family: 'dejavusans'; }
h1,h2,h3 { text-align: center; color: #333; }
table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 12px; }
th, td { border: 1px solid #999; padding: 6px; text-align: center; }
th { background-color: #222; color: white; }
.table-success { background-color: #d4edda; }
.table-danger { background-color: #f8d7da; }
.summary { margin-top: 20px; font-weight: bold; font-size: 13px; }
";
$mpdf->WriteHTML($stylesheet, 1);

// محتوای PDF
$html = "
<h2>گزارش تراکنش‌های مشتری</h2>
<p><strong>نام و تخلص:</strong> {$customer['name']} {$customer['last_name']}<br>
<strong>شماره تماس:</strong> {$customer['phone']}<br>
<strong>دفتر:</strong> {$customer['book_name']}<br>
<strong>دوره زمانی:</strong> " . 
    (!empty($from_date) ? "از {$from_date}" : "از ابتدا") . " تا " . 
    (!empty($to_date) ? "{$to_date}" : "اکنون") . "</p>

<table>
<thead>
<tr>
    <th>#</th>
    <th>تاریخ</th>
    <th>نوع تراکنش</th>
    <th>مبلغ</th>
    <th>نوع پرداخت</th>
    <th>از کارت</th>
    <th>به کارت</th>
    <th>توضیحات</th>
</tr>
</thead>
<tbody>
";

if (!empty($transactions)) {
    foreach ($transactions as $i => $t) {
        $rowClass = $t['transaction_type'] === 'واریز' ? 'table-success' : 'table-danger';
        $amountColor = $t['transaction_type'] === 'واریز' ? 'green' : 'red';
        $html .= "
        <tr class='{$rowClass}'>
            <td>" . ($i + 1) . "</td>
            <td>{$t['transaction_date']}</td>
            <td>{$t['transaction_type']}</td>
            <td style='color: {$amountColor}; font-weight:bold;'>" . number_format($t['amount']) . "</td>
            <td>{$t['payment_method']}</td>
            <td>" . ($t['card_from'] ?? '-') . "</td>
            <td>" . ($t['card_to'] ?? '-') . "</td>
            <td>" . ($t['description'] ?? '-') . "</td>
        </tr>
        ";
    }
} else {
    $html .= "<tr><td colspan='8'>تراکنشی یافت نشد.</td></tr>";
}

$html .= "
</tbody>
</table>

<div class='summary'>
<p>کل واریزها: <span style='color:green'>" . number_format($total_deposit) . " تومان</span></p>
<p>کل برداشت‌ها: <span style='color:red'>" . number_format($total_withdraw) . " تومان</span></p>
<p>مانده حساب: <span style='color:{$balance_color}'>" . number_format(abs($balance)) . " تومان ({$balance_status})</span></p>
</div>
";

$mpdf->WriteHTML($html);
$mpdf->Output("transactions_{$customer_id}.pdf", 'I'); // 'I' = نمایش در مرورگر
exit;
?>
