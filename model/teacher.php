<?php

use SQL\ExecSql;

require_once 'exec_sql.php';
require_once($_SERVER['DOCUMENT_ROOT'] . '/init/connect_db.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/function.php';

class Teacher
{
  function getQuery()
  {
    return "SELECT `id`, `email`, `passwd`, `name`, `initial_name`, `gender`, `phone_number`, `registration_date` FROM `usr` 
    INNER JOIN `teacher`
    ON `usr`.`id` = `teacher`.`user_id`;";
  }

  function getAddTeacherQuery()
  {
    $sqls = array(
      'usr' => "INSERT INTO `usr` (`email`, `passwd`) VALUES (?, ?)",
      'user_user_type' => "INSERT INTO `user_user_type` (`user_id`, `user_type_id`) VALUES (?, ?)",
      'teacher' => "INSERT INTO `teacher` (`user_id`, `name`, `initial_name`, `gender`, `phone_number`) VALUES (?, ?, ?, ?, ?)"
    );
    return $sqls;
  }

  function getEditTeacherQuery()
  {
    $sqls = array(
      'usr' => "UPDATE `usr` 
      SET `email` = ?, 
      `passwd` = ?
      WHERE `id` = ?;",
      'teacher' => "UPDATE `teacher` 
      SET `name` = ?,
      `initial_name` = ?,
      `gender` = ?,
      `phone_number` = ?
      WHERE `user_id` = ?;"
    );
    return $sqls;
  }

  function getDeleteTeacherQuery()
  {
    return "DELETE FROM `teacher` WHERE `user_id` = ?";
  }

  function execAddSqls($sqls, $inputKeyValues)
  {
    global $sqlObject;

    [
      'add_name' => $name,
      'add_initial_name' => $initial_name,
      'add_gender' => $gender,
      'add_phone_number' => $phone_number,
      'add_email' => $email, 
      'add_passwd' => $password
    ] = $inputKeyValues;

    $usrParamValues = array(
      $email,
      $password,
    );
    $usrResult = $sqlObject->execSql($sqls['usr'], 'ss', $usrParamValues, true);

    $userUserTypeParamValues = array(
      $usrResult['insert_id'], 
      2
    );
    $userUserTypeResult = $sqlObject->execSql($sqls['user_user_type'], 'ii', $userUserTypeParamValues);
  
    $teacherParamValues = array(
      $usrResult['insert_id'],
      $name, 
      $initial_name, 
      $gender, 
      $phone_number
    );
    $teacherResult = $sqlObject->execSql($sqls['teacher'], 'isssi', $teacherParamValues);

    if ($usrResult && $userUserTypeResult && $teacherResult) {
      // return an array of insert_id object
      return $usrResult;
    } else {
      return false;
    }
  }

  function execEditSqls($sqls, $inputKeyValues) {
    global $sqlObject;

    [
      'edit_id' => $id,
      'edit_name' => $name,
      'edit_initial_name' => $initial_name,
      'edit_gender' => $gender,
      'edit_phone_number' => $phone_number,
      'edit_email' => $email, 
      'edit_passwd' => $password
    ] = $inputKeyValues;

    $usrParamValues = array(
      $email,
      $password, 
      $id
    );
    $usrResult = $sqlObject->execSql($sqls['usr'], 'ssi', $usrParamValues);
  
    $teacherParamValues = array(
      $name, 
      $initial_name, 
      $gender, 
      $phone_number, 
      $id
    );
    $teacherResult = $sqlObject->execSql($sqls['teacher'], 'sssii', $teacherParamValues);

    if ($usrResult && $teacherResult) {
      return true;
    } else {
      return false;
    }
  }
}

foreach ($_POST as $key => $value) {
  // Initialize instance of the specified subject if $_POST has at least one value

  if (!empty($_POST[$key])) {
    $teacherQuery = new Teacher();
    $sqlObject = new ExecSql();
  }
}

if (is_post_not_empty('get_teacher_list')) {
  $sql = $teacherQuery->getQuery();
  $result = $sqlObject->execSql($sql, null, null);
}

if (is_post_not_empty($addTeacherKeys = array('add_name', 'add_initial_name', 'add_gender', 'add_phone_number', 'add_email', 'add_passwd'))) {
  $sqls = $teacherQuery->getAddTeacherQuery();
  $addPostKeyValueObject = setPostKeyValueObjectByKey($addTeacherKeys);
  $result = $teacherQuery->execAddSqls($sqls, $addPostKeyValueObject);
}

if (is_post_not_empty($editTeacherKeys = array('edit_name', 'edit_initial_name', 'edit_gender', 'edit_phone_number', 'edit_id', 'edit_email', 'edit_passwd'))) {
  $sqls = $teacherQuery->getEditTeacherQuery();
  $editPostKeyValueObject = setPostKeyValueObjectByKey($editTeacherKeys);
  $result = $teacherQuery->execEditSqls($sqls, $editPostKeyValueObject);
}

if (is_post_not_empty(array('delete_id'))) {
  $sql = $teacherQuery->getDeleteTeacherQuery();
  $paramValues = array($_POST['delete_id']);
  $result = $sqlObject->execSql($sql, 'i', $paramValues);
}

if (isset($result) && $result) {
  echo json_encode($result);
}
