<?php

session_start();

// require_once 'include/connection.php';
require_once 'models/files.php';

$sts = 0;

switch ($verb) {
    // Create new file
    case 'upload':
        if(isset($_POST['category']) && trim($_POST['category']) != "" && isset($_FILES['file']) && isset($_FILES['file']['type'])) {
            $category = $_POST['category'];
            $file = $_FILES['file'];

            $data = File::upload($category, $file);
        } else {
            $data = array(
                "sts" => 1,
                "msg" => "file or 'category' missing"
            );
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
