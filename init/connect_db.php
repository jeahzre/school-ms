<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/init/environment.php');
$connection = new mysqli($_ENV['db-servername'],$_ENV['db-username'],$_ENV['db-password'],$_ENV['db-name']);
$GLOBALS['conn'] = $connection;
// Check connection
if ($connection -> connect_errno) {
  echo "Failed to connect to MySQL: " . $connection -> connect_error;
  exit();
}
?>