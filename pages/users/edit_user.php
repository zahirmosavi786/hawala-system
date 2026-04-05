<?php
ob_start();
session_start();
include '../../layout/header.php';
include 'query_user.php';

$query_user = new Query_User();

// گرفتن ID از URL
$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: list_user.php");
    exit();
}

// دریافت اطلاعات کاربر
$user = $query_user->get_user_by_id($id);
if (!$user) {
    echo "<div class='alert alert-danger text-center m-3'>کاربر مورد نظر یافت نشد!</div>";
    include '../../layout/footer.php';
    exit();
}

$missing = [];
$message = '';
$messageType = '';

// اگر فرم ارسال شد
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $role = trim($_POST['role'] ?? '');
    $status = trim($_POST['status'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');
    $description = trim($_POST['description'] ?? '');

    // بررسی فیلدهای ضروری
    $required = ['name','last_name','username','role','status'];
    foreach ($required as $field) {
        if (empty($$field)) $missing[] = $field;
    }

    // بررسی تطابق رمز عبور
    if (!empty($password) && $password !== $confirm_password) {
        $missing[] = 'password_mismatch';
    }

    // بررسی یکتا بودن نام کاربری
    if (!in_array('username', $missing) && $query_user->check_username_exists_edit($username, $id)) {
        $missing[] = 'username_taken';
    }

    if (empty($missing)) {
        $hashedPassword = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : $user['password'];
        if ($query_user->update_user(
            $id, $username, $hashedPassword, $name, $last_name, $phone, $role, $status, $description
        )) {
            $_SESSION['success_message'] = "اطلاعات کاربر با موفقیت ویرایش شد!";
            header("Location: edit_user.php?id=$id");
            exit();
        } else {
            $_SESSION['danger_message'] = "خطا در ذخیره اطلاعات!";
        }
    }
}
?>

<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2 align-items-center">
      <div class="col-sm-6">
        <h1 class="text-primary"><i class="fa fa-user-edit"></i> ویرایش کاربر</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-left">
          <li class="breadcrumb-item"><a href="#"><i class="fa fa-home"></i> خانه</a></li>
          <li class="breadcrumb-item active">ویرایش کاربر</li>
        </ol>
      </div>
    </div>
  </div>
</section>

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
          <?= htmlspecialchars($message, ENT_QUOTES) ?>
      </div>
    <?php endif; ?>

    <div class="row justify-content-center">
      <div class="col-md-10">
        <div class="card shadow-lg border-0 rounded-3">
          <div class="card-header bg-primary text-white">
            <h3 class="card-title"><i class="fa fa-user-edit"></i> اطلاعات کاربر</h3>
          </div>

          <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . urlencode($id); ?>" method="post">
            <div class="card-body">

              <div class="row g-3">
                <div class="col-md-6">
                  <label>نام</label>
                  <input type="text" name="name" class="form-control" 
                         value="<?= htmlspecialchars($_POST['name'] ?? $user['name'], ENT_QUOTES) ?>">
                  <?php if (in_array('name', $missing)): ?><span class="text-danger small">ضروری</span><?php endif; ?>
                </div>

                <div class="col-md-6">
                  <label>تخلص</label>
                  <input type="text" name="last_name" class="form-control" 
                         value="<?= htmlspecialchars($_POST['last_name'] ?? $user['last_name'], ENT_QUOTES) ?>">
                  <?php if (in_array('last_name', $missing)): ?><span class="text-danger small">ضروری</span><?php endif; ?>
                </div>
              </div>

              <div class="row g-3 mt-2">
                <div class="col-md-6">
                  <label>نام کاربری</label>
                  <input type="text" name="username" class="form-control" 
                         value="<?= htmlspecialchars($_POST['username'] ?? $user['username'], ENT_QUOTES) ?>">
                  <?php if (in_array('username', $missing) || in_array('username_taken', $missing)): ?>
                    <span class="text-danger small"><?= in_array('username_taken', $missing) ? 'این نام کاربری قبلاً استفاده شده' : 'ضروری' ?></span>
                  <?php endif; ?>
                </div>

                <div class="col-md-6">
                  <label>شماره تماس</label>
                  <input type="text" name="phone" class="form-control" 
                         value="<?= htmlspecialchars($_POST['phone'] ?? $user['phone'], ENT_QUOTES) ?>">
                </div>
              </div>

              <div class="row g-3 mt-2">
                <div class="col-md-6">
                  <label>رمز عبور (در صورت تغییر)</label>
                  <input type="password" name="password" class="form-control">
                </div>

                <div class="col-md-6">
                  <label>تکرار رمز عبور</label>
                  <input type="password" name="confirm_password" class="form-control">
                  <?php if (in_array('password_mismatch', $missing)): ?>
                    <span class="text-danger small">رمز عبور مطابقت ندارد</span>
                  <?php endif; ?>
                </div>
              </div>

              <div class="row g-3 mt-2">
                <div class="col-md-6">
                  <label>نقش کاربر</label>
                  <select name="role" class="form-control">
                    <option value="">-- انتخاب نقش --</option>
                    <option value="admin" <?= (($user['role'] ?? '') == 'admin') ? 'selected' : '' ?>>مدیر</option>
                    <option value="cashier" <?= (($user['role'] ?? '') == 'cashier') ? 'selected' : '' ?>>صندوقدار</option>
                    <option value="viewer" <?= (($user['role'] ?? '') == 'viewer') ? 'selected' : '' ?>>مشاهده‌کننده</option>
                  </select>
                  <?php if (in_array('role', $missing)): ?><span class="text-danger small">ضروری</span><?php endif; ?>
                </div>

                <div class="col-md-6">
                  <label>وضعیت</label>
                  <select name="status" class="form-control">
                    <option value="active" <?= (($user['status'] ?? '') == 'active') ? 'selected' : '' ?>>فعال</option>
                    <option value="inactive" <?= (($user['status'] ?? '') == 'inactive') ? 'selected' : '' ?>>غیرفعال</option>
                  </select>
                  <?php if (in_array('status', $missing)): ?><span class="text-danger small">ضروری</span><?php endif; ?>
                </div>
              </div>

            </div>

            <div class="card-footer text-center">
              <button type="submit" class="btn btn-success px-4">ذخیره تغییرات</button>
              <a href="list_user.php" class="btn btn-secondary px-4">بازگشت</a>
            </div>

          </form>
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
document.addEventListener("DOMContentLoaded", () => {
  const alertBox = document.getElementById("alertBox");
  if (alertBox) setTimeout(() => { alertBox.style.opacity = "0"; setTimeout(() => alertBox.remove(), 800); }, 3000);
});
</script>

<?php ob_end_flush(); include '../../layout/footer.php'; ?>
