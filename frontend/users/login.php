<!DOCTYPE html>
<html lang="en">

<?php
require_once "../../dbConfig.php";
?>

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Login</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="<?php echo Helper::fullbaseUrl(); ?>vendors/feather/feather.css">
  <link rel="stylesheet" href="<?php echo Helper::fullbaseUrl(); ?>vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="<?php echo Helper::fullbaseUrl(); ?>vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="<?php echo Helper::fullbaseUrl(); ?>vendors/typicons/typicons.css">
  <link rel="stylesheet" href="<?php echo Helper::fullbaseUrl(); ?>vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="<?php echo Helper::fullbaseUrl(); ?>vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="<?php echo Helper::fullbaseUrl(); ?>css/vertical-layout-light/style.css">
  <link rel="stylesheet" href="<?php echo Helper::fullbaseUrl(); ?>assets/css/custom.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="<?php echo Helper::fullbaseUrl(); ?>images/brand.png" />
</head>

<body class="login-page">
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper auth px-0">
        <div class="row w-100 mx-0 login-wrapper">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left px-4 px-sm-5">
              <div class="brand-logo text-center">
                <img src="<?php echo Helper::fullbaseUrl(); ?>images/brand.png" alt="logo" style="background:black">
              </div>
              <h4 class="text-white">Hello! let's get started</h4>
              <h6 class="fw-light text-white">Sign in to continue.</h6>
              <form action="login-code.php" method="POST" class="pt-3">
                <?php if (isset($_GET['error'])) { ?>

                  <div class="danger"><?php echo $_GET['error']; ?></div>

                <?php } ?>
                <div class="form-group">
                  <input type="text" name="uname" class="form-control form-control-lg" id="uname" placeholder="Username">
                </div>
                <div class="form-group">
                  <input type="password" name="pass" class="form-control form-control-lg" id="pass" placeholder="Password">
                </div>
                <div class="mt-3">
                  <input type="submit" name="submit" value="SIGN IN" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                </div>
                <div class="my-2 d-flex justify-content-between align-items-center">
                  <!-- <a href="#" class="auth-link text-black text-white">Forgot password?</a> -->
                </div>
                <!-- <div class="text-center mt-4 fw-light text-white">
                  Don't have an account? <a href="register.php" class="text-primary">Create</a>
                </div> -->
              </form>
              <?php if (isset($_COOKIE['uname']) and isset($_COOKIE['pass'])) {
                $uname = $_COOKIE['uname'];
                $pass = $_COOKIE['pass'];
                echo "<script>
                document.getElementById('uname').value='$uname';
                document.getElementById('pass').value='$pass';
                <script>";
              }
              ?>
            </div>
          </div>
        </div>
        <div class="row w-100 mx-0">
          <div class="col-md-12 text-right">
            <ul class="nav nav-tabs footer-nav-tabs">
              <li>
                <a href="<?php echo Helper::fullbaseUrl(); ?>terms-service.php" class="">Terms & Service</a>&nbsp;<span>|</span>
              </li>
              <li>
                <a href="<?php echo Helper::fullbaseUrl(); ?>privacy-policy.php" class="">Privacy Policy</a>&nbsp;<span>|</span>
              </li>
              <li>
                <a href="<?php echo Helper::fullbaseUrl(); ?>contact-us.php" class="">Contact Us</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
  </div>

  <script src="<?php echo Helper::fullbaseUrl(); ?>vendors/js/vendor.bundle.base.js"></script>
  <script src="<?php echo Helper::fullbaseUrl(); ?>vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
  <script src="<?php echo Helper::fullbaseUrl(); ?>js/off-canvas.js"></script>
  <script src="<?php echo Helper::fullbaseUrl(); ?>js/hoverable-collapse.js"></script>
  <script src="<?php echo Helper::fullbaseUrl(); ?>js/template.js"></script>
  <script src="<?php echo Helper::fullbaseUrl(); ?>js/settings.js"></script>
  <script src="<?php echo Helper::fullbaseUrl(); ?>js/todolist.js"></script>
</body>

</html>