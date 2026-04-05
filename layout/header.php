<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>پنل مدیریت | داشبورد </title>

  <!-- Font Awesome -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
<!-- <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> -->


  <link rel="stylesheet" href="../../assets/plugins/font-awesome/css/font-awesome.min.css">
  <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css"> -->
  <!-- Ionicons -->
   <!-- <link rel="stylesheet" href="../../assets/plugins/ionicons/2.0.1/css/ionicons.min.css"> -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../../assets/plugins/datatables/dataTables.bootstrap4.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../assets/dist/css/adminlte.min.css">
    <!-- Morris chart -->
  <link rel="stylesheet" href="../../assets/plugins/morris/morris.css">
  <!-- Google Font: Source Sans Pro -->
  <!-- <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet"> -->

  <!-- bootstrap rtl -->
  <link rel="stylesheet" href="../../assets/dist/css/bootstrap-rtl.min.css">
  <!-- template rtl version -->
  <link rel="stylesheet" href="../../assets/dist/css/custom-style.css">

  <!-- Select2 -->
<link rel="stylesheet" href="../../assets/plugins/select2/select2.min.css">
</head>


<!-- saidbar -->
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="../dashbord/dashbord1.php" class="nav-link">خانه</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">تماس</a>
      </li>
    </ul>

    <!-- SEARCH FORM -->
    <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="جستجو" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fa fa-search"></i>
          </button>
        </div>
      </div>
    </form>

    <!-- Right navbar links -->
    <ul class="navbar-nav mr-auto">
      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="fa fa-comments-o"></i>
          <span class="badge badge-danger navbar-badge">3</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-left">
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="../../assets/dist/img/zahir.png" alt="User Avatar" class="img-size-50 ml-3 img-circle">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  سید ظاهر موسوی
                  <span class="float-left text-sm text-danger"><i class="fa fa-star"></i></span>
                </h3>
                <p class="text-sm">با من تماس بگیر لطفا...</p>
                <p class="text-sm text-muted"><i class="fa fa-clock-o mr-1"></i> 4 ساعت قبل</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="../../assets/dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle ml-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  پیمان احمدی
                  <span class="float-left text-sm text-muted"><i class="fa fa-star"></i></span>
                </h3>
                <p class="text-sm">من پیامتو دریافت کردم</p>
                <p class="text-sm text-muted"><i class="fa fa-clock-o mr-1"></i> 4 ساعت قبل</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="../../assets/dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle ml-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  سارا وکیلی
                  <span class="float-left text-sm text-warning"><i class="fa fa-star"></i></span>
                </h3>
                <p class="text-sm">پروژه اتون عالی بود مرسی واقعا</p>
                <p class="text-sm text-muted"><i class="fa fa-clock-o mr-1"></i>4 ساعت قبل</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">مشاهده همه پیام‌ها</a>
        </div>
      </li>
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="fa fa-bell-o"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-left">
          <span class="dropdown-item dropdown-header">15 نوتیفیکیشن</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fa fa-envelope ml-2"></i> 4 پیام جدید
            <span class="float-left text-muted text-sm">3 دقیقه</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fa fa-users ml-2"></i> 8 درخواست دوستی
            <span class="float-left text-muted text-sm">12 ساعت</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fa fa-file ml-2"></i> 3 گزارش جدید
            <span class="float-left text-muted text-sm">2 روز</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">مشاهده همه نوتیفیکیشن</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#"><i
                class="fa fa-th-large"></i></a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="../../assets/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">پنل مدیریت</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <div>
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="../../assets/dist/img/zahir.png" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            <a href="#" class="d-block">سید ظاهر موسوی</a>
          </div>
        </div>

        <!-- Sidebar Menu -->
         <?php
            $current_page = basename($_SERVER['PHP_SELF']);
          ?>
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
                 with font-awesome or any other icon font library -->
                  <!-- منوی اصلی داشبورد -->
            <li class="nav-item has-treeview <?= ($current_page == 'dashbord1.php' || $current_page == 'dashbord2.php') ? 'menu-open' : '' ?>">
              <a href="#" class="nav-link <?= ($current_page == 'dashbord1.php' || $current_page == 'dashbord2.php') ? 'active' : '' ?>">
                <i class="nav-icon fa fa-dashboard"></i>
                <p>
                  داشبوردها
                  <i class="right fa fa-angle-left"></i>
                </p>
              </a>

              <!-- زیرمنوها -->
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="../dashbord/dashbord1.php" class="nav-link <?= ($current_page == 'dashbord1.php') ? 'active' : '' ?>">
                    <i class="fa fa-circle-o nav-icon"></i>
                    <p>داشبورد اول</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="../dashbord/dashbord2.php" class="nav-link <?= ($current_page == 'dashbord2.php') ? 'active' : '' ?>">
                    <i class="fa fa-circle-o nav-icon"></i>
                    <p>داشبورد دوم</p>
                  </a>
                </li>
              </ul>
            </li>
             <li class="nav-item has-treeview <?= ($current_page == 'add_hawala.php' || $current_page == 'list_hawala.php') ? 'menu-open' : '' ?>">
              <a href="#" class="nav-link <?= ($current_page == 'add_hawala.php' || $current_page == 'list_hawala.php') ? 'active' : '' ?>">
                <i class="nav-icon fa fa-plus-square-o"></i>
                <p>
                  مدیریت حواله ها
                  <i class="fa fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                 <a href="../hawala/add_hawala.php" class="nav-link <?= ($current_page == 'add_hawala.php') ? 'active' : '' ?>">
                    <i class="fa fa-circle-o nav-icon"></i>
                    <p>ایجاد حواله جدید</p>
                  </a>
                </li>
                <li class="nav-item">
                 <a href="../hawala/list_hawala.php" class="nav-link <?= ($current_page == 'list_hawala.php') ? 'active' : '' ?>">
                    <i class="fa fa-circle-o nav-icon"></i>
                    <p>لیست حواله ها</p>
                  </a>
                </li>
              </ul>
            </li>
             <li class="nav-item has-treeview <?= ($current_page == 'add_transaction.php' || $current_page == 'list_transaction.php') ? 'menu-open' : '' ?>">
              <a href="#" class="nav-link <?= ($current_page == 'add_transaction.php' || $current_page == 'list_transaction.php') ? 'active' : '' ?>">
                <i class="nav-icon fa fa-plus-square-o"></i>
                <p>
                  مدیریت تراکنیش ها
                  <i class="fa fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                 <a href="../transactions/add_transaction.php" class="nav-link <?= ($current_page == 'add_transaction.php') ? 'active' : '' ?>">
                    <i class="fa fa-circle-o nav-icon"></i>
                    <p>ایجاد تراکنیش جدید</p>
                  </a>
                </li>
                <li class="nav-item">
                 <a href="../transactions/list_transaction.php" class="nav-link <?= ($current_page == 'list_transaction.php') ? 'active' : '' ?>">
                    <i class="fa fa-circle-o nav-icon"></i>
                    <p>لیست تراکنیش ها</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item has-treeview <?= ($current_page == 'add_user.php' || $current_page == 'list_user.php') ? 'menu-open' : '' ?>">
              <a href="#" class="nav-link <?= ($current_page == 'add_user.php' || $current_page == 'list_user.php') ? 'active' : '' ?>">
                <i class="nav-icon fa fa-table"></i>
                <p>
                  مدیریت کاربرها
                  <i class="fa fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                 <a href="../users/add_user.php" class="nav-link <?= ($current_page == 'add_user.php') ? 'active' : '' ?>">
                    <i class="fa fa-circle-o nav-icon"></i>
                    <p>ایجاد کاربر جدید</p>
                  </a>
                </li>
                <li class="nav-item">
                 <a href="../users/list_user.php" class="nav-link <?= ($current_page == 'list_user.php') ? 'active' : '' ?>">
                    <i class="fa fa-circle-o nav-icon"></i>
                    <p>لیست کاربرها</p>
                  </a>
                </li>
              </ul>
            </li>
           <li class="nav-item has-treeview <?= ($current_page == 'add_seller.php' || $current_page == 'list_seller.php') ? 'menu-open' : '' ?>">
              <a href="#" class="nav-link <?= ($current_page == 'add_seller.php' || $current_page == 'list_seller.php') ? 'active' : '' ?>">
                <i class="nav-icon fa fa-file"></i>
                <p>
                  مدیریت فروشنده ها
                  <i class="fa fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                 <a href="../sellers/add_seller.php" class="nav-link <?= ($current_page == 'add_seller.php') ? 'active' : '' ?>">
                    <i class="fa fa-circle-o nav-icon"></i>
                    <p>ایجاد فروشنده جدید</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="../sellers/list_seller.php" class="nav-link <?= ($current_page == 'list_seller.php') ? 'active' : '' ?>">
                    <i class="fa fa-circle-o nav-icon"></i>
                    <p>لیست فروشنده ها</p>
                  </a>
                </li>
              
              </ul>
            </li>
             <li class="nav-item has-treeview <?= ($current_page == 'add_customer.php' || $current_page == 'list_customer.php') ? 'menu-open' : '' ?>">
              <a href="#" class="nav-link <?= ($current_page == 'add_customer.php' || $current_page == 'list_customer.php') ? 'active' : '' ?>">
                <i class="nav-icon fa fa-book"></i>
                <p>
                  مدیریت مشتری ها
                  <i class="fa fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                 <a href="../customers/add_customer.php" class="nav-link <?= ($current_page == 'add_customer.php') ? 'active' : '' ?>">
                    <i class="fa fa-circle-o nav-icon"></i>
                    <p>ایجاد مشتری جدید</p>
                  </a>
                </li>
                <li class="nav-item">
                 <a href="../customers/list_customer.php" class="nav-link <?= ($current_page == 'list_customer.php') ? 'active' : '' ?>">
                    <i class="fa fa-circle-o nav-icon"></i>
                    <p>لیست تمام مشتری ها</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-header">دفتر ها</li>
            <li class="nav-item has-treeview <?= ($current_page == 'add_book.php' || $current_page == 'list_book.php') ? 'menu-open' : '' ?>">
              <a href="#" class="nav-link <?= ($current_page == 'add_book.php' || $current_page == 'list_book.php') ? 'active' : '' ?>">
                <i class="nav-icon fa fa-file"></i>
                <p>
                  مدیریت دفتر ها
                   <i class="fa fa-angle-left right"></i>
                </p>
              </a>
                <ul class="nav nav-treeview">
                <li class="nav-item">
                 <a href="../books/add_book.php" class="nav-link <?= ($current_page == 'add_book.php') ? 'active' : '' ?>">
                    <i class="fa fa-circle-o nav-icon"></i>
                    <p>ایجاد دفتر جدید</p>
                  </a>
                </li>
                  <li class="nav-item">
                 <a href="../books/list_book.php" class="nav-link <?= ($current_page == 'list_book.php') ? 'active' : '' ?>">
                    <i class="fa fa-circle-o nav-icon"></i>
                    <p>لیست دفتر ها</p>
                  </a>
                </li>
                <li class="nav-item">
                 <a href="index.php" class="nav-link <?= ($current_page == 'index.php') ? 'active' : '' ?>">
                    <i class="fa fa-circle-o nav-icon"></i>
                    <p> دفتر آجه</p>
                  </a>
                </li>
                 <li class="nav-item">
                  <a href="index.php" class="nav-link <?= ($current_page == 'index.php') ? 'active' : '' ?>">
                    <i class="fa fa-circle-o nav-icon"></i>
                    <p> دفتر نویان</p>
                  </a>
                </li>
                 <li class="nav-item">
                 <a href="index.php" class="nav-link <?= ($current_page == 'index.php') ? 'active' : '' ?>">
                    <i class="fa fa-circle-o nav-icon"></i>
                    <p> دفتر بکی</p>
                  </a>
                </li>
                 <li class="nav-item">
                 <a href="index.php" class="nav-link <?= ($current_page == 'index.php') ? 'active' : '' ?>">
                    <i class="fa fa-circle-o nav-icon"></i>
                    <p> دفتر متفرقه
                    </p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-header">برچسب‌ها</li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon fa fa-circle-o text-danger"></i>
                <p class="text">مهم</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon fa fa-circle-o text-warning"></i>
                <p>هشدار</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon fa fa-circle-o text-info"></i>
                <p>اطلاعات</p>
              </a>
            </li>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
    </div>
    <!-- /.sidebar -->
  </aside>
   <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
  