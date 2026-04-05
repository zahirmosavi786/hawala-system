<?php
ob_start();
session_start();
include '../../layout/header.php';
include 'query_transaction.php';
include '../customers/query.php';

// ایجاد نمونه کلاس‌ها
$query_customer = new Query_Customer();
$query_transaction = new Query_Transaction();

// دریافت شناسه مشتری
$customer_id = $_GET['customer_id'] ?? '';
if (empty($customer_id) || !($customer = $query_customer->get_customer_by_id($customer_id))) {
    $_SESSION['danger_message'] = "مشتری مورد نظر یافت نشد!";
    header("Location: list_transaction.php");
    exit();
}

// فیلتر تاریخ
$from_date = $_GET['from_date'] ?? '';
$to_date = $_GET['to_date'] ?? '';

// دریافت تراکنش‌ها با فیلتر تاریخ
$transactions = $query_transaction->get_transactions_by_customer($customer_id, $from_date, $to_date);

// محاسبه جمع کل تراکنش‌ها
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
    $balance_class = 'text-success';
} elseif ($balance < 0) {
    $balance_status = 'بدهکار';
    $balance_class = 'text-danger';
} else {
    $balance_status = 'تسویه شده';
    $balance_class = 'text-secondary';
}
?>

<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2 align-items-center">
      <div class="col-sm-6">
        <h1 class="text-primary"><i class="fa fa-user"></i> جزئیات تراکنش‌های مشتری</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-left">
          <li class="breadcrumb-item"><a href="#"><i class="fa fa-home"></i> خانه</a></li>
          <li class="breadcrumb-item active"><i class="fa fa-money-check"></i> تراکنش‌ها</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">

    <!-- مشخصات مشتری -->
    <div class="card shadow-sm rounded mb-3">
      <div class="card-header bg-primary text-white">
        <h4 class="card-title"><i class="fa fa-id-card"></i> مشخصات مشتری</h4>
      </div>
      <div class="card-body row text-sm">
        <div class="col-md-4 mb-2"><i class="fa fa-user me-1"></i> <strong>نام و تخلص:</strong> <?= htmlspecialchars($customer['name'] . ' ' . $customer['last_name']) ?></div>
        <div class="col-md-4 mb-2"><i class="fa fa-phone me-1"></i> <strong>شماره تماس:</strong> <?= htmlspecialchars($customer['phone'] ?? '-') ?></div>
        <div class="col-md-4 mb-2"><i class="fa fa-book me-1"></i> <strong>دفتر:</strong> <?= htmlspecialchars($customer['book_name']) ?></div>
        <div class="col-md-4 mb-2"><i class="fa fa-user-friends me-1"></i> <strong>نام پدر:</strong> <?= htmlspecialchars($customer['father_name']) ?></div>
        <div class="col-md-4 mb-2"><i class="fa fa-user-plus me-1"></i> <strong>معرف:</strong> <?= htmlspecialchars($customer['introducer'] ?? '-') ?></div>
        <div class="col-md-4 mb-2"><i class="fa fa-map-marker-alt me-1"></i> <strong>آدرس:</strong> <?= htmlspecialchars($customer['address'] ?? '-') ?></div>
      </div>
    </div>

    <!-- فیلتر تاریخ -->
    <div class="card shadow-sm rounded mb-3">
      <div class="card-header bg-secondary text-white">
        <h4 class="card-title"><i class="fa fa-filter"></i> فیلتر تراکنش‌ها</h4>
      </div>
      <div class="card-body">
        <form method="get" class="row g-2 align-items-end">
          <input type="hidden" name="customer_id" value="<?= $customer_id ?>">
          <div class="col-md-4">
            <label>از تاریخ</label>
            <input type="date" name="from_date" class="form-control form-control-sm" value="<?= htmlspecialchars($from_date) ?>">
          </div>
          <div class="col-md-4">
            <label>تا تاریخ</label>
            <input type="date" name="to_date" class="form-control form-control-sm" value="<?= htmlspecialchars($to_date) ?>">
          </div>
          <div class="col-md-2">
            <button type="submit" class="btn btn-info btn-sm w-100"><i class="fa fa-search"></i> اعمال</button>
          </div>
          <div class="col-md-2">
            <a href="?customer_id=<?= $customer_id ?>" class="btn btn-warning btn-sm w-100"><i class="fa fa-times"></i> حذف</a>
          </div>
        </form>
      </div>
    </div>

    <!-- جدول تراکنش‌ها -->
    <div class="card shadow-sm rounded mb-3">
      <div class="card-header bg-info text-white">
        <h4 class="card-title"><i class="fa fa-list"></i> لیست تراکنش‌ها</h4>
      </div>
      <div class="card-body table-responsive p-0">
        <table class="table table-bordered table-hover table-striped text-center mb-0">
          <thead class="table-dark small">
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
          <tbody class="small">
            <?php if (!empty($transactions)): ?>
              <?php foreach ($transactions as $index => $t): ?>
                <tr class="<?= $t['transaction_type'] === 'واریز' ? 'table-success' : 'table-danger' ?>">
                  <td><?= $index + 1 ?></td>
                  <td><?= htmlspecialchars($t['transaction_date']) ?></td>
                  <td><?= htmlspecialchars($t['transaction_type']) ?></td>
                  <td class="<?= $t['transaction_type'] === 'واریز' ? 'text-success' : 'text-danger' ?>"><?= number_format($t['amount']) ?></td>
                  <td><?= htmlspecialchars($t['payment_method']) ?></td>
                  <td><?= htmlspecialchars($t['card_from'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($t['card_to'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($t['description'] ?? '-') ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="8">تراکنشی ثبت نشده است.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- جمع تراکنش‌ها -->
    <div class="row text-center mb-4">
      <div class="col-md-4 bg-light p-3 rounded mb-2">
        <strong>کل واریزها:</strong>
        <span class="text-success"><?= number_format($total_deposit) ?> تومان</span>
      </div>
      <div class="col-md-4 bg-light p-3 rounded mb-2">
        <strong>کل برداشت‌ها:</strong>
        <span class="text-danger"><?= number_format($total_withdraw) ?> تومان</span>
      </div>
      <div class="col-md-4 bg-light p-3 rounded mb-2">
        <strong>مانده حساب:</strong>
        <span class="<?= $balance_class ?>"><?= number_format(abs($balance)) ?> تومان (<?= $balance_status ?>)</span>
      </div>
    </div>

    <!-- دکمه‌ها -->
    <div class="text-center mb-4 d-flex justify-content-center gap-3 flex-wrap">
      <a href="list_transaction.php" class="btn btn-secondary btn-sm"><i class="fa fa-arrow-left"></i> بازگشت</a>
      <form method="post" action="export_transaction_pdf.php" target="_blank" class="m-0">
        <input type="hidden" name="customer_id" value="<?= $customer_id ?>">
        <input type="hidden" name="from_date" value="<?= htmlspecialchars($from_date) ?>">
        <input type="hidden" name="to_date" value="<?= htmlspecialchars($to_date) ?>">
        <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-file-pdf"></i> دریافت PDF</button>
      </form>
    </div>

  </div>
</section>

<style>
.table-success { background-color: #d4edda !important; }
.table-danger { background-color: #f8d7da !important; }
.text-success, .text-danger { font-weight: bold; }
.card-body.row > div i { color: #555; }
</style>

<?php ob_end_flush(); include '../../layout/footer.php'; ?>
