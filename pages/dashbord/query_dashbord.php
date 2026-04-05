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

   

// مجموع حواله ها بر اساس داشبورد اول
public function count_hawala_stats() {
    // تعیین ماه جاری و ماه قبل
    $month = date('m'); 
    $year  = date('Y');

    $prev_month = $month - 1;
    $prev_year  = $year;
    if ($prev_month == 0) {
        $prev_month = 12;
        $prev_year--;
    }

    // پرس‌وجو اصلی برای آمار کلی و رشد ماهانه
    $sql = "
        SELECT 
            COUNT(*) AS total,
            SUM(CASE WHEN status = 'کنسل شده' THEN 1 ELSE 0 END) AS cancelled,
            SUM(CASE WHEN status = 'تحویل شده' THEN 1 ELSE 0 END) AS delivered,
            SUM(CASE WHEN status = 'در انتظار' THEN 1 ELSE 0 END) AS pending,
            COALESCE(SUM(commission), 0) AS total_commission,
            COALESCE(SUM(amount_to_seller), 0) AS total_buy,
            COALESCE(SUM(amount_to_customer), 0) AS total_sells_to_customer,
            -- مجموع ماه جاری
            (SELECT COUNT(*) FROM hawala WHERE YEAR(hawala_date) = ? AND MONTH(hawala_date) = ?) AS current_month_total,
            -- مجموع ماه قبل
            (SELECT COUNT(*) FROM hawala WHERE YEAR(hawala_date) = ? AND MONTH(hawala_date) = ?) AS prev_month_total,
            -- مشتریان فعال (دارای حداقل یک حواله غیرکنسل شده)
            (SELECT COUNT(DISTINCT sender_id) FROM hawala WHERE status != 'کنسل شده') AS active_customers,
            -- کل مشتریان
            (SELECT COUNT(*) FROM customers) AS total_customers
        FROM hawala
    ";

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("iiii", $year, $month, $prev_year, $prev_month);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // محاسبه رشد نسبت به ماه قبل
    $current = $row['current_month_total'] ?? 0;
    $prev    = $row['prev_month_total'] ?? 0;
    $row['growth_percent'] = ($prev > 0) ? round(($current - $prev) / $prev * 100) : 0;

    // درصد مشتریان فعال
    $active = $row['active_customers'] ?? 0;
    $total_customers = $row['total_customers'] ?? 0;
    $row['customers_percent'] = ($total_customers > 0) ? round($active / $total_customers * 100) : 0;

    // نسبت آنلاین و حضوری (در صورتی که ستون‌ها موجود باشند)
    $total_online  = $row['online_transactions'] ?? 0;
    $total_offline = $row['offline_transactions'] ?? 0;
    $row['online_percent'] = ($total_online + $total_offline > 0) ? round($total_online / ($total_online + $total_offline) * 100) : 0;

    return $row;
}


    // دریافت لیست حواله‌ها
    public function get_hawala_list() {
        $sql = "SELECT * FROM hawala ORDER BY hawala_date DESC";
        return $this->conn->query($sql);
    }

    // دریافت یک حواله
    public function get_hawala_by_id($id) {
        $stmt = $this->conn->prepare("SELECT * FROM hawala WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }


}
?>
