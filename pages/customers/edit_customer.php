<?php
ob_start();
include '../../layout/header.php';
include '../books/query.php';
include 'query.php';

$query = new Query();
$books = $query->get_books();
$query_customer = new Query_Customer();

// گرفتن ID از URL
$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: list_customer.php");
    exit();
}

// دریافت اطلاعات مشتری
$customer = $query_customer->get_customer_by_id($id);
if (!$customer) {
    echo "<div class='alert alert-danger text-center m-3'>مشتری مورد نظر یافت نشد!</div>";
    include '../../layout/footer.php';
    exit();
}

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
    $financial_type = trim($_POST['financial_type'] ?? '');
    $financial_status_raw = trim($_POST['financial_status'] ?? '');
    $currency = trim($_POST['currency'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $description = trim($_POST['description'] ?? '');

    // بررسی فیلدهای ضروری
    $required = ['name', 'last_name', 'book', 'financial_type', 'currency'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) $missing[] = $field;
    }

    // اگر نوع مالی طلبکار یا بدهکار است ولی مقدار مالی خالی است
    if (in_array($financial_type, ['طلبکار', 'بدهکار']) && $financial_status_raw === '') {
        $missing[] = 'financial_status';
    }

    if (empty($missing)) {
        $financial_status = is_numeric($financial_status_raw) ? (float)$financial_status_raw : 0;
        if ($financial_status < 0) $financial_status = abs($financial_status);

        if ($financial_type === 'بدهکار') $financial_status = -$financial_status;
        elseif ($financial_type === 'تسویه شده') $financial_status = 0;

        // ویرایش در دیتابیس
        
        if ($query_customer->update_customer(
            $id, $name, $last_name, $father_name, $phone, $introducer,
            $book, $financial_status, $currency, $address, $description
        )) {
             session_start();
            $_SESSION['success_message'] =  "اطلاعات مشتری با موفقیت ویرایش شد!";
            header("Location: edit_customer.php?id=$id&success=1");
            exit();
        } else {
             session_start();
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
        <h1 class="text-primary"><i class="fa fa-edit"></i> ویرایش مشتری</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-left">
          <li class="breadcrumb-item"><a href="#"><i class="fa fa-home"></i> خانه</a></li>
          <li class="breadcrumb-item active"><i class="fa fa-user"></i> ویرایش مشتری</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
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
      <div class="col-md-10">
        <div class="card shadow-lg border-0 rounded-3">
          <div class="card-header bg-primary text-white">
            <h3 class="card-title"><i class="fa fa-user-edit"></i> اطلاعات مشتری</h3>
          </div>

          <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . urlencode($id); ?>" method="post">
            <div class="card-body">

              <div class="row g-3">
                <div class="col-md-4">
                  <label>اسم مشتری</label>
                  <input type="text" name="name" class="form-control" placeholder = "اسم مشتری را وارد کنید"
                         value="<?= htmlspecialchars($_POST['name'] ?? $customer['name'], ENT_QUOTES) ?>">
                  <?php if (in_array('name', $missing)): ?><span class="text-danger small">ضروری</span><?php endif; ?>
                </div>

                <div class="col-md-4">
                  <label>تخلص مشتری</label>
                  <input type="text" name="last_name" class="form-control" placeholder = "تخلص مشتری را وارد کنید"
                         value="<?= htmlspecialchars($_POST['last_name'] ?? $customer['last_name'], ENT_QUOTES) ?>">
                  <?php if (in_array('last_name', $missing)): ?><span class="text-danger small">ضروری</span><?php endif; ?>
                </div>

                <div class="col-md-4">
                  <label>اسم پدر</label>
                  <input type="text" name="father_name" class="form-control" placeholder = "اسم پدر مشتری را وارد کنید"
                         value="<?= htmlspecialchars($_POST['father_name'] ?? $customer['father_name'], ENT_QUOTES) ?>">
                </div>
              </div>

              <div class="row g-3 mt-2">
                <div class="col-md-4">
                  <label>شماره موبایل</label>
                  <input type="text" name="phone" class="form-control" placeholder = "مثلاً 0935xxxxxxx"
                         value="<?= htmlspecialchars($_POST['phone'] ?? $customer['phone'], ENT_QUOTES) ?>">
                </div>

                <div class="col-md-4">
                  <label>معرف</label>
                  <input type="text" name="introducer" class="form-control" placeholder="نام معرف (اختیاری)"
                         value="<?= htmlspecialchars($_POST['introducer'] ?? $customer['introducer'], ENT_QUOTES) ?>">
                </div>

                <div class="col-md-4">
                  <label>دفتر مربوطه</label>
                  <select name="book" class="form-control select2">
                    <option disabled>انتخاب کنید</option>
                    <?php while ($row = $books->fetch_assoc()): ?>
                      <option value="<?= $row['id'] ?>" 
                        <?= ($_POST['book'] ?? $customer['id']) == $row['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($row['book_name']) ?>
                      </option>
                    <?php endwhile; ?>
                  </select>
                  <?php if (in_array('book', $missing)): ?><span class="text-danger small">ضروری</span><?php endif; ?>
                </div>
              </div>

              <div class="row g-3 mt-2">
                <div class="col-md-4">
                  <label>وضعیت مالی</label>
                  <select name="financial_type" id="financial_type" class="form-control">
                    <option disabled>انتخاب کنید</option>
                    <?php
                      $fin_type = $_POST['financial_type'] ?? (
                        $customer['financial_status'] > 0 ? 'طلبکار' :
                        ($customer['financial_status'] < 0 ? 'بدهکار' : 'تسویه شده')
                      );
                    ?>
                    <option <?= $fin_type === 'طلبکار' ? 'selected' : '' ?>>طلبکار</option>
                    <option <?= $fin_type === 'بدهکار' ? 'selected' : '' ?>>بدهکار</option>
                    <option <?= $fin_type === 'تسویه شده' ? 'selected' : '' ?>>تسویه شده</option>
                  </select>
                  <?php if (in_array('financial_type', $missing)): ?><span class="text-danger small">ضروری</span><?php endif; ?>
                </div>

                <div class="col-md-4" id="amountBox">
                  <label>مقدار مالی</label>
                  <input type="number" name="financial_status" id="financial_status" class="form-control" min="0" placeholder="مقدار مالی"
                         value="<?= htmlspecialchars($_POST['financial_status'] ?? abs($customer['financial_status']), ENT_QUOTES) ?>">
                  <small id="numberToWords" class="text-muted"></small>
                  <?php if (in_array('financial_status', $missing)): ?><span class="text-danger small">ضروری</span><?php endif; ?>
                </div>

                <div class="col-md-4">
                  <label>واحد پول</label>
                  <select name="currency" class="form-control">
                    <option disabled>انتخاب کنید</option>
                    <?php
                      $curr = $_POST['currency'] ?? $customer['currency'];
                      $currencies = ['تومان'];
                      foreach ($currencies as $c):
                    ?>
                      <option <?= $curr === $c ? 'selected' : '' ?>><?= $c ?></option>
                    <?php endforeach; ?>
                  </select>
                   <?php if (in_array('currency', $missing)): ?>
                    <span class="text-danger small">این فیلد ضروری است</span>
                  <?php endif; ?>
                </div>
              </div>

              <div class="row g-3 mt-2">
                <div class="col-md-12">
                  <label>آدرس</label>
                  <input type="text" name="address" class="form-control" placeholder="آدرس مشتری (اختیاری)"
                         value="<?= htmlspecialchars($_POST['address'] ?? $customer['address'], ENT_QUOTES) ?>">
                </div>
              </div>

              <div class="form-group mt-2">
                <label>توضیحات</label>
                <textarea name="description" class="form-control"  placeholder="توضیحات تکمیلی (اختیاری)" rows="2"><?= htmlspecialchars($_POST['description'] ?? $customer['description'], ENT_QUOTES) ?></textarea>
              </div>

            </div>

            <div class="card-footer text-center">
              <button type="submit" class="btn btn-success px-4">ذخیره تغییرات</button>
              <a href="list_customer.php" class="btn btn-secondary px-4">بازگشت</a>
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
  const type = document.getElementById("financial_type");
  const amountBox = document.getElementById("amountBox");
  const amount = document.getElementById("financial_status");
  const numberToWords = document.getElementById("numberToWords");

  if (alertBox) {
    setTimeout(() => { alertBox.style.opacity = "0"; setTimeout(() => alertBox.remove(), 800); }, 3000);
  }

  function toggleAmountBox() {
    if (type.value === "طلبکار" || type.value === "بدهکار") {
      amountBox.style.display = "block";
    } else {
      amountBox.style.display = "none";
      amount.value = "";
      numberToWords.innerText = "";
    }
  }
// تابع عدد به حروف فارسی
 function numberToPersianWords(num) {
  if (num === 0) return "صفر تومان";

  const ones = ["","یک","دو","سه","چهار","پنج","شش","هفت","هشت","نه"];
  const tens = ["","ده","بیست","سی","چهل","پنجاه","شصت","هفتاد","هشتاد","نود"];
  const teens = ["ده","یازده","دوازده","سیزده","چهارده","پانزده","شانزده","هفده","هجده","نوزده"];
  const hundreds = ["","یکصد","دویست","سیصد","چهارصد","پانصد","ششصد","هفتصد","هشتصد","نهصد"];

  function threeDigitsToWords(n){
    let w = "";
    let h = Math.floor(n / 100);
    let t = Math.floor((n % 100) / 10);
    let o = n % 10;

    if(h > 0) w += hundreds[h];
    if(t === 1) {
      if(w) w += " و ";
      w += teens[o];
    } else {
      if(t > 1) {
        if(w) w += " و ";
        w += tens[t];
      }
      if(o > 0) {
        if(w) w += " و ";
        w += ones[o];
      }
    }
    return w;
  }

  const units = ["","هزار","میلیون","میلیارد","تریلیون","کوادریلیون","کوینتیلیون","سکستیلیون","سپتیلیون","اکتیلیون","نانیلیون","دسیلیون"];

  let parts = [];
  let i = 0;
  while(num > 0){
    let n = num % 1000;
    if(n){
      let part = threeDigitsToWords(n);
      if(units[i]) part += " " + units[i];
      parts.unshift(part);
    }
    num = Math.floor(num / 1000);
    i++;
  }

  return parts.join(" و ") + " تومان";
}

  type.addEventListener("change", toggleAmountBox);
  amount.addEventListener("input", () => {
    if (amount.value < 0) amount.value = Math.abs(amount.value);
    numberToWords.innerText = numberToPersianWords(Number(amount.value));
  });

  toggleAmountBox();
});
</script>

<?php ob_end_flush(); include '../../layout/footer.php'; ?>
