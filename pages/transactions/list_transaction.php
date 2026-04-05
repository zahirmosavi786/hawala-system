<?php
session_start();
include '../../layout/header.php';
include 'query_transaction.php';
include '../customers/query.php';

// نمونه‌سازی از کلاس‌ها
$query_transaction = new Query_Transaction();
$query_customer = new Query_Customer();

// دریافت لیست تراکنش‌ها از دیتابیس
$transactions = $query_transaction->get_transactions();

// برای نمایش خطاهای PHP (در زمان توسعه)
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>

<!-- بخش عنوان -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2 align-items-center">
      <div class="col-sm-6">
        <h1 class="text-info"><i class="fa fa-exchange-alt"></i> لیست تراکنش‌ها</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-left">
          <li class="breadcrumb-item"><a href="#"><i class="fa fa-home"></i> خانه</a></li>
          <li class="breadcrumb-item active">لیست تراکنش‌ها</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<!-- محتوای اصلی -->
<section class="content">
  <div class="container-fluid">

    <?php 
      if (isset($_SESSION['success_message']) || isset($_SESSION['danger_message'])): 
        if (isset($_SESSION['success_message'])) {
          $message = $_SESSION['success_message'];
          $messageType = "success";
          unset($_SESSION['success_message']); 
        } elseif (isset($_SESSION['danger_message'])) {
          $message = $_SESSION['danger_message'];
          $messageType = "danger";
          unset($_SESSION['danger_message']); 
        }
    ?>
      <div id="alertBox" class="alert alert-<?= $messageType ?> text-center m-3 shadow">
        <i class="fa fa-check-circle"></i>
        <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
      </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0 rounded-3">
      <div class="card-body">
        <div class="table-responsive">
        <table id="example1" class="table table-bordered table-striped">
          <thead class="bg-info text-white">
            <tr>
              <th>#</th>
              <th>مشتری</th>
              <th>مبلغ</th>
              <th>نوع تراکنش</th>
              <th>روش پرداخت</th>
              <th>تاریخ</th>
              <th>عملیات</th>
            </tr>
          </thead>
          <tbody>
            <?php
            function formatMoney($amount) {
                $color = $amount < 0 ? 'text-danger' : 'text-success';
                return "<span class='{$color}'>" . number_format($amount, 0, '.', ',') . " تومان</span>";
            }

            if ($transactions && $transactions->num_rows > 0) {
                $i = 1;
                while ($row = $transactions->fetch_assoc()) {
                    $customer = $query_customer->get_customer_by_id($row['customer_id']);
                    $customer_name = is_array($customer) 
                        ? trim(($customer['name'] ?? '') . ' ' . ($customer['last_name'] ?? '') . ' (' . ($customer['father_name'] ?? '') . ')') 
                        : '---';

                    $type_icon = $row['transaction_type'] === 'واریز' 
                                ? "<i class='fa fa-arrow-down text-success'></i>" 
                                : "<i class='fa fa-arrow-up text-danger'></i>";

                    echo "<tr>
                            <td>{$i}</td>
                            <td>" . htmlspecialchars($customer_name, ENT_QUOTES, 'UTF-8') . "</td>
                            <td style='direction: rtl; text-align: right;'>" . formatMoney($row['amount']) . "</td>
                            <td>{$type_icon} " . htmlspecialchars($row['transaction_type'], ENT_QUOTES, 'UTF-8') . "</td>
                            <td>" . htmlspecialchars($row['payment_method'], ENT_QUOTES, 'UTF-8') . "</td>
                            <td>" . htmlspecialchars($row['transaction_date'], ENT_QUOTES, 'UTF-8') . "</td>
                            <td class='text-center'>
                                <a href='detail_transactions_for_customer.php?customer_id={$row['customer_id']}' class='btn btn-info btn-sm mx-1' title='جزئیات'>
                                  <i class='fa fa-eye'></i>
                                </a>
                                <a href='edit_transaction.php?id={$row['id']}' class='btn btn-warning btn-sm mx-1' title='ویرایش'>
                                  <i class='fa fa-edit'></i>
                                </a>
                                <button class='btn btn-danger btn-sm mx-1' onclick='confirmDelete({$row['id']})' title='حذف'>
                                  <i class='fa fa-trash'></i>
                                </button>
                            </td>
                          </tr>";
                    $i++;
                }
            } else {
                echo "<tr><td colspan='7' class='text-center text-muted'>هیچ تراکنشی ثبت نشده است</td></tr>";
            }
            ?>
          </tbody>
        </table>

        </div>
      </div>
    </div>
  </div>
</section>

<!-- استایل -->
<style>
#alertBox { animation: fadeIn 0.5s ease; transition: opacity 0.8s ease; }
@keyframes fadeIn { from {opacity:0;transform:translateY(-10px);} to {opacity:1;transform:translateY(0);} }
</style>

<!-- اسکریپت حذف با SweetAlert -->
<script>
function confirmDelete(id) {
  Swal.fire({
    title: 'آیا مطمئن هستید؟',
    text: "این عمل قابل بازگشت نیست!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'بله',
    cancelButtonText: 'نه خیر',
    reverseButtons: true,
    customClass: {
      popup: 'small-swal-popup',
      title: 'small-swal-title',
      htmlContainer: 'small-swal-text',
      confirmButton: 'small-swal-btn',
      cancelButton: 'small-swal-btn'
    }
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = 'delete_transaction.php?id=' + id;
    }
  });
}

// استایل SweetAlert
const style = document.createElement('style');
style.innerHTML = `
.small-swal-popup { width: 300px !important; padding: 1rem !important; border-radius: 12px !important; }
.small-swal-title { font-size: 16px !important; }
.small-swal-text { font-size: 13px !important; }
.small-swal-btn { font-size: 13px !important; padding: 4px 10px !important; border-radius: 6px !important; }
@media (max-width: 480px) {
  .small-swal-popup { width: 240px !important; font-size: 13px !important; }
}
`;
document.head.appendChild(style);

// مخفی کردن پیام هشدار
const alertBox = document.getElementById('alertBox');
if (alertBox) {
  setTimeout(() => { 
    alertBox.style.opacity = "0"; 
    setTimeout(() => alertBox.remove(), 800); 
  }, 3000);
}
</script>

<?php include '../../layout/footer.php'; ?>
