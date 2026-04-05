<?php
ob_start();
session_start();

include '../../layout/header.php';
include 'query_hawala.php';
include '../customers/query.php';
include '../sellers/query_seller.php';

$query_hawala   = new Query_Hawala();
$query_customer = new Query_Customer();
$query_seller   = new Query_Seller();


// گرفتن ID از URL
$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: list_hawala.php");
    exit();
}

// دریافت اطلاعات مشتری
$hawala = $query_hawala->get_hawala_by_id($id);
if (!$hawala) {
    echo "<div class='alert alert-danger text-center m-3'>مشتری مورد نظر یافت نشد!</div>";
    include '../../layout/footer.php';
    exit();
}

$message = '';
$messageType = '';
$missing = [];

// گرفتن لیست مشتری‌ها و فروشنده‌ها
$customers = $query_customer->get_customers(); // هر رکورد: id, name, last_name, father_name
$sellers   = $query_seller->get_sellers();     // هر رکورد: id, name, last_name, father_name

// اگر فرم ارسال شده
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $hawala_no      = trim($_POST['hawala_no'] ?? '');
    $from_name      = trim($_POST['from_name'] ?? '');
    $receiver_name  = trim($_POST['receiver_name'] ?? '');
    $amount_hawala  = floatval(str_replace(',', '', $_POST['amount_hawala'] ?? 0));
    $currency       = trim($_POST['currency'] ?? '');
    $from_address   = trim($_POST['from_address'] ?? '');
    $to_address     = trim($_POST['to_address']);
    $sender_id      = trim($_POST['sender_id'] ?? '');
    $rate_customer  = floatval($_POST['rate_customer'] ?? 0);
    $amount_to_customer = $rate_customer * $amount_hawala;
    $seller_id      = trim($_POST['seller_id'] ?? '');
    $rate_seller    = floatval($_POST['rate_seller'] ?? 0);
    $amount_to_seller = $rate_seller * $amount_hawala;
    $deduct_from_customer = isset($_POST['deduct_from_customer']) ? intval($_POST['deduct_from_customer']) : 1;
    $add_to_seller = isset($_POST['add_to_seller']) ? intval($_POST['add_to_seller']) : 1;
    $status         = trim($_POST['status'] ?? 'تحویل‌شده');
    $description    = trim($_POST['description'] ?? '');
    $updated_at     = date('Y-m-d H:i:s');
    $hawala_date    = $_POST['hawala_date'] ?? $updated_at;

    // بررسی فیلدهای ضروری
    $required = ['hawala_no','from_name','receiver_name','amount_hawala','currency','hawala_date','from_address','to_address','sender_id','rate_customer','seller_id','rate_seller'];
    foreach($required as $field){
        if(empty($_POST[$field])) $missing[] = $field;
    }

    // محاسبه کارمزد
    $commission = ($deduct_from_customer ? $amount_to_customer : 0) - ($add_to_seller ? $amount_to_seller : 0);
    
if (empty($missing)) {

    if ($query_hawala->update_hawala(
        $id,
        $hawala_no,
        $from_name,
        $receiver_name,
        $amount_hawala,
        $currency,
        $status,
        $hawala_date,
        $from_address,
        $to_address,
        (int)$sender_id,
        (float)$rate_customer,
        (float)$amount_to_customer,
        (int)$seller_id,
        (float)$rate_seller,
        (float)$amount_to_seller,
        (int)$deduct_from_customer,
        (int)$add_to_seller,
        (float)$commission,
        $description
    )) {
        $_SESSION['success_message'] = "حواله با موفقیت ادیت شد!";
        // header("Location: ?id=".$id);
            header("Location: ".$_SERVER['PHP_SELF']);
            exit;
        }else{
            $_SESSION['danger_message'] = "خطا در ادیت حواله!";
        }
    }
}
?>

<section class="content-header">
    <div class="container-fluid">
        <h1 class="text-info"><i class="fa fa-exchange-alt"></i> ادیت حواله </h1>
    </div>
</section>

<section class="content">
<div class="container-fluid">

<?php if(isset($_SESSION['success_message']) || isset($_SESSION['danger_message'])): 
    $message = $_SESSION['success_message'] ?? $_SESSION['danger_message'];
    $messageType = isset($_SESSION['success_message']) ? 'success' : 'danger';
    unset($_SESSION['success_message'], $_SESSION['danger_message']);
?>
    <div id="alertBox" class="alert alert-<?= $messageType ?> text-center m-3 shadow">
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>


<div class="card shadow-lg border-0">
<div class="card-header bg-info text-white">
    <h3 class="card-title"><i class="fa fa-hand-holding-usd"></i> فرم ادیت حواله</h3>
</div>

<form  method="post">
<div class="card-body">

<h5 class="text-primary"><i class="fa fa-info-circle"></i> اطلاعات پایه حواله</h5>

<div class="row g-3 mt-2">

    <!-- شماره حواله -->
    <div class="col-md-4">
        <label>شماره حواله</label>
        <input type="text" name="hawala_no" class="form-control form-control-sm" 
               placeholder="شماره حواله را وارد کنید" 
               value="<?= htmlspecialchars($_POST['hawala_no'] ?? $hawala['hawala_no'], ENT_QUOTES) ?>">
        <?php if(in_array('hawala_no', $missing)): ?>
            <span class="text-danger small">این فیلد ضروری است</span>
        <?php endif; ?>
    </div>

    <!-- از طرف -->
    <div class="col-md-4">
        <label>از طرف</label>
        <input type="text" name="from_name" class="form-control form-control-sm" 
               placeholder="نام فرستنده" value="<?= htmlspecialchars($_POST['from_name'] ?? $hawala['from_name'], ENT_QUOTES) ?>" readonly>
        <?php if(in_array('from_name', $missing)): ?>
            <span class="text-danger small">این فیلد ضروری است</span>
        <?php endif; ?>
    </div>

    <!-- نام گیرنده -->
    <div class="col-md-4">
        <label>نام گیرنده</label>
        <input type="text" name="receiver_name" class="form-control form-control-sm" 
               placeholder="نام گیرنده را وارد کنید" 
               value="<?= htmlspecialchars($_POST['receiver_name'] ?? $hawala['receiver_name'], ENT_QUOTES) ?>">
        <?php if(in_array('receiver_name', $missing)): ?>
            <span class="text-danger small">این فیلد ضروری است</span>
        <?php endif; ?>
    </div>
</div>

<div class="row g-3 mt-2">

    <!-- مبلغ حواله -->
    <div class="col-md-4">
        <label>مبلغ حواله</label>
        <input type="number" name="amount_hawala" id="amount_hawala" class="form-control form-control-sm" 
               placeholder="مبلغ حواله را وارد کنید" 
               value="<?= htmlspecialchars($_POST['amount_hawala'] ?? $hawala['amount_hawala'], ENT_QUOTES) ?>">
        <?php if(in_array('amount_hawala', $missing)): ?>
            <span class="text-danger small">این فیلد ضروری است</span>
        <?php endif; ?>
    </div>

    <!-- واحد پول -->
    <div class="col-md-4">
        <label>واحد پول حواله</label>
        <select name="currency" id="currency" class="form-control select2 form-control-sm">
           <option value="" disabled <?= !isset($_POST['currency']) ? 'selected' : '' ?>>-- انتخاب واحد پول --</option>
           <option value="AFN" <?= (($_POST['currency'] ?? $hawala['currency'])=='AFN') ? 'selected' : '' ?>>افغانی</option>
           <option value="PKR" <?= (($_POST['currency'] ?? $hawala['currency'])=='PKR') ? 'selected' : '' ?>>کلدار</option>
           <option value="USD" <?= (($_POST['currency'] ?? $hawala['currency'])=='USD') ? 'selected' : '' ?>>دلار</option>
           <option value="EUR" <?= (($_POST['currency'] ?? $hawala['currency'])=='EUR') ? 'selected' : '' ?>>یورو</option>
           <option value="IQD" <?= (($_POST['currency'] ?? $hawala['currency'])=='IQD') ? 'selected' : '' ?>>دینار</option>
           <option value="IRR" <?= (($_POST['currency'] ?? $hawala['currency'])=='IRR') ? 'selected' : '' ?>>تومان</option>
        </select>
        <?php if(in_array('currency', $missing)): ?>
            <span class="text-danger small">این فیلد ضروری است</span>
        <?php endif; ?>
    </div>

    <!-- وضعیت -->
    <div class="col-md-4">
        <label>وضعیت حواله</label>تحویل‌شده
        <select name="status" class="form-control form-control-sm">
            <option value="تحویل‌شده" <?= (($_POST['status'] ?? $hawala['status'])=='تحویل‌شده')?'selected':'' ?>>تحویل‌شده</option>
            <option value="کنسل شده" <?= (($_POST['status'] ?? $hawala['status'])=='کنسل شده')?'selected':'' ?>>کنسل شده</option>
            <option value="ارسال‌شده" <?= (($_POST['status'] ?? $hawala['status'])=='ارسال‌شده')?'selected':'' ?>>ارسال‌شده</option>
        </select>
    </div>

</div>

<div class="row g-3 mt-2">

<div class="col-md-4">
    <label>تاریخ حواله</label>

    <input type="datetime-local" 
           name="hawala_date" 
           id="hawala_date" 
           class="form-control"
           value="<?= htmlspecialchars($_POST['hawala_date'] ?? $hawala['hawala_date'], ENT_QUOTES) ?>">

    <?php if (in_array('hawala_date', $missing)): ?>
        <span class="text-danger small">این فیلد ضروری است</span>
    <?php endif; ?>
</div>


    <!-- آدرس مبدا -->
    <div class="col-md-4">
        <label>آدرس مبدا حواله</label>
         <select name="from_address" id="from_address" class="form-control select2 form-control-sm">
           <option value="ایران، قم" <?= (($_POST['from_address'] ??  $hawala['from_address'])=='ایران، قم') ? 'selected' : '' ?>>ایران، قم</option>
           <option value="عراق، نجف" <?= (($_POST['from_address'] ??  $hawala['from_address'])=='عراق، نجف') ? 'selected' : '' ?>>عراق، نجف</option>
        </select>
        <?php if(in_array('from_address', $missing)): ?>
            <span class="text-danger small">این فیلد ضروری است</span>
        <?php endif; ?>
    </div>

  <!-- آدرس مقصد -->
<div class="col-md-4">
    <label>آدرس مقصد حواله</label>
    <select name="to_address" id="to_address" class="form-control select2 form-control-sm">
        <option value="" disabled <?= !isset($_POST['to_address']) ? 'selected' : '' ?>>-- انتخاب کنید --</option>
        <option value="افغانستان، سنگماشه" <?= (($_POST['to_address'] ?? $hawala['to_address'])=='افغانستان، سنگماشه') ? 'selected' : '' ?>>افغانستان، سنگماشه</option>
        <option value="افغانستان، انگوری" <?= (($_POST['to_address'] ?? $hawala['to_address'])=='افغانستان، انگوری') ? 'selected' : '' ?>>افغانستان، انگوری</option>
    </select>
    <?php if(in_array('to_address', $missing)): ?>
        <span class="text-danger small">این فیلد ضروری است</span>
    <?php endif; ?>
</div>


</div>

<hr>

<h5 class="text-success"><i class="fa fa-calculator"></i> اطلاعات مشتری و فروشنده</h5>

<div class="row g-3 mt-2">

    <!-- اسم مشتری -->
    <div class="col-md-4">
        <label>اسم مشتری</label>
        <select name="sender_id" class="form-control select2">
            <option value="">انتخاب مشتری</option>
            <?php foreach($customers as $c): ?>
            <option value="<?= $c['id'] ?>" <?= (($_POST['sender_id'] ?? $hawala['sender_id'])==$c['id'])?'selected':'' ?>>
                <?= htmlspecialchars($c['name'].' '.$c['last_name'].' ('.$c['father_name'].')') ?>
            </option>
            <?php endforeach; ?>
        </select>
        <?php if(in_array('sender_id', $missing)): ?>
            <span class="text-danger small">این فیلد ضروری است</span>
        <?php endif; ?>
    </div>

    <!-- نرخ فروش به مشتری -->
    <div class="col-md-4">
        <label>نرخ فروش به مشتری</label>
        <input type="number" step="0.01" name="rate_customer" id="rate_customer" class="form-control form-control-sm" 
               placeholder="نرخ فروش به مشتری" 
               value="<?= htmlspecialchars($_POST['rate_customer'] ?? $hawala['rate_customer'], ENT_QUOTES) ?>">
        <?php if(in_array('rate_customer', $missing)): ?>
            <span class="text-danger small">این فیلد ضروری است</span>
        <?php endif; ?>
    </div>

    <!-- مبلغ کسر از مشتری -->
    <div class="col-md-4">
        <label>مبلغ کسر از مشتری (تومان)</label>
        <input type="text" name="amount_to_customer" id="amount_to_customer" class="form-control form-control-sm" 
               placeholder="محاسبه خودکار" readonly>
    </div>

</div>

<div class="row g-3 mt-2">

    <!-- اسم فروشنده -->
    <div class="col-md-4">
        <label>اسم فروشنده</label>
        <select name="seller_id" class="form-control select2">
            <option value="">انتخاب فروشنده</option>
            <?php foreach($sellers as $s): ?>
            <option value="<?= $s['id'] ?>" <?= (($_POST['seller_id'] ?? $hawala['seller_id'])==$s['id'])?'selected':'' ?>>
                <?= htmlspecialchars($s['name'].' '.$s['last_name'].' ('.$s['father_name'].')') ?>
            </option>
            <?php endforeach; ?>
        </select>
        <?php if(in_array('seller_id', $missing)): ?>
            <span class="text-danger small">این فیلد ضروری است</span>
        <?php endif; ?>
    </div>

    <!-- نرخ خرید از فروشنده -->
    <div class="col-md-4">
        <label>نرخ خرید از فروشنده</label>
        <input type="number" step="0.01" name="rate_seller" id="rate_seller" class="form-control form-control-sm" 
               placeholder="نرخ خرید از فروشنده" 
               value="<?= htmlspecialchars($_POST['rate_seller'] ?? $hawala['rate_seller'], ENT_QUOTES) ?>">
        <?php if(in_array('rate_seller', $missing)): ?>
            <span class="text-danger small">این فیلد ضروری است</span>
        <?php endif; ?>
    </div>

    <!-- مبلغ اضافه به فروشنده -->
    <div class="col-md-4">
        <label>مبلغ اضافه به فروشنده (تومان)</label>
        <input type="text" name="amount_to_seller" id="amount_to_seller" class="form-control form-control-sm" 
               placeholder="محاسبه خودکار" readonly>
    </div>

</div>

<!-- رادیوهای بلی/نخیر و کارمزد -->
<div class="row g-3 mt-3">
    <div class="col-md-4">
        <label>آیا مبلغ از مشتری کسر شود؟</label><br>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="deduct_from_customer" value="1" id="deduct_yes" checked>
            <label class="form-check-label" for="deduct_yes">بلی</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="deduct_from_customer" value="0" id="deduct_no">
            <label class="form-check-label" for="deduct_no">نخیر</label>
        </div>
    </div>

    <div class="col-md-4">
        <label>آیا مبلغ به فروشنده اضافه شود؟</label><br>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="add_to_seller" value="1" id="add_yes" checked>
            <label class="form-check-label" for="add_yes">بلی</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="add_to_seller" value="0" id="add_no">
            <label class="form-check-label" for="add_no">نخیر</label>
        </div>
    </div>

    <div class="col-md-4">
        <label>کارمزد (تومان)</label>
        <input type="text" name="commission" id="commission" class="form-control form-control-sm bg-warning" readonly placeholder="محاسبه خودکار">
    </div>
</div>

<hr>

<div class="col-md-12 mt-2">
    <label>توضیحات</label>
    <textarea name="description" class="form-control form-control-sm" rows="2" placeholder="توضیح اضافه (اختیاری)"><?= htmlspecialchars($_POST['description'] ?? '', ENT_QUOTES) ?></textarea>
</div>

<div class="mt-3 text-center">
    <button type="submit" name="submit_final" class="btn btn-success me-2">
      ذخیره
    </button>
    <!-- <button type="reset" class="btn btn-secondary">
        پاک کردن
    </button> -->
    <a href="list_hawala.php" class="btn btn-secondary px-4">بازگشت</a>
</div>



</div>
</form>
</div>
</div>
</section>
<style>
#alertBox { animation: fadeIn 0.5s ease; transition: opacity 0.8s ease; }
@keyframes fadeIn { from {opacity:0;transform:translateY(-10px);} to {opacity:1;transform:translateY(0);} }
</style>

<script>
document.addEventListener("DOMContentLoaded", () => {
    // محو شدن پیام موفقیت بعد از 3 ثانیه
    const alertBox = document.getElementById("alertBox");
    if (alertBox) {
        setTimeout(() => {
            alertBox.style.opacity = "0";
            setTimeout(() => alertBox.remove(), 800);
        }, 3000);
    }

    // ست کردن تاریخ و ساعت محلی اگر خالی باشد
    const hawalaInput = document.getElementById("hawala_date");
    if (hawalaInput && !hawalaInput.value) {
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        hawalaInput.value = now.toISOString().slice(0, 16);
    }

    // فراخوانی تابع toggleCardFields (در صورت وجود)
    if (typeof toggleCardFields === "function") toggleCardFields();

    // محاسبه مقادیر مشتری، فروشنده و کارمزد
    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    function calculateAmounts() {
        const amountHawala = parseFloat(document.getElementById('amount_hawala').value.replace(/,/g,'')) || 0;
        const rateCustomer = parseFloat(document.getElementById('rate_customer').value) || 0;
        const rateSeller = parseFloat(document.getElementById('rate_seller').value) || 0;

        const deductFromCustomer = document.querySelector('input[name="deduct_from_customer"]:checked').value === '1';
        const addToSeller = document.querySelector('input[name="add_to_seller"]:checked').value === '1';

        const amountToCustomer = deductFromCustomer ? amountHawala * rateCustomer : 0;
        const amountToSeller = addToSeller ? amountHawala * rateSeller : 0;
        const commission = amountToCustomer - amountToSeller;

        document.getElementById('amount_to_customer').value = numberWithCommas(amountToCustomer.toFixed(2));
        document.getElementById('amount_to_seller').value = numberWithCommas(amountToSeller.toFixed(2));
        document.getElementById('commission').value = numberWithCommas(commission.toFixed(0));
    }

    // اتصال event listener به input ها و radio ها
    ['amount_hawala','rate_customer','rate_seller'].forEach(id => {
        const el = document.getElementById(id);
        if(el) el.addEventListener('input', calculateAmounts);
    });

    ['deduct_from_customer','add_to_seller'].forEach(name => {
        document.querySelectorAll(`input[name="${name}"]`).forEach(el => {
            el.addEventListener('change', calculateAmounts);
        });
    });

    // محاسبه اولیه هنگام بارگذاری صفحه
    calculateAmounts();
});
</script>

<?php
ob_end_flush();
include '../../layout/footer.php';
?>
