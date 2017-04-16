<?php

class Project {
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

        // get matching projects if "name" is available
        if(strlen($query['name']) > 0) {
            $req = $db->prepare('SELECT * FROM projects WHERE name LIKE :name');
        }
        // get list of all projects if no "name" is supplied
        else {
            $req = $db->prepare('SELECT * FROM projects');
        }

        $req->execute(array('name' => "%" . $query['name'] . "%"));

        foreach($req->fetchAll() as $inst) {
            $list[] = new Project($inst['id'], $inst['name'], $inst['status'], $inst['creation_date'], $inst['created_by']);
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

    public static function create($name, $group_id, $address, $category, $category_other, $weeks, $amount, $funding_status, $contributing, $asking, $professor_id, $details,
            $contact_name, $sex, $age, $occupation, $contact_address, $gps, $phone, $email) {
        $status = 1;

        $db = Db::getInstance();
        $req = $db->prepare('INSERT INTO projects (name, group_id, address, category, category_other, weeks, amount, funding_status, contributing, asking, professor_id, details, status, contact_name, sex, age, occupation, contact_address, gps, phone, email)
                VALUES (:name, :group_id, :address, :category, :category_other, :weeks, :amount, :funding_status, :contributing, :asking, :professor_id, :details, :status, :contact_name, :sex, :age, :occupation, :contact_address, :gps, :phone, :email)');
        // try {
            $req->execute(array('name' => $name, 'group_id' => $group_id, 'address' => $address, 'category' => $category, 'category_other' => $category_other, 'weeks' => $weeks, 'amount' => $amount,
                    'funding_status' => $funding_status, 'contributing' => $contributing, 'asking' => $asking, 'professor_id' => $professor_id, 'details' => $details, 'status' => $status.
                    'contact_name' => $contact_name, 'sex' => $sex, 'age' => $age, 'occupation' => $occupation, 'contact_address' => $contact_address, 'gps' => $gps, 'phone' => $phone, 'email' => $email));
            $data = array(
                "sts" => 0,
                "msg" => "project created"
            );
        // }
        // catch (PDOException $e) {
        //     $err = $e->errorInfo[1];
        //
        //     $data = array(
        //         "sts" => 1,
        //         "msg" => "unknown error: " . $err
        //     );
        // }

        return $data;
    }

    public static function get($id) {
        $db = Db::getInstance();
        $req = $db->prepare('SELECT * FROM projects WHERE id = :id LIMIT 1');
        $req->execute(array('id' => $id));
        $project = $req->fetch();

        if ($project) {
            $data = array(
                "sts" => 0,
                "data" => new Project($project['id'], $project['name'], $project['status'], $project['creation_date'], $project['created_by'])
            );
        }
        else {
            $data = array(
                "sts" => 1,
                "msg" => "project not found"
            );
        }

        return $data;
    }

    public static function assign($project_id, $student_id_arr) {
        // convert string to array
        $student_id_arr = explode(",", $student_id_arr);

        $status = 1;

        $db = Db::getInstance();

        for($index = 0; $index < sizeof($student_id_arr); $index++) {

            $req = $db->prepare('INSERT INTO project_student_assignment (project_id, student_id, status) VALUES (:project_id, :student_id, :status)');
            $req->execute(array('project_id' => $project_id, 'student_id' => $student_id_arr[$index], 'status' => $status));
        }

        $data = array(
            "sts" => 0,
            "msg" => "students assigned to project"
        );

        return $data;
    }
}

?>
