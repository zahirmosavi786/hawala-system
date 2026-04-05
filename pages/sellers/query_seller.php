<?php
class Query_Seller{

    // اتصال به دیتابیس
    private $conn;

    public function __construct() {
        $this->conn = $this->connection();
    }

    // متد خصوصی برای اتصال به دیتابیس
    private function connection() {
        include __DIR__ . '/../../config/connection.php'; // مسیر فایل کانفیگ
        return $connection; // $connection باید در فایل کانفیگ تعریف شده باشد
    }

    // افزودن مشتری جدید
    public function insert_seller($name, $last_name, $father_name, $phone, 
                                   $introducer, $book, $address, $description) {
        $stmt = $this->conn->prepare("
            INSERT INTO sellers 
            (name, last_name, father_name, phone, introducer, book, address, description) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "ssssssss", 
            $name, $last_name, $father_name, $phone, $introducer, $book, $address, $description
        );
        return $stmt->execute();
    }

    // دریافت همه مشتریان
    public function get_sellers() {
        $sql = "SELECT * FROM sellers ORDER BY id DESC";
        return $this->conn->query($sql);
    }

    // دریافت مشتری بر اساس ID
    public function get_seller_by_id($id) {
        $stmt = $this->conn->prepare("SELECT * FROM sellers WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // بروزرسانی مشتری
    public function update_seller($id,$name, $last_name, $father_name, $phone, 
                                 $introducer, $book, $address, $description) {
        $stmt = $this->conn->prepare("
            UPDATE sellers SET
            name=?, last_name=?, father_name=?, phone=?, introducer=?, book=?, address=?, description=?
            WHERE id=?
        ");
        $stmt->bind_param(
            "ssssssssi",
            $name, $last_name, $father_name, $phone, $introducer, $book, $address, $description, $id
        );
        return $stmt->execute();
    }

    // حذف مشتری بر اساس ID
    public function delete_seller($id) {
        $stmt = $this->conn->prepare("DELETE FROM sellers WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // گرفتن نام دفتر بر اساس ID
    public function get_book_name_by_id($id) {
        $stmt = $this->conn->prepare("SELECT book_name FROM books WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($name);
        $stmt->fetch();
        $stmt->close();
        return $name ?? 'نامشخص';
    }
}
?>
