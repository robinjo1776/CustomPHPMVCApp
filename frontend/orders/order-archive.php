<?php session_start();
$uid = $_SESSION['id'];
$uname = $_SESSION['username'];

date_default_timezone_set('America/Edmonton');
include "../../dbConfig.php";

require_once BASEPATH . '/vendor/qb/vendor/autoload.php';

use QuickBooksOnline\API\DataService\DataService;

if (isset($uid)) {

    $customerSql = "SELECT * FROM customers";
    $customerResult = mysqli_query($conn, $customerSql);

    $usersSql = "SELECT * FROM users";
    $usersResult = mysqli_query($conn, $usersSql);

    $shipviaSql = "SELECT * FROM shipvia";
    $shipviaResult = mysqli_query($conn, $shipviaSql);

    $productSql = "SELECT
	pro.id,
	pro.code,
	pro.description,
	pro.price,
	pro.minw,
	pro.maxw,
	unt.short_name as unit,
	pro.upc,
	pro.item_in_box,
	pro.comments,
	pro.status_prod,
  procat.cat_name
FROM
	products AS pro
LEFT JOIN units AS unt ON unt.id = pro.unit
LEFT JOIN product_category AS procat ON procat.id = pro.cat_id
ORDER BY
	id DESC";
    $productsResult = mysqli_query($conn, $productSql);
    $productList = [];
    $productAllInfoList = [];
    if (!empty($productsResult) && mysqli_num_rows($productsResult) > 0) {
        foreach ($productsResult as $row) {
            $productList[$row['code']] = $row['code'];
            $productAllInfoList[$row['code']] = $row;
        }
    }
    // var_dump($productList);
    // die();

    $categorySql = "SELECT * FROM product_category ORDER BY id ASC";
    $categoryData = mysqli_query($conn, $categorySql);
    $categoryList = [];
    if (!empty($categoryData) && mysqli_num_rows($categoryData) > 0) {
        foreach ($categoryData as $row) {
            $categoryList[$row['cat_name']] = $row['cat_name'];
        }
    }

    $archive_menu_active = "active";
    $ord_archive_menu_active = "active";
    $pageTitle = "Archive Order List";

    // Create QuickBooks AUTH URL
    $dataService = DataService::Configure(array(
        'auth_mode' => 'oauth2',
        'ClientID' => CLIENT_ID,
        'ClientSecret' =>  CLIENT_SECRET,
        'RedirectURI' => Helper::fullbaseUrl() . OAUTH_REDIRECT_URI,
        'scope' => OAUTH_SCOPE,
        'baseUrl' => "development"
    ));

    $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
    $qbAuthUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();
    // Store the url in PHP Session Object;
    $_SESSION['qbAuthUrl'] = $qbAuthUrl;
    // var_dump($_SESSION);
?>
    <?php require_once(VIEW_PATH . 'common/header.php'); ?>

    <body>
        <div class="container-fluid-lg">
            <?php require_once(VIEW_PATH . 'common/nav.php'); ?>
            <div class="order-list-block">
                <table class="table table-hover item-list-dataTable" id="order-list-table">
                    <thead>
                        <tr>
                            <th onclick="sortTable(0)">Id</th>
                            <th onclick="sortTable(1)">Order</th>
                            <th onclick="sortTable(2)">Invoice #</th>
                            <th onclick="sortTable(3)">Customer</th>
                            <th onclick="sortTable(4)">Address</th>
                            <th onclick="sortTable(5)">Ship Via</th>
                            <th onclick="sortTable(6)">Ordered</th>
                            <th onclick="sortTable(7)">Requested</th>
                            <th onclick="sortTable(8)">Invoiced</th>
                            <th onclick="sortTable(9)">Shipped</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT
            ord.id,
            ord.orderno,
            ord.invoiceno,
            cus.`name` as customer_id,
            ord.ord_address1,
            ord.ord_address2,
            shv.shipvia as shipvia,
            ord.created_at,
            ord.reqdate,
            ord.shipdate,
            ord.invoicedate,
            ord.istatus,
            ord.status_ord
          FROM
            orders AS ord
          LEFT JOIN customers AS cus ON cus.id = ord.customer_id
          LEFT JOIN shipvia AS shv ON shv.id = ord.shipvia
          WHERE ord.archive = 1";
                        $result = mysqli_query($conn, $sql);

                        // output data of each row
                        if (!empty($result) && mysqli_num_rows($result) > 0) {
                            foreach ($result as $row) {
                                $id = $row["id"];
                                $orderno = $row["orderno"];
                                $invoiceno = $row["invoiceno"];
                                $customer = $row["customer_id"];
                                $address1 = $row["ord_address1"];
                                $shipvia = $row["shipvia"];
                                $status_ord = $row["status_ord"];
                        ?>
                                <tr>
                                    <td><?php echo $id; ?></td>
                                    <td>
                                        <?php if (isset($row["orderno"]) && !empty($row["orderno"])) { ?>
                                            <?php echo $row["orderno"]; ?>
                                        <?php } ?>
                                    </td>
                                    <td align="center">
                                        <?php if (isset($row["invoiceno"]) && !empty($row["invoiceno"])) { ?>
                                            <a title="Invoice" class="btn-view-sale invoice" href="<?php echo Helper::fullbaseUrl(); ?>frontend/invoices/invoice.php?invoiceno=<?php echo $row["invoiceno"]; ?>" target="_blank">
                                                <?php echo $row["invoiceno"]; ?>
                                            </a>
                                        <?php } else { ?>
                                            -
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php echo $customer; ?>
                                    </td>
                                    <td>
                                        <?php echo $address1; ?>
                                    </td>
                                    <td>
                                        <?php echo $shipvia; ?>
                                    </td>
                                    <td>
                                        <?php echo (!empty($row['created_at'])) ? date("m/d/Y", strtotime($row['created_at'])) : ""; ?>
                                    </td>
                                    <td>
                                        <?php echo (!empty($row['reqdate']) && $row['reqdate'] != "0000-00-00") ? date("m/d/Y", strtotime($row['reqdate'])) : ""; ?>
                                    </td>
                                    <td>
                                        <?php echo (!empty($row['invoicedate']) && $row['invoicedate'] != "0000-00-00") ? date("m/d/Y", strtotime($row['invoicedate'])) : ""; ?>
                                    </td>
                                    <td>
                                        <?php echo (!empty($row['shipdate']) && $row['shipdate'] != "0000-00-00") ? date("m/d/Y", strtotime($row['shipdate'])) : ""; ?>
                                    </td>
                                    <td class="text-nowrap table-action-col">
                                        <div class="btn-group table-btn-group order-table-action-container">
                                            <?php if (isset($row["invoiceno"]) && !empty($row["invoiceno"])) { ?>
                                                <a title="Invoice" class="btn-view-sale invoice" href="<?php echo Helper::fullbaseUrl(); ?>frontend/invoices/invoice.php?invoiceno=<?php echo $invoiceno; ?>" target="_blank">
                                                    <i class="fa fa-usd" aria-hidden="true"></i>
                                                </a>
                                            <?php } ?>
                                            <?php if (isset($row["invoiceno"]) && !empty($row["invoiceno"])) { ?>
                                                <a title="Bill of Lading" class="btn-invoice" href="<?php echo Helper::fullbaseUrl(); ?>frontend/orders/BOL.php?invoiceno=<?php echo $invoiceno; ?>" target="_blank">
                                                    <i class="fa-solid fa-file-invoice bol"></i>
                                                </a>
                                            <?php } ?>
                                            <?php if (isset($row["invoiceno"]) && !empty($row["invoiceno"])) { ?>
                                                <a title="Shipping Label" class="shipping-label" href="<?php echo Helper::fullbaseUrl(); ?>frontend/shipping/shipping-label.php?invoiceno=<?php echo $invoiceno; ?>" target="_blank">
                                                    <i class="fa-solid fa-file-invoice"></i>
                                                </a>
                                            <?php } ?>
                                            <?php if (isset($row["invoiceno"]) && !empty($row["invoiceno"])) { ?>
                                                <a title="Pick Sheet" class="btn-view-sale pick-sheet" href="<?php echo Helper::fullbaseUrl(); ?>frontend/orders/pick-sheet.php?invoiceno=<?php echo $invoiceno; ?>" target="_blank">
                                                    <i class="fa-solid fa-file-invoice pick-sheet"></i>
                                                </a>
                                            <?php } ?>
                                        </div>
                                    </td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="slide-order-form-block  slide-customer-form-block">
                <?php require_once(VIEW_PATH . 'order/order-form.php'); ?>
            </div>

            <?php require_once(VIEW_PATH . 'common/footer.php'); ?>
        </div>
    </body>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/4d4bd04373.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="<?php echo Helper::fullbaseUrl(); ?>assets/plugins/blockui/jquery.blockUI.js"></script>
    <script src="<?php echo Helper::fullbaseUrl(); ?>assets/plugins/toaster/toastr.min.js"></script>
    <script src="<?php echo Helper::fullbaseUrl(); ?>assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="<?php echo Helper::fullbaseUrl(); ?>assets/plugins/jquery-confirm/js/jquery-confirm.min.js"></script>
    <script src="<?php echo Helper::fullbaseUrl(); ?>assets/plugins/jquery-validate/js/jquery.validate.min.js"></script>
    <script src="<?php echo Helper::fullbaseUrl(); ?>assets/plugins/jquery-validate/js/additional-methods.min.js"></script>
    <script type="text/javascript" src="<?php echo Helper::fullbaseUrl(); ?>assets/plugins/tabulator/js/tabulator.min.js"></script>

    <script>
        var BASE_URL = '<?php echo Helper::fullbaseUrl(); ?>';
        var API_BASE_URL = '<?php echo Helper::fullbaseUrl(); ?>api/';
        var productList = '<?php echo json_encode($productList); ?>';
        var productAllInfoList = '<?php echo json_encode($productAllInfoList); ?>';
        var categoryList = '<?php echo json_encode($categoryList); ?>';
        var qb_auth_url = '<?php echo $qbAuthUrl; ?>';
    </script>
    <script src="<?php echo Helper::fullbaseUrl(); ?>assets/js/common_lib.js"></script>
    <script src="<?php echo Helper::fullbaseUrl(); ?>assets/js/common.js"></script>
    <script src="<?php echo Helper::fullbaseUrl(); ?>assets/js/order.js"></script>

    </html>
<?php } else {
    header('Location:frontend/users/login.php');
}
?>