<?php
class Helper
{
    /**
     * @var Array $USER_TYPE
     */
    public static $USER_TYPE = [
        'S' => 'Super Admin',
        'A' => 'Admin',
        'U' => 'User',
    ];
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
        return FOLDER_PATH; //rtrim($_SERVER['SCRIPT_NAME'], 'index.php');
    }

    public static function fullbaseUrl()
    {
        return self::siteUrl() . self::baseUrl();
    }

    public static function getDateTimeByFormat($dateTime, $format = "Y-m-d H:m:s")
    {
        return date($format, strtotime($dateTime));
    }

    public static function logText($logData, $folder_path = "logData/")
    {
        $file_path = BASEPATH . $folder_path;
		//var_dump($file_path);
		//var_dump($logData);

        $text = date("Y-m-d H:i:s") . "                   " . $logData . "\n";
        $file_name = $file_path . date("Y_m_d") . ".txt";
        $file_handler = fopen($file_name, "a");
        fwrite($file_handler, $text);
        fclose($file_handler);
    }
}
