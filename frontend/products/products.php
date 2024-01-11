<?php session_start();
$uid = $_SESSION['id'];
$uname = $_SESSION['username'];
if (isset($uid)) {
  include "../../dbConfig.php";

  $sql = "SELECT
	pro.id,
	pro.code,
	pro.description,
	pro.price,
	pro.minw,
	pro.maxw,
	unt.description as unit,
	pro.upc,
	pro.item_in_box,
	pro.comments,
	pro.size_des,
	pro.status_prod,
  procat.cat_name
FROM
	products AS pro
LEFT JOIN units AS unt ON unt.id = pro.unit
LEFT JOIN product_category AS procat ON procat.id = pro.cat_id
ORDER BY
	id DESC";
  $result = mysqli_query($conn, $sql);

  $unitsSql = "SELECT * FROM units";
  $unitsResult = mysqli_query($conn, $unitsSql);

  $categorySql = "SELECT * FROM product_category ORDER BY cat_name ASC";
  $categoryData = mysqli_query($conn, $categorySql);

  $product_menu_active = "active";
  $pageTitle = "Product List";
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
              <th onclick="sortTable(1)">Code</th>
              <th onclick="sortTable(2)">Category</th>
              <th onclick="sortTable(3)">Description</th>
              <th onclick="sortTable(4)">Price</th>
              <th onclick="sortTable(5)">Min wt</th>
              <th onclick="sortTable(6)">Max wt</th>
              <th onclick="sortTable(7)">Unit</th>
              <th onclick="sortTable(8)">UPC</th>
              <th onclick="sortTable(9)">Box</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
              $created_at = (!empty($row["created_at"])) ? Helper::getDateTimeByFormat($row["created_at"], 'm/d/Y h:i:s a') : "";
              $updated_at = (!empty($row["updated_at"])) ? Helper::getDateTimeByFormat($row["updated_at"], 'm/d/Y h:i:s a') : "";
            ?>
              <tr>
                <td><?php echo $row["id"]; ?></td>
                <td>
                  <a title="<?php echo $row["code"]; ?>" class="btn-edit-sale" href="product/<?php echo $row["id"]; ?>">
                    <?php echo $row["code"]; ?>
                  </a>
                </td>
                <td><?php echo $row["cat_name"]; ?></td>
                <td><?php echo $row["description"]; ?></td>
                <td><?php echo $row["price"]; ?></td>
                <td><?php echo $row["minw"]; ?></td>
                <td><?php echo $row["maxw"]; ?></td>
                <td><?php echo $row["unit"]; ?></td>
                <td><?php echo $row["upc"]; ?></td>
                <td><?php echo $row["item_in_box"]; ?></td>
                <td class="text-nowrap table-action-col">
                  <div class="btn-group table-btn-group order-table-action-container">
                    <a title="Edit" class="btn-edit-sale" href="product/<?php echo $row["id"]; ?>">
                      <i class="fa-sharp fa-solid fa-pencil"></i>
                    </a>
                    <a title="Delete" class="btn-delete-sale delete" href="product/delete/<?php echo $row["id"]; ?>">
                      <i class="fa-sharp fa-regular fa-trash-can"></i>
                    </a>
                  </div>
                </td>
              </tr>
              <?php
              if (mysqli_num_rows($result) == 0) { ?>
                <span>No records found</span>
            <?php
              }
            }
            ?>
          </tbody>
        </table>
      </div>

      <div class="slide-order-form-block">
        <?php require_once(VIEW_PATH . 'product/product-form.php'); ?>
      </div>

      <?php require_once(VIEW_PATH . 'common/footer.php'); ?>
    </div>
  </body>
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
  <script src="<?php echo Helper::fullbaseUrl(); ?>assets/plugins/blockui/jquery.blockUI.js"></script>
  <script src="<?php echo Helper::fullbaseUrl(); ?>assets/plugins/toaster/toastr.min.js"></script>
  <script src="<?php echo Helper::fullbaseUrl(); ?>assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
  <script src="<?php echo Helper::fullbaseUrl(); ?>assets/plugins/jquery-confirm/js/jquery-confirm.min.js"></script>
  <script src="<?php echo Helper::fullbaseUrl(); ?>assets/plugins/jquery-validate/js/jquery.validate.min.js"></script>
  <script src="<?php echo Helper::fullbaseUrl(); ?>assets/plugins/jquery-validate/js/additional-methods.min.js"></script>

  <script>
    var BASE_URL = '<?php echo Helper::fullbaseUrl(); ?>';
    var API_BASE_URL = '<?php echo Helper::fullbaseUrl(); ?>api/';
  </script>
  <script src="<?php echo Helper::fullbaseUrl(); ?>assets/js/common_lib.js"></script>
  <script src="<?php echo Helper::fullbaseUrl(); ?>assets/js/common.js"></script>
  <script src="<?php echo Helper::fullbaseUrl(); ?>assets/js/product.js"></script>

  </html>
<?php } else {
  header('Location:../users/login.php');
}
?>