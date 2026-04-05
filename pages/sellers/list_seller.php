<?php
session_start();
include '../../layout/header.php';
include 'query_seller.php';

// ساختن نمونه از کلاس Query_Seller
$query_seller = new Query_Seller();
$sellers = $query_seller->get_sellers();
?>

<!-- بخش عنوان -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2 align-items-center">
      <div class="col-sm-6">
        <h1 class="text-warning"><i class="fa fa-store"></i> لیست فروشندگان</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-left">
          <li class="breadcrumb-item"><a href="#"><i class="fa fa-home"></i> خانه</a></li>
          <li class="breadcrumb-item active"><i></i> لیست فروشندگان</li>
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
            <thead class="bg-warning text-white">
                <tr>
                    <th>#</th>
                    <th>نام و تخلص</th>
                    <th>نام پدر</th>
                    <th>شماره موبایل</th>
                    <th>دفتر مربوطه</th>
                    <th>معرف</th>
                    <th>آدرس</th>
                    <th>توضیح</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
              <?php
              if ($sellers && $sellers->num_rows > 0) {
                  $i = 1;
                  while ($row = $sellers->fetch_assoc()) {
                      echo "
                      <tr>
                          <td>{$i}</td>
                          <td>" . htmlspecialchars($row['name'] . ' ' . $row['last_name']) . "</td>
                          <td>" . htmlspecialchars($row['father_name']) . "</td>
                          <td>" . htmlspecialchars($row['phone']) . "</td>
                          <td>" . htmlspecialchars($row['book']) . "</td>
                          <td>" . htmlspecialchars($row['introducer']) . "</td>
                          <td>" . htmlspecialchars($row['address']) . "</td>
                          <td>" . htmlspecialchars($row['description']) . "</td>
                          <td class='text-center operations'>
                              <a href='edit_seller.php?id={$row['id']}' class='btn btn-warning btn-sm' title='ویرایش'>
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
                  echo "<tr><td colspan='7' class='text-center text-muted'>هیچ فروشنده‌ای موجود نیست</td></tr>";
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
</style>

<script>
// پیام موفقیت یا خطا
const alertBox = document.getElementById('alertBox');
if (alertBox) {
  setTimeout(() => { 
    alertBox.style.opacity = "0"; 
    setTimeout(() => alertBox.remove(), 800); 
  }, 3000);
}

// هشدار حذف با SweetAlert2
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
      window.location.href = 'delete_seller.php?id=' + id;
    }
  });
}

// استایل SweetAlert کوچک‌تر
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
</script>

<?php include '../../layout/footer.php'; ?>
