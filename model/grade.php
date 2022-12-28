<?php
use SQL\ExecSql;

require_once 'exec_sql.php';
require_once($_SERVER['DOCUMENT_ROOT'] . '/init/connect_db.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/function.php';

class Grade
{
  function getGradeListQuery()
  {
    return "SELECT * FROM `grade`";
  }

  function getAddGradeQuery()
  {
    return "INSERT INTO `grade` VALUES (?, ?, ?)";
  }

  function getEditGradeQuery()
  {
    return "UPDATE `grade` 
    SET `grade` = ?,
    `admission_fee` = ?, 
    `hall_charge` = ?
    WHERE `grade` = ?";
  }

  function getDeleteGradeQuery()
  {
    return "DELETE FROM `grade` WHERE `grade` = ?";
  }
}

foreach ($_POST as $key => $value) {
  // Initialize instance of the specified grade if $_POST has at least one value

  if (!empty($_POST[$key])) {
    $gradeQuery = new Grade();
    $sqlObject = new ExecSql();
  }
}

if (is_post_not_empty('get_grade_list')) {
  $sql = $gradeQuery->getGradeListQuery();
  $result = $sqlObject->execSql($sql, null, null);
}

if (is_post_not_empty(array('add_grade', 'add_admission_fee', 'add_hall_charge'))) {
  $sql = $gradeQuery->getAddGradeQuery();
  $paramValues = array($_POST['add_grade'], $_POST['add_admission_fee'], $_POST['add_hall_charge']);
  $result = $sqlObject->execSql($sql, 'idi', $paramValues);
}

if (is_post_not_empty(array('edit_grade', 'edit_admission_fee', 'edit_hall_charge', 'before_edit_grade'))) {
  $sql = $gradeQuery->getEditGradeQuery();
  $paramValues = array($_POST['edit_grade'], $_POST['edit_admission_fee'], $_POST['edit_hall_charge'], $_POST['before_edit_grade']);
  $result = $sqlObject->execSql($sql, 'idii', $paramValues);
}

if (is_post_not_empty(array('delete_grade'))) {
  $sql = $gradeQuery->getDeleteGradeQuery();
  $paramValues = array($_POST['delete_grade']);
  $result = $sqlObject->execSql($sql, 'i', $paramValues);
}

if (isset($result) && $result) {
  echo json_encode($result);
  
}
