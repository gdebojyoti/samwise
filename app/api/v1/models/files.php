<?php

class File {
    public $url;

    public function __construct($url) {
        $this->url = $url;
    }

    public static function upload($category, $file) {
        $CATEGORIES = ["STUDENT", "PROJECT", "GROUP", "INSTITUTE"];
        $FILE_TYPES = ["png", "jpg", "jpeg"];
        $UPLOAD_DIRECTORY_BASE = "../../../user-data/";

        // verify if category is valid; if not return error msg
        if (!in_array($category, $CATEGORIES)) {
            return array(
                "sts" => 1,
                "data" => "invalid file 'category'"
            );
        }

        // verify file type
        $file_name = $file["name"];
        $temp = explode(".", $file_name);
        $file_type = end( $temp );
        if (!in_array($file_type, $FILE_TYPES)) {
            return array(
                "sts" => 1,
                "data" => "invalid file type"
            );
        }

        // OPTIONAL: verify file size

        // upload file; if success, return URL; else return error
        $sourcePath = $file['tmp_name']; // Storing source path of the file in a variable
        $targetPath = $UPLOAD_DIRECTORY_BASE . $file['name']; // Target path where file is to be stored
        $uploading_file = move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file

        if($uploading_file == 1) {
            $data = array(
                "sts" => 0,
                "data" => "file upload successful"
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
