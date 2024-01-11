<?php session_start();
$uid = $_SESSION['id'];
$uname = $_SESSION['username'];
if (isset($uid)) {
?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Orders</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../../vendors/feather/feather.css">
    <link rel="stylesheet" href="../../vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../../vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../../vendors/typicons/typicons.css">
    <link rel="stylesheet" href="../../vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="../../vendors/css/vendor.bundle.base.css">
    <script src="https://kit.fontawesome.com/4d4bd04373.js" crossorigin="anonymous"></script>
    <style>
      th,
      td {
        padding: 3px !important;
        font-size: 15px !important;
      }


      #myInput {
        background-image: url('../../images/searchicon.png');
        background-size: 35px;
        background-position: 3px 5px;
        background-repeat: no-repeat;
        width: 25%;
        float: right;
        font-size: 16px;
        padding: 12px 20px 12px 40px;
        border: 1px solid #ddd;
        margin-bottom: 12px;
      }

      #myTable {
        width: 100%;
        font-size: 18px;
      }

      #myTable th,
      #myTable td {
        text-align: left;
      }

      #myTable tr.header,
      #myTable tr:hover {
        background-color: #f1f1f1;
      }

      .pagination {
        justify-content: center;
        display: inline-block;
        margin: 0 auto;
        height: auto;



      }

      .pagination a {
        color: black;
        padding: 8px 16px;
        text-decoration: none;
        transition: background-color .3s;
      }

      .pagination a.active {
        background-color: #4CAF50;
        color: white;
      }

      .btn btn-primary:hover:not(.active) {
        background-color: #1c1e78;
      }

      .pagination .left-arrow:hover:not(.active) {
        background-color: #ddd;
      }

      .pagination .right-arrow:hover:not(.active) {
        background-color: #ddd;
      }
    </style>
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="../../css/vertical-layout-light/style.css">
    <!-- endinject -->
    <link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

    <!-- Javascript -->
    <script>
      $(function() {


        $(function() {
          $("#datepicker-7").datepicker();
          $("#datepicker-9").datepicker();
          $("#datepicker-8").datepicker();
          //$(datepicker).datepicker(disable).
          $("#datepicker-7").datepicker("disable");
          $("#datepicker-9").datepicker("disable");
          $("#datepicker-8").datepicker("disable");
        });
      });
    </script>
    <!-- Datepicker style -->
    <style>
      .datepicker.datepicker-dropdown {
        max-width: 525px;
        width: 40%;

      }

      .datepicker-9 {
        float: left;
        width: 25%
      }

      span.month,
      span.year,
      span.decade,
      span.century {
        padding: 3px;
      }

      th.datepicker-switch {
        text-align: center;
      }

      table.table-condensed {
        margin: 0 auto;
      }
    </style>
    <link rel="shortcut icon" href="../../images/logo.png" />
  </head>

  <body>
    <?php date_default_timezone_set('America/Edmonton');
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "winters";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    // Check connection
    if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
    }

    $sql2 = "SELECT * FROM users
WHERE id='{$_SESSION['id']}'";
    $result2 = mysqli_query($conn, $sql2);

    $sql3 = "SELECT * FROM users
    WHERE id='{$_SESSION['id']}'";
    $result3 = mysqli_query($conn, $sql3);

    $sql4 = "SELECT * FROM notifications
        WHERE uname='$uname' AND status_all=1
        ORDER BY id DESC LIMIT 2";

    $result4 = mysqli_query($conn, $sql4);

    $sql6 = "SELECT * FROM notifications
      WHERE status_all=1";
    $result6 = mysqli_query($conn, $sql6);
    // output data of each row
    $results_per_page = 20;

    //find the total number of results stored in the database  
    $query = "select *from orders";
    $result = mysqli_query($conn, $query);
    $number_of_result = mysqli_num_rows($result);

    //determine the total number of pages available  
    $number_of_page = ceil($number_of_result / $results_per_page);

    //determine which page number visitor is currently on  
    if (!isset($_GET['page'])) {
      $page = 1;
    } else {
      $page = $_GET['page'];
    }

    //determine the sql LIMIT starting number for the results on the displaying page  
    $page_first_result = ($page - 1) * $results_per_page;

    //retrieve the selected results from database   
    $query = "SELECT *FROM orders LIMIT " . $page_first_result . ',' . $results_per_page;
    $result = mysqli_query($conn, $query);
    ?>
    <div class="container-scroller">
      <?php while ($row3 = mysqli_fetch_assoc($result3)) {
        $id = $row3["id"];
        $name = $row3["name"];
        $uname = $row3["uname"];
        $sname = $row3["sname"];
        $pic = $row3["pic"];
        $utype = $row3["utype"];
        $phone = $row3["phone"];
        $add1 = $row3["add1"];
        $add2 = $row3["add2"];
        $pcode = $row3["pcode"];
        $email = $row3["email"];

      ?>
        <!-- partial:../../partials/_navbar.html -->
        <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
          <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start" style="background: #232227;height: 110px;">
            <div class="me-3" style="margin-top: 25px;">
              <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize" style="color: #ffffff;">
                <span class="icon-menu"></span>
              </button>
            </div>
            <div>
              <a class="navbar-brand brand-logo" href="../../index.php">
                <img style="width: 100px;
    height: 100px;margin-top:25px" src="../../images/logo.png" alt="logo" /> </a>

            </div>
          </div>
          <div class="navbar-menu-wrapper d-flex align-items-top" style="background: #34B1AA;margin-top: -13px;">
            <ul class="navbar-nav">

              <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
                <h1 class="welcome-text" style="color: #FFFFFF;">Good Morning, <span class="text-black fw-bold"><?php echo $name; ?></span></h1>
                <h3 class="welcome-sub-text" style="color: #FFFFFF;">Your order summary</h3>
              </li>
            </ul>
            <ul class="navbar-nav ms-auto">
              <li class="nav-item dropdown">

                <a href="../email/customer-email.php">
                  <i class="icon-mail icon-lg" style="color: #FFFFFF;font-size: 24px;"></i>
                </a>

              </li>


              <li class="nav-item dropdown">
                <a class="nav-link count-indicator" id="countDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="icon-bell" style="color: #FFFFFF;"></i>
                  <?php while ($row6 = mysqli_fetch_assoc($result6)) {
                    $id = $row6["id"];
                    $status_all = $row6["status_all"];
                    if (!empty($row6)) {

                  ?>
                      <span class="count"></span>
                  <?php
                    }
                  }
                  ?>
                </a>

                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0" aria-labelledby="notificationDropdown">
                  <a class="dropdown-item py-3 border-bottom" href="../notifications/notifications.php">

                    <span class="badge badge-pill badge-primary float-right" style="margin-left: 51px;">View all notifications</span>
                  </a>

                  <a class="dropdown-item preview-item py-3" href="../notifications/settings.php">
                    <div class="preview-thumbnail">
                      <i class="mdi mdi-settings m-auto text-primary"></i>
                    </div>

                    <div class="preview-item-content">


                      <h6 class="preview-subject fw-normal text-dark mb-1">Settings</h6>


                    </div>
                  </a>
                  <?php while ($row4 = mysqli_fetch_assoc($result4)) {
                    $id = $row4["id"];
                    $uname = $row4["uname"];
                    $message = $row4["message"];
                    $ndate = $row4["ndate"];

                  ?>
                    <a class="dropdown-item preview-item py-3">
                      <div class="preview-thumbnail">
                        <i class="mdi mdi-airballoon m-auto text-primary"></i>
                      </div>

                      <div class="preview-item-content">
                        <h6 class="preview-subject fw-normal text-dark mb-1">
                          <?php echo $message; ?>
                        </h6>
                        <p class="fw-light small-text mb-0"><?php echo $ndate; ?></p>
                      </div>

                    </a>
                  <?php }
                  ?>
                </div>

              </li>

              <?php while ($row2 = mysqli_fetch_assoc($result2)) {
                $id = $row2["id"];
                $uname = $row2["uname"];
                $sname = $row2["sname"];
                $pic = $row2["pic"];

                $phone = $row2["phone"];
                $add1 = $row2["add1"];
                $add2 = $row2["add2"];
                $pcode = $row2["pcode"];
                $email = $row2["email"];

              ?>
                <li class="nav-item dropdown d-none d-lg-block user-dropdown">
                  <?php if (!empty($pic)) { ?>
                    <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                      <img class="img-xs rounded-circle" src="../../uploads/<?php echo $pic; ?>" alt="Profile image"> </a>
                  <?php
                  } else { ?>
                    <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                      <img class="img-xs rounded-circle" src="../../images/default.jpg" alt="Profile image"> </a>
                  <?php
                  }
                  ?>

                  <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                    <div class="dropdown-header text-center">
                      <?php if (!empty($pic)) { ?>
                        <img width="42px" height="42px" class="img-md rounded-circle" src="../../uploads/<?php echo $pic; ?>" alt="Profile image">
                      <?php
                      } else { ?> <img width="42px" height="42px" class="img-md rounded-circle" src="../../images/default.jpg" alt="Profile image">
                      <?php
                      }
                      ?>
                      <p class="mb-1 mt-3 font-weight-semibold"><?php echo $uname; ?></p>
                      <p class="fw-light text-muted mb-0"><?php echo $email; ?></p>
                    </div>

                    <a class="dropdown-item" href="../users/profile.php"><i class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i> My
                      Profile </a>
                    <a class="dropdown-item"><i class="dropdown-item-icon mdi mdi-message-text-outline text-primary me-2"></i>
                      Messages</a>

                    <a class="dropdown-item" href="../users/logout.php"><i class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>Sign Out</a>
                  </div>
                </li>
              <?php
              }
              ?>
            </ul>
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
              <span class="mdi mdi-menu"></span>
            </button>
          </div>
        </nav>
      <?php
      }
      ?>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:../../partials/_settings-panel.html -->

        <div id="right-sidebar" class="settings-panel">
          <i class="settings-close ti-close"></i>
          <ul class="nav nav-tabs border-top" id="setting-panel" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="todo-tab" data-bs-toggle="tab" href="#todo-section" role="tab" aria-controls="todo-section" aria-expanded="true">TO DO LIST</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="chats-tab" data-bs-toggle="tab" href="#chats-section" role="tab" aria-controls="chats-section">CHATS</a>
            </li>
          </ul>
          <div class="tab-content" id="setting-content">
            <div class="tab-pane fade show active scroll-wrapper" id="todo-section" role="tabpanel" aria-labelledby="todo-section">
              <div class="add-items d-flex px-3 mb-0">
                <form class="form w-100">
                  <div class="form-group d-flex">
                    <input type="text" class="form-control todo-list-input" placeholder="Add To-do">
                    <button type="submit" class="add btn btn-primary todo-list-add-btn" id="add-task">Add</button>
                  </div>
                </form>
              </div>
              <div class="list-wrapper px-3">
                <ul class="d-flex flex-column-reverse todo-list">
                  <li>
                    <div class="form-check">
                      <label class="form-check-label">
                        <input class="checkbox" type="checkbox">
                        Team review meeting at 3.00 PM
                      </label>
                    </div>
                    <i class="remove ti-close"></i>
                  </li>
                  <li>
                    <div class="form-check">
                      <label class="form-check-label">
                        <input class="checkbox" type="checkbox">
                        Prepare for presentation
                      </label>
                    </div>
                    <i class="remove ti-close"></i>
                  </li>
                  <li>
                    <div class="form-check">
                      <label class="form-check-label">
                        <input class="checkbox" type="checkbox">
                        Resolve all the low priority tickets due today
                      </label>
                    </div>
                    <i class="remove ti-close"></i>
                  </li>
                  <li class="completed">
                    <div class="form-check">
                      <label class="form-check-label">
                        <input class="checkbox" type="checkbox" checked>
                        Schedule meeting for next week
                      </label>
                    </div>
                    <i class="remove ti-close"></i>
                  </li>
                  <li class="completed">
                    <div class="form-check">
                      <label class="form-check-label">
                        <input class="checkbox" type="checkbox" checked>
                        Project review
                      </label>
                    </div>
                    <i class="remove ti-close"></i>
                  </li>
                </ul>
              </div>
              <h4 class="px-3 text-muted mt-5 fw-light mb-0">Events</h4>
              <div class="events pt-4 px-3">
                <div class="wrapper d-flex mb-2">
                  <i class="ti-control-record text-primary me-2"></i>
                  <span>Feb 11 2018</span>
                </div>
                <p class="mb-0 font-weight-thin text-gray">Creating component page build a js</p>
                <p class="text-gray mb-0">The total number of sessions</p>
              </div>
              <div class="events pt-4 px-3">
                <div class="wrapper d-flex mb-2">
                  <i class="ti-control-record text-primary me-2"></i>
                  <span>Feb 7 2018</span>
                </div>
                <p class="mb-0 font-weight-thin text-gray">Meeting with Alisa</p>
                <p class="text-gray mb-0 ">Call Sarah Graves</p>
              </div>
            </div>
            <!-- To do section tab ends -->
            <div class="tab-pane fade" id="chats-section" role="tabpanel" aria-labelledby="chats-section">
              <div class="d-flex align-items-center justify-content-between border-bottom">
                <p class="settings-heading border-top-0 mb-3 pl-3 pt-0 border-bottom-0 pb-0">Friends</p>
                <small class="settings-heading border-top-0 mb-3 pt-0 border-bottom-0 pb-0 pr-3 fw-normal">See All</small>
              </div>
              <ul class="chat-list">
                <li class="list active">
                  <div class="profile"><img src="../../images/faces/face1.jpg" alt="image"><span class="online"></span></div>
                  <div class="info">
                    <p>Thomas Douglas</p>
                    <p>Available</p>
                  </div>
                  <small class="text-muted my-auto">19 min</small>
                </li>
                <li class="list">
                  <div class="profile"><img src="../../images/faces/face2.jpg" alt="image"><span class="offline"></span></div>
                  <div class="info">
                    <div class="wrapper d-flex">
                      <p>Catherine</p>
                    </div>
                    <p>Away</p>
                  </div>
                  <div class="badge badge-success badge-pill my-auto mx-2">4</div>
                  <small class="text-muted my-auto">23 min</small>
                </li>
                <li class="list">
                  <div class="profile"><img src="../../images/faces/face3.jpg" alt="image"><span class="online"></span></div>
                  <div class="info">
                    <p>Daniel Russell</p>
                    <p>Available</p>
                  </div>
                  <small class="text-muted my-auto">14 min</small>
                </li>
                <li class="list">
                  <div class="profile"><img src="../../images/faces/face4.jpg" alt="image"><span class="offline"></span></div>
                  <div class="info">
                    <p>James Richardson</p>
                    <p>Away</p>
                  </div>
                  <small class="text-muted my-auto">2 min</small>
                </li>
                <li class="list">
                  <div class="profile"><img src="../../images/faces/face5.jpg" alt="image"><span class="online"></span></div>
                  <div class="info">
                    <p>Madeline Kennedy</p>
                    <p>Available</p>
                  </div>
                  <small class="text-muted my-auto">5 min</small>
                </li>
                <li class="list">
                  <div class="profile"><img src="../../images/faces/face6.jpg" alt="image"><span class="online"></span></div>
                  <div class="info">
                    <p>Sarah Graves</p>
                    <p>Available</p>
                  </div>
                  <small class="text-muted my-auto">47 min</small>
                </li>
              </ul>
            </div>
            <!-- chat tab ends -->
          </div>
        </div>
        <!-- partial -->
        <!-- partial:../../partials/_sidebar.html -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar" style="background: #232227;margin-top: 10px;">
          <ul class="nav" style="margin-top: 25px">
            <?php if ($utype == "Super Admin") { ?>
              <li class="nav-item nav-category" style="color: #FFFFFF;">Summary</li>
              <li class="nav-item">
                <a class="nav-link" href="../../index.php" style="background: #232227;
    color: #ffffff;">
                  <i class="mdi mdi-grid-large menu-icon" style="color: #FFFFFF;"></i>
                  <span class="menu-title" style="color: #FFFFFF;background: #232227;">Detailed Summary</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="../dashboard/add-orders.php" style="background: #232227;
    color: #ffffff;">
                  <i class="mdi mdi-plus-box" style="color: #FFFFFF;font-size: 22px;"></i>
                  <span class="menu-title" style="color: #FFFFFF;background: #232227;margin-left: 16px;font-weight:normal">Add Summary</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="../dashboard/add-load.php" style="background: #232227;
    color: #ffffff;">
                  <i class="fa-solid fa-plus" style="color: #FFFFFF;font-size: 22px;"></i>
                  <span class="menu-title" style="color: #FFFFFF;background: #232227;margin-left: 16px;">Add Load</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="../dashboard/loads.php" style="background: #232227;
    color: #ffffff;">
                  <i class="fa-sharp fa-solid fa-truck-ramp-box" style="color: #FFFFFF;font-size: 22px;"></i>
                  <span class="menu-title" style="color: #FFFFFF;background: #232227;margin-left: 16px;">Loads</span>
                </a>
              </li>
            <?php
            }
            ?>
            <li class="nav-item nav-category" style="color: #FFFFFF;">Customers</li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic" style="background: #232227;
    color: #ffffff">
                <i class="menu-icon mdi mdi-account-multiple" style="color: #FFFFFF;"></i>
                <span class="menu-title" style="color: #FFFFFF;background: #232227;">Customers</span>
                <i class="menu-arrow" style="color: #FFFFFF;"></i>
              </a>
              <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu" style="background: #1a1f26;">
                  <li class="nav-item"> <a class="nav-link" href="../customers/customers.php" style="color: white;">Customers</a></li>
                  <li class="nav-item"> <a class="nav-link" href="../customers/add-customer.php" style="color: white;">Add New Customer</a></li>
                </ul>
              </div>
            </li>
            <li class="nav-item nav-category" style="color: #FFFFFF;">Orders and Invoices</li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts" style="background: #232227;
    color: #ffffff;">
                <i class="menu-icon mdi mdi-cart" style="color: #FFFFFF;"></i>
                <span class="menu-title" style="color: #FFFFFF;background: #232227;">Products</span>
                <i class="menu-arrow" style="color: #FFFFFF;"></i>
              </a>
              <div class="collapse" id="charts">
                <ul class="nav flex-column sub-menu" style="background: #1a1f26;">
                  <li class="nav-item"> <a class="nav-link" href="../products/products.php" style="color: #FFFFFF;">Products</a></li>
                  <li class="nav-item"> <a class="nav-link" href="../products/add-product.php" style="color: #FFFFFF;">Add New Product</a></li>
                </ul>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#tables" aria-expanded="false" aria-controls="tables" style="background: #232227;
    color: #ffffff;border-left: 2px solid white;border-radius: 0px">
                <i class="menu-icon mdi mdi-phone-incoming" style="color: #FFFFFF;"></i>
                <span class="menu-title" style="color: #FFFFFF;background: #232227;">Orders</span>
                <i class="menu-arrow" style="color: #FFFFFF;"></i>
              </a>
              <div class="collapse" id="tables">
                <ul class="nav flex-column sub-menu" style="background: #1a1f26;">
                  <li class="nav-item"> <a class="nav-link" href="orders.php" style="color: #FFFFFF;">Orders</a></li>
                  <li class="nav-item"> <a class="nav-link" href="add-order.php" style="color: #FFFFFF;">Add New Order</a></li>
                </ul>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#icons" aria-expanded="false" aria-controls="icons" style="background: #232227;
    color: #ffffff;">
                <i class="menu-icon mdi mdi-email-open" style="color: #FFFFFF;"></i>
                <span class="menu-title" style="color: #FFFFFF;background: #232227;">Invoices</span>
                <i class="menu-arrow" style="color: #FFFFFF;"></i>
              </a>
              <div class="collapse" id="icons">
                <ul class="nav flex-column sub-menu" style="background: #1a1f26;">
                  <li class="nav-item"> <a class="nav-link" href="../invoices/invoices.php" style="color: #FFFFFF;">Invoices</a></li>
                </ul>
              </div>
            </li>
            <li class="nav-item nav-category" style="color: #FFFFFF;">Users</li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth" style="background: #232227;
    color: #ffffff;">
                <i class="menu-icon mdi mdi-account-circle" style="color: #FFFFFF;"></i>
                <span class="menu-title" style="color: #FFFFFF;background: #232227;">Users</span>
                <i class="menu-arrow" style="color: #FFFFFF;"></i>
              </a>
              <div class="collapse" id="auth">
                <ul class="nav flex-column sub-menu" style="background: #1a1f26;">
                  <li class="nav-item"> <a class="nav-link" href="../users/login.php" style="color: #FFFFFF;">Login</a></li>
                  <li class="nav-item"> <a class="nav-link" href="../users/register.php" style="color: #FFFFFF;">Register</a></li>
                  <li class="nav-item"> <a class="nav-link" href="../users/users.php" style="color: #FFFFFF;">All Users</a></li>
                </ul>
              </div>
            </li>

          </ul>
        </nav>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper" style="zoom: 79%;">
            <div class="row">
              <div class="col-lg-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">

                    <div class="table-responsive">

                      <form method="GET">
                        <label for="exampleInputConfirmPassword1">Dates Between</label>
                        <div id="datepicker-popup" class="input-group date datepicker navbar-date-picker" style="width:25%">
                          <span class="input-group-addon input-group-prepend border-right">
                            <span class="icon-calendar input-group-text calendar-icon" style="color: black;"></span>
                          </span>
                          <input type="text" id="datepicker-8" class="form-control" name="sdate" value="<?php if (isset($_GET['sdate'])) {
                                                                                                          echo $_GET['sdate'];
                                                                                                        } ?>">
                        </div><br>


                        <div id="datepicker-popup" class="input-group date datepicker navbar-date-picker" style="width:25%">
                          <span class="input-group-addon input-group-prepend border-right">
                            <span class="icon-calendar input-group-text calendar-icon" style="color: black;"></span>
                          </span>
                          <input type="text" id="datepicker-9" class="form-control" name="edate" value="<?php if (isset($_GET['edate'])) {
                                                                                                          echo $_GET['edate'];
                                                                                                        } ?>">
                        </div><br>
                        <button type="submit" name="submit" class="btn btn-primary me-2" style="float: left;margin-bottom:20px">Search</button>
                      </form> <br><br>

                      <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for orders.." title="Type in a name">

                      <table class="table">
                        <thead>
                          <tr>
                            <th>Order No</th>
                            <th>Invoice No</th>
                            <th>Customer</th>
                            <th>Address</th>
                            <th>Ship Via</th>
                            <th>Ordered</th>
                            <th>Received</th>
                            <th>Shipped</th>
                            <th>Invoiced</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody id="myTable" style="color: black;">
                          <?php
                          $servername = "localhost";
                          $username = "root";
                          $password = "";
                          $dbname = "winters";

                          // Create connection
                          $conn = mysqli_connect($servername, $username, $password, $dbname);
                          // Check connection
                          if (!$conn) {
                            die("Connection failed: " . mysqli_connect_error());
                          }
                          if (isset($_GET['sdate']) && isset($_GET['edate'])) {
                            $sdate = $_GET['sdate'];
                            $sd = strtotime($sdate);
                            $d1 = date("Y-m-d", $sd);
                            $edate = $_GET['edate'];
                            $ed = strtotime($edate);
                            $d2 = date("Y-m-d", $ed);

                            $sql = "SELECT * FROM orders
                            WHERE orderdate>= '$d1' AND orderdate<='$d2'";
                            $result = mysqli_query($conn, $sql);

                            // output data of each row
                            if (mysqli_num_rows($result) > 0) {
                              foreach ($result as $row) {
                                $id = $row["id"];
                                $orderno = $row["orderno"];
                                $invoiceno = $row["invoiceno"];
                                $customer = $row["customer"];
                                $address_ship = $row["address_ship"];
                                $shipvia = $row["shipvia"];
                                $orderdate = $row["orderdate"];
                                $reqdate = $row["reqdate"];
                                $shipdate = $row["shipdate"];
                                $istatus = $row["istatus"];

                          ?>
                                <tr>
                                  <td style="padding: 0px 0px 0px 3px !important;"><?php echo $orderno; ?></td>
                                  <td style="padding: 0px 0px 0px 3px !important;"><?php echo $invoiceno; ?></td>
                                  <td style="padding: 0px 0px 0px 3px !important;"><?php echo $customer; ?></td>
                                  <td style="padding: 0px 0px 0px 3px !important;"><?php echo $address_ship; ?></td>
                                  <td style="padding: 0px 0px 0px 3px !important;"><?php echo $shipvia; ?></td>
                                  <td style="padding: 0px 0px 0px 3px !important;"><?php echo $orderdate; ?></td>
                                  <td style="padding: 0px 0px 0px 3px !important;"><?php echo $reqdate; ?></td>
                                  <td style="padding: 0px 0px 0px 3px !important;"><?php echo $shipdate; ?></td>
                                  <td style="padding: 0px 0px 0px 3px !important;">
                                    <?php if ($istatus == 1) { ?>
                                      <label class="badge rounded-pill bg-success">Invoiced</label>
                                    <?php } else if ($istatus == 0) { ?>
                                      <label class="badge rounded-pill bg-warning text-dark font-weight-bold">Pending</label>
                                  <?php
                                    }
                                  } ?>
                                  </td>
                                  <td style="padding: 0px 0px 0px 2px !important;">
                                    <div class="btn-group" style="padding: 6px 3px 6px 0px;height:50px">

                                      <a title="Scan Order" class="btn btn-success" style="padding: 8px;" href="scan.php?orderno=<?php echo $orderno; ?>"> <i class="fa-sharp fa-solid fa-barcode" style="font-size: 10px; padding: 0px 8px 3px 8px;"></i></a>



                                      <a title="Edit" href="edit-order.php?orderno=<?php echo $orderno; ?>" class="btn btn-primary btnEditSale" style="padding: 8px"><i class="fa-sharp fa-solid fa-pencil" style="font-size: 10px; padding: 0px 8px 3px 8px;"></i> </a>

                                      <a title="Delete" href="../../backend/orders/delete-order.php?id=<?php echo $id; ?>&status_ord=<?php echo $status_ord; ?>" style="padding: 8px" class="btn btn-danger btnDeleteSale"><i class="fa-sharp fa-regular fa-trash-can" style="font-size: 10px; padding: 0px 8px 3px 8px;"></i></a>


                                    </div>
                                  </td>
                                </tr>
                                <?php
                              } else {
                                echo "Sorry no records found";
                              }
                            } else {
                              $sql = "SELECT * FROM orders";
                              $result = mysqli_query($conn, $sql);

                              // output data of each row
                              if (mysqli_num_rows($result) > 0) {
                                foreach ($result as $row) {
                                  $id = $row["id"];
                                  $orderno = $row["orderno"];
                                  $invoiceno = $row["invoiceno"];
                                  $customer = $row["customer"];
                                  $address1 = $row["address1"];
                                  $shipvia = $row["shipvia"];
                                  $orderdate = $row["orderdate"];
                                  $reqdate = $row["reqdate"];
                                  $shipdate = $row["shipdate"];
                                  $istatus = $row["istatus"];

                                ?>
                                  <tr>
                                    <td style="padding: 0px 0px 0px 3px !important;"><?php echo $orderno; ?></td>
                                    <td style="padding: 0px 0px 0px 3px !important;"><?php echo $invoiceno; ?></td>
                                    <td style="padding: 0px 0px 0px 3px !important;"><?php echo $customer; ?></td>
                                    <td style="padding: 0px 0px 0px 3px !important;"><?php echo $address1; ?></td>
                                    <td style="padding: 0px 0px 0px 3px !important;"><?php echo $shipvia; ?></td>
                                    <td style="padding: 0px 0px 0px 3px !important;"><?php echo $orderdate; ?></td>
                                    <td style="padding: 0px 0px 0px 3px !important;"><?php echo $reqdate; ?></td>
                                    <td style="padding: 0px 0px 0px 3px !important;"><?php echo $shipdate; ?></td>
                                    <td style="padding: 0px 0px 0px 3px !important;">
                                      <?php if ($istatus == 1) { ?>
                                        <label class="badge rounded-pill bg-success">Invoiced</label>
                                      <?php } else if ($istatus == 0) { ?>
                                        <label class="badge rounded-pill bg-warning text-dark font-weight-bold">Pending</label>
                                      <?php
                                      }
                                      ?>
                                    </td>
                                    <td style="padding: 0px 0px 0px 2px !important;">
                                      <div class="btn-group" style="padding: 6px 3px 6px 0px;height:50px">
                                        <a title="BOL" class="btn btn-dark" style="padding: 8px;" href="BOL.php?orderno=<?php echo $orderno; ?>"> <i class="fa-solid fa-file-invoice" style="font-size: 10px; padding: 0px 8px 3px 8px;"></i></a>
                                        <a title="Scan Order" class="btn btn-success" style="padding: 8px;" href="scan.php?orderno=<?php echo $orderno; ?>"> <i class="fa-sharp fa-solid fa-barcode" style="font-size: 10px; padding: 0px 8px 3px 8px;"></i></a>



                                        <a title="Edit" href="edit-order.php?orderno=<?php echo $orderno; ?>" class="btn btn-primary btnEditSale" style="padding: 8px"><i class="fa-sharp fa-solid fa-pencil" style="font-size: 10px; padding: 0px 8px 3px 8px;"></i> </a>

                                        <a title="Delete" href="../../backend/orders/delete-order.php?id=<?php echo $id; ?>" style="padding: 8px" class="btn btn-danger btnDeleteSale"><i class="fa-sharp fa-regular fa-trash-can" style="font-size: 10px; padding: 0px 8px 3px 8px;"></i></a>


                                      </div>
                                    </td>
                                  </tr>
                            <?php
                                }
                              } else {
                                echo "Sorry no records found";
                              }
                            }
                            ?>


                        </tbody>
                      </table><br>
                      <script>
                        function myFunction() {
                          var input, filter, table, tr, td, i, txtValue;
                          input = document.getElementById("myInput");
                          filter = input.value.toUpperCase();
                          table = document.getElementById("myTable");
                          tr = table.getElementsByTagName("tr");
                          for (i = 0; i < tr.length; i++) {
                            td = tr[i];
                            if (td) {
                              txtValue = td.textContent || td.innerText;
                              if (txtValue.toUpperCase().indexOf(filter) > -1) {
                                tr[i].style.display = "";
                              } else {
                                tr[i].style.display = "none";
                              }
                            }
                          }
                        }
                      </script>
                      <?php
                      if (mysqli_num_rows($result) > 1) {
                      ?>
                        <div class="pagination">
                          <a href="orders.php?page=' . $page_first_result . '" class="left-arrow">&laquo;</a>
                          <!--display the link of the pages in URL -->
                          <?php
                          for ($page = 1; $page <= $number_of_page; $page++) {
                            echo '<a class="btn btn-primary" style="color:white;margin-right: 2px" href = "orders.php?page=' . $page . '">' . $page . ' </a>';
                          }  ?>
                          <a href="orders.php?page=' . $number_of_page . '" class="right-arrow">&raquo;</a>

                        </div>
                      <?php }
                      ?>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>
          <!-- content-wrapper ends -->
          <!-- partial:../../partials/_footer.html -->
          <footer class="footer">

            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Copyright © 2023. All rights
              reserved.</span>
        </div>
        </footer>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="../../vendors/js/vendor.bundle.base.js"></script>

    <script>

    </script>

    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="../../vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="../../js/off-canvas.js"></script>
    <script src="../../js/hoverable-collapse.js"></script>
    <script src="../../js/template.js"></script>
    <script src="../../js/settings.js"></script>
    <script src="../../js/todolist.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <!-- End custom js for this page-->
  </body>

  </html>
<?php } else {
  header('Location:../users/login.php');
}
?>