<?php

require_once 'API.class.php';

class MyAPI extends API {
    protected $User;

    public function __construct($request, $origin) {
        parent::__construct($request);
    }

    protected function students($verb, $args) {
        require_once "controllers/students.php";
        if (isset($data)) return $data;
        else return array(
            "sts" => -1,
            "msg" => "Invalid request"
        );
    }

    protected function professors($verb, $args) {
        require_once "controllers/professors.php";
        if (isset($data)) return $data;
        else return array(
            "sts" => -1,
            "msg" => "Invalid request"
        );
    }

    protected function institutes($verb, $args) {
        require_once "controllers/institutes.php";
        if (isset($data)) return $data;
        else return array(
            "sts" => -1,
            "msg" => "Invalid request"
        );
    }

    protected function groups($verb, $args) {
        require_once "controllers/groups.php";
        if (isset($data)) return $data;
        else return array(
            "sts" => -1,
            "msg" => "Invalid request"
        );
    }

    protected function projects($verb, $args) {
        require_once "controllers/projects.php";
        if (isset($data)) return $data;
        else return array(
            "sts" => -1,
            "msg" => "Invalid request"
        );
    }
}
