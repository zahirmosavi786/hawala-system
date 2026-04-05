<?php
class Query {

    // ✅ اتصال به دیتابیس
    private function connection() {
        include __DIR__ . '../../../config/connection.php';
        return $connection;
    }

      // ✅ افزودن دفتر جدید
    public function insert_book($book_name, $date) {
        $conn = $this->connection(); 
        $stmt = $conn->prepare("INSERT INTO books (book_name, created_at) VALUES (?, ?)");
        $stmt->bind_param("ss", $book_name, $date);
        return $stmt->execute();
    }


    // ✅ دریافت همه‌ی دفترها از دیتابیس
    public function get_books() {
        $conn = $this->connection();
        $sql = "SELECT * FROM books ORDER BY id DESC";
        $result = $conn->query($sql);
        return $result;
    }
     // ✅ دریافت یک دفتر بر اساس ID
    public function get_book_by_id($id) {
         $conn = $this->connection();
        $stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $book = $result->fetch_assoc();
        $stmt->close();
        return $book;
    }

    // ✅ بروزرسانی دفتر بر اساس ID
    public function update_book($id, $book_name) {
         $conn = $this->connection();
       $stmt = $conn->prepare("UPDATE books SET book_name = ? WHERE id = ?");
        $stmt->bind_param("si", $book_name, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

     // ✅ تابع حذف دفتر بر اساس ID 
    function delete_book($id) {
        $conn = $this->connection();
        $stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
 
    // ✅ تبدیل تاریخ میلادی به هجری شمسی (بدون نیاز به intl)
    public function gregorianToJalali($date) {
        if (!$date) return '';

        list($gy, $gm, $gd) = explode('-', date('Y-m-d', strtotime($date)));
        $g_d_m = [0,31,59,90,120,151,181,212,243,273,304,334];
        $gy2 = ($gm > 2) ? ($gy + 1) : $gy;
        $days = 355666 + (365 * $gy) + floor(($gy2 + 3) / 4) - floor(($gy2 + 99) / 100) + floor(($gy2 + 399) / 400) + $gd + $g_d_m[$gm - 1];
        $jy = -1595 + (33 * intval($days / 12053));
        $days %= 12053;
        $jy += 4 * intval($days / 1461);
        $days %= 1461;
        if ($days > 365) {
            $jy += intval(($days - 1) / 365);
            $days = ($days - 1) % 365;
        }
        $jm = ($days < 186) ? 1 + intval($days / 31) : 7 + intval(($days - 186) / 30);
        $jd = 1 + (($days < 186) ? ($days % 31) : (($days - 186) % 30));
        return sprintf('%04d/%02d/%02d', $jy, $jm, $jd);
    }

}
?>
