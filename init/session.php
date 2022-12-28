<?php
require_once('connect_db.php');
require_once '../model/Session.php';

use Model\Session;

session_start();

if (!isset($_SESSION['login_email'])) {
  header('location: /view/login.php');
}

$session = new Session();
$userExists = $session->getUserExists();

if (!$userExists) {
  $errorMessage = 'Your email or password is invalid';
  return $errorMessage;
  header();
}
