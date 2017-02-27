<?php

session_start();

require_once 'include/connection.php';
require_once 'models/groups.php';

$sts = 0;

switch ($verb) {
    // Create new group
    case 'create':
        if(isset($_POST['name']) && trim($_POST['name']) != "") {
            $name = $_POST['name'];
            $created_by = isset($_POST['created_by']) ? $_POST['created_by'] : "";

            $data = Group::create($name, $created_by);
        }
        break;

    // Update group details
    case 'update':
        if(isset($_POST['name'])) {
            $name = $_POST['name'];

            $rows_affected = Group::update($name);

            if ($rows_affected == 1) {
                $data = array(
                    "sts" => 0,
                    "msg" => "group updated"
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

    // Get details of a single group
    case 'get':
        if(isset($_GET['id'])) {
            $id = $_GET['id'];

            $data = Group::get($id);

            if($data['sts'] == 0) {
                $data['data']->session = null;
            }
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
