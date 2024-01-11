<?php session_start();
$uid = $_SESSION['id'];
$uname = $_SESSION['username'];
if (isset($uid)) {
    include "../../dbConfig.php";

    $sql = "SELECT
        lda.id,
        lda.invoiceno,
        lda.pnumber,
        lda.pdescription,
        lda.loading_date,
        lda.cases,
        lda.birds,
        lda.created_by,
        lda.created_at,
        lda.updated_by,
        lda.updated_at,
        lda.status_load
    FROM loads AS lda 
    LEFT JOIN users as c_usr ON c_usr.id = lda.created_by 
    LEFT JOIN users as u_usr ON u_usr.id = lda.updated_by 
    ORDER BY lda.id DESC";
    $result = mysqli_query($conn, $sql);

    $invoicenoSql = "SELECT invoiceno 
        FROM orders 
        WHERE orders.invoiceno != '' 
        ORDER BY invoiceno ASC";
    $invoicenResult = mysqli_query($conn, $invoicenoSql);

    $load_menu_active = "active";
    $pageTitle = "Load List";
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
                            <th onclick="sortTable(1)">Invoice No</th>
                            <th onclick="sortTable(2)">Pallet No</th>
                            <th onclick="sortTable(3)">Description</th>
                            <th onclick="sortTable(4)">Load Date</th>
                            <th onclick="sortTable(5)">Cases</th>
                            <th onclick="sortTable(6)">Birds</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            $created_at = Helper::getDateTimeByFormat($row["created_at"], 'm/d/Y h:i:s a');
                            $updated_at = (!empty($row["updated_at"])) ? Helper::getDateTimeByFormat($row["updated_at"], 'm/d/Y h:i:s a') : "";
                            $loading_date = (!empty($row["loading_date"])) ? Helper::getDateTimeByFormat($row["loading_date"], 'm/d/Y') : "";
                        ?>
                            <tr>
                                <td><?php echo  $row["id"]; ?></td>
                                <td>
                                    <?php if (isset($row["invoiceno"]) && !empty($row["invoiceno"])) { ?>
                                        <a title="Invoice" class="btn-view-sale invoice" href="<?php echo Helper::fullbaseUrl(); ?>frontend/invoices/invoice.php?invoiceno=<?php echo $row["invoiceno"]; ?>" target="_blank">
                                            <?php echo $row["invoiceno"]; ?>
                                        </a>
                                    <?php } else { ?>
                                        -
                                    <?php } ?>
                                </td>
                                <td>
                                    <a title="<?php echo $row["pnumber"]; ?>" class="btn-edit-sale" href="load/<?php echo $row['id']; ?>">
                                        <?php echo $row["pnumber"]; ?>
                                    </a>
                                </td>
                                <td><?php echo $row["pdescription"]; ?></td>
                                <td><?php echo $loading_date; ?></td>
                                <td><?php echo $row["cases"]; ?></td>
                                <td><?php echo $row["birds"]; ?></td>
                                <td class="text-nowrap table-action-col">
                                    <div class="btn-group table-btn-group order-table-action-container sss">
                                        <?php if (isset($row["invoiceno"]) && !empty($row["invoiceno"])) { ?>
                                            <a title="Invoice" class="btn-view-sale invoice" href="<?php echo Helper::fullbaseUrl(); ?>frontend/invoices/invoice.php?invoiceno=<?php echo $row["invoiceno"]; ?>" target="_blank">
                                                <i class="fa fa-usd" aria-hidden="true"></i>
                                            </a>
                                        <?php } ?>
                                        <?php if (isset($row["invoiceno"]) && !empty($row["invoiceno"])) { ?>
                                            <a title="Bill of Lading" class="btn-invoice" href="<?php echo Helper::fullbaseUrl(); ?>frontend/orders/BOL.php?invoiceno=<?php echo $row["invoiceno"]; ?>" target="_blank">
                                                <i class="fa-solid fa-file-invoice bol"></i>
                                            </a>
                                        <?php } ?>
                                        <?php if (isset($row["invoiceno"]) && !empty($row["invoiceno"])) { ?>
                                            <a title="Shipping Label" class="shipping-label" href="<?php echo Helper::fullbaseUrl(); ?>frontend/shipping/shipping-label.php?invoiceno=<?php echo $row["invoiceno"]; ?>" target="_blank">
                                                <i class="fa-solid fa-file-invoice"></i>
                                            </a>
                                        <?php } ?>
                                        <a title="Edit" class="btn-edit-sale" href="load/<?php echo $row['id']; ?>">
                                            <i class="fa-sharp fa-solid fa-pencil"></i>
                                        </a>
                                        <a title="Delete" class="btn-delete-sale delete" href="load/delete/<?php echo $row['id']; ?>">
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
                <?php require_once(VIEW_PATH . 'load/load-form.php'); ?>
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
    </script>
    <script src="<?php echo Helper::fullbaseUrl(); ?>assets/js/common_lib.js"></script>
    <script src="<?php echo Helper::fullbaseUrl(); ?>assets/js/common.js"></script>
    <script src="<?php echo Helper::fullbaseUrl(); ?>assets/js/load.js"></script>

    </html>
<?php } else {
    header('Location:../users/login.php');
}
?>