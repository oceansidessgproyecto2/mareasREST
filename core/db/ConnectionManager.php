<?php
    Class ConnectionManager {
        protected $con;
        protected function connect() {
            require dirname(dirname(dirname(__FILE__))) . '/config/dbconfig.php';
            try {
                $options = [
                    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::ATTR_EMULATE_PREPARES   => false,
                    \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                ];
                $dsn = "mysql:host=$host;dbname=$db;charset=$charset;port=$port";
                $this->con = new \PDO($dsn, $user, $pass, $options);
            } catch (\PDOException $e) {
                throw $e;
            }
        }
        protected function disconnect() {
            $this->con = '';
        }
    }