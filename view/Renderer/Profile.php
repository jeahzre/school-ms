<?php
namespace Renderer;

require_once '../model/exec_sql.php';
use SQL\ExecSql;

require_once('../model/Session.php');
use Model\Session;

class Profile
{
  function __construct() {
    $session = new Session();
    $sqlObject = new ExecSql();
    $this->session = $session;
    $this->sqlObject = $sqlObject;
  }

  function getProfilePictureQuery()
  {
    return "SELECT `profile_picture` FROM usr_profile_picture WHERE `user_id` = ?";
  }

  function getUserTypeIDByUserIDQuery()
  {
    return "SELECT `user_type_id` FROM user_user_type WHERE `user_id` = ?";
  }

  function getUserTypeByUserTypeID()
  {
    return "SELECT `user_type` FROM user_type WHERE `id` = ?";
  }

  function getProfilePicturePath()
  {
    $sql = $this->getProfilePictureQuery();
    $user_id = $this->session->getUserID();
    $paramValues = array($user_id);
    $fileResult = $this->sqlObject->execSql($sql, 'i', $paramValues);
    $profilePictureFile = $fileResult[0]['profile_picture'];
    $profilePictureFolder = '/upload/';
    $profilePicturePath = $profilePictureFolder . $profilePictureFile;

    return $profilePicturePath;
  }

  function getUserPosition()
  {
    $userID = $this->session->getUserID();
    $sql = $this->getUserTypeIDByUserIDQuery();
    $paramValues = [$userID];
    $userTypeID = $this->sqlObject->execSql($sql, 'i', $paramValues);
    $sql = $this->getUserTypeByUserTypeID();
    $paramValues = [$userTypeID];
    $userType = $this->sqlObject->execSql($sql, 'i', $paramValues);

    return $userType[0]['user_type'];
  }
}
?>
