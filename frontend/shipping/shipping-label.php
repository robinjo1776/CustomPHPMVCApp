<?php session_start();
$uid = $_SESSION['id'];
$uname = $_SESSION['username'];
if (isset($uid)) {
    include "../../dbConfig.php";
    $invoiceno = $_GET["invoiceno"];

    $sql = "SELECT
            ord.id,
            ord.orderno,
            ord.invoiceno,
            cus.`name` as customer,
            ord.ord_address1,
            ord.ord_address2,
            ord.ord_city,
            ord.ord_province,
            ord.ord_postal_code,
            shv.shipvia as shipvia,
            ord.created_at,
            ord.reqdate,
            ord.shipdate,
            ord.istatus,
            ord.invoicedate,
            ord.orderedby,
            ord.ord_terms as terms,
            ord.status_ord,
            loads.pnumber,
            loads.loading_date,
            loads.cases,
            loads.birds
          FROM
            orders AS ord
          LEFT JOIN customers AS cus ON cus.id = ord.customer_id
          LEFT JOIN shipvia AS shv ON shv.id = ord.shipvia
          LEFT JOIN users AS usr ON usr.id = ord.orderedby
          LEFT JOIN loads AS loads ON loads.invoiceno = ord.invoiceno
          WHERE ord.invoiceno='$invoiceno'";

    $orderDetailsResult = mysqli_query($conn, $sql);
    $orderDetails = [];
    if (mysqli_num_rows($orderDetailsResult) > 0) {
        $orderDetails = mysqli_fetch_assoc($orderDetailsResult);
    }

    $orderItemsSql = "SELECT items.orderid, 
        products.description, 
        items.code, items.weight as tw, 
        items.qty as tq, items.price as tp, 
        items.minw, items.maxw 
        FROM items
        LEFT JOIN products ON items.code=products.code
        WHERE items.orderid='" . $orderDetails["orderno"] . "'";
    $orderItems = mysqli_query($conn, $orderItemsSql);

    $sumBoxSql = "SELECT sum(items.box) as totalBox 
        FROM items
        WHERE items.orderid='" . $orderDetails["orderno"] . "'";
    $sumBoxResult = mysqli_query($conn, $sumBoxSql);
    $sumBoxData = [];
    if (mysqli_num_rows($sumBoxResult) > 0) {
        $sumBoxData = mysqli_fetch_assoc($sumBoxResult);
    }
    // var_dump($sumBoxData);
    // die();

    $order_menu_active = "active";
    $pageTitle = "Shipping Label";
?>
    <?php require_once(VIEW_PATH . 'common/header.php'); ?>

    <body>
        <div class="container-fluid-lg">
            <?php require_once(VIEW_PATH . 'common/nav.php'); ?>
            <div class="pdf-print-btn-container" style="width: 4in; margin-bottom: 30px;">
                <div id="printBtnBlock">
                    <button class="btn btn-success" id="printButton">Print <i class="fa fa-print" aria-hidden="true"></i></button>
                    <a target="_blank" href="<?php echo Helper::fullbaseUrl(); ?>frontend/shipping/shipping-label-pdf.php?invoiceno=<?php echo $invoiceno; ?>" class="btn btn-info" id="saveAsPdfButton">Save as PDF <i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
                </div>
            </div>
            <?php require_once(BASEPATH . 'frontend/shipping/shipping-label-content.php'); ?>
            <?php require_once(VIEW_PATH . 'common/footer.php'); ?>
        </div>
    </body>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/4d4bd04373.js" crossorigin="anonymous"></script>

    <script>
        var BASE_URL = '<?php echo Helper::fullbaseUrl(); ?>';
        var API_BASE_URL = '<?php echo Helper::fullbaseUrl(); ?>api/';
    </script>
    <script src="<?php echo Helper::fullbaseUrl(); ?>assets/js/common_lib.js"></script>
    <script src="<?php echo Helper::fullbaseUrl(); ?>assets/js/common.js"></script>
    <script src="<?php echo Helper::fullbaseUrl(); ?>assets/js/shipping-label.js"></script>

    </html>
<?php } else {
    header('Location:../users/login.php');
}
?>