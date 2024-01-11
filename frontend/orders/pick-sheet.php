<?php session_start();
$uid = $_SESSION['id'];
$uname = $_SESSION['username'];
if (isset($uid)) {
    date_default_timezone_set('America/Edmonton');
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
            ord.status_ord
          FROM
            orders AS ord
          LEFT JOIN customers AS cus ON cus.id = ord.customer_id
          LEFT JOIN shipvia AS shv ON shv.id = ord.shipvia
          LEFT JOIN users AS usr ON usr.id = ord.orderedby
          WHERE invoiceno='$invoiceno'";

    $orderDetailsResult = mysqli_query($conn, $sql);
    $orderDetails = [];
    if (mysqli_num_rows($orderDetailsResult) > 0) {
        $orderDetails = mysqli_fetch_assoc($orderDetailsResult);
    }

    $orderItemsSql = "SELECT items.orderid, 
        products.description, 
        items.code, 
        items.weight as tw, 
        items.qty as tq, 
        items.price as tp, 
        items.minw, items.maxw, 
        product_category.cat_name as cat_code,
        product_category.cat_des,
        items.box as totalBox,
        product_subcategory.scat_name
    FROM items
        LEFT JOIN products ON items.code=products.code        
        LEFT JOIN product_category ON product_category.id = products.cat_id
		LEFT JOIN product_subcategory ON product_subcategory.id = products.scat_id
    WHERE items.orderid='" . $orderDetails["orderno"] . "' 
        ORDER BY product_category.cat_name, product_subcategory.scat_name";
    $orderItems = mysqli_query($conn, $orderItemsSql);

    $order_menu_active = "active";
    $pageTitle = "Pick Sheet";

    $subTotal = 0;
    $total = 0;
    $totalQty = 0;
    $totalWeight = 0;
    $totalBox = 0;
?>
    <?php require_once(VIEW_PATH . 'common/header.php'); ?>

    <body>
        <div class="container-fluid-lg">
            <?php require_once(VIEW_PATH . 'common/nav.php'); ?>
            <div class="container">
                <div class="row">
                    <div id="printBtnBlock" class="col-md-12" style="margin-top: 90px; text-align: right;">
                        <button class="btn btn-success" id="printButton">Print <i class="fa fa-print" aria-hidden="true"></i></button>
                        <a target="_blank" href="<?php echo Helper::fullbaseUrl(); ?>frontend/orders/pick-sheet-pdf.php?invoiceno=<?php echo $invoiceno; ?>" class="btn btn-info" id="saveAsPdfButton">Save as PDF <i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
                    </div>
                    <div class="col-md-12" style="margin-top: 20px; padding-bottom:85px;">
                        <?php require_once(BASEPATH . 'frontend/orders/pick-sheet-content.php'); ?>
                    </div>
                </div>
            </div>
            <?php require_once(VIEW_PATH . 'common/footer.php'); ?>
        </div>
    </body>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/4d4bd04373.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <script>
        var BASE_URL = '<?php echo Helper::fullbaseUrl(); ?>';
        var API_BASE_URL = '<?php echo Helper::fullbaseUrl(); ?>api/';
    </script>
    <script src="<?php echo Helper::fullbaseUrl(); ?>assets/js/common_lib.js"></script>
    <script src="<?php echo Helper::fullbaseUrl(); ?>assets/js/common.js"></script>
    <script src="<?php echo Helper::fullbaseUrl(); ?>assets/js/pick-sheet.js"></script>

    </html>
<?php } else {
    header('Location:../users/login.php');
}
?>