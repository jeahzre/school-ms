<?php

use SQL\ExecSql;

require_once 'exec_sql.php';
require_once($_SERVER['DOCUMENT_ROOT'] . '/init/connect_db.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/function.php';

class StudentAttendance
{
    function getQuery()
    {
        return "SELECT * FROM `student_attendance`";
    }

    function getAddStudentAttendanceQuery()
    {
        $sql = "INSERT INTO `student_attendance`
        (`user_id`, `date`) VALUES (?, ?)";

        return $sql;
    }

    function getEditStudentAttendanceQuery()
    {
        return "UPDATE `student_attendance` 
        SET
        `date` = ?
        WHERE `user_id` = ?;";
    }

    function getDeleteStudentAttendanceQuery()
    {
        return "DELETE FROM `student_attendance` WHERE `user_id` = ?";
    }
}

foreach ($_POST as $key => $value) {
    // Initialize instance of the specified class if $_POST has at least one value

    if (!empty($_POST[$key])) {
        $student_attendanceQuery = new StudentAttendance();
        $sqlObject = new ExecSql();

        break;
    }
}

if (is_post_not_empty('get_student_attendance_list')) {
    $sql = $student_attendanceQuery->getQuery();
    $result = $sqlObject->execSql($sql, null, null);
}

if (is_post_not_empty($addStudentAttendanceKeys = array('add_user_id', 'add_date'))) {
    $sql = $student_attendanceQuery->getAddStudentAttendanceQuery();
    $paramValues = [$_POST['add_user_id'], $_POST['add_date']];
    $result = $sqlObject->execSql($sql, 'is', $paramValues);
}

if (is_post_not_empty($editStudentAttendanceKeys = array('edit_date', 'edit_user_id'))) {
    $sql = $student_attendanceQuery->getEditStudentAttendanceQuery();
    $paramValues = [$_POST['edit_date'], $_POST['edit_user_id']];
    $result = $sqlObject->execSql($sql, 'si', $editPostKeyValueObject);
}

if (is_post_not_empty($deleteStudentAttendanceKeys = array('delete_user_id'))) {
    $sql = $student_attendanceQuery->getDeleteStudentAttendanceQuery();
    $paramValues = [$_POST['delete_user_id']];
    $result = $sqlObject->execSql($sql, 'i', $paramValues);
}

if (isset($result) && $result) {
    echo json_encode($result);
}
