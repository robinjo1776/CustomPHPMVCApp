<?php session_start();
$uid = $_SESSION['id'];
$uname = $_SESSION['username'];
if (isset($uid)) {
    include "../../dbConfig.php";

    $sql = "SELECT * FROM users AS usr where usr.id != $uid ORDER BY id DESC";
    $result = mysqli_query($conn, $sql);

    $sql4 = "SELECT * FROM notifications
        WHERE uname='$uname' AND status_all=1
        ORDER BY id DESC LIMIT 2";

    $result4 = mysqli_query($conn, $sql4);
    $sql6 = "SELECT * FROM notifications
      WHERE status_all=1";
    $result6 = mysqli_query($conn, $sql6);

    $user_menu_active = "active";
    $pageTitle = "User List";
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
                            <th onclick="sortTable(1)">First Name</th>
                            <th onclick="sortTable(2)">Surname</th>
                            <th onclick="sortTable(3)">Username</th>
                            <th onclick="sortTable(4)">Type</th>
                            <th onclick="sortTable(5)">Address</th>
                            <th onclick="sortTable(6)">Phone</th>
                            <th onclick="sortTable(7)">Email</th>
                            <th onclick="sortTable(8)">Created On</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            $created_at = Helper::getDateTimeByFormat($row["created_at"], 'm/d/Y h:i:s a');
                            $updated_at = (!empty($row["updated_at"])) ? Helper::getDateTimeByFormat($row["updated_at"], 'm/d/Y h:i:s a') : "";
                        ?>
                            <tr>
                                <td><?php echo $row["id"]; ?></td>
                                <td><?php echo $row["name"]; ?></td>
                                <td><?php echo $row["sname"]; ?></td>
                                <td>
                                    <a title="<?php echo $row["uname"]; ?>" class="btn-edit-sale" href="user/<?php echo $row["id"]; ?>">
                                        <?php echo $row["uname"]; ?>
                                    </a>
                                </td>
                                <td><?php echo Helper::$USER_TYPE[$row["utype"]]; ?></td>
                                <td><?php echo $row["add1"] . ' ' . $row["add2"]; ?></td>
                                <td><?php echo $row["phone"]; ?></td>
                                <td><?php echo $row["email"]; ?></td>
                                <td><?php echo $created_at; ?></td>
                                <td class="text-nowrap table-action-col">
                                    <div class="btn-group table-btn-group order-table-action-container">
                                        <a title="Edit" class="btn-edit-sale" href="user/<?php echo $row["id"]; ?>">
                                            <i class="fa-sharp fa-solid fa-pencil"></i>
                                        </a>
                                        <a title="Delete" class="btn-delete-sale delete" href="user/delete/<?php echo $row["id"]; ?>">
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
                <?php require_once(VIEW_PATH . 'user/user-form.php');
                ?>
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
    <script src="<?php echo Helper::fullbaseUrl(); ?>assets/js/user.js"></script>

    </html>
<?php } else {
    header('Location:../users/login.php');
}
?>