<?php
class Helper
{
    /**
     * @var Array $API_CODE_MESSAGE
     */
    public static $API_CODE = [
        200 => 'OK',
        204 => 'No Content',
        400 => "Bad Request",
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        500 => 'Internal Server Error'
    ];

    /**
     * @var Array $USER_TYPE
     */
    public static $USER_TYPE = [
        'S' => 'Super Admin',
        'A' => 'Admin',
        'U' => 'User',
    ];

    /**
     * @var Array $METHOD_CODE_MESSAGE
     */
    public static $METHOD_CODE = [];

    public static function pr($obj)
    {
        echo "<pre>" . print_r($obj, true) . "</pre>";
    }

    public static function checkRequest($controller, $method)
    {
        if (!preg_match('/^[a-zA-Z\-]+$/i', $controller))
            return false;

        if (empty($method) || !preg_match('/^[a-zA-Z\-]+$/i', $method))
            return false;

        return true;
    }

    public static function routeFormate($route)
    {
        $routeName = '';
        $wrRoutes = explode('-', $route);
        foreach ($wrRoutes as $wrRoute) {
            $routeName .= ucfirst($wrRoute);
        }

        return $routeName;
    }

    public static function siteUrl()
    {
        $site_url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ?  "https" : "http");
        $site_url .= "://" . $_SERVER['HTTP_HOST'];

        return $site_url;
    }

    public static function baseUrl()
    {
        return rtrim($_SERVER['SCRIPT_NAME'], 'index.php');
    }

    public static function fullbaseUrl()
    {
        return self::siteUrl() . self::baseUrl();
    }
    public static function AddModel($modelname)
    {
        $modelname = rtrim($modelname, ".php");
        $modelname .= ".php";
        if (file_exists(APP_PATH . "/models/" . $modelname)) {
            include_once APP_PATH . "/models/" . $modelname;
        } elseif (file_exists(APP_PATH . "/models/" . strtolower($modelname))) {
            include_once APP_PATH . "/models/" . strtolower($modelname);
        }
    }
    public static function getDateTimeByFormat($dateTime, $format = "Y-m-d H:m:s")
    {
        return date($format, strtotime($dateTime));
    }
}
