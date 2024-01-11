<?php session_start();
$uid = $_SESSION['id'];
$uname = $_SESSION['username'];
date_default_timezone_set('America/Edmonton');
include "../../dbConfig.php";

require_once BASEPATH . '/vendor/qb/vendor/autoload.php';

use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Facades\Customer;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Line;
use QuickBooksOnline\API\Facades\Item;
use QuickBooksOnline\API\Exception\SdkException;

Helper::logText("--------------------- Start importing invoice to QB --------------------.");
Helper::logText("UID: " . $uid);
//var_dump($uid);
//var_dump($_POST);
//die();
if (isset($uid)) {
    $invoicenos = isset($_POST["invoicenos"]) ? $_POST["invoicenos"] : array();
    Helper::logText(json_encode($_POST["invoicenos"]));
    if (!empty($invoicenos)) {
        foreach ($invoicenos as $invoiceno) {
            // read invoice information from database
            Helper::logText("----- Read invoice information from database -----");
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
                ord.inv_label,
                ord.inv_val,
                ord.customer_id
            FROM
                orders AS ord
            LEFT JOIN customers AS cus ON cus.id = ord.customer_id
            LEFT JOIN shipvia AS shv ON shv.id = ord.shipvia
            LEFT JOIN users AS usr ON usr.id = ord.orderedby
            WHERE ord.id='$invoiceno'";
            Helper::logText($sql);

            $orderDetailsResult = mysqli_query($conn, $sql);
            $orderDetails = [];
            if (mysqli_num_rows($orderDetailsResult) > 0) {
                $orderDetails = mysqli_fetch_assoc($orderDetailsResult);
            }
            Helper::logText(json_encode($orderDetails));

            // order items
            Helper::logText("----- Read order items information from database -----");
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
            Helper::logText($orderItemsSql);

            // read customer email address
            Helper::logText("----- read customer email address -----");
            $sql = "SELECT * FROM cust_phones WHERE method=1 AND cus_id = " . $orderDetails['customer_id'] . " LIMIT 1";
            Helper::logText($sql);
            $customerEmailResult = mysqli_query($conn, $sql);
            $customerEmailDetails = [];
            if (mysqli_num_rows($customerEmailResult) > 0) {
                $customerEmailDetails = mysqli_fetch_assoc($customerEmailResult);
            }
            Helper::logText(json_encode($customerEmailDetails));

            // read the customer phone
            Helper::logText("----- read the customer phone -----");
            $sql = "SELECT * FROM cust_phones WHERE method=4 AND cus_id = " . $orderDetails['customer_id'] . " LIMIT 1";
            Helper::logText($sql);
            $customerPhoneResult = mysqli_query($conn, $sql);
            $customerPhoneDetails = [];
            if (mysqli_num_rows($customerPhoneResult) > 0) {
                $customerPhoneDetails = mysqli_fetch_assoc($customerPhoneResult);
            }
            Helper::logText(json_encode($customerPhoneDetails));

            // OAuth 2.0 Settings
            // Create SDK instance
            try {
                $errorQBMessage = [];
                $dataService = DataService::Configure(array(
                    'auth_mode' => 'oauth2',
                    'ClientID' => CLIENT_ID,
                    'ClientSecret' =>  CLIENT_SECRET,
                    'RedirectURI' => Helper::fullbaseUrl() . OAUTH_REDIRECT_URI,
                    'scope' => OAUTH_SCOPE,
                    'baseUrl' => QB_ENVIRONMENT
                ));
                Helper::logText("Create SDK instance.");
                /*
                * Retrieve the accessToken value from session variable
                */
                $accessToken = $_SESSION['sessionAccessToken'];
                Helper::logText("Deserializing accessToken");
                $accessToken = unserialize($accessToken);

                /*
                * Update the OAuth2Token of the dataService object
                */
                $refreshToken = $accessToken->getRefreshToken();
                Helper::logText("save new refresh token for future use.");
                // Update the OAuth2Token in the data service object
                $dataService->updateOAuth2Token($accessToken);
                // The access token is now updated, and you can continue making API calls.
                // You should also save your new refresh token for future use.
                Helper::logText("The access token is now updated, and you can continue making API calls.");
                /* 
                * 1. Create a Customer
                */
                $customerName = $orderDetails["customer"];
                $escapedCustomerName = addslashes($customerName);
                $customerArray = $dataService->Query("select * from Customer where DisplayName='" . $escapedCustomerName . "'");
                Helper::logText("select * from Customer where DisplayName='" . $escapedCustomerName . "'");
                $error = $dataService->getLastError();
                $customerRef = null;
                if ($error) {
                    $errorQBMessage[$orderDetails["invoiceno"]] = $error->getResponseBody();
                    Helper::logText("Error getting when read customer information.");
                    Helper::logText(json_encode($errorQBMessage));
                    Helper::logText("Error: " . $error->getHttpStatusCode());
                    Helper::logText("IntuitTid: " . $error->getIntuitTid());
                } else {
                    //var_dump($customerArray);
                    //die();
                    if (is_array($customerArray) && sizeof($customerArray) > 0) {
                        // Assign Customer Object & Print ID
                        $customerRef =  current($customerArray);
                        // echo "Found Customer with Id={$customerRef->Id}.\n\n";
                        Helper::logText("Found Customer");
                        Helper::logText(json_encode($customerRef));
                    } else {
                        $email = (isset($customerEmailDetails["phone_email"]) && !empty($customerEmailDetails["phone_email"])) ? $customerEmailDetails["phone_email"] : "";

                        $phone = (isset($customerPhoneDetails["phone_email"]) && !empty($customerPhoneDetails["phone_email"])) ? $customerPhoneDetails["phone_email"] : "";
                        //$escapedNewCustomerName = str_replace("'", "''", trim($customerName));
                        //var_dump($email);
                        //var_dump($customerName);
                        //var_dump($escapedNewCustomerName);
                        //die();

                        $newCustomerData = [
                            "FullyQualifiedName" => $escapedCustomerName,
                            "DisplayName" => $escapedCustomerName,
                            "CompanyName" => $escapedCustomerName,
                            "PrimaryEmailAddr" => [
                                "Address" => $email,
                            ],
                            "PrimaryPhone" => [
                                "FreeFormNumber" => $phone,
                            ],
                            "BillAddr" => [
                                "CountrySubDivisionCode" => "CA",
                                "City" => $orderDetails["ord_city"],
                                "PostalCode" => $orderDetails["ord_postal_code"],
                                "Line1" => $orderDetails["ord_address1"] . " " . $orderDetails["ord_address2"],
                                "Country" => "Canada"
                            ],
                        ];

                        // Create Customer
                        Helper::logText("Create Customer");
                        Helper::logText(json_encode($newCustomerData));
                        $customerRequestObj = Customer::create($newCustomerData);
                        $customerResponseObj = $dataService->Add($customerRequestObj);
                        $error = $dataService->getLastError();
                        if ($error) {
                            $errorQBMessage[$orderDetails["invoiceno"]] = $error->getResponseBody();
                            Helper::logText("Error getting when creating new customer.");
                            Helper::logText(json_encode($errorQBMessage));
                            Helper::logText("Error: " . $error->getHttpStatusCode());
                            Helper::logText("IntuitTid: " . $error->getIntuitTid());
                        } else {
                            $customerRef = $customerResponseObj;
                            Helper::logText(json_encode($customerRef));
                        }
                    }
                }
                // echo "Created Customer with Id={$customerRef->Id}.\n\n";
                // var_dump($customerRef->PrimaryEmailAddr->Address);
                // die();

                /*
                * 2. Read tax information
                */
                Helper::logText("Read tax information.");
                $taxCodes = $dataService->Query("SELECT * FROM TaxCode");
                Helper::logText("SELECT * FROM TaxCode");
                $gstTaxCodeRef = "";
                // Loop through all tax codes to find the one for GST/HST
                Helper::logText(json_encode($taxCodes));
                if ($taxCodes) {
                    foreach ($taxCodes as $taxCode) {
                        if ($taxCode->Name == 'Zero') {
                            $gstTaxCodeRef = $taxCode->Id;
                            break;
                        }
                    }
                }
                Helper::logText($gstTaxCodeRef);

                /*
                * 3. Add Item
                */
                $lineItems = [];
                Helper::logText("Starting adding item...");
                if (empty($errorQBMessage) && mysqli_num_rows($orderItems) > 0) {
                    while ($item = mysqli_fetch_assoc($orderItems)) {
                        Helper::logText(json_encode($item));
                        // Check if the item exists
                        $currentQBItem = null;
                        $itemName = $item['cat_code'];
                        $entities = $dataService->Query("SELECT * FROM Item WHERE Name='{$itemName}'");
                        Helper::logText("SELECT * FROM Item WHERE Name='{$itemName}'");
                        $error = $dataService->getLastError();
                        if ($error) {
                            $errorQBMessage[$orderDetails["invoiceno"]] = $error->getResponseBody();
                            Helper::logText("Error getting when reading item.");
                            Helper::logText(json_encode($errorQBMessage));
                            Helper::logText("Error: " . $error->getHttpStatusCode());
                            Helper::logText("IntuitTid: " . $error->getIntuitTid());
                        }
                        if (is_array($entities) && !empty($entities)) {
                            Helper::logText("Reading item.");
                            $currentQBItem =  current($entities);
                            Helper::logText(json_encode($currentQBItem));
                        }
                        // var_dump($currentQBItem);

                        if ($currentQBItem) {
                            $newItemData = [
                                "Description" => $item['cat_des'],
                                "Amount" => $item['tp'] * $item['tw'],
                                "DetailType" => "SalesItemLineDetail",
                                "SalesItemLineDetail" => [
                                    "ItemRef" => [
                                        "value" => $currentQBItem->Id,
                                        "name" => $currentQBItem->Name
                                    ],
                                    "UnitPrice" => $item['tp'],
                                    "Qty" => $item['tw'],
                                    "TaxCodeRef" => [
                                        "value" => $gstTaxCodeRef // Apply the tax code here
                                    ]
                                ]
                            ];
                            Helper::logText("Create new item.");
                            Helper::logText(json_encode($newItemData));
                            $line = Line::create($newItemData);
                            Helper::logText(json_encode($line));
                            $lineItems[] = $line;
                        }
                    }
                }
                // echo "Line Item Count: " . count($lineItems) . "\n\n";
                Helper::logText("Invoice Line items...");
                Helper::logText(json_encode($lineItems));

                /*
                * 3. Create an Invoice using the CustomerRef
                */
                if (empty($errorQBMessage)) {
                    $customerEmail = (isset($customerRef->PrimaryEmailAddr) && isset($customerRef->PrimaryEmailAddr->Address) ? $customerRef->PrimaryEmailAddr->Address : "");
                    //echo "<pre>";
                    //var_dump($customerRef);
                    //var_dump($customerEmail);
                    //die();

                    $newInvoiceData = [
                        "Line" => $lineItems,
                        "CustomerRef" => [
                            "value" => $customerRef->Id
                        ],
                        "DocNumber" => $orderDetails["invoiceno"],
                        "BillEmail" =>  [
                            "Address" => $customerEmail, // replace $customerEmail with the customer's email address
                        ],
                        "ShipDate" => $orderDetails["shipdate"],
                        "ShipMethodRef" => [
                            "value" => ($orderDetails["shipvia"]) ? $orderDetails["shipvia"] : "",
                        ],
                        "ShipAddr" => [
                            "City" => $orderDetails["ord_city"],
                            "Line1" => $orderDetails["ord_address1"] . " " . $orderDetails["ord_address2"],
                            "PostalCode" => $orderDetails["ord_postal_code"],
                            "CountrySubDivisionCode" => "CA",
                            "Country" => "Canada"
                        ]
                    ];
                    //echo "<pre>";
                    //var_dump($newInvoiceData);
                    //die();

                    Helper::logText("Create new invoice...");
                    Helper::logText(json_encode($newInvoiceData));
                    $invoiceObj = Invoice::create($newInvoiceData);
                    Helper::logText(json_encode($invoiceObj));
                    $resultingInvoiceObj = $dataService->Add($invoiceObj);
                    $error = $dataService->getLastError();
                    if ($error) {
                        Helper::logText("Error adding invoice");
                        $errorQBMessage[$orderDetails["invoiceno"]] = $error->getResponseBody();
                        Helper::logText(json_encode($errorQBMessage));
                        Helper::logText("Error: " . $error->getHttpStatusCode());
                        Helper::logText("IntuitTid: " . $error->getIntuitTid());
                    }
                }

                // var_dump($errorQBMessage);
            } catch (SdkException $e) {
                // Handle the exception
                Helper::logText("SdkException");
                $errorQBMessage[$orderDetails["invoiceno"]] = "Error: " . $e->getMessage();
                Helper::logText(json_encode($errorQBMessage));
            }
            // var_dump($errorQBMessage);
            if ($error && $error->getHttpStatusCode() == 401) {
                Helper::logText("If authtoken is invalid.");
                $dataService = DataService::Configure(array(
                    'auth_mode' => 'oauth2',
                    'ClientID' => CLIENT_ID,
                    'ClientSecret' =>  CLIENT_SECRET,
                    'RedirectURI' => Helper::fullbaseUrl() . OAUTH_REDIRECT_URI,
                    'baseUrl' => QB_ENVIRONMENT,
                    'refreshTokenKey' => $accessToken->getRefreshToken(),
                    'QBORealmID' => QB_REALM_ID,
                ));
                Helper::logText("Create SDK instance.");
                /*
                * Update the OAuth2Token of the dataService object
                */
                $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
                Helper::logText("generating refresh token...");
                $refreshedAccessTokenObj = $OAuth2LoginHelper->refreshToken();
                Helper::logText("updateing authtoken by refresh token...");
                $dataService->updateOAuth2Token($refreshedAccessTokenObj);
                // var_dump($refreshedAccessTokenObj);

                Helper::logText("serializing access token and save into session");
                $_SESSION['sessionAccessToken'] = serialize($refreshedAccessTokenObj);
                Helper::logText("Go to previouse  page with refresh token and again start importing process...");
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit;
            }

            if (empty($errorQBMessage)) {
                Helper::logText("Updating archive field of the order....");
                $sql = "UPDATE orders SET archive=1 WHERE id=" . $orderDetails["id"];
                Helper::logText("UPDATE orders SET archive=1 WHERE id=" . $orderDetails["id"]);
                $flag = mysqli_query($conn, $sql);
                Helper::logText(json_encode($flag));
            }
        }
    }

    $qb_invoice_menu_active = "active";
    $pageTitle = "QB Invoice";

    $subTotal = 0;
    $total = 0;

    Helper::logText("After all Invoice imports have been processed.");
    Helper::logText(json_encode($errorQBMessage));
?>
    <?php require_once(VIEW_PATH . 'common/header.php'); ?>

    <body>
        <div class="container-fluid-lg">
            <?php require_once(VIEW_PATH . 'common/nav.php'); ?>
            <div class="container">
                <div class="row">
                    <div id="error-msg-show" class="col-md-12 text-center" style="margin-top: 100px;">
                        <?php
                        if (!empty($invoicenos)) {
                            if (!empty($errorQBMessage)) {
                                foreach ($errorQBMessage as $key => $msg) {
                        ?>
                                    <div class="alert alert-danger alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <strong><?php echo $key; ?>: </strong>
                                        <?php echo $msg; ?>
                                    </div>
                                <?php
                                }
                            } else {
                                ?>
                                <div class="alert alert-success alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    Your invoice data has been store in the QuickBooksOnline account successfully.
                                </div>
                            <?php
                            }
                        } else {
                            ?>
                            <p>No Record Found</p>
                        <?php } ?>
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

    </html>
<?php
} else {
    header('Location:../users/login.php');
}
