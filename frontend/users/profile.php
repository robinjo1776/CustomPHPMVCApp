<?php error_reporting(E_ALL ^ E_NOTICE);
session_start();
$uid = $_SESSION['id'];
$uname = $_SESSION['username'];
if (isset($uid)) {
  date_default_timezone_set('America/Edmonton');
include "../../dbConfig.php";
?>
  <!DOCTYPE html>

  <html lang="en">

  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Edit User</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../../vendors/feather/feather.css">
    <link rel="stylesheet" href="../../vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../../vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../../vendors/typicons/typicons.css">
    <link rel="stylesheet" href="../../vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="../../vendors/css/vendor.bundle.base.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

    <script src="https://kit.fontawesome.com/4d4bd04373.js" crossorigin="anonymous"></script>

    <link class="jsbin" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />
    <script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/jquery-ui.min.js"></script>

    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="../../vendors/select2/select2.min.css">
    <link rel="stylesheet" href="../../vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="../../css/vertical-layout-light/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="../../images/brand.png" />
  </head>

  <body>
    <?php 

    $sql = "SELECT * FROM users
WHERE id='{$_SESSION['id']}'";
    $result = mysqli_query($conn, $sql);

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
          <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start" style="background: #F4F5F7;height: 110px;">
            <div class="me-3" style="margin-top: 25px;">
              <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize" style="color: #000000;">
                <span class="icon-menu"></span>
              </button>
            </div>
            <div>
              <a class="navbar-brand brand-logo" href="../../index.php">
                <img style="width: 100px;
    height: 100px;" src="../../images/logo.png" alt="logo" /> 
    
    </a>
         <a class="navbar-brand brand-logo-mini" href="../../index.php" style="margin-right: 10px;">
            <img src="../../images/logo.png" alt="logo" />
          </a>
            </div>
          </div>
          <div class="navbar-menu-wrapper d-flex align-items-top" style="background: #F4F5F7;margin-top: -13px;">
            <ul class="navbar-nav">
              <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
              <h1 class="welcome-text" style="color: #000000;">Hello <span class="text-black fw-bold"><?php echo $name; ?></span></h1>

                <h3 class="welcome-sub-text" style="color: #000000;">Your Profile</h3>
              </li>
            </ul>
            <ul class="navbar-nav ms-auto">
              <li class="nav-item dropdown">

                <a href="../email/customer-email.php">
                  <i class="icon-mail icon-lg" style="color: #000000;font-size: 24px;"></i>
                </a>

              </li>


              <li class="nav-item dropdown">
                <a class="nav-link count-indicator" id="countDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="icon-bell" style="color: #000000;"></i>
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
                  <div class="profile"><img src="../../images/faces/face1.jpg" alt="image"><span class="online"></span>
                  </div>
                  <div class="info">
                    <p>Thomas Douglas</p>
                    <p>Available</p>
                  </div>
                  <small class="text-muted my-auto">19 min</small>
                </li>
                <li class="list">
                  <div class="profile"><img src="../../images/faces/face2.jpg" alt="image"><span class="offline"></span>
                  </div>
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
                  <div class="profile"><img src="../../images/faces/face3.jpg" alt="image"><span class="online"></span>
                  </div>
                  <div class="info">
                    <p>Daniel Russell</p>
                    <p>Available</p>
                  </div>
                  <small class="text-muted my-auto">14 min</small>
                </li>
                <li class="list">
                  <div class="profile"><img src="../../images/faces/face4.jpg" alt="image"><span class="offline"></span>
                  </div>
                  <div class="info">
                    <p>James Richardson</p>
                    <p>Away</p>
                  </div>
                  <small class="text-muted my-auto">2 min</small>
                </li>
                <li class="list">
                  <div class="profile"><img src="../../images/faces/face5.jpg" alt="image"><span class="online"></span>
                  </div>
                  <div class="info">
                    <p>Madeline Kennedy</p>
                    <p>Available</p>
                  </div>
                  <small class="text-muted my-auto">5 min</small>
                </li>
                <li class="list">
                  <div class="profile"><img src="../../images/faces/face6.jpg" alt="image"><span class="online"></span>
                  </div>
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
        <nav class="sidebar sidebar-offcanvas" id="sidebar" style="background: #F4F5F7;margin-top: 10px;">
          <ul class="nav" style="margin-top: 25px">
          <li class="nav-item nav-category" style="color: #000000;">Customers</li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic" style="background: #F4F5F7;
    color: #000000">
                <i class="menu-icon mdi mdi-account-multiple" style="color: #000000;"></i>
                <span class="menu-title" style="color: #000000;background: #F4F5F7;">Customers</span>
                <i class="menu-arrow" style="color: #000000;"></i>
              </a>
              <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu" style="background: #F4F5F7;">
                  <li class="nav-item"> <a class="nav-link" href="../customers/customers.php" style="color: black;">Customers</a></li>
                  <li class="nav-item"> <a class="nav-link" href="../customers/add-customer.php" style="color: black;">Add
                      New Customer</a></li>
                </ul>
              </div>
            </li>
            <li class="nav-item nav-category" style="color: #000000;">Orders and Invoices</li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts" style="background: #F4F5F7;
    color: #000000;">
                <i class="menu-icon mdi mdi-cart" style="color: #000000;"></i>
                <span class="menu-title" style="color: #000000;background: #F4F5F7;">Products</span>
                <i class="menu-arrow" style="color: #000000;"></i>
              </a>
              <div class="collapse" id="charts">
                <ul class="nav flex-column sub-menu" style="background: #F4F5F7;">
                  <li class="nav-item"> <a class="nav-link" href="../products/products.php" style="color: #000000;">Products</a></li>
                  <li class="nav-item"> <a class="nav-link" href="../products/add-product.php" style="color: #000000;">Add
                      New
                      Product</a></li>
                </ul>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#tables" aria-expanded="false" aria-controls="tables" style="background: #F4F5F7;
    color: #000000;">
                <i class="menu-icon mdi mdi-phone-incoming" style="color: #000000;"></i>
                <span class="menu-title" style="color: #000000;background: #F4F5F7;">Orders</span>
                <i class="menu-arrow" style="color: #000000;"></i>
              </a>
              <div class="collapse" id="tables">
                <ul class="nav flex-column sub-menu" style="background: #F4F5F7;">
                  <li class="nav-item"> <a class="nav-link" href="../../index.php" style="color: #000000;">Orders</a>
                  </li>
                  <li class="nav-item"> <a class="nav-link" href="../orders/add-order.php" style="color: #000000;">Add New
                      Order</a></li>
                </ul>
              </div>
            </li>
            <?php if ($utype == "Super Admin") { ?>
              <li class="nav-item nav-category" style="color: #000000;">Summary</li>
              <li class="nav-item">
                <a class="nav-link" href="../summary/summary.php" style="background: #F4F5F7;
    color: #000000;">
                  <i class="mdi mdi-grid-large menu-icon" style="color: #000000;"></i>
                  <span class="menu-title" style="color: #000000;background: #F4F5F7;">Detailed Summary</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="../summary/add-orders.php" style="background: #F4F5F7;
    color: #000000;">
                  <i class="mdi mdi-plus-box" style="color: #000000;font-size: 22px;"></i>
                  <span class="menu-title" style="color: #000000;background: #F4F5F7;margin-left: 16px;">Add Summary</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="../summary/loads.php" style="background: #F4F5F7;
    color: #000000;">
                  <i class="fa-sharp fa-solid fa-truck-ramp-box" style="color: #000000;font-size: 22px;"></i>
                  <span class="menu-title" style="color: #000000;background: #F4F5F7;margin-left: 16px;">Loads</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="../summary/add-load.php" style="background: #F4F5F7;
    color: #000000;">
                  <i class="fa-solid fa-plus" style="color: #000000;font-size: 22px;"></i>
                  <span class="menu-title" style="color: #000000;background: #F4F5F7;margin-left: 16px;">Add Load</span>
                </a>
              </li>
            <?php
            }
            ?>
          
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#icons" aria-expanded="false" aria-controls="icons" style="background: #F4F5F7;
    color: #000000;">
                <i class="menu-icon mdi mdi-email-open" style="color: #000000;"></i>
                <span class="menu-title" style="color: #000000;background: #F4F5F7;">Invoices</span>
                <i class="menu-arrow" style="color: #000000;"></i>
              </a>
              <div class="collapse" id="icons">
                <ul class="nav flex-column sub-menu" style="background: #F4F5F7;">
                  <li class="nav-item"> <a class="nav-link" href="../invoices/invoices.php" style="color: #000000;">Invoices</a></li>
                </ul>
              </div>
            </li>
            <li class="nav-item nav-category" style="color: #000000;">Users</li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth" style="background: #F4F5F7;
    color: #000000;border-left: 2px solid black;border-radius: 0px">
                <i class="menu-icon mdi mdi-account-circle" style="color: #000000;"></i>
                <span class="menu-title" style="color: #000000;background: #F4F5F7;">Users</span>
                <i class="menu-arrow" style="color: #000000;"></i>
              </a>
              <div class="collapse" id="auth">
                <ul class="nav flex-column sub-menu" style="background: #F4F5F7;">
                  <li class="nav-item"> <a class="nav-link" href="login.php" style="color: #000000;">Login</a>
                  </li>
                  <li class="nav-item"> <a class="nav-link" href="register.php" style="color: #000000;">Register</a></li>
                  <li class="nav-item"> <a class="nav-link" href="users.php" style="color: #000000;">
                      Users</a></li>
                </ul>
              </div>
            </li>

            <li class="nav-item nav-category" style="color: #000000;">Settings</li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic" style="background: #F4F5F7;
    color: #000000;">
                <i class="menu-icon mdi mdi-settings" style="color: #000000;"></i>
                <span class="menu-title" style="color: black;background: #F4F5F7;">Settings</span>
                <i class="menu-arrow" style="color: #000000;"></i>
              </a>
              <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu" style="background: #F4F5F7;">
                <li class="nav-item"> <a class="nav-link" href="../settings/provinces/provinces.php" style="color: black;">Provinces and Territories</a></li>
                <li class="nav-item"> <a class="nav-link" href="../settings/cities/cities.php" style="color: black;">Cities</a></li>
                  <li class="nav-item"> <a class="nav-link" href="../settings/address-types/add_types.php" style="color: black;">Address Types</a></li>
                  <li class="nav-item"> <a class="nav-link" href="../settings/contact-types/cont_types.php" style="color: black;">Contact Types</a></li>
                <li class="nav-item"> <a class="nav-link" href="../settings/contact-methods/cont_methods.php" style="color: black;">Contact Methods</a></li>
                  <li class="nav-item"> <a class="nav-link" href="../settings/ship-via/ship_via.php" style="color: black;">Ship VIA</a></li>  
                  <li class="nav-item"> <a class="nav-link" href="../settings/units/units.php" style="color: black;">Units</a></li>  
                </ul>
              </div>
            </li>

          </ul>
        </nav>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <?php include '../../backend/users/profile.php';
                    while ($row = mysqli_fetch_assoc($result)) {
                      $id = $row["id"];
                      $uname = $row["uname"];
                      $sname = $row["sname"];
                      $pic = $row["pic"];

                      $phone = $row["phone"];
                      $add1 = $row["add1"];
                      $add2 = $row["add2"];
                      $pcode = $row["pcode"];
                      $email = $row["email"];
                      $pass = $row["pass"];
                      $cpass = $row["cpass"];
                      $con = $row["con"];
                      $status_user = $row["status_user"];
                    ?>
                      <form method="POST" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="forms-sample">
                        <?php if (isset($_GET['message'])) { ?>
                          <p class="message"><?php echo $_GET['message']; ?></p>

                        <?php } ?>
                        <div class="row">
                          <div class="col-md-3 border-right">
                            <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                              <img class="rounded-circle mt-5" alt="../../images/default.jpg" width="150px" height="150px" src="../../uploads/<?php echo $pic; ?>" />

                              <span class="font-weight-bold"><?php echo $uname; ?></span><span class="text-black-50"><?php echo $email; ?></span><br>
                              <span style="margin-left: 120px;">
                                <input type="hidden" name="old_name" value="<?php echo $pic; ?>">

                                <input id="image" type="file" name="fileToUpload"></span>
                              <?php echo $pic; ?>

                            </div>

                          </div>
                          <div class="col-md-5 border-right">
                            <div class="p-3 py-5">
                              <div class="d-flex justify-content-between align-items-center mb-3">
                              </div>
                              <div class="row mt-2">
                                <div class="col-md-6"><label class="labels">First Name</label><input type="text" name="name" class="form-control" value="<?php echo $name; ?>"></div>
                                <div class="col-md-6"><label class="labels">Surname</label><input type="text" name="sname" class="form-control" value="<?php echo $sname; ?>"></div>

                                <div class="col-md-6"><label class="labels">Username</label><input type="text" name="uname" class="form-control" value="<?php echo $uname; ?>"></div>
                                <input type="hidden" name="id" class="form-control" id="exampleInputUsername1" value="<?php echo $id; ?>">

                                <input type="hidden" name="status_user" class="form-control" id="exampleInputUsername1" value="<?php echo $status_user; ?>">
                                <div class="col-md-6"><label class="labels">User Type</label>
                                  <select name="utype" id="cars" class="form-control" value="<?php echo $utype; ?>">
                                    <option value="Super Admin">Super Admin</option>
                                    <option value="Admin">Admin</option>
                                    <option value="User">User</option>
                                  </select>
                                </div>
                              </div>
                            </div>
                            <div class="row mt-3">
                              <div class="col-md-12" style="margin-top: 16px;"><label class="labels">Mobile Number</label><input type="text" name="phone" class="form-control" value="<?php echo $phone; ?>"></div><br><br>
                              <div class="col-md-12" style="margin-top: 16px;"><label class="labels">Address Line 1</label><input type="text" name="add1" class="form-control" value="<?php echo $add1; ?>"></div><br><br>
                              <div class="col-md-12" style="margin-top: 16px;"><label class="labels">Address Line 2</label><input type="text" name="add2" class="form-control" value="<?php echo $add2; ?>"></div><br><br>
                              <div class="col-md-12" style="margin-top: 16px;"><label class="labels">Postcode</label><input type="text" name="pcode" class="form-control" value="<?php echo $pcode; ?>"></div><br><br>
                              <div class="col-md-12" style="margin-top: 16px;"> <label class="labels">Password</label><input type="text" name="pass" name="pass" class="form-control" value="<?php echo $pass; ?>"></div><br><br>
                              <div class="col-md-12" style="margin-top: 16px;"><label class="labels">Confirm Password</label><input type="text" name="cpass" name="cpass" class="form-control" value="<?php echo $cpass; ?>"></div><br><br>
                              <div class="col-md-12" style="margin-top: 16px;"><label class="labels">Email ID</label><input type="text" name="email" name="email" class="form-control" value="<?php echo $email; ?>">
                              </div><br>

                            </div>

                            <div class="mt-5 text-center">
                              <input type="submit" name="submit" class="btn btn-primary me-2" value="Save Profile">
                            </div>

                          </div>
                        </div>

                  </div>
                  </form>
                <?php


                    }

                ?>
                </div>
              </div>
            </div>

          </div>




          <!-- content-wrapper ends -->
          <!-- partial:../../partials/_footer.html -->
          <footer class="footer">

            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Copyright © 2023. All rights
              reserved.</span>

          </footer>
          <!-- partial -->

          <!-- main-panel ends -->
        </div>
      </div>
    </div>
    <!-- page-body-wrapper ends -->

    <!-- container-scroller -->
    <!-- plugins:js -->
    <!--   <script type="text/javascript">
    document.getElementById("cancel").onclick = function () {
        location.href = "customers.php";
    };
</script> -->
    <script src="../../vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="../../vendors/typeahead.js/typeahead.bundle.min.js"></script>
    <script src="../../vendors/select2/select2.min.js"></script>
    <script src="../../vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <!-- End plugin js for this page -->
    <script>
      function readURL(input) {
        if (input.files && input.files[0]) {
          var reader = new FileReader();

          reader.onload = function(e) {
            $('#image')
              .attr('src', e.target.result)
              .width(150)
              .height(200);
          };

          reader.readAsDataURL(input.files[0]);
        }
      }
    </script>
    <!-- inject:js -->
    <script src="../../js/off-canvas.js"></script>
    <script src="../../js/hoverable-collapse.js"></script>
    <script src="../../js/template.js"></script>
    <script src="../../js/settings.js"></script>
    <script src="../../js/todolist.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="../../js/file-upload.js"></script>
    <script src="../../js/typeahead.js"></script>
    <script src="../../js/select2.js"></script>
    <!-- End custom js for this page-->
  </body>

  </html>
<?php } else {
  header('Location:../users/login.php');
}
?>