<?php

use SQL\ExecSql;

require_once 'exec_sql.php';
require_once($_SERVER['DOCUMENT_ROOT'] . '/init/connect_db.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/function.php';

class Student
{
  function getQuery()
  {
    return "SELECT * FROM `usr` 
    INNER JOIN `student`
    ON `usr`.`id` = `student`.`user_id`;";
  }

  function getAddStudentQuery()
  {
    $sqls = array(
      'usr' => "INSERT INTO `usr` (`email`, `passwd`) VALUES (?, ?)",
      'user_user_type' => "INSERT INTO `user_user_type` (`user_id`, `user_type_id`) VALUES (?, ?)",
      'student' => "INSERT INTO `student`
      (`user_id`, `name`, `initial_name`, `gender`, `phone_number`, `guardian_name`, `guardian_phone`, `guardian_email`)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );
    
    return $sqls;
  }

  function getEditStudentQuery()
  {
    $sqls = array(
      'usr' => "UPDATE `usr` 
      SET `email` = ?, 
      `passwd` = ?
      WHERE `id` = ?;",
      'student' => "UPDATE `student` 
      SET `name` = ?,
      `initial_name` = ?,
      `gender` = ?,
      `phone_number` = ?,
      `guardian_name` = ?,
      `guardian_phone` = ?,
      `guardian_email` = ?
      WHERE `user_id` = ?;"
    );
    return $sqls;
  }

  function getDeleteStudentQuery()
  {
    return "DELETE FROM `student` WHERE `user_id` = ?";
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
      'add_passwd' => $password,
      'add_guardian_name' => $add_guardian_name,
      'add_guardian_phone' => $add_guardian_phone,
      'add_guardian_email' => $add_guardian_email
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
  
    $studentParamValues = array(
      $usrResult['insert_id'],
      $name, 
      $initial_name, 
      $gender, 
      $phone_number,
      $add_guardian_name,
      $add_guardian_phone,
      $add_guardian_email
    );
    $studentResult = $sqlObject->execSql($sqls['student'], 'isssisis', $studentParamValues);

    if ($usrResult && $userUserTypeResult && $studentResult) {
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
      'edit_passwd' => $password,
      'edit_guardian_name' => $guardian_name,
      'edit_guardian_phone' => $guardian_phone,
      'edit_guardian_email' => $guardian_email,
    ] = $inputKeyValues;

    $usrParamValues = array(
      $email,
      $password, 
      $id
    );
    $usrResult = $sqlObject->execSql($sqls['usr'], 'ssi', $usrParamValues);
  
    $studentParamValues = array(
      $name, 
      $initial_name, 
      $gender, 
      $phone_number,
      $guardian_name,
      $guardian_phone,
      $guardian_email,
      $id
    );
    $studentResult = $sqlObject->execSql($sqls['student'], 'sssisisi', $studentParamValues);

    if ($usrResult && $studentResult) {
      return true;
    } else {
      return false;
    }
  }
}

foreach ($_POST as $key => $value) {
  // Initialize instance of the specified subject if $_POST has at least one value

  if (!empty($_POST[$key])) {
    $studentQuery = new Student();
    $sqlObject = new ExecSql();
  }
}

if (is_post_not_empty('get_student_list')) {
  $sql = $studentQuery->getQuery();
  $result = $sqlObject->execSql($sql, null, null);
}

if (is_post_not_empty($addStudentKeys = array('add_name', 'add_initial_name', 'add_gender', 'add_phone_number', 'add_email', 'add_passwd', 'add_guardian_name', 'add_guardian_phone', 'add_guardian_email'))) {
  $sqls = $studentQuery->getAddStudentQuery();
  $addPostKeyValueObject = setPostKeyValueObjectByKey($addStudentKeys);
  $result = $studentQuery->execAddSqls($sqls, $addPostKeyValueObject);
}

if (is_post_not_empty($editStudentKeys = array('edit_name', 'edit_initial_name', 'edit_gender', 'edit_phone_number', 'edit_id', 'edit_email', 'edit_passwd', 'edit_guardian_name', 'edit_guardian_phone', 'edit_guardian_email'))) {
  $sqls = $studentQuery->getEditStudentQuery();
  $editPostKeyValueObject = setPostKeyValueObjectByKey($editStudentKeys);
  $result = $studentQuery->execEditSqls($sqls, $editPostKeyValueObject);
}

if (is_post_not_empty(array('delete_id'))) {
  $sql = $studentQuery->getDeleteStudentQuery();
  $paramValues = array($_POST['delete_id']);
  $result = $sqlObject->execSql($sql, 'i', $paramValues);
}

if (isset($result) && $result) {
  echo json_encode($result);
}
