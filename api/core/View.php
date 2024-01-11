<?php
class View
{
    public static function render($view, $data = [])
    {
        extract($data);
        include(APP_PATH . "/views/{$view}.php");
    }
}
