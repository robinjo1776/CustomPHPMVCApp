<?php
session_start();
include "../../dbConfig.php";

$sql = "DELETE FROM login WHERE sid='{$_SESSION["id"]}'";
unset($_SESSION["id"]);
session_unset();
if ($conn->query($sql) === TRUE) {
    header("Location:login.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
