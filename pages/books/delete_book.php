<?php
include 'query.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $query = new Query();
    $deleted = $query->delete_book($id);

     if ($deleted) {
           // ذخیره موفق، ایجاد پیام در session
            session_start();
            $_SESSION['success_message'] = "دفتر با موفقیت حذف شد!";
            // ریدایرکت به همان صفحه بدون query string
            header("Location:list_book.php");
            exit();
        } else {
           session_start();
            $_SESSION['danger_message'] = "خطا در حذف اطلاعات!";
             header("Location:list_book.php");
             exit;
        }
    } else {
    header("Location: list_book.php");
    exit;
}
?>