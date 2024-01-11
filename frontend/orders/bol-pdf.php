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
    $num_rows = mysqli_num_rows($orderDetailsResult);
    if ($num_rows > 0) {
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
        items.box as totalBox 
    FROM items
        LEFT JOIN products ON items.code=products.code
        LEFT JOIN product_category ON product_category.id = products.cat_id
    WHERE items.orderid='" . $orderDetails["orderno"] . "' 
        ORDER BY product_category.cat_name";
    $orderItems = mysqli_query($conn, $orderItemsSql);

    $subTotal = 0;
    $total = 0;
    $order_menu_active = "active";
    $pageTitle = "Bill of Lading";

    require_once BASEPATH . '/vendor/mpdf/vendor/autoload.php';

    $mpdf = new \Mpdf\Mpdf();
    ob_start();
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title><?php echo $pageTitle; ?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1" shrink-to-fit="no">
    </head>

    <body>
        <?php require_once(BASEPATH . 'frontend/orders/bol-content-pdf.php'); ?>
    </body>

    </html>
<?php
    // Now collect the output buffer into a variable
    $html = ob_get_contents();
    ob_end_clean();

    // send the captured HTML from the output buffer to the mPDF class for processing
    $mpdf->WriteHTML($html);
    $mpdf->Output();
} else {
    header('Location:../users/login.php');
}
?>