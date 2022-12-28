<?php
session_start();

use SQL\ExecSql;

require '../model/Session.php';
use Model\Session;

require_once 'exec_sql.php';
require_once($_SERVER['DOCUMENT_ROOT'] . '/init/connect_db.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/function.php';

class Profile
{
    function getUserPictureRowExistsQuery() {
        return "SELECT `user_id` FROM usr_profile_picture WHERE `user_id` = ?";
    }

    function getUpdatePictureQuery() {
        return "UPDATE `usr_profile_picture` SET `profile_picture` = ? WHERE `user_id` = ?";
    }

    function getInsertPictureQuery() {
        return "INSERT INTO `usr_profile_picture` (`user_id`, `profile_picture`)
        VALUES (?, ?)";
    }
}

$post_not_empty = false;

foreach ($_POST as $key => $value) {
    // Initialize instance of the specified class if $_POST has at least one value
    if (!empty($_POST[$key])) {
        $profileQuery = new Profile();
        $sqlObject = new ExecSql();

        $post_not_empty = true;
        break;
    }
}

if (!$post_not_empty) {
    foreach ($_FILES as $key => $value) {
        // Initialize instance of the specified class if $_FILES has at least one value
        if (!empty($_FILES[$key])) {
            $profileQuery = new Profile();
            $sqlObject = new ExecSql();
    
            break;
        }
    }
}

if (are_files_not_empty('set_profile_picture')) {
    $tmpFilePath = $_FILES['set_profile_picture']['tmp_name'];
    $fileName = $_FILES['set_profile_picture']['name'];
    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
    $uploadTargetDir = '/upload/';
    $bytes = random_bytes(20);
    $fileID = bin2hex($bytes);
    $uniqueFileName = $fileID . '.' . $ext;
    $uploadTargetPath = $_SERVER['DOCUMENT_ROOT'] . $uploadTargetDir . $uniqueFileName;

    if (file_exists($uploadTargetPath)) {
        echo "Sorry, file already exists.";
    } else {
        move_uploaded_file($tmpFilePath, $uploadTargetPath);
        echo "Congratulations! File Uploaded Successfully.";
    }
    
    $sql = $profileQuery->getUserPictureRowExistsQuery();
    $session = new Session();
    $user_id = $session->getUserID();
    $paramValues = array($user_id);
    $userPictureRowExists = $sqlObject->execSql($sql, 'i', $paramValues, false, true);

    if($userPictureRowExists) {
        $sql = $profileQuery->getUpdatePictureQuery();
        $paramValues = array($uniqueFileName, $user_id);
        $result = $sqlObject->execSql($sql, 'si', $paramValues);
    } else {
        $sql = $profileQuery->getInsertPictureQuery();
        $paramValues = array($user_id, $uniqueFileName);
        $result = $sqlObject->execSql($sql, 'is', $paramValues);
    }
}

if (isset($result) && $result) {
    echo json_encode($result);
    header('Location: /view/my_profile.php');
    exit();
}
?>
