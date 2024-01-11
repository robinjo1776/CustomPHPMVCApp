<?php
class Model
{
    protected $db;
    protected $user_id;
    protected $user_name;

    public function __construct()
    {
        // Adjust database connection details accordingly
        $this->db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
        // set session variable
        $this->user_id = (isset($_SESSION['id']) && !empty($_SESSION['id'])) ? $_SESSION['id'] : '';
        $this->user_name = (isset($_SESSION['username']) && !empty($_SESSION['username'])) ? $_SESSION['username'] : '';
    }
}
