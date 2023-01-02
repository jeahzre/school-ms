<?php

use SQL\ExecSql;

require_once 'exec_sql.php';
require_once($_SERVER['DOCUMENT_ROOT'] . '/init/connect_db.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/function.php';

class Plan
{
    function getQuery()
    {
        return "SELECT * FROM `plan`";
    }

    function getAddPlanQuery()
    {
        $sql = "INSERT INTO `plan` (`activity_name`, `datetime`) VALUES (?, ?)";

        return $sql;
    }

    function getEditPlanQuery()
    {
        return "UPDATE `plan` 
      SET `activity_name` = ?, 
      `datetime` = ?
      WHERE `id` = ?;";
    }

    function getDeletePlanQuery()
    {
        return "DELETE FROM `plan` WHERE `id` = ?";
    }
}

foreach ($_POST as $key => $value) {
    // Initialize instance of the specified class if $_POST has at least one value

    if (!empty($_POST[$key])) {
        $planQuery = new Plan();
        $sqlObject = new ExecSql();

        break;
    }
}

if (is_post_not_empty('get_plan_list')) {
    $sql = $planQuery->getQuery();
    $result = $sqlObject->execSql($sql, null, null);
}

if (is_post_not_empty($addPlanKeys = array('add_activity_name', 'add_datetime'))) {
    $sql = $planQuery->getAddPlanQuery();
    $paramValues = array($_POST['add_activity_name'], $_POST['add_datetime']);
    $result = $sqlObject->execSql($sql, 'ss', $paramValues);
}

if (is_post_not_empty($editPlanKeys = array('edit_activity_name', 'edit_datetime', 'edit_id'))) {
    $sql = $planQuery->getEditPlanQuery();
    $paramValues = array($_POST['edit_activity_name'], $_POST['edit_datetime'], $_POST['edit_id']);
    $result = $sqlObject->execSql($sql, 'ssi', $paramValues);
}

if (is_post_not_empty(array('delete_id'))) {
    $sql = $planQuery->getDeletePlanQuery();
    $paramValues = array($_POST['delete_id']);
    $result = $sqlObject->execSql($sql, 'i', $paramValues);
}

if (isset($result) && $result) {
    echo json_encode($result);
}
