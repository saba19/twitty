<?php
require_once __DIR__ . './../.config.php';

class DB {

    private static $instance;
    private $host = DB_SERVER;
    private $dbname = DB_NAME;
    private $login = DB_USER;
    private $password = DB_PASS;
    private function __construct() {}
    public static function getInstance() {
        if(!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public function initConnection() {
        // connect do DB
        $connection = new PDO("mysql:host=". $this->getHost() .";dbname=". $this->getDbname() .";charset=utf8", $this->getLogin(), $this->getPassword());
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $connection;
    }
    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }
    /**
     * @return mixed
     */
    public function getDbname()
    {
        return $this->dbname;
    }
    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }
    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }
    private function __clone() {}
}