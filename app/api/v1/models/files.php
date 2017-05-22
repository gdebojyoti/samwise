<?php

class File {
    public $url;

    public function __construct($url) {
        $this->url = $url;
    }

    public static function upload($category, $file) {
        $CATEGORIES = ["STUDENTS", "PROJECTS", "GROUPS", "INSTITUTES"];
        $FILE_TYPES = ["png", "jpg", "jpeg"];
        $UPLOAD_DIRECTORY_BASE = "../../../user-data/";

        // verify if category is valid; if not return error msg
        if (!in_array($category, $CATEGORIES)) {
            return array(
                "sts" => 1,
                "msg" => "invalid file 'category'"
            );
        }

        // verify file type
        $file_name = $file["name"];
        $temp = explode(".", $file_name);
        $file_type = end( $temp );
        if (!in_array($file_type, $FILE_TYPES)) {
            return array(
                "sts" => 1,
                "msg" => "invalid file type"
            );
        }

        // OPTIONAL: verify file size

        // get current timestamp, and use it to create new file name
        $time_stamp = (new DateTime())->getTimestamp();
        $file_name = md5($time_stamp . $file_name) . "." . $file_type;

        // append destination folder name to file name
        $file_url = strtolower($category) . "/" . $file_name;

        // upload file; if success, return URL; else return error
        $source_path = $file['tmp_name']; // storing source path of the file in a variable
        $target_path = $UPLOAD_DIRECTORY_BASE . $file_url; // target path where file is to be stored
        $uploading_file = move_uploaded_file($source_path, $target_path) ; // moving Uploaded file

        if($uploading_file == 1) {
            $data = array(
                "sts" => 0,
                "data" => array(
                    "file_url" => $file_url
                )
            );
        } else {
            $data = array(
                "sts" => 1,
                "msg" => "file upload failed"
            );
        }

        return $data;
    }
}

?>
