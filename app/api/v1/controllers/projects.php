<?php

session_start();

require_once 'include/connection.php';
require_once 'models/projects.php';

$sts = 0;

switch ($verb) {
    // Create new project
    case 'create':
        if(isset($_POST['name']) && trim($_POST['name']) != "") {
            $name = $_POST['name']; // string
            $group_id = $_POST['group_id'] || 1; // int
            $address = $_POST['address']; // string
            $category = $_POST['category']; // string
            $weeks = $_POST['weeks']; // int
            $amount = $_POST['amount']; // int
            $funding_status = $_POST['funding_status']; // string
            $contributing = $_POST['contributing']; // int
            $asking = $_POST['asking']; // int
            $professor_id = $_POST['professor_id']; // int
            $details = $_POST['details']; // string

            $data = Project::create($name, $group_id, $address, $category, $weeks, $amount, $funding_status, $contributing, $asking, $professor_id, $details);
        }
        break;

    // Update project details
    case 'update':
        if(isset($_POST['name'])) {
            $name = $_POST['name'];

            $rows_affected = Project::update($name);

            if ($rows_affected == 1) {
                $data = array(
                    "sts" => 0,
                    "msg" => "project updated"
                );
            }
            else if ($rows_affected == 0) {
                $data = array(
                    "sts" => 1,
                    "msg" => "no changes made"
                );
            }
            else {
                $data = array(
                    "sts" => 1,
                    "msg" => "unknown error"
                );
            }

            $data['boo'] = $rows_affected;
        }
        break;

    // Assign students to project
    case 'assign':
        if(isset($_POST['id']) && isset($_POST['students'])) {
            $project_id = $_POST['id'];
            $student_id_arr = $_POST['students'];

            $data = Project::assign($project_id, $student_id_arr);
        }
        break;

    // Get details of a single project
    case 'get':
        if(isset($_GET['id'])) {
            $id = $_GET['id'];

            $data = Project::get($id);
        }
        break;

    // Doesn't satisfy any of the above verbs. Default verb.
    default:
        $data = array(
            "sts" => 1,
            "msg" => "No such verb exists"
        );
        break;
}

?>
