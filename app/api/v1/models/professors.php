<?php

class Professor {
    public $id;
    public $name;

    public function __construct($id, $email, $name, $street_address, $city, $state, $pin, $country, $status) {
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
    }

    public static function search($query) {
        $list = [];
        $db = Db::getInstance();

        $req = $db->prepare('SELECT * FROM professors WHERE name LIKE :name');
        $req->execute(array('name' => "%" . $query['name'] . "%"));

        foreach($req->fetchAll() as $user) {
            $list[] = new Professor($user['id'], $user['email'], $user['name'], $user['street_address'], $user['city'],
            $user['state'], $user['pin'], $user['country'], $user['status']);
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

    public static function register($email, $password, $confirm_password, $institute_id, $name, $phone, $country, $street_address, $city, $state, $pin) {
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

        $rand_token = rand(11111111, 99999999);
        $hash_token = md5($email . $rand_token);
        $rand_pswd_rest = rand(11111111, 99999999);

        $token = $hash_token . $timestamp . $rand_token;
        $pswd_reset = $rand_pswd_rest . $timestamp . $rand_token;

        // $country = "India";
        $status = 1;
        $level = 1;

        $db = Db::getInstance();
        $req = $db->prepare('INSERT INTO professors (email, password, token, pswd_reset, name, phone, institute_id, country, street_address, city, state, pin, status)
                VALUES (:email, :password, :token, :pswd_reset, :name, :phone, :institute_id, :country, :street_address, :city, :state, :pin, :status)');
        try {
            $req->execute(array('email' => $email, 'password' => $password, 'token' => $token, 'pswd_reset' => $pswd_reset, 'name' => $name,
                    'phone' => $phone, 'institute_id' => $institute_id, 'country' => $country, 'street_address' => $street_address,
                    'city' => $city, 'state' => $state, 'pin' => $pin, 'status' => $status));
            $data = array(
                "sts" => 0,
                "msg" => "professor added"
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
        $req = $db->prepare('SELECT * FROM professors WHERE email = :email AND password = :password LIMIT 1');
        $req->execute(array('email' => $email, 'password' => $password));
        $user = $req->fetch();

        // TODO: Consider regenerating a token on successful login

        if ($user) {
            $data = array(
                "sts" => 0,
                "data" => new Professor($user['id'], $user['email'], $user['name'], $user['street_address'], $user['city'],
                $user['state'], $user['pin'], $user['country'], $user['status'])
            );
            // $data["data"]->token = self::_get_authorized_professor($user['token']);
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

    // Check if professor is logged in - get professor 'id' by searching using 'token'
    private static function _get_authorized_professor($token) {
        $db = Db::getInstance();
        $req = $db->prepare('SELECT id FROM professors WHERE token = :token LIMIT 1');
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
