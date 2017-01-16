<?php

session_start();

require_once 'include/connection.php';
require_once 'models/institutes.php';

$sts = 0;

switch ($verb) {
    // Search for institute
    case 'search':
        if(isset($_GET['name']) && trim($_GET['name']) != "") {
            $name = $_GET['name'];

            $query = array(
                "name" => $name
            );

            $data = Institute::search($query);
        }
        // get list of all institutes if no "name" is supplied
        else {
            // $data = array(
            //     "sts" => 1,
            //     "msg" => "name required"
            // );
            $query = array(
                "name" => ""
            );

            $data = Institute::search($query);
        }
        break;

    // Add new institute
    case 'register':
        if(isset($_POST['name'])) {
            $name = $_POST['name'];
            $type = $_POST['type'];
            $country = $_POST['country'];

            $phone = isset($_POST['phone']) ? $_POST['phone'] : "";
            $email = isset($_POST['email']) ? $_POST['email'] : "";
            $website = isset($_POST['website']) ? $_POST['website'] : "";

            $street_address = isset($_POST['street_address']) ? $_POST['street_address'] : "";
            $city = isset($_POST['city']) ? $_POST['city'] : "";
            $district = isset($_POST['district']) ? $_POST['district'] : "";
            $state = isset($_POST['state']) ? $_POST['state'] : "";
            $pin = isset($_POST['pin']) ? $_POST['pin'] : "";
            $created_by = isset($_POST['created_by']) ? $_POST['created_by'] : "";

            $data = Institute::register($name, $type, $phone, $email, $website, $street_address, $city, $district, $state, $pin, $country, $created_by);
        }
        break;

    // Update institute details
    case 'update':
        if(isset($_POST['name'])) {
            $name = $_POST['name'];
            $type = $_POST['type'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            $website = $_POST['website'];
            $country = $_POST['country'];

            $street_address = isset($_POST['street_address']) ? $_POST['street_address'] : "";
            $city = isset($_POST['city']) ? $_POST['city'] : "";
            $district = isset($_POST['district']) ? $_POST['district'] : "";
            $state = isset($_POST['state']) ? $_POST['state'] : "";
            $pin = isset($_POST['pin']) ? $_POST['pin'] : "";
            $created_by = isset($_POST['created_by']) ? $_POST['created_by'] : "";

            $rows_affected = Institute::update($name, $type, $phone, $email, $website, $street_address, $city, $district, $state, $pin, $country);

            if ($rows_affected == 1) {
                $data = array(
                    "sts" => 0,
                    "msg" => "institute updated"
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

    // Get details of a single institute
    case 'get':
        if(isset($_GET['id'])) {
            $id = $_GET['id'];

            $data = Institute::get($id);

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
