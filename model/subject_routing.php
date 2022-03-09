<?php

use SQL\ExecSql;

require_once 'exec_sql.php';
require_once($_SERVER['DOCUMENT_ROOT'] . '/init/connect_db.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/function.php';

class SubjectRouting
{
  function getSubjectRoutingListQuery()
  {
    return "SELECT `grade`, `subject_id`, `subject_name`, `teacher_id`, `teacher`.`name` AS `teacher_name`, `fee`  
    FROM subject_routing
    LEFT JOIN `subject` 
    ON `subject_routing`.`subject_id` = `subject`.`id`
    LEFT JOIN `teacher`
    ON `subject_routing`.`teacher_id` = `teacher`.`user_id`;";
  }

  function getAddSubjectRoutingQuery()
  {
    return "INSERT INTO `subject_routing` VALUES (?, ?, ?, ?)";
  }

  function getEditSubjectRoutingQuery()
  {
    return "UPDATE `subject_routing` 
    SET `grade` = ?,
    `subject_id` = ?, 
    `teacher_id` = ?,
    `fee` = ?
    WHERE `grade` = ? AND subject_id = ?";
  }

  function getDeleteSubjectRoutingQuery()
  {
    return "DELETE FROM `subject_routing` WHERE `grade` = ? AND `subject_id` = ?";
  }

  function getOptions()
  {
    $sqls = array(
      'grade' => "SELECT `grade` FROM `grade`",
      'subject' => "SELECT `id` as `subject_id`, subject_name FROM `subject`",
      'teacher' => "SELECT `user_id` as `teacher_id`, `name` as `teacher_name` FROM `teacher`",
    );
    return $sqls;
  }

  function execOptionSqls($sqls)
  {
    global $sqlObject;

    $gradeResult = $sqlObject->execSql($sqls['grade'], null, null);
    $subjectResult = $sqlObject->execSql($sqls['subject'], null, null);
    $teacherResult = $sqlObject->execSql($sqls['teacher'], null, null);

    if ($gradeResult && $subjectResult && $teacherResult) {
      return array(
        // optionKey => array(db_field => value)
        // optionKey -> element id: 'edit_{optionKey}'
        'grade' => $gradeResult,
        'subject_id' => $subjectResult,
        'teacher_id' => $teacherResult
      );
    }
  }
}

foreach ($_POST as $key => $value) {
  // Initialize instance of the specified grade if $_POST has at least one value

  if (!empty($_POST[$key])) {
    $subjectRoutingQuery = new SubjectRouting();
    $sqlObject = new ExecSql();
  }
}

if (is_post_not_empty('get_subject_routing_list')) {
  $sql = $subjectRoutingQuery->getSubjectRoutingListQuery();
  $result = $sqlObject->execSql($sql, null, null);
}

if (is_post_not_empty(array('add_grade', 'add_subject_id', 'add_teacher_id', 'add_fee'))) {
  $sql = $subjectRoutingQuery->getAddSubjectRoutingQuery();
  $paramValues = array($_POST['add_grade'], $_POST['add_subject_id'], $_POST['add_teacher_id'],  $_POST['add_fee']);
  $result = $sqlObject->execSql($sql, 'iiid', $paramValues);
}

if (is_post_not_empty(array('edit_grade', 'edit_subject_id', 'edit_teacher_id', 'edit_fee', 'before_edit_grade', 'before_edit_subject_id'))) {
  $sql = $subjectRoutingQuery->getEditSubjectRoutingQuery();
  $paramValues = array($_POST['edit_grade'], $_POST['edit_subject_id'], $_POST['edit_teacher_id'],  $_POST['edit_fee'], $_POST['before_edit_grade'], $_POST['before_edit_subject_id']);
  $result = $sqlObject->execSql($sql, 'iiidii', $paramValues);
}

if (is_post_not_empty(array('delete_grade', 'delete_subject_id'))) {
  $sql = $subjectRoutingQuery->getDeleteSubjectRoutingQuery();
  $paramValues = array($_POST['delete_grade'], $_POST['delete_subject_id']);
  $result = $sqlObject->execSql($sql, 'ii', $paramValues);
}

if (is_post_not_empty(array('get_option_list'))) {
  $sqls = $subjectRoutingQuery->getOptions();
  $result = $subjectRoutingQuery->execOptionSqls($sqls);
}

if (isset($result) && $result) {
  echo json_encode($result);
}
