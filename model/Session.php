<?php
namespace Model;

require_once '../model/Config.php';

use Model\Config;

class Session {
    public function getStatement($sql) {
        $statement = $GLOBALS['conn']->prepare($sql);

        return $statement;
    }

    public function executeQuery($sql, $dataTypes, $paramValues) {
        $statement = $this->getStatement($sql);
        $statement->bind_param($dataTypes, ...$paramValues);
        $statement->execute();
        $result = $statement->get_result();

        return $result;
    }

    public function getUserExists() {
        $login_email = $_SESSION['login_email'];
        $sql = "SELECT `id` FROM `usr` WHERE `email` = ?";
        $dataTypes = 's';
        $paramValues = [$login_email];
        $result = $this->executeQuery($sql, $dataTypes, $paramValues);

        return (boolean)($result->num_rows === 1);
    }

    public function getUserID() {
        $login_email = $_SESSION['login_email'];
        $sql = "SELECT `id` FROM `usr` WHERE `email` = ?";
        $dataTypes = 's';
        $paramValues = [$login_email];
        $result = $this->executeQuery($sql, $dataTypes, $paramValues);
        $resultArr = $result->fetch_assoc();
        $user_id = $resultArr['id'];

        return $user_id;
    }

    public function getUserType($user_id) {
        $sql = "SELECT `user_type_id` FROM `user_user_type` WHERE `user_id` = ?";
        $dataTypes = 's';
        $paramValues = [$user_id];
        $result = $this->executeQuery($sql, $dataTypes, $paramValues);
        $resultArr = $result->fetch_assoc();
        $user_type_id = $resultArr['user_type_id'];
        $config = new Config();
        $userTypeIDToUserTypeMap = $config->getUserTypeIDToUserTypeMap();
        $userType = $userTypeIDToUserTypeMap[$user_type_id];
        
        return $userType;
    }

    public function getUsername() {
        $user_id = $this->getUserID();
        $user_type = $this->getUserType($user_id);
        $sql = "SELECT `name` FROM {$user_type} WHERE `user_id` = ?";
        $dataTypes = 'i';
        $paramValues = [$user_id];
        $result = $this->executeQuery($sql, $dataTypes, $paramValues);
        $resultArr = $result->fetch_assoc();

        return $resultArr['name'];
    }

    public function getUserCurrentEmail() {
        return $_SESSION['login_email'];
    }

    public function setUserCurrentEmail($email) {
        $_SESSION['login_email'] = $email;
    }
}
?>
