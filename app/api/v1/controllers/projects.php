<?php

session_start();

require_once 'include/connection.php';
require_once 'models/projects.php';

$sts = 0;

switch ($verb) {
    // Create new project
    case 'create':
        if(isset($_POST['name']) && trim($_POST['name']) != "") {
            // Project details
            $name = $_POST['name']; // string
            $group_id = $_POST['group_id'] || 1; // int
            $address = $_POST['address']; // string
            $category = $_POST['category']; // string
            $category_other = $_POST['category_other']; // string
            $weeks = $_POST['weeks']; // int
            $amount = $_POST['amount']; // int
            $funding_status = $_POST['funding_status']; // string
            $contributing = $_POST['contributing'] || 0; // int
            $asking = $_POST['asking'] || 0; // int
            $professor_id = $_POST['professor_id']; // int
            $details = $_POST['details']; // string

            // Details of contact person
            $contact_name = $_POST['contact_name']; // string
            $sex = $_POST['sex']; // int; MALE: 1, FEMALE: 2
            $age = $_POST['age']; // int
            $occupation = $_POST['occupation']; // string
            $contact_address = $_POST['contact_address']; // string
            $gps = $_POST['gps'] || ""; // string
            $phone = $_POST['phone']; // int
            $email = $_POST['email']; // string

            $data = Project::create($name, $group_id, $address, $category, $category_other, $weeks, $amount, $funding_status, $contributing, $asking, $professor_id, $details,
                    $contact_name, $sex, $age, $occupation, $contact_address, $gps, $phone, $email);
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
