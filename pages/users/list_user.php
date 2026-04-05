<?php
session_start();
include '../../layout/header.php';
include 'query_user.php';

// ساخت نمونه از کلاس Query_User
$query_user = new Query_User();
$users = $query_user->get_users(); // تابعی که تمام کاربران را می‌آورد
?>

<!-- بخش عنوان -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2 align-items-center">
      <div class="col-sm-6">
        <h1 class="text-info"><i class="fa fa-users"></i> لیست کاربران</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-left">
          <li class="breadcrumb-item"><a href="#"><i class="fa fa-home"></i> خانه</a></li>
          <li class="breadcrumb-item active">لیست کاربران</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<!-- محتوای اصلی -->
<section class="content">
  <div class="container-fluid">

    <!-- نمایش پیام موفقیت یا خطا -->
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
                    <th>نام و تخلص</th>
                    <th>نام کاربری</th>
                    <th>شماره تماس</th>
                    <th>نقش</th>
                    <th>وضعیت</th>
                    <th>تاریخ ایجاد</th>
                    <th>آخرین ورود</th>
                    <th>ویرایش</th>
                    <th>حذف</th>
                </tr>
            </thead>
            <tbody>
              <?php
              if ($users && $users->num_rows > 0) {
                  $i = 1;
                  while ($row = $users->fetch_assoc()) {
                      echo "<tr>
                              <td>{$i}</td>
                              <td>" . htmlspecialchars($row['name'] . ' ' . $row['last_name']) . "</td>
                              <td>" . htmlspecialchars($row['username']) . "</td>
                              <td>" . htmlspecialchars($row['phone']) . "</td>
                              <td>" . htmlspecialchars($row['role']) . "</td>
                              <td>" . htmlspecialchars($row['status']) . "</td>
                              <td>" . htmlspecialchars($row['created_at']) . "</td>
                              <td>" . htmlspecialchars($row['last_login']) . "</td>
                              <td class='text-center'>
                                  <a href='edit_user.php?id={$row['id']}' class='btn btn-warning btn-sm' title='ویرایش'>
                                      <i class='fa fa-edit'></i> ویرایش
                                  </a>
                                </td>
                                <td>
                                  <a href='javascript:void(0);' class='btn btn-danger btn-sm'
                                     onclick=\"confirmDelete({$row['id']}); return false;\" title='حذف'>
                                     <i class='fa fa-trash'></i> حذف
                                  </a>
                              </td>
                            </tr>";
                      $i++;
                  }
              } else {
                  echo "<tr><td colspan='11' class='text-center text-muted'>هیچ کاربری موجود نیست</td></tr>";
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
    reverseButtons: true
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = 'delete_user.php?id=' + id;
    }
  });
}
</script>

<?php include '../../layout/footer.php'; ?>