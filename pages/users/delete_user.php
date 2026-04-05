<?php
include 'query_user.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $query = new Query_User();
    $deleted = $query->delete_user($id);

    if ($deleted) {
           // ذخیره موفق، ایجاد پیام در session
            session_start();
            $_SESSION['success_message'] = "مشتری با موفقیت حذف شد!";
            // ریدایرکت به همان صفحه بدون query string
            header("Location:list_user.php");
            exit();
        } else {
           session_start();
            $_SESSION['danger_message'] = "خطا در حذف اطلاعات!";
             header("Location:list_user.php");
             exit;
        }
    } else {
    header("Location: list_user.php");
    exit;
}
?>
