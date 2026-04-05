<?php
ob_start();
session_start();
include '../../layout/header.php';
include 'query_user.php';

$query_user = new Query_User();
$missing = [];
$messages = [];

// توابع کمکی
function generatePassword(int $length = 12): string {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[random_int(0, strlen($chars) - 1)];
    }
    return $password;
}

function generateUsername(string $name, string $lastName, Query_User $queryUser): string {
    $base = strtolower($name . "." . $lastName);
    $username = $base;
    $counter = 1;
    while ($queryUser->check_username_exists($username)) {
        $username = $base . $counter;
        $counter++;
    }
    return $username;
}

// دریافت ورودی‌ها
$name       = isset($_POST['name']) ? trim($_POST['name']) : '';
$lastName   = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
$phone      = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$username   = isset($_POST['username']) ? trim($_POST['username']) : '';
$password   = isset($_POST['password']) ? trim($_POST['password']) : '';
$password2  = isset($_POST['password2']) ? trim($_POST['password2']) : '';
$role       = isset($_POST['role']) ? trim($_POST['role']) : '';
$status     = isset($_POST['status']) ? trim($_POST['status']) : 'active';

// پردازش فرم
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach (['username', 'password', 'password2', 'name', 'role'] as $field) {
        if (empty($$field)) $missing[] = $field;
    }

    // بررسی مطابقت رمز عبور
    if (!in_array('password', $missing) && !in_array('password2', $missing) && $password !== $password2) {
        $missing[] = 'password_mismatch';
        $_SESSION['danger_message'] = "رمزهای عبور مطابقت ندارند!";
    }

    // بررسی یکتا بودن نام کاربری
    if (!in_array('username', $missing) && $query_user->check_username_exists($username)) {
        $missing[] = 'username';
        $_SESSION['danger_message'] = "نام کاربری قبلاً استفاده شده است!";
    }

    // ثبت کاربر
    if (empty($missing)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        if ($query_user->insert_user($username, $hashedPassword, $name, $lastName, $phone, $role, $status)) {
            $_SESSION['success_message'] = "کاربر با موفقیت ثبت شد!";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $_SESSION['danger_message'] = "خطا در ثبت اطلاعات!";
        }
    }
}

// مدیریت پیام‌ها
if (!empty($_SESSION['success_message'])) {
    $messages['success'] = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
if (!empty($_SESSION['danger_message'])) {
    $messages['danger'] = $_SESSION['danger_message'];
    unset($_SESSION['danger_message']);
}
?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="text-info"><i class="fa fa-user-plus"></i> ثبت کاربر جدید</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-left">
                    <li class="breadcrumb-item"><a href="#"><i class="fa fa-home"></i> خانه</a></li>
                    <li class="breadcrumb-item active"><i class="fa fa-user"></i> ثبت کاربر</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <?php foreach ($messages as $type => $msg): ?>
            <div id="alertBox" class="alert alert-<?= $type ?> text-center m-3 shadow fade show">
                <i class="fa fa-check-circle"></i> <?= htmlspecialchars($msg, ENT_QUOTES) ?>
            </div>
        <?php endforeach; ?>

        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-header bg-info text-white">
                        <h3 class="card-title"><i class="fa fa-user"></i> اطلاعات کاربر</h3>
                    </div>

                    <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                        <div class="card-body">

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label>نام</label>
                                    <input type="text" name="name" class="form-control" placeholder="نام کاربر را وارد کنید"
                                           value="<?= htmlspecialchars($name, ENT_QUOTES) ?>">
                                    <?php if (in_array('name', $missing)): ?>
                                        <span class="text-danger small">این فیلد ضروری است</span>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-4">
                                    <label>تخلص</label>
                                    <input type="text" name="last_name" class="form-control" placeholder="تخلص / فامیلی کاربر"
                                           value="<?= htmlspecialchars($lastName, ENT_QUOTES) ?>">
                                </div>

                                <div class="col-md-4">
                                    <label>شماره تماس (اختیاری)</label>
                                    <input type="text" name="phone" class="form-control" placeholder="مثلاً 0935xxxxxxx"
                                           value="<?= htmlspecialchars($phone, ENT_QUOTES) ?>">
                                </div>
                            </div>

                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <label>نام کاربری</label>
                                    <input type="text" name="username" class="form-control" placeholder="مثال: sample.user"
                                           value="<?= htmlspecialchars($username, ENT_QUOTES) ?>">
                                    <?php if (in_array('username', $missing)): ?>
                                        <span class="text-danger small">نام کاربری ضروری یا تکراری است</span>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-3">
                                    <label>رمز عبور</label>
                                    <input type="password" name="password" class="form-control" placeholder="رمز عبور">
                                    <?php if (in_array('password', $missing)): ?>
                                        <span class="text-danger small">این فیلد ضروری است</span>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-3">
                                    <label>تکرار رمز عبور</label>
                                    <input type="password" name="password2" class="form-control" placeholder="تکرار رمز عبور">
                                    <?php if (in_array('password2', $missing) || in_array('password_mismatch', $missing)): ?>
                                        <span class="text-danger small">رمز عبور مطابقت ندارد یا خالی است</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <label>نقش کاربر</label>
                                    <select name="role" class="form-control select2">
                                        <option value="">-- انتخاب نقش --</option>
                                        <option value="admin" <?= ($role == 'admin') ? 'selected' : '' ?>>مدیر</option>
                                        <option value="cashier" <?= ($role == 'cashier') ? 'selected' : '' ?>>صندوقدار</option>
                                        <option value="viewer" <?= ($role == 'viewer') ? 'selected' : '' ?>>مشاهده‌کننده</option>
                                    </select>
                                    <?php if (in_array('role', $missing)): ?>
                                        <span class="text-danger small">انتخاب نقش الزامی است</span>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-6">
                                    <label>وضعیت حساب</label>
                                    <select name="status" class="form-control select2">
                                        <option value="active" <?= ($status == 'active') ? 'selected' : '' ?>>فعال</option>
                                        <option value="inactive" <?= ($status == 'inactive') ? 'selected' : '' ?>>غیرفعال</option>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="card-footer text-center">
                            <button type="submit" class="btn btn-success px-4">ذخیره</button>
                            <button type="reset" class="btn btn-secondary px-4">پاک کردن</button>
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
document.addEventListener('DOMContentLoaded', function () {
    const alertBox = document.getElementById("alertBox");
    if (alertBox) setTimeout(() => { alertBox.style.opacity="0"; setTimeout(() => alertBox.remove(),800); }, 3000);
});
</script>

<?php ob_end_flush(); include '../../layout/footer.php'; ?>
