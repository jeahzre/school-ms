<?php

use SQL\ExecSql;

require_once 'exec_sql.php';
require_once($_SERVER['DOCUMENT_ROOT'] . '/init/connect_db.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/function.php';

$sqlObject = new ExecSql();
class MyProfile
{
  function getQuery()
  {
    return ("SELECT `id`, `email`, `passwd`, `name`, `initial_name`, `gender`, `phone_number`, `registration_date` FROM `usr` 
    INNER JOIN `teacher`
    ON `usr`.`id` = `teacher`.`user_id`
    WHERE `usr`.`id` = ?");
  }

  function getEditQuery()
  {
    // $sql -> array(table name => statement)
    $sql = array(
      'usr' => "UPDATE `usr`
    SET `email` = ?, 
    `passwd` = ?
    WHERE `id` = ?;",
      'teacher' =>
      "UPDATE `teacher` 
    SET `name` = ?, 
    `initial_name` = ?, 
    `gender` = ?, 
    `phone_number` = ? 
    WHERE `user_id` = ?;"
    );
    return $sql;
  }

  function execSqls($sqls, $myProfileInputKeyValues)
  {
    global $sqlObject;

    [
      'id' => $id,
      'email' => $email,
      'passwd' => $passwd,
      'name' => $name,
      'initial_name' => $initial_name,
      'gender' => $gender,
      'phone_number' => $phone_number
    ] = $myProfileInputKeyValues;

    $usrParamValues = array($email, $passwd, $id);
    $result1 = $sqlObject->execSql($sqls['usr'], 'ssi', $usrParamValues);

    $usrOfTypeParamValues = array($name, $initial_name, $gender, $phone_number, $id);
    $result2 = $sqlObject->execSql($sqls['teacher'], 'sssii', $usrOfTypeParamValues);
    if ($result1 && $result2) {
      return true;
    } else {
      return false;
    }
  }
}

foreach ($_POST as $key => $value) {
  if (!empty($_POST[$key])) {
    $myProfile = new MyProfile();
  }
}

if (is_post_not_empty('get_my_profile')) {
  $sql = $myProfile->getQuery();
  $paramValues = array($_POST['user_id']
  );
  $result =  $sqlObject->execSql($sql, 'i', $paramValues);
}

$myProfileFields = array('id', 'email', 'passwd', 'name', 'initial_name', 'gender', 'phone_number');

$formattedEditMyProfileFieldToInputNameMap = formatPostVars('edit_', $myProfileFields);

if (is_post_not_empty(array_values($formattedEditMyProfileFieldToInputNameMap))) {
  // $myProfileInputKeyValue => array('id' => 18)
  $editPostKeyValueObject = setPostKeyValueObjectByKey($formattedEditMyProfileFieldToInputNameMap);
  $sqls = $myProfile->getEditQuery();
  $deformattedEditPostKeyValueObject = deformatKey('edit_', $editPostKeyValueObject);
  $result = $myProfile->execSqls($sqls, $deformattedEditPostKeyValueObject);
}

if (isset($result) && $result) {
  echo json_encode($result);
}
