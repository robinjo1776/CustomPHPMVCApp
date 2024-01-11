<?php
$env = "production"; // production or development
define('DEBUG', true);
define('BASEPATH', dirname(__FILE__) . "/");
define('HELPER_PATH', BASEPATH . "/helper/");
define('VIEW_PATH', BASEPATH . "/views/");
define('FOLDER_PATH', "/wintersturkeys/");
define('SALT', "6d918b4118ca0f5d6b3c3bd766926a33");

// QickBooks Configuration
define("AUTHORIZATION_REQUEST_URL", "6d918b4118ca0f5d6b3c3bd766926a33");
define("TOKEN_ENDPOINT_URL", "https://oauth.platform.intuit.com/oauth2/v1/tokens/bearer");
define("CLIENT_ID", "ABiNxxYxVoplJiwIvd0q17LOkjxGf9DxkjUj4j64AQU9J2GcjT");
define("CLIENT_SECRET", "mbehQEc117dTK7X2pxnva9vvLxk3nW5b1CpJy8iD");
define("OAUTH_SCOPE", "com.intuit.quickbooks.accounting");
define("OAUTH_REDIRECT_URI", "frontend/qb/callback.php");
define("QB_ENVIRONMENT", "Development");
define("QB_REALM_ID", "4620816365354357830");

if (DEBUG) {
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
} else {
  error_reporting(0);
}

include_once(HELPER_PATH . 'Helper.php');

$servername = "localhost";
$username = "root";
$password = "Sal@2486#dev";
$dbname = "wintersturkeys";

if ($env == "development") {
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "wintersturkeys";
}

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
