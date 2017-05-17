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

    public static function create($name, $created_by, $student_id_arr) {
        $status = 1;

        $db = Db::getInstance();
        $req = $db->prepare('INSERT INTO groups (name, status, created_by)
                VALUES (:name, :status, :created_by)');
        try {
            $req->execute(array('name' => $name, 'status' => $status, 'created_by' => $created_by));

            // get ID of last inserted row
            $req_get_last_id = $db->prepare('SELECT LAST_INSERT_ID()');
            $req_get_last_id->execute();
            $get_last_id = $req_get_last_id->fetch();

            // alter student ID array to include group creator ID
            $student_id_arr .= "," . $created_by;

            // trigger assign method
            $result = self::assign($get_last_id[0], $student_id_arr);

            if ($result["sts"] == 0) {
                $data = array(
                    "sts" => 0,
                    "msg" => "group created and students assigned"
                );
            } else {
                $data = array(
                    "sts" => 1,
                    "msg" => "group created but no students assigned"
                );
            }
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

    // public static function create($name, $created_by) {
    //     $status = 1;
    //
    //     $db = Db::getInstance();
    //     $req = $db->prepare('INSERT INTO groups (name, status, created_by)
    //             VALUES (:name, :status, :created_by)');
    //     try {
    //         $req->execute(array('name' => $name, 'status' => $status, 'created_by' => $created_by));
    //         $data = array(
    //             "sts" => 0,
    //             "msg" => "group created"
    //         );
    //     }
    //     catch (PDOException $e) {
    //         $err = $e->errorInfo[1];
    //
    //         $data = array(
    //             "sts" => 1,
    //             "msg" => "unknown error: " . $err
    //         );
    //     }
    //
    //     return $data;
    // }

    public static function get($id) {
        $db = Db::getInstance();
        $req = $db->prepare('SELECT * FROM groups WHERE id = :id LIMIT 1');
        $req->execute(array('id' => $id));
        $group = $req->fetch();

        if ($group) {
            $data = array(
                "sts" => 0,
                "data" => new Group($group['id'], $group['name'], $group['status'], $group['creation_date'], $group['created_by'])
            );
        }
        else {
            $data = array(
                "sts" => 1,
                "msg" => "group not found"
            );
        }

        return $data;
    }

    public static function assign($group_id, $student_id_arr) {
        // convert string to array
        $student_id_arr = explode(",", $student_id_arr);

        $status = 1;

        $db = Db::getInstance();

        for($index = 0; $index < sizeof($student_id_arr); $index++) {

            $req = $db->prepare('INSERT INTO group_student_assignment (group_id, student_id, status) VALUES (:group_id, :student_id, :status)');
            $req->execute(array('group_id' => $group_id, 'student_id' => $student_id_arr[$index], 'status' => $status));
        }

        $data = array(
            "sts" => 0,
            "msg" => "students assigned to group"
        );

        return $data;
    }
}

?>
