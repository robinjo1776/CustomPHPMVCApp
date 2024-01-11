<?php
class Controller
{
    public $response;
    public $request;
    private static $instance = null;

    function __construct()
    {
        self::$instance = &$this;
        $this->request = new Request();
        $this->response = new Response();
    }

    public static function &getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getRequest()
    {
        return $this->request;
    }

    protected function render($view, $data = [])
    {
        extract($data);
        include(APP_PATH . "/views/{$view}.php");
    }
}
