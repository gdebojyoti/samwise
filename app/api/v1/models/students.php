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

    public static function register($email, $password, $confirm_password, $name, $phone, $institute_id, $dob, $street_address, $city, $district, $state, $pin, $country) {
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

        if(strlen($dob) > 0) {
            $error = false;

            if (!preg_match("/[0-9]{2}\/[0-9]{2}\/[0-9]{2}/", $dob)) {
                $error = true;
            } else {
                list($dd,$mm,$yy) = explode('/', $dob);
                if (!checkdate($mm,$dd,$yy)) {
                    $error = true;
                } else {
                    $error = false;
                }
            }

            if(!$error) {
                $data = array(
                    "sts" => 1,
                    "msg" => "invalid date of birth"
                );
                return $data;
            }
        } else {
            $dd = 0;
            $mm = 0;
            $yy = 0;
        }

        $password = md5($password);
        $timestamp = date("mdHis");
        $rand = rand(11111111, 99999999);
        $hash = md5($email);

        $session = $hash . $rand . $timestamp;

        $rand_token = rand(11111111, 99999999);
        $hash_token = md5($email . $rand_token);
        $rand_pswd_rest = rand(11111111, 99999999);

        $token = $hash_token . $timestamp . $rand_token;
        $pswd_reset = $rand_pswd_rest . $timestamp . $rand_token;

        // $country = "India";
        $status = 1;
        $level = 1;

        $db = Db::getInstance();
        $req = $db->prepare('INSERT INTO students (email, password, session, token, pswd_reset, name, phone, institute_id, country, street_address, city, district, state, pin, status, level)
                VALUES (:email, :password, :session, :token, :pswd_reset, :name, :phone, :institute_id, :country, :street_address, :city, :district, :state, :pin, :status, :level)');
        try {
            $req->execute(array('email' => $email, 'password' => $password, 'session' => $session, 'token' => $token, 'pswd_reset' => $pswd_reset, 'name' => $name,
                    'phone' => $phone, 'dob_d' => $dd, 'dob_m' => $mm, 'dob_y' => $yy, 'institute_id' => $institute_id, 'country' => $country, 'street_address' => $street_address,
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

        // TODO: Consider regenerating a token on successful login

        if ($user) {
            $data = array(
                "sts" => 0,
                "data" => new Student($user['id'], $user['email'], $user['name'], $user['street_address'], $user['city'], $user['district'],
                $user['state'], $user['pin'], $user['country'], $user['status'], $user['level'], $user['creation_date'])
            );
            // $data["data"]->token = self::_get_authorized_student($user['token']);
            $data["data"]->token = $user['token'];
        }
        else {
            $data = array(
                "sts" => 1,
                "msg" => "invalid credentials"
            );
        }

        return $data;
    }

    // Check if student is logged in - get student 'id' by searching using 'token'
    private static function _get_authorized_student($token) {
        $db = Db::getInstance();
        $req = $db->prepare('SELECT id FROM students WHERE token = :token LIMIT 1');
        $req->execute(array('token' => $token));
        $user = $req->fetch();

        if ($user) {
            return $user['id'];
        }
        else {
            return 0;
        }
    }
}

?>
