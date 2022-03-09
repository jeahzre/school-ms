<?php

use SQL\ExecSql;

require_once 'exec_sql.php';
require_once($_SERVER['DOCUMENT_ROOT'] . '/init/connect_db.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/function.php';

class Subject
{
  function getQuery()
  {
    return "SELECT * FROM `subject`";
  }

  function getAddSubjectQuery()
  {
    return "INSERT INTO `subject` (`subject_name`) VALUES (?)";
  }

  function getEditSubjectQuery()
  {
    return "UPDATE `subject` 
    SET `subject_name` = ? 
    WHERE `id` = ?";
  }

  function getDeleteSubjectQuery()
  {
    return "DELETE FROM `subject` WHERE `id` = ?";
  }
}

foreach ($_POST as $key => $value) {
  // Initialize instance of the specified subject if $_POST has at least one value

  if (!empty($_POST[$key])) {
    $subjectQuery = new Subject();
    $sqlObject = new ExecSql();
  }
}

if (is_post_not_empty('get_subject_list')) {
  $sql = $subjectQuery->getQuery();
  $result = $sqlObject->execSql($sql, null, null);
}

if (is_post_not_empty(array('add_subject_name'))) {
  $sql = $subjectQuery->getAddSubjectQuery();
  $paramValues = array($_POST['add_subject_name']);
  $result = $sqlObject->execSql($sql, 's', $paramValues, true);
}

if (is_post_not_empty(array('edit_subject_name', 'before_edit_id'))) {
  $sql = $subjectQuery->getEditSubjectQuery();
  $paramValues = array($_POST['edit_subject_name'], $_POST['before_edit_id']);
  $result = $sqlObject->execSql($sql, 'si', $paramValues);
}

if (is_post_not_empty(array('delete_id'))) {
  $sql = $subjectQuery->getDeleteSubjectQuery();
  $paramValues = array($_POST['delete_id']);
  $result = $sqlObject->execSql($sql, 'i', $paramValues);
}

if (isset($result) && $result) {
  echo json_encode($result);
}
