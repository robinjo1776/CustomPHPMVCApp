<?php session_start();
$uid = $_SESSION['id'];
$uname = $_SESSION['username'];

date_default_timezone_set('America/Edmonton');
include "../../dbConfig.php";

require_once BASEPATH . '/vendor/qb/vendor/autoload.php';

use QuickBooksOnline\API\DataService\DataService;

if (isset($uid)) {
    $qb_invoice_menu_active = "active";
    $pageTitle = "QB Invoice";

    // Create QuickBooks AUTH URL
    $dataService = DataService::Configure(array(
        'auth_mode' => 'oauth2',
        'ClientID' => CLIENT_ID,
        'ClientSecret' =>  CLIENT_SECRET,
        'RedirectURI' => Helper::fullbaseUrl() . OAUTH_REDIRECT_URI,
        'scope' => OAUTH_SCOPE,
        'baseUrl' => QB_ENVIRONMENT
    ));

    $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
    $qbAuthUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();
    // Store the url in PHP Session Object;
    $_SESSION['qbAuthUrl'] = $qbAuthUrl;
    /*
    * Retrieve the accessToken value from session variable
    */
    $accessToken = isset($_SESSION['sessionAccessToken']) ? $_SESSION['sessionAccessToken'] : "";
    $accessToken = unserialize($accessToken);
    // var_dump($accessToken);
?>
    <?php require_once(VIEW_PATH . 'common/header.php'); ?>

    <body>
        <div class="container-fluid-lg">
            <?php require_once(VIEW_PATH . 'common/nav.php'); ?>
            <div class="order-list-block">
                <?php if (empty($accessToken)) { ?>
                    <div class="row">
                        <div class="col-md-12 text-center" style="margin-top: 10%;">
                            <p style="font-weight: bold; font-size:14px;">Please log in to QuickBooks before clicking the button below to import invoices.</p>
                            <a style="font-size: 24px;" href="javascript:void(0);" onclick="qbPopup()" class="btn btn-success">QB Login</a>
                        </div>
                    </div>
                <?php } else {
                    /*$dataService1 = DataService::Configure(array(
                        'auth_mode' => 'oauth2',
                        'ClientID' => CLIENT_ID,
                        'ClientSecret' => CLIENT_SECRET,
                        'accessTokenKey' => $accessToken->getAccessToken(),
                        'refreshTokenKey' => $accessToken->getRefreshToken(),
                        'QBORealmID' => QB_REALM_ID,
                        'baseUrl' => "Development"
                    ));
                    $accounts = $dataService1->Query("SELECT * FROM Account");
                    // echo "<pre>";
                    // print_r($accounts);
                    */
                ?>
                    <div class="row">
                        <div class="col-md-6">
                            <?php
                            /*    
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="qb_aacount">QuickBook Account</label>
                                    <select name="qb_aacount" id="qb_aacount" class="form-control">
                                        <option value="">Select QuickBook Account</option>
                                        <?php foreach ($accounts as $key => $account) {
                                        ?>
                                            <option value="<?php echo $account->Id; ?>"><?php echo $account->Name; ?></option>
                                        <?php }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            */
                            ?>
                            <table class="table table-hover item-list-dataTable" id="order-list-table">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" class="select-invoice-ids-all">
                                        </th>
                                        <th>Order</th>
                                        <th>Invoice #</th>
                                        <th>Customer</th>
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
                                WHERE ord.archive = 0 AND ord.invoiceno !=''";
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
                                                <td>
                                                    <input type="checkbox" name="inv_id" value="<?php echo $id; ?>##<?php echo $row["invoiceno"]; ?>" class="select-invoice-ids">
                                                </td>
                                                <td>
                                                    <?php echo $row["orderno"]; ?>
                                                </td>
                                                <td align="center">
                                                    <a title="Invoice" class="btn-view-sale invoice" href="<?php echo Helper::fullbaseUrl(); ?>frontend/invoices/invoice.php?invoiceno=<?php echo $row["invoiceno"]; ?>" target="_blank">
                                                        <?php echo $row["invoiceno"]; ?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <?php echo $customer; ?>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6" style="margin-top: 38px;">
                            <form method="post" action="<?php echo Helper::fullbaseUrl(); ?>frontend/qb/qb-invoice.php">
                                <?php
                                /*
                                <input type="hidden" id="qb_account_id" name="qb_account_id">
                                */
                                ?>
                                <div id="invoice-id-list" class="row">
                                    <div class="col-md-12">
                                    </div>
                                </div>
                                <div class="row">
                                    <div id="invoice-btn-container" class="col-md-12" style="display: none; padding-left: 0px; margin-top: 10px;">
                                        <a id="invoice-data-clear" href="<?php echo Helper::fullbaseUrl(); ?>frontend/invoices/qb-invoices.php" class="btn btn-default">Clear</a>
                                        <button type="submit" class="btn btn-primary" onclick="alert('Please do not refresh this page until the process is finished!');">Import</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php } ?>
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
    <script src="<?php echo Helper::fullbaseUrl(); ?>assets/plugins/select2/js/select2.min.js"></script>
    <script type="text/javascript" src="<?php echo Helper::fullbaseUrl(); ?>assets/plugins/tabulator/js/tabulator.min.js"></script>

    <script>
        var BASE_URL = '<?php echo Helper::fullbaseUrl(); ?>';
        var API_BASE_URL = '<?php echo Helper::fullbaseUrl(); ?>api/';
        var qb_auth_url = '<?php echo $qbAuthUrl; ?>';
    </script>
    <script src="<?php echo Helper::fullbaseUrl(); ?>assets/js/common_lib.js"></script>
    <script src="<?php echo Helper::fullbaseUrl(); ?>assets/js/common.js"></script>
    <script src="<?php echo Helper::fullbaseUrl(); ?>assets/js/qb-invoice.js"></script>

    </html>
<?php } else {
    header('Location:frontend/users/login.php');
}
?>