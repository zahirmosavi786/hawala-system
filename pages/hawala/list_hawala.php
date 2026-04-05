<?php
ob_start();
session_start();

include '../../layout/header.php';
include 'query_hawala.php';
include '../customers/query.php';
include '../sellers/query_seller.php';

$query_hawala  = new Query_Hawala();
$query_customer = new Query_Customer();
$query_seller   = new Query_Seller();

// دریافت لیست حواله‌ها
$hawalas = $query_hawala->get_hawala_list();
?>

<!-- عنوان صفحه -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="text-info"><i class="fa fa-list"></i> لیست حواله‌ها</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-left">
                    <li class="breadcrumb-item"><a href="#"><i class="fa fa-home"></i> خانه</a></li>
                    <li class="breadcrumb-item active">لیست حواله‌ها</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        <!-- نمایش پیام -->
        <?php if (isset($_SESSION['success_message']) || isset($_SESSION['danger_message'])):
            $msg  = $_SESSION['success_message'] ?? $_SESSION['danger_message'];
            $type = isset($_SESSION['success_message']) ? 'success' : 'danger';
            unset($_SESSION['success_message'], $_SESSION['danger_message']);
        ?>
            <div id="alertBox" class="alert alert-<?= $type ?> text-center m-3 shadow">
                <?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm border-0 rounded-3">
           <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0" style="font-weight: bold;">
                    <i class="fa fa-database"></i> فهرست حواله‌ها
                </h3>

                <a href="add_hawala.php" class="btn btn-warning btn-sm text-dark fw-bold">
                    <i class="fa fa-plus"></i> ایجاد حواله جدید
                </a>
            </div>


            <div class="card-body table-responsive">
                <table id="example1" class="table table-bordered table-striped text-center">
                    <thead class="bg-light">
                        <tr>
                            <th>#</th>
                            <th>نمبر حواله</th>
                            <th>مبلغ</th>
                            <th>گیرنده</th>
                            <th>مقصد</th>
                            <th>ارسال‌کننده</th>
                            <th>نرخ فروش</th>
                            <th>مبلغ تومان</th>
                            <th>فروشنده</th>
                            <th>نرخ خرید</th>
                            <th>مبلغ خرید</th>
                            <th>کارمزد</th>
                            <th>وضعیت</th>
                            <th>مبدا</th>
                            <th>تاریخ حواله</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        if ($hawalas && $hawalas->num_rows > 0):
                            $i = 1;
                            while ($row = $hawalas->fetch_assoc()):

                                // گرفتن نام مشتری
                                $sender_data = $query_customer->get_customer_by_id($row['sender_id']);
                                $sender = $sender_data ? $sender_data['name'] . ' ' . $sender_data['last_name'] : 'نامشخص';

                                // گرفتن نام فروشنده
                                $seller_data2 = $query_seller->get_seller_by_id($row['seller_id']);
                                $seller = $seller_data2 ? $seller_data2['name'] . ' ' . $seller_data2['last_name'] : 'نامشخص';

                                // تعیین رنگ وضعیت
                                $status = $row['status'];
                                $badge = "secondary";
                                if ($status == "کنسل شده") $badge = "danger";
                                elseif ($status == "در انتظار") $badge = "info";
                                elseif ($status == "تحویل‌ شده") $badge = "success";
                        ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><span class="badge bg-info"><?= htmlspecialchars($row['hawala_no']); ?></span></td>
                                <td><?= number_format($row['amount_hawala']) . ' ' . $row['currency']; ?></td>
                                <td><?= htmlspecialchars($row['receiver_name']); ?></td>
                                <td><?= htmlspecialchars($row['to_address']); ?></td>
                                <td><?= htmlspecialchars($sender); ?></td>
                                <td><?= number_format($row['rate_customer']); ?></td>
                                <td><?= number_format($row['amount_to_customer']); ?></td>
                                <td><?= htmlspecialchars($seller); ?></td>
                                <td><?= number_format($row['rate_seller']); ?></td>
                                <td><?= number_format($row['amount_to_seller']); ?></td>
                                <td><span class="badge bg-success"><?= number_format($row['commission']); ?></span></td>
                                <td><span class="badge bg-<?= $badge ?>"><?= htmlspecialchars($status) ?></span></td>
                                <td><?= htmlspecialchars($row['from_address']); ?></td>
                                <td><?= htmlspecialchars(date('Y-m-d', strtotime($row['hawala_date']))); ?></td>

                                <td class="text-center">
                                    <a href="view_hawala.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i></a>
                                    <a href="edit_hawala.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                                    <a href="javascript:void(0);" onclick="confirmDelete(<?= $row['id']?>)" class="btn btn-sm btn-danger">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>

                        <?php endwhile;
                        else: ?>
                            <tr>
                                <td colspan="16" class="text-muted">هیچ حواله‌ای ثبت نشده است.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</section>

<!-- پیام Fade Out -->
<style>
#alertBox { animation: fadeIn 0.5s ease; transition: opacity 0.8s ease; }
@keyframes fadeIn { from {opacity:0; transform:translateY(-10px);} to {opacity:1; transform:translateY(0);} }
</style>

<script>
const alertBox = document.getElementById("alertBox");
if (alertBox) {
    setTimeout(() => {
        alertBox.style.opacity = "0";
        setTimeout(() => alertBox.remove(), 800);
    }, 3000);
}
</script>

<!-- Swal حذف -->
<script>
function confirmDelete(id) {
  Swal.fire({
    title: 'آیا مطمئن هستید؟',
    text: "این عمل قابل بازگشت نیست!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'بله حذف شود',
    cancelButtonText: 'لغو',

    customClass: {
      popup: 'small-swal-popup',
      title: 'small-swal-title',
      htmlContainer: 'small-swal-text',
      confirmButton: 'small-swal-btn',
      cancelButton: 'small-swal-btn'
    }

  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = 'delete_hawala.php?id=' + id;
    }
  });
}

const style = document.createElement('style');
style.innerHTML = `
.small-swal-popup { width: 300px !important; padding: 1rem !important; border-radius: 12px !important; }
.small-swal-title { font-size: 16px !important; }
.small-swal-text { font-size: 13px !important; }
.small-swal-btn { font-size: 13px !important; padding: 4px 10px !important; border-radius: 6px !important; }
`;
document.head.appendChild(style);
</script>

<?php include '../../layout/footer.php'; ob_end_flush(); ?>
