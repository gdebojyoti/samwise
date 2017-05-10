<?php

session_start();

require_once 'include/connection.php';
require_once 'models/students.php';

$sts = 0;

switch ($verb) {
    // Search for student
    case 'search':
        if(isset($_GET['name']) && trim($_GET['name']) != "") {
            $name = $_GET['name'];

            $query = array(
                "name" => $name
            );

            $data = Student::search($query);
        }
        else {
            $data = array(
                "sts" => 1,
                "msg" => "name required"
            );
        }
        break;

    // Add new student
    case 'register':
        if(isset($_POST['email'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $institute_id = $_POST['institute_id'];

            $dob = isset($_POST['dob']) ? $_POST['dob'] : "";
            $street_address = isset($_POST['street_address']) ? $_POST['street_address'] : "";
            $city = isset($_POST['city']) ? $_POST['city'] : "";
            $district = isset($_POST['district']) ? $_POST['district'] : "";
            $state = isset($_POST['state']) ? $_POST['state'] : "";
            $pin = isset($_POST['pin']) ? $_POST['pin'] : "";
            $country = isset($_POST['country']) ? $_POST['country'] : "India";

            $data = Student::register($email, $password, $confirm_password, $name, $phone, $institute_id, $dob, $street_address, $city, $district, $state, $pin, $country);
        }
        break;

    // Login student
    case 'login':
        if(isset($_POST['email']) && isset($_POST['password'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $data = Student::login($email, $password);
        }
        break;

    // Check if anyne logged in
    case 'logged_status':
        if(isset($_SESSION['session'])) {
            $session = $_SESSION['session'];

            $data = Student::logged_status($session);

            if($data['sts'] == 0) {
                $data['data']->session = null;
            }
        }
        break;

    // Update student details
    case 'update':
        if(isset($_SESSION['session']) && isset($_POST['title']) && isset($_POST['pin'])) {
            $title = $_POST['title'];
            $description = $_POST['description'];
            $phone = $_POST['phone'];
            $phone_alt = $_POST['phone_alt'];
            $address = $_POST['address'];
            $address_2 = $_POST['address_2'];
            $city = $_POST['city'];
            $state = $_POST['state'];
            $pin = $_POST['pin'];
            $contact_email = $_POST['contact_email'];

            $website = $_POST['website'];
            $social_facebook = $_POST['social_facebook'];
            $social_google_plus = $_POST['social_google_plus'];
            $social_twitter = $_POST['social_twitter'];
            $social_youtube = $_POST['social_youtube'];

            $session = $_SESSION['session'];

            $rows_affected = Student::update($title, $description, $phone, $phone_alt, $address, $address_2, $city, $state, $pin, $contact_email, $website, $social_facebook, $social_google_plus, $social_twitter, $social_youtube, $session);

            if ($rows_affected == 1) {
                $data = array(
                    "sts" => 0,
                    "msg" => "student updated"
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

    // Get details of a single student
    case 'get':
        if(isset($_GET['id'])) {
            $id = $_GET['id'];

            $data = Student::get($id);

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
