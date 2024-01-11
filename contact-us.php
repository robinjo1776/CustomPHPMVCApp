<?php
date_default_timezone_set('America/Edmonton');
include "dbConfig.php";

$contact_us_menu_active = "active";
$pageTitle = "Contact Us";
?>
<?php require_once(VIEW_PATH . 'common/header.php'); ?>

<body>
    <div class="container-fluid-lg">
        <?php require_once(VIEW_PATH . 'common/nav.php'); ?>
        <div class="inner-banner-container">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1><?php echo $pageTitle; ?></h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-container">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <p>If you have any questions about this Privacy Policy, You can contact us:</p>
                        Email: <a href="mailto:lyle@wintersturkeys.ca" title="Email Lyle">lyle@wintersturkeys.ca</a>
                    </div>
                </div>
            </div>
        </div>
        <?php require_once(VIEW_PATH . 'common/footer.php'); ?>
    </div>
</body>

</html>