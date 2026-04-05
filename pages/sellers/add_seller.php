<?php
ob_start();
session_start();
include '../../layout/header.php';
include 'query_seller.php';
// include '../books/query.php';

// ایجاد نمونه از کلاس‌ها
// $query = new Query();
// $books = $query->get_books();
$query_seller = new Query_Seller();

// متغیرها
$message = '';
$messageType = '';
$missing = [];


// اگر فرم ارسال شده
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $father_name = trim($_POST['father_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $introducer = trim($_POST['introducer'] ?? '');
    $book = trim($_POST['book'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $description = trim($_POST['description'] ?? '');

    // فیلدهای ضروری
    $required = ['name', 'last_name', 'book'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) $missing[] = $field;
    }


    // اگر هیچ فیلدی خالی نبود
    if (empty($missing)) {  
        // درج در دیتابیس
        if ($query_seller->insert_seller(
            $name, $last_name, $father_name, $phone, 
            $introducer, $book, $address, $description
        )) {
           // ذخیره موفق، ایجاد پیام در session        
            $_SESSION['success_message'] = "فروشنده با موفقیت ثبت شد!";
            // ریدایرکت به همان صفحه بدون query string
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $_SESSION['danger_message'] = "خطا در ذخیره اطلاعات!";
        }
    } 
}
?>

<!-- بخش عنوان -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2 align-items-center">
      <div class="col-sm-6">
        <h1 class="text-info"><i class="fa fa-user-plus"></i> فرم فروشنده</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-left">
          <li class="breadcrumb-item"><a href="#"><i class="fa fa-home"></i> خانه</a></li>
          <li class="breadcrumb-item active"><i class="fa fa-user"></i> فرم فروشنده</li>
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
      <div class="col-md-10">
        <div class="card shadow-lg border-0 rounded-3">
          <div class="card-header bg-info text-white">
            <h3 class="card-title"><i class="fa fa-user-circle"></i> ایجاد فروشنده جدید</h3>
          </div>

          <form id="sellerForm" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="card-body">

              <div class="row g-3">
                <div class="col-md-4">
                  <label>اسم فروشنده</label>
                  <input type="text" name="name" class="form-control" placeholder = "اسم فروشنده را وارد کنید"
                         value="<?= htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES) ?>">
                  <?php if (in_array('name', $missing)): ?>
                    <span class="text-danger small">این فیلد ضروری است</span>
                  <?php endif; ?>
                </div>

                <div class="col-md-4">
                  <label>تخلص فروشنده</label>
                  <input type="text" name="last_name" class="form-control" placeholder = "تخلص فروشنده را وارد کنید"
                         value="<?= htmlspecialchars($_POST['last_name'] ?? '', ENT_QUOTES) ?>">
                  <?php if (in_array('last_name', $missing)): ?>
                    <span class="text-danger small">این فیلد ضروری است</span>
                  <?php endif; ?>
                </div>

                <div class="col-md-4">
                  <label>اسم پدر</label>
                  <input type="text" name="father_name" class="form-control" placeholder = "اسم پدر فروشنده (اختیاری)"
                         value="<?= htmlspecialchars($_POST['father_name'] ?? '', ENT_QUOTES) ?>">
                </div>
              </div>

              <div class="row g-3 mt-2">
                <div class="col-md-4">
                  <label>شماره موبایل</label>
                  <input type="text" name="phone" class="form-control" placeholder = "مثلاً 0935xxxxxxx"
                         value="<?= htmlspecialchars($_POST['phone'] ?? '', ENT_QUOTES) ?>">
                </div>

                <div class="col-md-4">
                  <label>معرف</label>
                  <input type="text" name="introducer" class="form-control" placeholder="نام معرف (اختیاری)"
                         value="<?= htmlspecialchars($_POST['introducer'] ?? '', ENT_QUOTES) ?>">
                </div>

                <div class="col-md-4">
                  <label>دفتر مربوطه</label>
                  <select name="book" class="form-control select2">
                    <option >فروشنده</option>
                  </select>
                  <?php if (in_array('book', $missing)): ?>
                    <span class="text-danger small">این فیلد ضروری است</span>
                  <?php endif; ?>
                </div>
              </div>

              <div class="row g-3 mt-2">
                <div class="col-md-12">
                  <label>آدرس</label>
                  <input type="text" name="address" class="form-control" placeholder="آدرس فروشنده (اختیاری)"
                         value="<?= htmlspecialchars($_POST['address'] ?? '', ENT_QUOTES) ?>">
                </div>
              </div>

              <div class="form-group mt-2">
                <label>توضیحات</label>
                <textarea name="description" class="form-control" placeholder="توضیحات تکمیلی (اختیاری)" rows="2"><?= htmlspecialchars($_POST['description'] ?? '', ENT_QUOTES) ?></textarea>
              </div>
            </div>

            <div class="card-footer text-center">
              <button type="submit" name="submit" class="btn btn-success px-4">ذخیره</button>
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
#amountBox { display:none; }
</style>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const alertBox = document.getElementById("alertBox");

  // نمایش پیام
  if (alertBox) {
    setTimeout(() => { alertBox.style.opacity = "0"; setTimeout(() => alertBox.remove(), 800); }, 3000);
  }

  // بررسی وضعیت هنگام لود
  toggleAmountBox();
});
</script>

<?php ob_end_flush(); include '../../layout/footer.php'; ?>
