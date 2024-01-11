<?php
session_start();
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

define('APP_PATH', dirname(__DIR__) . '/app');
define('UPLOAD_PATH', dirname(__DIR__) . '/public/uploads/');
define('SALT', "6d918b4118ca0f5d6b3c3bd766926a33");

require_once('../config.php');
require_once('../core/Request.php');
require_once('../core/Response.php');
require_once('../core/Router.php');
require_once('../core/Controller.php');
require_once('../core/Model.php');
require_once('../core/View.php');
require_once('../core/Helper.php');
require_once('../../vendor/phpspreadsheet/vendor/autoload.php');

$url = $_SERVER['REQUEST_URI'];
$route = new Router();
$route->route($url);
