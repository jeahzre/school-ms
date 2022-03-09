<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/init/environment.php');
$conn = new mysqli($_ENV['db-servername'],$_ENV['db-username'],$_ENV['db-password'],$_ENV['db-name']);
$GLOBALS['conn'] = $conn;
// Check connection
if ($conn -> connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();
}
?>