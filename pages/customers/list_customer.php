<?php
session_start();
include '../../layout/header.php';
include 'query.php';

// ساختن نمونه از کلاس Query_Customer
$query_customer = new Query_Customer();
$customers = $query_customer->get_customers();
?>

<!-- بخش عنوان -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2 align-items-center">
      <div class="col-sm-6">
        <h1 class="text-info"><i class="fa fa-users"></i> لیست مشتری</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-left">
          <li class="breadcrumb-item"><a href="#"><i class="fa fa-home"></i> خانه</a></li>
          <li class="breadcrumb-item active"><i></i> لیست مشتری</li>
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

        }elseif (isset($_SESSION['danger_message'])) {
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
                    <th>نام و تخلص</th>
                    <th>نام پدر</th>
                    <th>دفتر مربوطه</th>
                    <th>وضعیت مالی</th>
                    <th>جزئیات</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
              <?php
              function formatFinancial($amount) {
                  $negative = $amount < 0;
                  $abs_amount = abs($amount);

                  // جدا کردن قسمت اعشاری
                  $parts = explode('.', number_format($abs_amount, 3, '.', ','));
                  $integer_part = $parts[0];
                  $decimal_part = $parts[1];

                  // رنگ آبی برای کاماها
                  $integer_with_spans = preg_replace_callback('/,/', function($m){
                      return '<span class="text-warning">,</span>';
                  }, $integer_part);

                  $formatted = $integer_with_spans . '.' . $decimal_part;

                  if($negative) $formatted = '- ' . $formatted;

                  return $formatted . ' تومان';
              }

              // در حلقه نمایش
              if ($customers && $customers->num_rows > 0) {
                  $i = 1;
                  while ($row = $customers->fetch_assoc()) {
                      $book_name = $query_customer->get_book_name_by_id($row['book']);

                      // تعیین کلاس رنگ عدد (سبز یا قرمز)
                      $financial_class = $row['financial_status'] < 0 ? 'text-danger' : 'text-success';

                      $financial_status_display = formatFinancial($row['financial_status']);

                      echo "
                      <tr>
                          <td>{$i}</td>
                          <td>" . htmlspecialchars($row['name'] . ' ' . $row['last_name']) . "</td>
                          <td>" . htmlspecialchars($row['father_name']) . "</td>
                          <td>" . htmlspecialchars($book_name) . "</td>
                          <td class='{$financial_class}' style='text-align: right; direction: rtl;'>
                              {$financial_status_display}
                          </td>
                          <td class='text-center'>            
                            <a href='../transactions/detail_transactions_for_customer.php?customer_id={$row['id']}' title='جزئیات'>
                              <i class='fa fa-eye text-info'></i> جزئیات
                            </a>
                          </td>
                          <td class='text-center operations'>
                              <a href='edit_customer.php?id={$row['id']}' class='btn btn-warning btn-sm' title='ویرایش'>
                                  <i class='fa fa-edit'></i> ویرایش
                              </a>
                              <a href='javascript:void(0);'
                                class='btn btn-danger btn-sm'
                                onclick=\"confirmDelete({$row['id']}); return false;\"
                                title='حذف'>
                                <i class='fa fa-trash'></i> حذف
                              </a>
                          </td>
                      </tr>";
                      $i++;
                  }
              } else {
                  echo "<tr><td colspan='6' class='text-center text-muted'>هیچ مشتری موجود نیست</td></tr>";
              }
              ?>

            </tbody>
        </table>

        </div>
      </div>
    </div>
  </div>
</section>




<style>

#alertBox { animation: fadeIn 0.5s ease; transition: opacity 0.8s ease; }
@keyframes fadeIn { from {opacity:0;transform:translateY(-10px);} to {opacity:1;transform:translateY(0);} }
#amountBox { display:none; }
</style>

<script>
// نمایش پیام
const alertBox = document.getElementById('alertBox');
if (alertBox) {
  setTimeout(() => { 
    alertBox.style.opacity = "0"; 
    setTimeout(() => alertBox.remove(), 800); 
  }, 3000);
}
</script>
<!-- اسکریپت حذف امن -->
<script>
function confirmDelete(id) {
  if (confirm('آیا مطمئن هستید که می‌خواهید این مشتری را حذف کنید؟')) {
    window.location.href = 'delete_customer.php?id=' + id;
  }
}
</script>
 <script>
function confirmDelete(id) {
  Swal.fire({
    title: 'آیا مطمئن هستید؟',
    text: "این عمل قابل بازگشت نیست!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'بله حذف شود',
    cancelButtonText: 'لغو',
    reverseButtons: true,

    // 👇 تنظیمات سفارشی برای اندازه و ظاهر
    customClass: {
      popup: 'small-swal-popup',
      title: 'small-swal-title',
      htmlContainer: 'small-swal-text'
      ,
      confirmButton: 'small-swal-btn',
      cancelButton: 'small-swal-btn'
      
    }
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = 'delete_customer.php?id=' + id;
    }
  });
}

// 👇 استایل برای نسخه‌ی کوچک‌تر (درون صفحه)
const style = document.createElement('style');
style.innerHTML = `
.small-swal-popup {
  width: 300px !important;
  padding: 1rem !important;
  border-radius: 12px !important;
}

.small-swal-title {
  font-size: 16px !important;
}

.small-swal-text {
  font-size: 13px !important;
}

.small-swal-btn {
  font-size: 13px !important;
  padding: 4px 10px !important;
  border-radius: 6px !important;
}

/* برای گوشی‌ها */
@media (max-width: 480px) {
  .small-swal-popup {
    width: 240px !important;
    font-size: 13px !important;
  }
}
`;
document.head.appendChild(style);
</script>
<?php include '../../layout/footer.php'; ?>
