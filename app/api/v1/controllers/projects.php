<?php

session_start();

require_once 'include/connection.php';
require_once 'models/projects.php';

$sts = 0;

switch ($verb) {
    // Search for project
    case 'search':
        $name = isset($_GET['name']) ? trim($_GET['name']) : "";
        $query = array(
            "name" => $name
        );

        $data = Project::search($query);

        break;

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

    // Search for project
    case 'search':
        $name = isset($_GET['name']) ? trim($_GET['name']) : "";
        $query = array(
            "name" => $name
        );

        $data = Project::search($query);

        break;

    // Update project details
    case 'update':
        if(isset($_POST['id'])) {
            $id = $_POST['id'];
            $status = isset($_POST['status']) ? trim($_POST['status']) : 1;

            $data = Project::update($id, $status);
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

    // Delete project
    case 'delete':
        if(isset($_POST['id'])) {
            $id = $_POST['id'];

            $data = Project::delete($id);
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
