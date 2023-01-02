<?php

use SQL\ExecSql;

require_once 'exec_sql.php';
require_once($_SERVER['DOCUMENT_ROOT'] . '/init/connect_db.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/function.php';

class Exam
{
    function getQuery()
    {
        return "SELECT * FROM `exam`";
    }

    function getAddExamQuery()
    {
        $sql = "INSERT INTO `exam`
        (`name`, `subject_id`, `datetime`) VALUES (?, ?, ?)";

        return $sql;
    }

    function getEditExamQuery()
    {
        return "UPDATE `exam` 
        SET
        `name` = ?,
        `subject_id` = ?,
        `datetime` = ?
        WHERE `id` = ?;";
    }

    function getDeleteExamQuery()
    {
        return "DELETE FROM `exam` WHERE `id` = ?";
    }
}

foreach ($_POST as $key => $value) {
    // Initialize instance of the specified class if $_POST has at least one value

    if (!empty($_POST[$key])) {
        $examQuery = new Exam();
        $sqlObject = new ExecSql();

        break;
    }
}

if (is_post_not_empty($addExamKeys = array('add_name', 'add_subject_id', 'add_datetime'))) {
    $sql = $examQuery->getAddExamQuery();
    $paramValues = [$_POST['add_name'], $_POST['add_subject_id'], $_POST['add_datetime']];
    $result = $sqlObject->execSql($sql, 'sis', $paramValues);
    
    returnGetResult($result, 'Location: /view/exam.php');
}

if (is_post_not_empty('get_exam_list')) {
    $sql = $examQuery->getQuery();
    $result = $sqlObject->execSql($sql, null, null);
}

if (is_post_not_empty($editExamKeys = array('edit_id', 'edit_name', 'edit_subject_id', 'edit_datetime'))) {
    $sql = $examQuery->getEditExamQuery();
    $paramValues = [$_POST['edit_name'], $_POST['edit_subject_id'], $_POST['edit_datetime'], $_POST['edit_id']];
    $result = $sqlObject->execSql($sql, 'sisi', $paramValues);
}

if (is_post_not_empty($deleteExamKeys = array('delete_id'))) {
    $sql = $examQuery->getDeleteExamQuery();
    $paramValues = [$_POST['delete_id']];
    $result = $sqlObject->execSql($sql, 'i', $paramValues);
}

returnResult($result);
