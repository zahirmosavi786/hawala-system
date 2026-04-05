<?php
session_start();
include '../../layout/header.php';
include 'query.php';

// ساختن نمونه از کلاس Query
$query = new Query();
$books = $query->get_books();
?>

<!-- بخش عنوان صفحه -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2 align-items-center">
      <div class="col-sm-6">
        <h1 class="font-weight-bold text-primary">
          <i class="fas fa-book"></i> لیست دفترها
        </h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-left">
          <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i> خانه</a></li>
          <li class="breadcrumb-item active">لیست دفترها</li>
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
      <div class="card-header bg-gradient-primary text-dark d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0"> فهرست دفترهای موجود</h3>
        <a href="add_book.php" class="btn btn-light btn-sm">
          افزودن دفتر جدید
        </a>
      </div>

      <div class="card-body">
      <div class="table-responsive">
        <table id="example1" class="table table-bordered table-striped">
          <thead class="bg-info text-white">
            <tr>
              <th>#</th>
              <th>نام دفتر</th>
              <th>تاریخ ثبت (هجری شمسی)</th>
              <th>ویرایش</th>
              <th>حذف</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if ($books && $books->num_rows > 0) {
                $i = 1;
                while ($row = $books->fetch_assoc()) {
                    $jalali_date = $query->gregorianToJalali($row['created_at']);
                    echo "
                      <tr>
                        <td>{$i}</td>
                        <td>" . htmlspecialchars($row['book_name']) . "</td>
                        <td>{$jalali_date}</td>
                        <td>
                          <a href='edit_book.php?id={$row['id']}' class='btn btn-warning btn-sm'>
                            </i> ویرایش
                          </a>
                        </td>
                        <td>
                          <a href='javascript:void(0);'
                             class='btn btn-danger btn-sm'
                             onclick=\"confirmDelete({$row['id']}); return false;\">
                             حذف
                          </a>
                        </td>
                      </tr>";
                    $i++;
                }
            } else {
                echo "<tr><td colspan='5' class='text-center text-muted'>هیچ دفتری موجود نیست</td></tr>";
            }
            ?>
          </tbody>
        </table>
         </dive>
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
  if (confirm('آیا مطمئن هستید که می‌خواهید این دفتر را حذف کنید؟')) {
    window.location.href = 'delete_book.php?id=' + id;
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
    confirmButtonText: 'بله',
    cancelButtonText: 'نه خیر',
    reverseButtons: true,

    // 👇 تنظیمات سفارشی برای اندازه و ظاهر
    customClass: {
      popup: 'small-swal-popup',
      title: 'small-swal-title',
      htmlContainer: 'small-swal-text',
      confirmButton: 'small-swal-btn',
      cancelButton: 'small-swal-btn'
      
    }
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = 'delete_book.php?id=' + id;
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
