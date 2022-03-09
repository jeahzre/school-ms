<?php

use SQL\ExecSql;

require_once 'exec_sql.php';
require_once($_SERVER['DOCUMENT_ROOT'] . '/init/connect_db.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/function.php';

class Classroom
{
  function getQuery()
  {
    return "SELECT * FROM `class`";
  }

  function getAddClassQuery()
  {
    return "INSERT INTO `class` 
  VALUES (?, ?)";
  }

  function getEditClassQuery()
  {
    return "UPDATE `class` 
  SET `class_name` = ?,
  `student_count` = ? 
  WHERE `class_name` = ?";
  }

  function getDeleteClassQuery()
  {
    return "DELETE FROM `class` WHERE `class_name` = ?";
  }
}

foreach ($_POST as $key => $value) {
  // Initialize instance of the specified class if $_POST has at least one value

  if (!empty($_POST[$key])) {
    $classroomQuery = new Classroom();
    $sqlObject = new ExecSql();
  }
}

if (is_post_not_empty('get_class_list')) {
  $sql = $classroomQuery->getQuery();
  $result = $sqlObject->execSql($sql, null, null);
}

if (is_post_not_empty(array('add_class_name', 'add_student_count'))) {
  $sql = $classroomQuery->getAddClassQuery();
  $paramValues = array($_POST['add_class_name'], $_POST['add_student_count']);
  $result = $sqlObject->execSql($sql, 'si', $paramValues);
}

if (is_post_not_empty(array('edit_class_name', 'edit_student_count', 'before_edit_class_name'))) {
  $sql = $classroomQuery->getEditClassQuery();
  $paramValues = array($_POST['edit_class_name'], $_POST['edit_student_count'], $_POST['before_edit_class_name']);
  $result = $sqlObject->execSql($sql, 'sis', $paramValues);
}

if (is_post_not_empty(array('delete_class_name'))) {
  $sql = $classroomQuery->getDeleteClassQuery();
  $paramValues = array($_POST['delete_class_name']);
  $result = $sqlObject->execSql($sql, 's', $paramValues);
}

if (isset($result) && $result) {
  echo json_encode($result);
}
