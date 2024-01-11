<?php session_start();

include "../../dbConfig.php";

if (isset($_POST["submit"])) {
    $uname = $_POST['uname'];
    $pass = $_POST['pass'];

    function validate($data)
    {

        $data = trim($data);

        $data = stripslashes($data);

        $data = htmlspecialchars($data);

        return $data;
    }

    $uname = validate($_POST['uname']);

    $pass = validate($_POST['pass']);

    if (empty($uname)) {

        header("Location: login.php?error=User Name is required");

        exit();
    } else if (empty($pass)) {

        header("Location: login.php?error=Password is required");

        exit();
    } else {

        $result = mysqli_query($conn, "SELECT id, uname, pass, hpass FROM users WHERE uname = '" . $uname . "' and pass = '" . $pass . "'");
        if ($row = mysqli_fetch_array($result)) {

            $saltedPassword = $pass . SALT;
            $hashedEnteredPassword = password_hash($saltedPassword, PASSWORD_BCRYPT);

            if (password_verify($saltedPassword, $row['hpass'])) {
                // Password is correct, grant access to the user
                $_SESSION['id']     = $row['id'];
                $_SESSION['username']   = $row['uname'];
                $_SESSION['password'] = $row['pass'];

                $sql = "INSERT INTO login (sid,uname)VALUES ('{$_SESSION['id']}','{$row['uname']}')";

                header('Location:../../index.php?id=' . $_SESSION['id']);
            } else {
                // Password is incorrect, show an error message
                header("Location: login.php?error=Incorrect User name or password");

                exit();
            }
        } else {

            header("Location: login.php?error=Incorrect User name or password");

            exit();
        }
    }
} else {

    header("Location: login.php");

    exit();
}
