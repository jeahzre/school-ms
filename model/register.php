<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/init/connect_db.php');
require_once 'function.php';
require_once '../helper/Request.php';

use Helper\Request;

session_start();

if (is_post_not_empty(['email', 'username', 'passwd'])) {

  try {
    $posts = ['email', 'username', 'passwd'];
    $requestObject = new Request($posts, null);
    $requestObject->modifyPostValue();

    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['passwd'];

    $checkHasEmailRecordSql = "SELECT `id` FROM `usr` WHERE `email` = ?";
    $statement = $GLOBALS['conn']->prepare($checkHasEmailRecordSql);
    $dataTypes = 's';
    $paramValues = [$email];
    $statement->bind_param($dataTypes, ...$paramValues);
    $statement->execute();
    $hasEmailRecordResult = $statement->get_result();
    $hasEmailRecord = $hasEmailRecordResult->num_rows === 1;

    if ($hasEmailRecord) {
      throw new Exception('Email has been registered');
    }

    $checkHasUsernameRecordSql = "SELECT `id` FROM `usr` WHERE `username` = ?";
    $statement = $GLOBALS['conn']->prepare($checkHasUsernameRecordSql);
    $dataTypes = 's';
    $paramValues = [$username];
    $statement->bind_param($dataTypes, ...$paramValues);
    $statement->execute();
    $hasUsernameRecordResult = $statement->get_result();
    $hasUsernameRecord = $hasUsernameRecordResult->num_rows === 1;

    if ($hasUsernameRecord) {
      throw new Exception('Username has been registered');
    }

    $insertSql = "INSERT INTO `usr` (email, username, passwd) 
  VALUES (?, ?, ?)";
    $statement = $GLOBALS['conn']->prepare($insertSql);
    $dataTypes = 'sss';
    $paramValues = [$email, $username, $password];
    $statement->bind_param($dataTypes, ...$paramValues);
    $statement->execute();
    $inserted = $statement->affected_rows === 1;

    if ($inserted) {
      $_SESSION['login_email'] = $email;
      header('location: /index.php');
    } else {
      throw new Exception('Something went wrong');
    }
  } catch (Exception $e) {
    $error = $e->getMessage();
    // header('location: /view/register.php');
  }
}
