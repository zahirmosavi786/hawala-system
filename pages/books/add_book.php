<?php
ob_start();
include '../../layout/header.php';
include 'query.php';

// ساختن نمونه از کلاس
$query = new Query();

// پیام‌ها
$message = '';
$messageType = '';
$missing = [];

// بررسی پارامتر success برای نمایش پیام بعد از redirect
if (isset($_GET['success'])) {
    $message = "دفتر با موفقیت ایجاد شد!";
    $messageType = "success";
}

// وقتی فرم ارسال شد
if (isset($_POST['submit'])) {
    $name = trim($_POST['name'] ?? '');
    $date = trim($_POST['date'] ?? '');

    // بررسی فیلدهای ضروری
    if (empty($name)) $missing[] = 'name';
    if (empty($date)) $missing[] = 'date';

    if (empty($missing)) {
        if ($query->insert_book($name, $date)) {
           // ذخیره موفق، ایجاد پیام در session
            session_start();
            $_SESSION['success_message'] = "دفتر با موفقیت ثبت شد!";
            // ریدایرکت به همان صفحه بدون query string
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
           session_start();
            $_SESSION['danger_message'] = "خطا در ذخیره اطلاعات!";
        }
    }
}
?>

<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2 align-items-center">
      <div class="col-sm-6">
        <h1 class="text-info"><i class="fa fa-book"></i> فرم ایجاد دفتر</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-left">
          <li class="breadcrumb-item"><a href="#"><i class="fa fa-home"></i> خانه</a></li>
          <li class="breadcrumb-item active"><i class="fa fa-book-open"></i> ایجاد دفتر</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">

    <!-- پیام‌ها -->
     <?php 
      session_start();
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

    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card shadow-lg border-0 rounded-3">
          <div class="card-header bg-info text-white">
            <h3 class="card-title"><i class="fa fa-plus-circle"></i> افزودن دفتر جدید</h3>
          </div>

          <!-- فرم -->
          <form id="bookForm" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="card-body">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label"><i class="fa fa-book"></i> نام دفتر</label>
                  <input type="text" name="name" class="form-control" placeholder="نام دفتر را وارد کنید"
                         value="<?= htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES) ?>">
                  <?php if (in_array('name', $missing)): ?>
                    <span class="text-danger" style="font-size:13px;">این فیلد ضروری است</span>
                  <?php endif; ?>
                </div>
                <div class="col-md-6">
                  <label class="form-label"><i class="fa fa-calendar"></i> تاریخ ایجاد</label>
                  <input type="date" name="date" class="form-control"
                         value="<?= htmlspecialchars($_POST['date'] ?? '', ENT_QUOTES) ?>">
                  <?php if (in_array('date', $missing)): ?>
                    <span class="text-danger" style="font-size:13px;">این فیلد ضروری است</span>
                  <?php endif; ?>
                </div>
              </div>
            </div>

            <div class="card-footer text-center">
              <button type="submit" name="submit" class="btn btn-success px-4">
                <i class="fa fa-save"></i> ذخیره
              </button>
              <button type="reset" class="btn btn-secondary px-4">
                <i class="fa fa-undo"></i> پاک کردن
              </button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</section>

<style>
#alertBox {
  animation: fadeIn 0.5s ease;
  transition: opacity 0.8s ease;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
  const alertBox = document.getElementById("alertBox");
  const form = document.getElementById("bookForm");

  if (alertBox) {
    // بعد از 3 ثانیه پیام را محو کن
    setTimeout(() => {
      alertBox.style.opacity = "0";
      setTimeout(() => alertBox.remove(), 800);
    }, 3000);
  }
});
</script>

<?php 
ob_end_flush();
include '../../layout/footer.php';
?>
