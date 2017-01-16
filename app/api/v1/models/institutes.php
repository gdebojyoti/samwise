<?php

class Institute {
    public $id;
    public $name;

    public function __construct($id, $name, $type, $phone, $email, $website, $street_address, $city, $district, $state, $pin, $country, $status, $creation_date, $created_by) {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->phone = $phone;
        $this->email = $email;
        $this->website = $website;
        $this->street_address = $street_address;
        $this->city = $city;
        $this->district = $district;
        $this->state = $state;
        $this->pin = $pin;
        $this->country = $country;
        $this->status = $status;
        $this->creation_date = $creation_date;
        $this->created_by = $created_by;
    }

    public static function search($query) {
        $list = [];
        $db = Db::getInstance();

        // get matching institutes if "name" is available
        if(strlen($query['name']) > 0) {
            $req = $db->prepare('SELECT * FROM institutes WHERE name LIKE :name');
        }
        // get list of all institutes if no "name" is supplied
        else {
            $req = $db->prepare('SELECT * FROM institutes');
        }
        
        $req->execute(array('name' => "%" . $query['name'] . "%"));

        foreach($req->fetchAll() as $inst) {
            $list[] = new Institute($inst['id'], $inst['name'], $inst['type'], $inst['phone'], $inst['email'], $inst['website'],
                    $inst['street_address'], $inst['city'], $inst['district'], $inst['state'], $inst['pin'], $inst['country'], $inst['status'], $inst['creation_date'], $inst['created_by']);
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

    public static function register($name, $type, $phone, $email, $website, $street_address, $city, $district, $state, $pin, $country, $created_by) {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $data = array(
                "sts" => 1,
                "msg" => "invalid email"
            );
            return $data;
        }

        $status = 1;

        $db = Db::getInstance();
        $req = $db->prepare('INSERT INTO institutes (name, type, phone, email, website, street_address, city, district, state, pin, country, status, created_by)
                VALUES (:name, :type, :phone, :email, :website, :street_address, :city, :district, :state, :pin, :country, :status, :created_by)');
        try {
            $req->execute(array('name' => $name, 'type' => $type, 'phone' => $phone, 'email' => $email, 'website' => $website,
                    'street_address' => $street_address, 'city' => $city, 'district' => $district, 'state' => $state, 'pin' => $pin,
                    'country' => $country, 'status' => $status, 'created_by' => $created_by));
            $data = array(
                "sts" => 0,
                "msg" => "institute added"
            );
        }
        catch (PDOException $e) {
            $err = $e->errorInfo[1];

            $data = array(
                "sts" => 1,
                "msg" => "unknown error: " . $err
            );
        }

        return $data;
    }
}

?>
