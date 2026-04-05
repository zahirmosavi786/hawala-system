<?php
class Query_Customer {

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
    public function insert_customer($name, $last_name, $father_name, $phone, 
                                    $introducer, $book, $financial_status, 
                                    $currency, $address, $description) {
        $stmt = $this->conn->prepare("
            INSERT INTO customers 
            (name, last_name, father_name, phone, introducer, book, financial_status, currency, address, description) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "sssssiisss", 
            $name, $last_name, $father_name, $phone, $introducer, $book, $financial_status, $currency, $address, $description
        );
        return $stmt->execute();
    }

    // دریافت همه مشتریان
    public function get_customers() {
        $sql = "SELECT * FROM customers ORDER BY id DESC";
        return $this->conn->query($sql);
    }

      //  دریافت همه مشتریان همراه با نام کتاب 
    //    براس استفاده در select2s
    public function get_customers_And_books() {
        $sql =  "SELECT c.id, c.name, c.last_name, c.father_name, c.book AS book_id, b.book_name
            FROM customers c
            LEFT JOIN books b ON c.book = b.id ORDER BY id DESC";
        return $this->conn->query($sql);
    }

    //  دریافت همه مشتریان همراه با نام دفتر
public function get_customer_by_id($id) {
    $stmt = $this->conn->prepare("
        SELECT c.*, b.book_name AS book_name
        FROM customers c
        LEFT JOIN books b ON c.book = b.id
        WHERE c.id = ?
        LIMIT 1
    ");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}




    // // دریافت مشتری بر اساس ID
    // public function get_customer_by_id($id) {
    //     $stmt = $this->conn->prepare("SELECT * FROM customers WHERE id = ?");
    //     $stmt->bind_param("i", $id);
    //     $stmt->execute();
    //     return $stmt->get_result()->fetch_assoc();
    // }

    // بروزرسانی مشتری
    public function update_customer($id, $name, $last_name, $father_name, $phone, 
                                    $introducer, $book, $financial_status, 
                                    $currency, $address, $description) {
        $stmt = $this->conn->prepare("
            UPDATE customers SET
            name=?, last_name=?, father_name=?, phone=?, introducer=?, book=?, financial_status=?, currency=?, address=?, description=?
            WHERE id=?
        ");
        $stmt->bind_param(
            "sssssiisssi",
            $name, $last_name, $father_name, $phone, $introducer, $book, $financial_status, $currency, $address, $description, $id
        );
        return $stmt->execute();
    }

    // حذف مشتری بر اساس ID
    public function delete_customer($id) {
        $stmt = $this->conn->prepare("DELETE FROM customers WHERE id = ?");
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
