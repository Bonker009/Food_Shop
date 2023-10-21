<?php
class App
{
    public $host = HOST;
    public $dbname = DBNAME;
    public $user = USER;
    public $pass = PASS;
    public $link;


    public function __construct()
    {
        $this->connect();
    }
    public function connect()
    {
        $this->link = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->dbname . "", $this->user, $this->pass);
        if ($this->link) {
            echo "Connected";
        }
    }
    public function selectAll($query)
    {
        $rows = $this->link->query($query);
        $rows->execute();
        $allRows = $rows->fetchAll(PDO::FETCH_OBJ);
        if ($allRows) {
            return $allRows;
        } else {
            return false;
        }
    }
    public function selectOne($query)
    {
        $rows = $this->link->query($query);
        $rows->execute();
        $allRows = $rows->fetch(PDO::FETCH_OBJ);
        if ($allRows) {
            return $allRows;
        } else {
            return false;
        }
    }
    public function insert($query, $arr, $path)
    {
        if ($this->validate($arr) == 'emtry') {
            echo "<script>alert('One or more input are empty');</script>";
        } else {
            $insert_record = $this->link->prepare($query);
            $insert_record->execute($arr);
            header("loaction:" . $path . "");
        }

    }
    public function update($query, $arr, $path)
    {
        if ($this->validate($arr) == 'emtry') {
            echo "<script>alert('One or more input are empty');</script>";
        } else {
            $update_record = $this->link->prepare($query);
            $update_record->execute($arr);
            header("loaction:" . $path . "");
        }

    }
    public function delete($query, $path)
    {
        $update_record = $this->link->prepare($query);
        $update_record->execute();
        header("loaction:" . $path . "");
    }
    public function validate($arr)
    {
        if (in_array("", $arr)) {
            echo "empty";
        }
    }
    public function register($query, $arr, $path)
    {
        if ($this->validate($arr) == "empty") {
            echo "<script>alert('One or more input are empty');</script>";
        } else {
            $register_user = $this->link->prepare($query);
            $register_user->execute($arr);
            header("location:" . $path . "");
        }
    }
    public function login($query, $data, $path)
    {
        $login_user = $this->link->query($query);
        $login_user->execute($data);

        $fetch = $login_user->fetch(PDO::FETCH_OBJ);
        if ($login_user->rowCount() > 0) {
            if (password_verify($data["password"], $fetch->password)) {
                header("location:" . $path . "");
            }
        }
    }
    public function startingSession()
    {
        session_start();
    }
    public function validateSession($path)
    {

        if (!isset($_SESSION["user_id"])) {
            header("location: ".$path."");
        }
    }
}
