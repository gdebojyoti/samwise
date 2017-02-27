<?php

class Group {
    public $id;
    public $name;

    public function __construct($id, $name, $status, $creation_date, $created_by) {
        $this->id = $id;
        $this->name = $name;
        $this->status = $status;
        $this->creation_date = $creation_date;
        $this->created_by = $created_by;
    }

    public static function search($query) {
        $list = [];
        $db = Db::getInstance();

        // get matching groups if "name" is available
        if(strlen($query['name']) > 0) {
            $req = $db->prepare('SELECT * FROM groups WHERE name LIKE :name');
        }
        // get list of all groups if no "name" is supplied
        else {
            $req = $db->prepare('SELECT * FROM groups');
        }

        $req->execute(array('name' => "%" . $query['name'] . "%"));

        foreach($req->fetchAll() as $inst) {
            $list[] = new Group($inst['id'], $inst['name'], $inst['status'], $inst['creation_date'], $inst['created_by']);
        }

        if(sizeof($list) > 0) {
            $data = array(
                "sts" => 0,
                "data" => $list
            );
        } else {
            $data = array(
                "sts" => 1,
                "msg" => "no results found"
            );
        }

        return $data;
    }

    public static function create($name, $created_by) {
        $status = 1;

        $db = Db::getInstance();
        $req = $db->prepare('INSERT INTO groups (name, status, created_by)
                VALUES (:name, :status, :created_by)');
        try {
            $req->execute(array('name' => $name, 'status' => $status, 'created_by' => $created_by));
            $data = array(
                "sts" => 0,
                "msg" => "group created"
            );
        }
        catch (PDOException $e) {
            $err = $e->errorInfo[1];

            $data = array(
                "sts" => 1,
                "msg" => "unknown error: " . $err
            );
        }

        return $data;
    }
}

?>
