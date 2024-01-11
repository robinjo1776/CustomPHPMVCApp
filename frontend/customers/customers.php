<?php session_start();
$uid = $_SESSION['id'];
$uname = $_SESSION['username'];
if (isset($uid)) {
    include "../../dbConfig.php";

    $sql = "SELECT cus.*, c_usr.name as cname, c_usr.sname as csname, 
        u_usr.name as uname, u_usr.sname as usname  
        FROM customers as cus
        LEFT JOIN users as c_usr ON c_usr.id = cus.created_by 
        LEFT JOIN users as u_usr ON u_usr.id = cus.updated_by
        ORDER BY cus.id DESC";
    $result = mysqli_query($conn, $sql);

    $addTypeSql = "SELECT * FROM address_types";
    $addTypeResult = mysqli_query($conn, $addTypeSql);
    $addTypeList = [];
    if (!empty($addTypeResult) && mysqli_num_rows($addTypeResult) > 0) {
        foreach ($addTypeResult as $row) {
            $addTypeList[$row['id']] = $row['description'];
        }
    }

    $contactMethodSql = "SELECT * FROM contact_methods";
    $contactMethodResult = mysqli_query($conn, $contactMethodSql);
    $contactMethodList = [];
    if (!empty($contactMethodResult) && mysqli_num_rows($contactMethodResult) > 0) {
        foreach ($contactMethodResult as $row) {
            $contactMethodList[$row['id']] = $row['method'];
        }
    }

    $contactTypeSql = "SELECT * FROM contact_types";
    $contactTypeResult = mysqli_query($conn, $contactTypeSql);
    $contactTypeList = [];
    if (!empty($contactTypeResult) && mysqli_num_rows($contactTypeResult) > 0) {
        foreach ($contactTypeResult as $row) {
            $contactTypeList[$row['id']] = $row['description'];
        }
    }

    $customer_menu_active = "active";
    $pageTitle = "Customer List";
?>
    <?php require_once(VIEW_PATH . 'common/header.php'); ?>

    <body>
        <div class="container-fluid-lg">
            <?php require_once(VIEW_PATH . 'common/nav.php'); ?>
            <div class="dt-list-item-block">
                <div class="" style="text-align: right; margin-bottom:10px;">
                    <a href="javascript:void(0);" class="btn btn-success open-import-data-btn"><i class="fa fa-plus" aria-hidden="true"></i> Import Data</a>
                </div>
                <table class="table table-hover item-list-dataTable">
                    <thead>
                        <tr>
                            <th onclick="sortTable(0)">Id</th>
                            <th onclick="sortTable(1)">Company Name</th>
                            <th onclick="sortTable(2)">Created By</th>
                            <th onclick="sortTable(3)">Since</th>
                            <th onclick="sortTable(4)">Modified By</th>
                            <th onclick="sortTable(5)">Modified At</th>
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
                                    <a title="<?php echo $row["name"]; ?>" class="btn-edit-sale" href="customer/<?php echo $row["id"]; ?>">
                                        <?php echo $row["name"]; ?>
                                    </a>
                                </td>
                                <td><?php echo $row["cname"] . " " . $row["csname"]; ?></td>
                                <td><?php echo $created_at; ?></td>
                                <td><?php echo $row["uname"] . " " . $row["usname"]; ?></td>
                                <td><?php echo $updated_at; ?></td>
                                <td class="text-nowrap table-action-col">
                                    <div class="btn-group table-btn-group order-table-action-container">
                                        <a title="Edit" class="btn-edit-sale" href="customer/<?php echo $row["id"]; ?>">
                                            <i class="fa-sharp fa-solid fa-pencil"></i>
                                        </a>
                                        <a title="Delete" class="btn-delete-sale delete" href="customer/delete/<?php echo $row["id"]; ?>">
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

            <div class="slide-order-form-block slide-customer-form-block">
                <?php require_once(VIEW_PATH . 'customer/customer-form.php'); ?>
            </div>
            <div class="slide-import-form-block">
                <?php require_once(VIEW_PATH . 'customer/customer-import-form.php'); ?>
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

    <script>
        var BASE_URL = '<?php echo Helper::fullbaseUrl(); ?>';
        var API_BASE_URL = '<?php echo Helper::fullbaseUrl(); ?>api/';
        var addTypeList = '<?php echo json_encode($addTypeList); ?>';
        var contactMethodList = '<?php echo json_encode($contactMethodList); ?>';
        var contactTypeList = '<?php echo json_encode($contactTypeList); ?>';
    </script>
    <script src="<?php echo Helper::fullbaseUrl(); ?>assets/js/common_lib.js"></script>
    <script src="<?php echo Helper::fullbaseUrl(); ?>assets/js/common.js"></script>
    <script src="<?php echo Helper::fullbaseUrl(); ?>assets/js/customer.js"></script>

    </html>
<?php } else {
    header('Location:../users/login.php');
}
?>