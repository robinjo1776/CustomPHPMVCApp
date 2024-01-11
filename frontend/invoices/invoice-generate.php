<?php session_start();
$uid = $_SESSION['id'];
$uname = $_SESSION['username'];
if (isset($uid)) {
  date_default_timezone_set('America/Edmonton');
  include "../../dbConfig.php";
  $orderno = $_GET["orderno"];

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
            ord.status_ord
          FROM
            orders AS ord
          LEFT JOIN customers AS cus ON cus.id = ord.customer_id
          LEFT JOIN shipvia AS shv ON shv.id = ord.shipvia
          LEFT JOIN users AS usr ON usr.id = ord.orderedby
          WHERE orderno='$orderno'";

  $orderDetailsResult = mysqli_query($conn, $sql);
  $orderDetails = [];
  if (mysqli_num_rows($orderDetailsResult) > 0) {
    $orderDetails = mysqli_fetch_assoc($orderDetailsResult);
  }
  if (!empty($orderDetails)) {
    if (!empty($orderDetails["invoiceno"])) {
      header('Location:' . Helper::fullbaseUrl() . "frontend/invoices/invoice.php?invoiceno=" . $orderDetails["invoiceno"]);
    } else {
      $invNum = "INV" . date("Ymd");
      $sql = "SELECT * FROM orders WHERE invoiceno LIKE '$invNum%' ORDER BY invoiceno DESC LIMIT 1";
      $lastInvoiceDetailsResult = mysqli_query($conn, $sql);
      $lastInvoiceDetails = [];
      if (mysqli_num_rows($lastInvoiceDetailsResult) > 0) {
        $lastInvoiceDetails = mysqli_fetch_assoc($lastInvoiceDetailsResult);
      }
      $invNum = "INV" . date("Ymd") . "0001";
      if (!empty($lastInvoiceDetails)) {
        // Extract the numeric part (assuming it's at the end of the string)
        $numericPart = preg_replace("/[^0-9]/", '', $lastInvoiceDetails["invoiceno"]);
        // Increment the numeric part
        $numericPart = (int)$numericPart + 1;
        // Get the non-numeric part of the string
        $nonNumericPart = preg_replace("/[0-9]/", '', $lastInvoiceDetails["invoiceno"]);
        // Concatenate the non-numeric and incremented numeric parts
        $invNum = $nonNumericPart . $numericPart;
      }

      $sql = "UPDATE orders SET invoiceno='$invNum' WHERE orderno='$orderno'";
      mysqli_query($conn, $sql);
      header('Location:' . Helper::fullbaseUrl() . "frontend/invoices/invoice.php?invoiceno=" . $invNum);
    }
  } else {
    header('Location:' . Helper::fullbaseUrl());
  }
} else {
  header('Location:../users/login.php');
}
