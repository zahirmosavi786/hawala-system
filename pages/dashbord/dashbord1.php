<?php
ob_start();
session_start();
include '../../layout/header.php';
include 'query_dashbord.php';
include '../customers/query.php';
include '../sellers/query_seller.php';

$query_hawala  = new Query_Hawala();
$query_customer = new Query_Customer();
$query_seller   = new Query_Seller();


$hawala_stats= $query_hawala->count_hawala_stats();

// دریافت لیست حواله‌ها
$hawalas = $query_hawala->get_hawala_list();


?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">داشبورد</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-left">
              <li class="breadcrumb-item"><a href="#">خانه</a></li>
              <li class="breadcrumb-item active">داشبورد اول</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
              <h4><?php echo $hawala_stats['total'];  ?></h4>
              

                <p>کل حواله‌ ها</p>
              </div>
              <div class="icon">
                <i class="ion ion-cash"></i>
              </div>
              <!-- <a href="#" class="small-box-footer">اطلاعات بیشتر <i class="fa fa-arrow-circle-left"></i></a> -->
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <!-- <h3>53<sup style="font-size: 20px">%</sup></h3> -->
                <h4><?php echo $hawala_stats['delivered']; ?></h4>
               
                <p>حواله های موفق </p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <!-- <a href="#" class="small-box-footer">اطلاعات بیشتر <i class="fa fa-arrow-circle-left"></i></a> -->
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h4><?php echo $hawala_stats['pending']; ?></h4>
               
                <p>حواله های در انتظار</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <!-- <a href="#" class="small-box-footer">اطلاعات بیشتر <i class="fa fa-arrow-circle-left"></i></a> -->
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                 <h4><?php echo $hawala_stats['cancelled']; ?></h4>
               
                <p>حواله های لغو شده</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <!-- <a href="#" class="small-box-footer">اطلاعات بیشتر <i class="fa fa-arrow-circle-left"></i></a> -->
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
      
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title">گزارش  حواله ها بر اساس ماه</h5>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                  </button>
                  <div class="btn-group">
                    <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                      <i class="fa fa-wrench"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-left" role="menu">
                      <a href="#" class="dropdown-item">منو اول</a>
                      <a href="#" class="dropdown-item">منو دوم</a>
                      <a href="#" class="dropdown-item">منو سوم</a>
                      <a class="dropdown-divider"></a>
                      <a href="#" class="dropdown-item">لینک</a>
                    </div>
                  </div>
                  <button type="button" class="btn btn-tool" data-widget="remove">
                    <i class="fa fa-times"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                  <div class="col-md-8">
                    <p class="text-center">
                      <strong>فروش سال 1405</strong>
                    </p>

                    <div class="chart">
                      <!-- Sales Chart Canvas -->
                      <canvas id="salesChart" height="180" style="height: 180px;"></canvas>
                    </div>
                    <!-- /.chart-responsive -->
                  </div>
                  <!-- /.col -->
                  <div class="col-md-4">
                    <p class="text-center">
                      <strong>تحلیل هوشمند سیستم حواله</strong>
                    </p>

                    <?php
                    // رشد نسبت به ماه قبل
                    $current_month_total = $hawala_stats['current_month_total'] ?? 0;
                    $prev_month_total = $hawala_stats['prev_month_total'] ?? 0;
                    $growth_percent = ($prev_month_total > 0) ? round(($current_month_total - $prev_month_total) / $prev_month_total * 100) : 0;

                    // میانگین مبلغ حواله
                    $avg_hawala = ($hawala_stats['total'] > 0) ? round($hawala_stats['total_sells_to_customer'] / $hawala_stats['total']) : 0;

                    // شلوغ‌ترین روز هفته
                    $busiest_day = $hawala_stats['busiest_day'] ?? 'نامشخص';

                    // نسبت آنلاین و حضوری
                    $total_online = $hawala_stats['online_transactions'] ?? 10;
                    $total_offline = $hawala_stats['offline_transactions'] ?? 5;
                    $online_percent = ($total_online + $total_offline > 0) ? round($total_online / ($total_online + $total_offline) * 100) : 0;

                    // مشتریان فعال
                    $active_customers = $hawala_stats['active_customers'] ?? 0;
                    $total_customers = $hawala_stats['total_customers'] ?? 0;
                    $customers_percent = ($total_customers > 0) ? round($active_customers / $total_customers * 100) : 0;
                    ?>

                    <!-- رشد نسبت به ماه قبل -->
                    <div class="progress-group">
                      رشد نسبت به ماه قبل
                      <span class="float-left"><b><?php echo ($growth_percent > 0 ? '+' : '') . $growth_percent; ?>%</b></span>
                      <div class="progress progress-sm">
                        <div class="progress-bar bg-success" style="width: <?php echo min(abs($growth_percent), 100); ?>%"></div>
                      </div>
                    </div>

                    <!-- میانگین مبلغ حواله -->
                    <div class="progress-group">
                      میانگین مبلغ هر حواله
                      <span class="float-left"><b><?php echo number_format($avg_hawala); ?></b> تومان</span>
                      <div class="progress progress-sm">
                        <div class="progress-bar bg-primary" style="width: <?php echo min($avg_hawala / 10000 * 100, 100); ?>%"></div>
                      </div>
                    </div>

                    <!-- شلوغ‌ترین روز هفته -->
                    <div class="progress-group">
                      شلوغ‌ترین روز هفته
                      <span class="float-left"><b><?php echo $busiest_day; ?></b></span>
                      <div class="progress progress-sm">
                        <div class="progress-bar bg-info" style="width: 80%"></div>
                      </div>
                    </div>

                    <!-- آنلاین vs حضوری -->
                    <div class="progress-group">
                      حواله آنلاین vs حضوری
                      <span class="float-left"><b><?php echo $online_percent; ?>% آنلاین</b></span>
                      <div class="progress progress-sm">
                        <div class="progress-bar bg-warning" style="width: <?php echo $online_percent; ?>%"></div>
                      </div>
                    </div>

                    <!-- مشتریان فعال -->
                    <div class="progress-group">
                      مشتریان فعال
                      <span class="float-left"><b><?php echo $active_customers; ?></b>/<?php echo $total_customers; ?></span>
                      <div class="progress progress-sm">
                        <div class="progress-bar bg-purple" style="width: <?php echo $customers_percent; ?>%"></div>
                      </div>
                    </div>

                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
              <!-- ./card-body -->
              <div class="card-footer">
                <?php 
                $total = $hawala_stats['total_buy'] + $hawala_stats['total_sells_to_customer'];
                $buy_percent = ($total > 0) ? round($hawala_stats['total_buy'] / $total * 100) : 0;
                $sell_percent = ($total > 0) ? round($hawala_stats['total_sells_to_customer'] / $total * 100) : 0;

                // درصد اهداف و کمیشن
                $goal_total = 10000000000; // هدف ماهانه خودت
                $goal_percent = ($goal_total > 0) ? round($hawala_stats['total_sells_to_customer'] / $goal_total * 100) : 0;

                $commission_percent = ($total > 0) ? round($hawala_stats['total_commission'] / $total * 100) : 0;
                ?>
                <div class="row">
                  <div class="col-sm-3 col-6">
                    <div class="description-block border-right">
                      <span class="description-percentage text-success"><i class="fa fa-caret-up"></i> <?php echo $sell_percent; ?>%</span>
                      <h5 class="description-header">تومان <?php echo number_format($hawala_stats['total_sells_to_customer']); ?></h5>
                      <span class="description-text">فروش کل</span>
                    </div>
                  </div>

                  <div class="col-sm-3 col-6">
                    <div class="description-block border-right">
                      <span class="description-percentage text-primary"><i class="fa fa-caret-left"></i> <?php echo $buy_percent; ?>%</span>
                      <h5 class="description-header">تومان <?php echo number_format($hawala_stats['total_buy']); ?></h5>
                      <span class="description-text">خرید کل</span>
                    </div>
                  </div>

                  <div class="col-sm-3 col-6">
                    <div class="description-block border-right">
                      <span class="description-percentage text-info"><i class="fa fa-caret-up"></i> <?php echo $commission_percent; ?>%</span>
                      <h5 class="description-header">تومان <?php echo number_format($hawala_stats['total_commission']); ?></h5>
                      <span class="description-text">سود کل</span>
                    </div>
                  </div>

                  <div class="col-sm-3 col-6">
                    <div class="description-block">
                      <span class="description-percentage text-warning"><i class="fa fa-caret-up"></i> <?php echo $goal_percent; ?>%</span>
                      <h5 class="description-header"><?php echo number_format($goal_total); ?>تومان </h5>
                      <span class="description-text">مقدار هدف سود</span>
                    </div>
                  </div>
                </div>
                <!-- /.row -->
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
                     <!-- TABLE: LATEST ORDERS -->
            <div class="card">
              <div class="card-header border-transparent">
                <h3 class="card-title">آخرین حواله ها</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-widget="remove">
                    <i class="fa fa-times"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped text-center">
                    <thead class="bg-light">
                        <tr>
                            <th>نمبر حواله</th>
                            <th>مبلغ</th>
                            <th>ارسال‌کننده</th>
                            <th>گیرنده</th>
                            <th>وضعیت</th>
                            <th>مقصد</th>
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
                                <td><span class="badge bg-info"><?= htmlspecialchars($row['hawala_no']); ?></span></td>
                                <td><?= number_format($row['amount_hawala']) . ' ' . $row['currency']; ?></td>
                                <td><?= htmlspecialchars($sender); ?></td>
                                <td><?= htmlspecialchars($row['receiver_name']); ?></td>
                                <td><span class="badge bg-<?= $badge ?>"><?= htmlspecialchars($status) ?></span></td>
                                <td><?= htmlspecialchars($row['to_address']); ?></td>
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
                <!-- /.table-responsive -->
              </div>
              <!-- /.card-body -->
              <div class="card-footer clearfix">
                <a href="javascript:void(0)" class="btn btn-sm btn-info float-left">سفارش جدید</a>
                <a href="javascript:void(0)" class="btn btn-sm btn-secondary float-right">مشاهده همه سفار</a>
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
     
<?php
ob_end_flush();
include '../../layout/footer.php';
?>