<?php

session_start();

require_once 'include/connection.php';
require_once 'models/professors.php';

$sts = 0;

switch ($verb) {
    // Search for professor
    case 'search':
        if(isset($_GET['name']) && trim($_GET['name']) != "") {
            $name = $_GET['name'];

            $query = array(
                "name" => $name
            );

            $data = Professor::search($query);
        }
        else {
            $data = array(
                "sts" => 1,
                "msg" => "name required"
            );
        }
        break;

    // Add new professor
    case 'register':
        if(isset($_POST['email'])) {
            /* Required fields */
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            $institute_id = $_POST['institute_id'];

            /* Optional fields */
            $name = isset($_POST['name']) ? $_POST['name'] : "";
            $phone = isset($_POST['phone']) ? $_POST['phone'] : "";
            $country = isset($_POST['country']) ? $_POST['country'] : "";
            $street_address = isset($_POST['street_address']) ? $_POST['street_address'] : "";
            $city = isset($_POST['city']) ? $_POST['city'] : "";
            $state = isset($_POST['state']) ? $_POST['state'] : "";
            $pin = isset($_POST['pin']) ? $_POST['pin'] : "";

            $data = Professor::register($email, $password, $confirm_password, $institute_id, $name, $phone, $country, $street_address, $city, $state, $pin);
        }
        break;

    // Login professor
    case 'login':
        if(isset($_POST['email']) && isset($_POST['password'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $data = Professor::login($email, $password);
        }
        break;

    // Check if anyne logged in
    case 'logged_status':
        if(isset($_SESSION['session'])) {
            $session = $_SESSION['session'];

            $data = Professor::logged_status($session);

            if($data['sts'] == 0) {
                $data['data']->session = null;
            }
        }
        break;

    // Update professor details
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

            $rows_affected = Professor::update($title, $description, $phone, $phone_alt, $address, $address_2, $city, $state, $pin, $contact_email, $website, $social_facebook, $social_google_plus, $social_twitter, $social_youtube, $session);

            if ($rows_affected == 1) {
                $data = array(
                    "sts" => 0,
                    "msg" => "professor updated"
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

    // Get details of a single professor
    case 'get':
        if(isset($_GET['id'])) {
            $id = $_GET['id'];

            $data = Professor::get($id);

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
