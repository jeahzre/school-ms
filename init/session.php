<?php
require_once('connect_db.php');

session_start();

if (!isset($_SESSION['login_email'])) {
  header('location: /login.php');
}

$login_email = $_SESSION['login_email'];
$sql = "SELECT `username` FROM `usr` WHERE `email` = ?";
$statement = $GLOBALS['conn']->prepare($sql);
$dataTypes = 's';
$paramValues = [$login_email];
$statement->bind_param($dataTypes, ...$paramValues);
$statement->execute();
$result = $statement->get_result();
$resultArr = $result->fetch_assoc();
$username = $resultArr['username'];

// if ($result->num_rows === 1) {
//   $_SESSION['login_email'] = $login_email;
// } else {
//   $errorMessage = 'Your email or password is invalid';
  // return $errorMessage;
//   header()
// }
