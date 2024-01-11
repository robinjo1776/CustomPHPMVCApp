<?php session_start();
$uid = $_SESSION['id'];
$uname = $_SESSION['username'];
if (isset($uid)) {
  include "../../dbConfig.php";

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

  $sql7 = "SELECT * FROM address_types";
  $result7 = mysqli_query($conn, $sql7);

  $sql8 = "SELECT * FROM cust_addresses";
  $result8 = mysqli_query($conn, $sql8);

  $sql9 = "SELECT * FROM units";
  $result9 = mysqli_query($conn, $sql9);

  $sql10 = "SELECT * FROM cities";
  $result10 = mysqli_query($conn, $sql10);

  $sql11 = "SELECT * FROM provinces";
  $result11 = mysqli_query($conn, $sql11);

  $sql12 = "SELECT * FROM cust_addresses";
  $result12 = mysqli_query($conn, $sql12);

  $sql13 = "SELECT * FROM cust_phones";
  $result13 = mysqli_query($conn, $sql13);

  $sql14 = "SELECT * FROM shipvia";
  $result14 = mysqli_query($conn, $sql14);

  $sql15 = "SELECT * FROM orders";
  $result15 = mysqli_query($conn, $sql15);

  $sql16 = "SELECT * FROM loads";
  $result16 = mysqli_query($conn, $sql16);

  $sql17 = "SELECT * FROM cust_addresses";
  $result17 = mysqli_query($conn, $sql17);

  $sql72 = "SELECT * FROM address_types";
  $result72 = mysqli_query($conn, $sql72);

  $sql82 = "SELECT * FROM cust_addresses";
  $result82 = mysqli_query($conn, $sql82);

  $sql102 = "SELECT * FROM cities";
  $result102 = mysqli_query($conn, $sql102);

  $sql112 = "SELECT * FROM provinces";
  $result112 = mysqli_query($conn, $sql112);

  $sql73 = "SELECT * FROM cust_addresses";
  $result73 = mysqli_query($conn, $sql73);

  $invoice_menu_active = "active";
  $pageTitle = "Invoice List";
?>
  <?php require_once(VIEW_PATH . 'common/header.php'); ?>

  <body>
    <div class="container-fluid-lg">
      <?php require_once(VIEW_PATH . 'common/nav.php'); ?>
      <div class="dt-list-item-block">
        <table class="table table-hover item-list-dataTable">
          <thead>
            <tr>
              <th onclick="sortTable(0)">Id</th>
              <th onclick="sortTable(1)">Order No</th>
              <th onclick="sortTable(2)">Invoice No</th>
              <th onclick="sortTable(3)">Customer</th>
              <th onclick="sortTable(4)">Address</th>
              <th onclick="sortTable(5)">Ship Via</th>
              <th onclick="sortTable(6)">Ordered</th>
              <th onclick="sortTable(7)">Invoiced</th>
              <th onclick="sortTable(8)">Received</th>
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
            ord.orderdate,
            ord.reqdate,
            ord.shipdate,
            ord.istatus,
            ord.invoicedate,
            ord.status_ord
          FROM
            orders AS ord
          LEFT JOIN customers AS cus ON cus.id = ord.customer_id
          LEFT JOIN shipvia AS shv ON shv.id = ord.shipvia
          WHERE ord.istatus=1";
            $result = mysqli_query($conn, $sql);
            $result = mysqli_query($conn, $sql);

            // output data of each row
            if (mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
                $id = $row["id"];
                $orderno = $row["orderno"];
                $invoiceno = $row["invoiceno"];
                $customer = $row["customer_id"];
                $address1 = $row["ord_address1"];
                $shipvia = $row["shipvia"];
                $orderdate = $row["orderdate"];
                $invoicedate = $row["invoicedate"];
                $reqdate = $row["reqdate"];
                $shipdate = $row["shipdate"];
                $istatus = $row["istatus"];

            ?>
                <tr>
                  <td>
                    <?php echo $id; ?>
                  </td>
                  <td>
                    <?php echo $orderno; ?>
                  </td>
                  <td>
                    <?php echo $invoiceno; ?>
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
                    <?php echo $orderdate; ?>
                  </td>
                  <td>
                    <?php echo $invoicedate; ?>
                  </td>
                  <td>
                    <?php echo $reqdate; ?>
                  </td>
                  <td>
                    <?php echo $shipdate; ?>
                  </td>
                  <td class="text-nowrap table-action-col">
                    <div class="btn-group table-btn-group">
                      <!-- <a title="Edit" class="btn-edit-sale" href="#">
                        <i class="fa-sharp fa-solid fa-pencil"></i>
                      </a> -->
                      <a title="View" class="btn-view-sale" href="invoice.php?orderno=<?php echo $orderno; ?>">
                        <i class="fa-sharp fa-solid fa-file-lines"></i>
                      </a>
                      <a title="Delete" class="btn-delete-sale" href="#">
                        <i class="fa-sharp fa-regular fa-trash-can"></i>
                      </a>
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

  <script>
    var BASE_URL = '<?php echo Helper::fullbaseUrl(); ?>';
    var API_BASE_URL = '<?php echo Helper::fullbaseUrl(); ?>api/';
  </script>
  <script src="<?php echo Helper::fullbaseUrl(); ?>assets/js/common_lib.js"></script>
  <script src="<?php echo Helper::fullbaseUrl(); ?>assets/js/common.js"></script>
  <script src="<?php echo Helper::fullbaseUrl(); ?>assets/js/invoice.js"></script>

  </html>
<?php } else {
  header('Location:../users/login.php');
}
?>