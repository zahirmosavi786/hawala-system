<?php
class Query_Transaction {

    // اتصال به دیتابیس
    private $conn;

    public function __construct() {
        $this->conn = $this->connection();
    }

    // متد خصوصی برای اتصال به دیتابیس
    private function connection() {
        include __DIR__ . '/../../config/connection.php'; // مسیر فایل کانفیگ
        return $connection; // باید در فایل connection.php تعریف شده باشد
    }

    // افزودن تراکنش جدید
    public function insert_transaction($customer_id, $amount, $transaction_type, $payment_method,
                                       $card_from, $card_to, $transaction_date, $description) {
        $stmt = $this->conn->prepare("
            INSERT INTO transactions
            (customer_id, amount, transaction_type, payment_method, card_from, card_to, transaction_date, description)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "idssssss",
            $customer_id, $amount, $transaction_type, $payment_method,
            $card_from, $card_to, $transaction_date, $description
        );
        return $stmt->execute();
    }

    // دریافت همه تراکنش‌ها
    public function get_transactions() {
        $sql = "SELECT t.*, 
                       c.name AS customer_name, 
                       c.last_name AS customer_last_name
                FROM transactions t
                LEFT JOIN customers c ON t.customer_id = c.id
                ORDER BY t.id DESC";
        return $this->conn->query($sql);
    }

    // دریافت تراکنش بر اساس ID
    public function get_transaction_by_id($id) {
        $stmt = $this->conn->prepare("SELECT * FROM transactions WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // بروزرسانی تراکنش
    public function update_transaction($id, $customer_id, $amount, $transaction_type, $payment_method,
                                       $card_from, $card_to, $transaction_date, $description) {
        $stmt = $this->conn->prepare("
            UPDATE transactions SET
            customer_id=?, amount=?, transaction_type=?, payment_method=?, 
            card_from=?, card_to=?, transaction_date=?, description=?
            WHERE id=?
        ");
        $stmt->bind_param(
            "idssssssi",
            $customer_id, $amount, $transaction_type, $payment_method,
            $card_from, $card_to, $transaction_date, $description, $id
        );
        return $stmt->execute();
    }

    // حذف تراکنش بر اساس ID
    public function delete_transaction($id) {
        $stmt = $this->conn->prepare("DELETE FROM transactions WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // مجموع تراکنش‌های یک مشتری خاص
    public function get_total_by_customer($customer_id) {
        $stmt = $this->conn->prepare("
            SELECT 
                SUM(CASE WHEN transaction_type = 'واریز' THEN amount ELSE 0 END) AS total_deposit,
                SUM(CASE WHEN transaction_type = 'برداشت' THEN amount ELSE 0 END) AS total_withdraw
            FROM transactions
            WHERE customer_id = ?
        ");
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // دریافت تمام تراکنش‌های یک مشتری با فیلتر اختیاری تاریخ
    public function get_transactions_by_customer($customer_id, $from_date = null, $to_date = null) {
        $query = "SELECT * FROM transactions WHERE customer_id = ?";
        $params = [$customer_id];
        $types = "i";

        // افزودن فیلتر تاریخ در صورت وجود
        if (!empty($from_date)) {
            $query .= " AND DATE(transaction_date) >= ?";
            $params[] = $from_date;
            $types .= "s";
        }
        if (!empty($to_date)) {
            $query .= " AND DATE(transaction_date) <= ?";
            $params[] = $to_date;
            $types .= "s";
        }

        $query .= " ORDER BY transaction_date DESC";

        $stmt = $this->conn->prepare($query);

        // بایند کردن پارامترها
        $stmt->bind_param($types, ...$params);

        $stmt->execute();
        $result = $stmt->get_result();

        $transactions = [];
        while ($row = $result->fetch_assoc()) {
            $transactions[] = $row;
        }
        return $transactions;
    }
}
?>
