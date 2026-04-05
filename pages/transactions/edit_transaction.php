<?php
ob_start();
session_start();
include '../../layout/header.php';
include 'query_transaction.php';
include '../customers/query.php';

// ایجاد نمونه کلاس‌ها
$query_customer = new Query_Customer();
$query_transaction = new Query_Transaction();

// دریافت لیست مشتریان
$customers_And_books = $query_customer->get_customers_And_books();

// متغیرها
$message = '';
$messageType = '';
$missing = [];

// دریافت شناسه تراکنش
$transaction_id = $_GET['id'] ?? '';
if (empty($transaction_id) || !($transaction = $query_transaction->get_transaction_by_id($transaction_id))) {
    $_SESSION['danger_message'] = "تراکنش مورد نظر یافت نشد!";
    header("Location: list_transaction.php");
    exit();
}

// اگر فرم ارسال شده
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = trim($_POST['customer_id'] ?? '');
    $amount = trim($_POST['amount'] ?? '');
    $transaction_type = trim($_POST['transaction_type'] ?? '');
    $payment_method = trim($_POST['payment_method'] ?? '');
    $card_from = trim($_POST['card_from'] ?? '');
    $card_to = trim($_POST['card_to'] ?? '');
    $transaction_date = trim($_POST['transaction_date'] ?? date('Y-m-d H:i:s'));
    $description = trim($_POST['description'] ?? '');

    // فیلدهای ضروری
    $required = ['customer_id', 'amount', 'transaction_type', 'payment_method', 'transaction_date'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) $missing[] = $field;
    }

    // بررسی معتبر بودن نوع پرداخت
    $valid_payment_methods = ['نقد', 'چک', 'کارت به کارت', 'شبا'];
    if (!in_array($payment_method, $valid_payment_methods, true)) {
        $missing[] = 'payment_method';
        $_SESSION['danger_message'] = "نوع پرداخت معتبر نیست!";
    }

    // اگر روش پرداخت کارت به کارت یا شبا است، فیلدهای کارت اجباری شوند
    if ($payment_method === 'کارت به کارت' || $payment_method === 'شبا') {
        if (empty($card_from)) $missing[] = 'card_from';
        if (empty($card_to)) $missing[] = 'card_to';
    }

    // اگر هیچ فیلدی خالی نیست، بروزرسانی تراکنش
    if (empty($missing)) {
        if ($query_transaction->update_transaction(
            $transaction_id, $customer_id, $amount, $transaction_type, $payment_method,
            $card_from, $card_to, $transaction_date, $description
        )) {
            $_SESSION['success_message'] = "تراکنش با موفقیت بروزرسانی شد!";
            header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $transaction_id);
            exit();
        } else {
            $_SESSION['danger_message'] = "خطا در ذخیره اطلاعات!";
        }
    }
} else {
    // پر کردن فرم با اطلاعات موجود
    $_POST = $transaction;
}
?>

<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2 align-items-center">
      <div class="col-sm-6">
        <h1 class="text-warning"><i class="fa fa-edit"></i> ویرایش تراکنش</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-left">
          <li class="breadcrumb-item"><a href="#"><i class="fa fa-home"></i> خانه</a></li>
          <li class="breadcrumb-item active"><i class="fa fa-money-check"></i> ویرایش تراکنش</li>
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
        <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
      </div>
    <?php endif; ?>

    <div class="row justify-content-center">
      <div class="col-md-10">
        <div class="card shadow-lg border-0 rounded-3">
          <div class="card-header bg-warning text-white">
            <h3 class="card-title"><i class="fa fa-edit"></i> ویرایش تراکنش</h3>
          </div>

          <form id="transactionForm" action="<?= htmlspecialchars($_SERVER['PHP_SELF'] . '?id=' . $transaction_id); ?>" method="post">
            <div class="card-body">
              <div class="row g-3">
                <div class="col-md-6">
                  <label>اسم، تخلص و اسم پدر مشتری</label>
                  <select name="customer_id" class="form-control select2">
                    <option value="" disabled>-- انتخاب مشتری --</option>
                    <?php foreach ($customers_And_books as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= ($_POST['customer_id'] ?? '') == $c['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['name'] . ' ' . $c['last_name']) ?> 
                            ولد: <?= htmlspecialchars($c['father_name']) ?>
                            ، دفتر: <?= htmlspecialchars($c['book_name']) ?>
                        </option>
                    <?php endforeach; ?>
                  </select>
                  <?php if (in_array('customer_id', $missing)): ?>
                    <span class="text-danger small">این فیلد ضروری است</span>
                  <?php endif; ?>
                </div>

                <div class="col-md-6">
                  <label>مبلغ تراکنش</label>
                  <input type="number" name="amount" class="form-control"
                         value="<?= htmlspecialchars($_POST['amount'] ?? '', ENT_QUOTES) ?>">
                  <?php if (in_array('amount', $missing)): ?>
                    <span class="text-danger small">این فیلد ضروری است</span>
                  <?php endif; ?>
                </div>
              </div>

              <div class="row g-3 mt-2">
                <div class="col-md-4">
                  <label>نوع تراکنش</label>
                  <select name="transaction_type" class="form-control">
                    <option value="">-- انتخاب نوع --</option>
                    <option value="واریز" <?= ($_POST['transaction_type'] ?? '') == 'واریز' ? 'selected' : '' ?>>واریز</option>
                    <option value="برداشت" <?= ($_POST['transaction_type'] ?? '') == 'برداشت' ? 'selected' : '' ?>>برداشت</option>
                  </select>
                  <?php if (in_array('transaction_type', $missing)): ?>
                    <span class="text-danger small">این فیلد ضروری است</span>
                  <?php endif; ?>
                </div>

                <div class="col-md-4">
                  <label>نوع پرداخت</label>
                  <select name="payment_method" id="payment_method" class="form-control">
                    <option value="">-- انتخاب روش --</option>
                    <option value="نقد" <?= ($_POST['payment_method'] ?? '') == 'نقد' ? 'selected' : '' ?>>نقد</option>
                    <option value="چک" <?= ($_POST['payment_method'] ?? '') == 'چک' ? 'selected' : '' ?>>چک</option>
                    <option value="کارت به کارت" <?= ($_POST['payment_method'] ?? '') == 'کارت به کارت' ? 'selected' : '' ?>>کارت به کارت</option>
                    <option value="شبا" <?= ($_POST['payment_method'] ?? '') == 'شبا' ? 'selected' : '' ?>>شبا</option>
                  </select>
                  <?php if (in_array('payment_method', $missing)): ?>
                    <span class="text-danger small">این فیلد ضروری است</span>
                  <?php endif; ?>
                </div>

                <div class="col-md-4">
                  <label>تاریخ و ساعت تراکنش</label>
                  <input type="datetime-local" name="transaction_date" class="form-control"
                         value="<?= htmlspecialchars($_POST['transaction_date'] ?? date('Y-m-d\TH:i', strtotime($transaction['transaction_date'])), ENT_QUOTES) ?>">
                  <?php if (in_array('transaction_date', $missing)): ?>
                    <span class="text-danger small">این فیلد ضروری است</span>
                  <?php endif; ?>
                </div>
              </div>

              <div id="cardFields" class="row g-3 mt-2 <?= in_array($_POST['payment_method'] ?? '', ['کارت به کارت','شبا']) ? '' : 'd-none' ?>">
                <div class="col-md-6">
                  <label>از کارت</label>
                  <input type="text" name="card_from" class="form-control"
                         value="<?= htmlspecialchars($_POST['card_from'] ?? '', ENT_QUOTES) ?>">
                  <?php if (in_array('card_from', $missing)): ?>
                    <span class="text-danger small">این فیلد ضروری است</span>
                  <?php endif; ?>
                </div>
                <div class="col-md-6">
                  <label>به کارت</label>
                  <input type="text" name="card_to" class="form-control"
                         value="<?= htmlspecialchars($_POST['card_to'] ?? '', ENT_QUOTES) ?>">
                  <?php if (in_array('card_to', $missing)): ?>
                    <span class="text-danger small">این فیلد ضروری است</span>
                  <?php endif; ?>
                </div>
              </div>

              <div class="form-group mt-3">
                <label>توضیحات</label>
                <textarea name="description" class="form-control" rows="2"><?= htmlspecialchars($_POST['description'] ?? '', ENT_QUOTES) ?></textarea>
              </div>
            </div>

            <div class="card-footer text-center">
              <button type="submit" name="submit" class="btn btn-warning px-4">بروزرسانی</button>
              <a href="list_transaction.php" class="btn btn-secondary px-4">بازگشت</a>
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
  const paymentMethod = document.getElementById("payment_method");
  const cardFields = document.getElementById("cardFields");

  if (alertBox) {
    setTimeout(() => { alertBox.style.opacity = "0"; setTimeout(() => alertBox.remove(), 800); }, 3000);
  }

  function toggleCardFields() {
    const value = paymentMethod.value;
    if (value === "کارت به کارت" || value === "شبا") {
      cardFields.classList.remove("d-none");
    } else {
      cardFields.classList.add("d-none");
    }
  }

  paymentMethod.addEventListener("change", toggleCardFields);
  toggleCardFields();
});
</script>

<?php ob_end_flush(); include '../../layout/footer.php'; ?>
