<?php

$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/Database.php');
include_once($filepath . '/../lib/Session.php');
include_once($filepath . '/../helpers/Format.php');

class User {
    private $db;
    private $fm;

    public function __construct() {
        $this->db = new Database();
        $this->fm = new Format();
    }

    public function userRegistration($name, $userName, $password, $email) {
        $name = mysqli_real_escape_string($this->db->link, $this->fm->validation($name));
        $userName = mysqli_real_escape_string($this->db->link, $this->fm->validation($userName));
        $password = mysqli_real_escape_string($this->db->link, $this->fm->validation($password));
        $email = mysqli_real_escape_string($this->db->link, $this->fm->validation($email));

        if ($name == "" || $userName == "" || $password == "" || $email == "") {
            return "Fields must not be empty.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email address.";
        } else {
            $chkquery = "SELECT * FROM tbl_user WHERE email = '$email'";
            $chkresult = $this->db->select($chkquery);
            if ($chkresult) {
                return "Email already exists. Try a different email.";
            } else {
                $query = "INSERT INTO tbl_user(name, userName, password, email) VALUES('$name','$userName', MD5('$password'),'$email')";
                $insertr = $this->db->insert($query);
                return $insertr ? "Registration successful. Please login." : "Registration unsuccessful.";
            }
        }
    }

    public function userLogin($email, $password) {
        $email = mysqli_real_escape_string($this->db->link, $this->fm->validation($email));
        $password = mysqli_real_escape_string($this->db->link, $this->fm->validation($password));

        if ($email == "" || $password == "") {
            return "Fields must not be empty.";
        } else {
            $query = "SELECT * FROM tbl_user WHERE email = '$email' AND password = MD5('$password')";
            $result = $this->db->select($query);
            if ($result) {
                $value = $result->fetch_assoc();
                if ($value['status'] == '1') {
                    return "User is disabled.";
                } else {
                    Session::init();
                    Session::set("login", true);
                    Session::set("userId", $value['userId']);
                    Session::set("userName", $value['userName']);
                    Session::set("name", $value['name']);
                    return "Login successful.";
                }
            } else {
                return "Invalid email or password.";
            }
        }
    }

    public function updateUserProfile($userId, $data) {
        $name = mysqli_real_escape_string($this->db->link, $this->fm->validation($data['name']));
        $userName = mysqli_real_escape_string($this->db->link, $this->fm->validation($data['userName']));
        $email = mysqli_real_escape_string($this->db->link, $this->fm->validation($data['email']));

        $query = "UPDATE tbl_user SET name = '$name', userName = '$userName', email = '$email' WHERE userId = '$userId'";
        $result = $this->db->update($query);
        return $result ? "Profile updated successfully." : "Profile update failed.";
    }

    public function getUserData() {
        $query = "SELECT * FROM tbl_user ORDER BY userId DESC";
        return $this->db->select($query);
    }

    public function disableUser($userId) {
        $update = "UPDATE tbl_user SET status = '1' WHERE userId = '$userId'";
        return $this->db->update($update) ? "User disabled successfully." : "Failed to disable user.";
    }

    public function enableUser($userId) {
        $update = "UPDATE tbl_user SET status = '0' WHERE userId = '$userId'";
        return $this->db->update($update) ? "User enabled successfully." : "Failed to enable user.";
    }

    public function deleteUser($userId) {
        $delete = "DELETE FROM tbl_user WHERE userId = '$userId'";
        return $this->db->delete($delete) ? "User deleted successfully." : "Failed to delete user.";
    }

    public function getUserProfile($userId) {
        $query = "SELECT * FROM tbl_user WHERE userId = '$userId'";
        return $this->db->select($query);
    }
}
