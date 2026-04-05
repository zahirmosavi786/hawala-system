<?php
class Query_Hawala {
    private $conn;

    public function __construct() {
        $this->conn = $this->connection();
    }

    private function connection() {
        include __DIR__ . '/../../config/connection.php';
        return $connection;
    }

    // افزودن حواله
    public function insert_hawala(
    $hawala_no, 
    $from_name, 
    $receiver_name, 
    $amount_hawala, 
    $currency, 
    $status,
    $hawala_date, 
    $from_address, 
    $to_address, 
    $sender_id, 
    $rate_customer,
    $amount_to_customer, 
    $seller_id, 
    $rate_seller, 
    $amount_to_seller, 
    $deduct_from_customer, 
    $add_to_seller, 
    $commission, 
    $description,
    $created_at
) {

    $stmt = $this->conn->prepare("
        INSERT INTO hawala (
            hawala_no, from_name, receiver_name, amount_hawala, currency, status,
            hawala_date, from_address, to_address, sender_id, rate_customer,
            amount_to_customer, seller_id, rate_seller, amount_to_seller,
            deduct_from_customer, add_to_seller, commission, description, created_at
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
                // انواع:
            // s = string
            // i = integer
            // d = double
            // نوع‌ها را بر اساس داده‌های شما تنظیم کردم
       
    $stmt->bind_param(
        "sssdsssssiddiddssdss", 
        $hawala_no,
        $from_name,
        $receiver_name,
        $amount_hawala,
        $currency,
        $status,
        $hawala_date,
        $from_address,
        $to_address,
        $sender_id,
        $rate_customer,
        $amount_to_customer,
        $seller_id,
        $rate_seller,
        $amount_to_seller,
        $deduct_from_customer,
        $add_to_seller,
        $commission,
        $description,
        $created_at
    );

    return $stmt->execute();
}

    // دریافت لیست حواله‌ها
    public function get_hawala_list() {
        $sql = "SELECT * FROM hawala ORDER BY id DESC";
        return $this->conn->query($sql);
    }

    // دریافت یک حواله
    public function get_hawala_by_id($id) {
        $stmt = $this->conn->prepare("SELECT * FROM hawala WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    
    // حذف حواله بر اساس ID
    public function delete_hawala($id) {
        $stmt = $this->conn->prepare("DELETE FROM hawala WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

public function update_hawala(
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
    $sender_id,
    $rate_customer,
    $amount_to_customer,
    $seller_id,
    $rate_seller,
    $amount_to_seller,
    $deduct_from_customer,
    $add_to_seller,
    $commission,
    $description
) {
    $stmt = $this->conn->prepare("
        UPDATE hawala SET
        hawala_no = ?, 
        from_name = ?, 
        receiver_name = ?, 
        amount_hawala = ?, 
        currency = ?,
        status = ?, 
        hawala_date = ?, 
        from_address = ?, 
        to_address = ?, 
        sender_id = ?,
        rate_customer = ?, 
        amount_to_customer = ?, 
        seller_id = ?, 
        rate_seller = ?,
        amount_to_seller = ?, 
        deduct_from_customer = ?, 
        add_to_seller = ?, 
        commission = ?,
        description = ?
        WHERE id = ?
    ");

    $stmt->bind_param(
        "sssdsssssiddiddiidsi",
        $hawala_no,
        $from_name,
        $receiver_name,
        $amount_hawala,
        $currency,
        $status,
        $hawala_date,
        $from_address,
        $to_address,
        $sender_id,
        $rate_customer,
        $amount_to_customer,
        $seller_id,
        $rate_seller,
        $amount_to_seller,
        $deduct_from_customer,
        $add_to_seller,
        $commission,
        $description,
        $id
    );

    return $stmt->execute();
}


}
?>
