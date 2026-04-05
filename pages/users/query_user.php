<?php
class Query_User {

    // اتصال به دیتابیس
    private $conn;

    public function __construct() {
        $this->conn = $this->connection();
    }

    // متد خصوصی برای اتصال به دیتابیس
    private function connection() {
        include __DIR__ . '/../../config/connection.php'; // مسیر فایل اتصال به دیتابیس
        return $connection; // باید در فایل connection.php متغیر $connection تعریف شده باشد
    }

    // ➕ افزودن کاربر جدید
    public function insert_user($username, $password, $name, $last_name, $phone, $role, $status) {
        $stmt = $this->conn->prepare("
            INSERT INTO users (username, password, name, last_name, phone, role, status, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");
        $stmt->bind_param("sssssss", $username, $password, $name, $last_name, $phone, $role, $status);
        return $stmt->execute();
    }

    // 📋 دریافت همه کاربران
    public function get_users() {
        $sql = "SELECT * FROM users ORDER BY id DESC";
        return $this->conn->query($sql);
    }

    // 🔍 دریافت کاربر بر اساس ID
    public function get_user_by_id($id) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // ✏️ بروزرسانی اطلاعات کاربر
    public function update_user($id, $username, $password, $name, $last_name, $phone, $role, $status) {
        if (!empty($password)) {
            $stmt = $this->conn->prepare("
                UPDATE users 
                SET username=?, password=?, name=?, last_name=?, phone=?, role=?, status=?, updated_at=NOW() 
                WHERE id=?
            ");
            $stmt->bind_param("sssssssi", $username, $password, $name, $last_name, $phone, $role, $status, $id);
        } else {
            $stmt = $this->conn->prepare("
                UPDATE users 
                SET username=?, name=?, last_name=?, phone=?, role=?, status=?, updated_at=NOW() 
                WHERE id=?
            ");
            $stmt->bind_param("ssssssi", $username, $name, $last_name, $phone, $role, $status, $id);
        }
        return $stmt->execute();
    }

    // ❌ حذف کاربر بر اساس ID
    public function delete_user($id) {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

// 🔎 بررسی وجود نام کاربری 
    public function check_username_exists($username) {
    $stmt = $this->conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    return $stmt->num_rows > 0;
}


// 🔎 بررسی وجود نام کاربری هنگام ویرایش (کاربر فعلی را نادیده می‌گیرد)
public function check_username_exists_edit($username, $user_id) {
    $stmt = $this->conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
    $stmt->bind_param("si", $username, $user_id);
    $stmt->execute();
    $stmt->store_result();
    return $stmt->num_rows > 0;
}

}
?>
