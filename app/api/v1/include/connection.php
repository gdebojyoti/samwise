<?php

    require_once '../../config/db.php';

    class Db {
        private static $instance = NULL;

        private function __construct() {
            $this->appName = $appName;
        }

        private function __clone() {}

        public static function getInstance() {
            if (!isset(self::$instance)) {
                $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
                self::$instance = new PDO('mysql:host=' . $GLOBALS['db_host'] . ';dbname=' . $GLOBALS['db_name'], $GLOBALS['db_user'], $GLOBALS['db_pass'], $pdo_options);
            }
            return self::$instance;
        }
    }
?>
