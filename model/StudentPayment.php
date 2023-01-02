<?php

use SQL\ExecSql;

require_once 'exec_sql.php';
require_once($_SERVER['DOCUMENT_ROOT'] . '/init/connect_db.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/function.php';

class StudentPayment
{
    function getQuery()
    {
        return "SELECT * FROM `student_payment`";
    }

    function getAddStudentPaymentQuery()
    {
        $sql = "INSERT INTO `student_payment`
        (`user_id`, `paid`, `paid_date`) VALUES (?, ?, ?)";

        return $sql;
    }

    function getEditStudentPaymentQuery()
    {
        return "UPDATE `student_payment` 
        SET
        `paid` = ?,
        `paid_date` = ?
        WHERE `user_id` = ?;";
    }

    function getDeleteStudentPaymentQuery()
    {
        return "DELETE FROM `student_payment` WHERE `user_id` = ?";
    }
}

foreach ($_POST as $key => $value) {
    // Initialize instance of the specified class if $_POST has at least one value

    if (!empty($_POST[$key])) {
        $student_paymentQuery = new StudentPayment();
        $sqlObject = new ExecSql();

        break;
    }
}

if (is_post_not_empty('get_student_payment_list')) {
    $sql = $student_paymentQuery->getQuery();
    $result = $sqlObject->execSql($sql, null, null);
}

if (is_post_not_empty($addStudentPaymentKeys = array('add_user_id', 'add_paid', 'add_paid_date'))) {
    $sql = $student_paymentQuery->getAddStudentPaymentQuery();
    $paramValues = [$_POST['add_user_id'], $_POST['add_paid'], $_POST['add_paid_date']];
    $result = $sqlObject->execSql($sql, 'iis', $paramValues);
}

if (is_post_not_empty($editStudentPaymentKeys = array('edit_paid', 'edit_paid_date', 'edit_user_id'))) {
    $sql = $student_paymentQuery->getEditStudentPaymentQuery();
    $paramValues = [$_POST['edit_user_id'], $_POST['edit_paid'], $_POST['edit_paid_date']];
    $result = $sqlObject->execSql($sql, 'isi', $editPostKeyValueObject);
}

if (is_post_not_empty($deleteStudentPaymentKeys = array('delete_user_id'))) {
    $sql = $student_paymentQuery->getDeleteStudentPaymentQuery();
    $paramValues = [$_POST['delete_user_id']];
    $result = $sqlObject->execSql($sql, 'i', $paramValues);
}

if (isset($result) && $result) {
    echo json_encode($result);
}
