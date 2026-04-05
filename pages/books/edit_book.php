<?php
ob_start();
include '../../layout/header.php';
include 'query.php';

$query = new Query();

// بررسی وجود ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<div class='alert alert-danger m-3'>شناسه دفتر نامعتبر است.</div>";
    exit;
}

$id = intval($_GET['id']);

// ✅ دریافت اطلاعات دفتر
$book = $query->get_book_by_id($id);

if (!$book) {
    echo "<div class='alert alert-danger m-3'>دفتر مورد نظر یافت نشد.</div>";
    exit;
}

// ✅ بروزرسانی دفتر در صورت ارسال فرم
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book_name = trim($_POST['book_name']);

    if ($book_name === '') {
        $error = "نام دفتر نمی‌تواند خالی باشد.";
    } else {
        $updated = $query->update_book($id, $book_name);
        if ($updated) {
            session_start();
            $_SESSION['success_message'] =  "اطلاعات دفتر با موفقیت ویرایش شد!";
             header("Location:list_book.php");
             exit;
        } else {
            session_start();
            $_SESSION['danger_message'] = "خطا در ذخیره اطلاعات!";
             header("Location:list_book.php");
             exit;
        }
    }
}
?>

<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2 align-items-center">
      <div class="col-sm-6">
        <h1 class="font-weight-bold text-primary">
          <i class="fas fa-edit"></i> ویرایش دفتر
        </h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-left">
          <li class="breadcrumb-item"><a href="list_book.php"><i class="fas fa-book"></i> لیست دفترها</a></li>
          <li class="breadcrumb-item active">ویرایش دفتر</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-3">
      <div class="card-header bg-gradient-warning text-dark">
        <h3 class="card-title mb-0">ویرایش اطلاعات دفتر</h3>
      </div>
      <div class="card-body">
        <?php if (isset($error)) echo "<div class='alert alert-danger'>{$error}</div>"; ?>

        <form method="POST">
          <div class="form-group">
            <label for="book_name">نام دفتر:</label>
            <input type="text" name="book_name" id="book_name" class="form-control" 
                   value="<?php echo htmlspecialchars($book['book_name']); ?>" required>
          </div>

          <div class="form-group mt-3">
            <button type="submit" class="btn btn-success">
              <i class="fas fa-save"></i> ذخیره تغییرات
            </button>
            <a href="list_book.php" class="btn btn-secondary">
              بازگشت
            </a>
          </div>
        </form>

      </div>
    </div>
  </div>
</section>

<?php ob_start(); include '../../layout/footer.php'; ?>
