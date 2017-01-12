<?php

class Student {
    public $id;
    public $name;

    public function __construct($id, $email, $name, $street_address, $city, $district, $state, $pin, $country, $status, $level, $creation_date) {
        $this->id = $id;
        $this->email = $email;
        $this->name = $name;
        $this->street_address = $street_address;
        $this->city = $city;
        $this->district = $district;
        $this->state = $state;
        $this->pin = $pin;
        $this->country = $country;
        $this->status = $status;
        $this->level = $level;
        $this->creation_date = $creation_date;
    }

    public static function all($q) {
        $list = [];
        $db = Db::getInstance();
        $req = $db->prepare('SELECT id, name FROM countries WHERE name LIKE :q ORDER BY name ASC');
        $req->execute(array('q' => '%' . $q . '%'));

        foreach($req->fetchAll() as $country) {
            $list[] = new Country($country['id'], $country['name']);
        }

        return $list;
    }

    public static function search($query) {
        $list = [];
        $db = Db::getInstance();

        $req = $db->prepare('SELECT * FROM students WHERE name LIKE :name');
        $req->execute(array('name' => "%" . $query['name'] . "%"));

        foreach($req->fetchAll() as $user) {
            $list[] = new Student($user['id'], $user['email'], $user['name'], $user['street_address'], $user['city'],
            $user['district'], $user['state'], $user['pin'], $user['country'], $user['status'], $user['level'], $user['creation_date']);
        }

        if(sizeof($list) > 0) {
            $data = array(
                "sts" => 0,
                "data" => $list
            );
        } else {
            $data = array(
                "sts" => 1,
                "msg" => "no results found"
            );
        }

        return $data;
    }

    public static function register($email, $password, $confirm_password, $name, $phone, $institute_id, $country, $street_address, $city, $district, $state, $pin) {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $data = array(
                "sts" => 1,
                "msg" => "invalid email"
            );
            return $data;
        }

        if($password != $confirm_password) {
            $data = array(
                "sts" => 1,
                "msg" => "password mismatch"
            );
            return $data;
        }

        if(strlen($phone) != 10) {
            $data = array(
                "sts" => 1,
                "msg" => "invalid phone number"
            );
            return $data;
        }

        $password = md5($password);
        $timestamp = date("mdHis");
        $rand = rand(11111111, 99999999);
        $hash = md5($email);

        $session = $hash . $rand . $timestamp;

        // $country = "India";
        $status = 1;
        $level = 1;

        $db = Db::getInstance();
        $req = $db->prepare('INSERT INTO students (email, password, session, name, phone, institute_id, country, street_address, city, district, state, pin, status, level)
                VALUES (:email, :password, :session, :name, :phone, :institute_id, :country, :street_address, :city, :district, :state, :pin, :status, :level)');
        try {
            $req->execute(array('email' => $email, 'password' => $password, 'session' => $session, 'name' => $name,
                    'phone' => $phone, 'institute_id' => $institute_id, 'country' => $country, 'street_address' => $street_address,
                    'city' => $city, 'district' => $district, 'state' => $state, 'pin' => $pin, 'status' => $status, 'level' => $level));
            $data = array(
                "sts" => 0,
                "msg" => "student added"
            );
        }
        catch (PDOException $e) {
            $data = array(
                "sts" => 1,
                "msg" => ""
            );

            $err = $e->errorInfo[1];
            if($err == 1062) $data['msg'] = "email already exists";
            else $data['msg'] = "unknown error: " . $err;
        }

        return $data;
    }

    public static function login($email, $password) {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $data = array(
                "sts" => 1,
                "msg" => "invalid email"
            );
            return $data;
        }

        $password = md5($password);

        $db = Db::getInstance();
        $req = $db->prepare('SELECT * FROM students WHERE email = :email AND password = :password LIMIT 1');
        $req->execute(array('email' => $email, 'password' => $password));
        $user = $req->fetch();

        if ($user) {
            $data = array(
                "sts" => 0,
                "data" => new Student($user['id'], $user['email'], $user['name'], $user['street_address'], $user['city'], $user['district'],
                $user['state'], $user['pin'], $user['country'], $user['status'], $user['level'], $user['creation_date'])
            );
            $data["data"]->session = $user['session'];
        }
        else {
            $data = array(
                "sts" => 1,
                "msg" => "invalid credentials"
            );
        }

        return $data;
    }

    // Check if student is logged in - get student 'id' by searching using 'session'
    public static function get_student_by_session($session) {
        $db = Db::getInstance();
        $req = $db->prepare('SELECT id FROM students WHERE session = :session LIMIT 1');
        $req->execute(array('session' => $session));
        $user = $req->fetch();

        if ($user) {
            return $user['id'];
        }
        else {
            return 0;
        }
    }

    // public static function update($title, $description, $phone, $phone_alt, $address, $address_2, $city, $state, $pin, $contact_email, $website, $social_facebook, $social_google_plus, $social_twitter, $social_youtube, $session) {
    //     $db = Db::getInstance();
    //
    //     $req = $db->prepare('
    //         UPDATE students
    //         SET title = :title, description = :description, phone = :phone, phone_alt = :phone_alt, address = :address, address_2 = :address_2, city = :city, state = :state, pin = :pin, contact_email = :contact_email, website = :website, social_facebook = :social_facebook, social_google_plus = :social_google_plus, social_twitter = :social_twitter, social_youtube = :social_youtube
    //         WHERE session = :session
    //     ');
    //     $req->execute(array('title' => $title, 'description' => $description, 'phone' => $phone, 'phone_alt' => $phone_alt,
    //     'address' => $address, 'address_2' => $address_2, 'city' => $city, 'state' => $state, 'pin' => $pin, 'contact_email' => $contact_email,
    //     'website' => $website, 'social_facebook' => $social_facebook, 'social_google_plus' => $social_google_plus, 'social_twitter' => $social_twitter, 'social_youtube' => $social_youtube, 'session' => $session));
    //
    //     $rows_affected = $req->rowCount();
    //     return $rows_affected;
    // }
    //
    // public static function logged_status($session) {
    //     $db = Db::getInstance();
    //     $req = $db->prepare('SELECT * FROM students WHERE session = :session LIMIT 1');
    //     $req->execute(array('session' => $session));
    //     $user = $req->fetch();
    //
    //     if ($user) {
    //         $data = array(
    //             "sts" => 0,
    //             "data" => new Student($user['id'], $user['email'], $user['session'], $user['title'], $user['description'], $user['profile_pic'],
    //             $user['phone'], $user['phone_alt'], $user['contact_email'], $user['website'], $user['social_facebook'], $user['social_google_plus'], $user['social_twitter'], $user['social_youtube'],
    //             $user['address'], $user['address_2'], $user['city'], $user['state'], $user['pin'], $user['country'], $user['status'])
    //         );
    //     }
    //     else {
    //         $data = array(
    //             "sts" => 1,
    //             "msg" => "access forbidden"
    //         );
    //     }
    //
    //     return $data;
    // }
    //
    // public static function get($id) {
    //     $db = Db::getInstance();
    //     $req = $db->prepare('SELECT * FROM students WHERE id = :id LIMIT 1');
    //     $req->execute(array('id' => $id));
    //     $user = $req->fetch();
    //
    //     if ($user) {
    //         $data = array(
    //             "sts" => 0,
    //             "data" => new Student($user['id'], $user['email'], null, $user['title'], $user['description'], $user['profile_pic'],
    //             $user['phone'], $user['phone_alt'], $user['contact_email'], $user['website'], $user['social_facebook'], $user['social_google_plus'], $user['social_twitter'], $user['social_youtube'],
    //             $user['address'], $user['address_2'], $user['city'], $user['state'], $user['pin'], $user['country'], $user['status'])
    //         );
    //     }
    //     else {
    //         $data = array(
    //             "sts" => 1,
    //             "msg" => "access forbidden"
    //         );
    //     }
    //
    //     return $data;
    // }
}

?>
