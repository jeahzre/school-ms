<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/init/connect_db.php');
require_once 'middleware.php';
require_once 'function.php';

use Helper\Request;

session_start();

if (is_post_not_empty(['email', 'passwd'])) {
  $posts = ['email', 'passwd'];
  $requestObject = new Request($posts, null);
  $requestObject->modifyPostValue();

  $email = $_POST['email'];
  $password = $_POST['passwd'];

  $sql = "SELECT `id` FROM `usr` WHERE 
  `email` = ? AND `passwd` = ?";
  $statement = $GLOBALS['conn']->prepare($sql);
  $dataTypes = 'ss';
  $paramValues = [$email, $password];
  $statement->bind_param($dataTypes, ...$paramValues);
  $statement->execute();
  $result = $statement->get_result();

  if ($result->num_rows === 1) {
    $_SESSION['login_email'] = $email;
    header('location: /index.php');
  } else {
    $errorMessage = 'Your email or password is invalid';
    $_SESSION['message'] = $errorMessage;
    header('location: /view/login.php');
  }
}
