<?php
class Request
{
    public $_posts;
    public $_gets;
    public $_request;
    public $_files;
    public $_is_post;
    public $_rowData;
    public $_controller_name;
    public $_action_name;

    public function __construct()
    {
        $this->_is_post = false;
        $this->_gets = array();
        $this->_posts = array();
        $this->_files = array();
        $this->_rowData = array();

        if ($_SERVER['REQUEST_METHOD'] == 'POST')
            $this->_is_post = true;

        if (isset($_POST) && !empty($_POST)) {
            $this->_is_post = true;
            foreach ($_POST as $key => $val) {
                $this->_posts[$key] = $val;
            }
        }

        if (isset($_GET) && !empty($_GET)) {
            foreach ($_GET as $key => $val) {
                $this->_gets[$key] = $val;
            }
        }

        if (isset($_REQUEST) && !empty($_REQUEST)) {
            foreach ($_REQUEST as $key => $val) {
                $this->_request[$key] = $val;
            }
        }

        if (isset($_FILES) && !empty($_FILES)) {
            foreach ($_FILES as $key => $val) {
                $this->_files[$key] = $val;
            }
        }

        $rawData = file_get_contents("php://input");
        if (!empty($rawData)) {
            $this->_rowData = json_decode($rawData, true);
        }
    }

    public function getPostData($key = '')
    {
        if (empty($key)) return $this->_posts;
        return isset($this->_posts[$key]) ?  $this->_posts[$key] : null;
    }

    public function getRequestData($key = '', $defaultValue = '')
    {
        if (empty($key)) return $this->_request;
        return isset($this->_request[$key]) ? $this->_request[$key] : $defaultValue;
    }

    public function getFilesData($key = '', $defaultValue = '')
    {
        if (empty($key)) return $this->_files;
        return isset($this->_files[$key]) ? $this->_files[$key] : $defaultValue;
    }

    public function getGetData($key = '')
    {
        if (empty($key)) return $this->_gets;
        return isset($this->_gets[$key]) ? $this->_gets[$key] : null;
    }

    public function getRawData($key = '')
    {
        if (empty($key)) return $this->_rowData;
        return isset($this->_rowData[$key]) ? $this->_rowData[$key] : null;
    }

    public function isPost()
    {
        return $this->_is_post ? true : false;
    }
}
