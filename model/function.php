<?php
function is_post_not_empty($variable)
{
  if (is_array($variable)) {
    foreach ($variable as $var) {
      if (!isset($_POST[$var]) || !$_POST[$var]) {
        return false;
      }
    }
  } else {
    if (!isset($_POST[$variable]) || !$_POST[$variable]) {
      return false;
    }
  }

  return true;
}

function are_files_not_empty($variable)
{
  if (is_array($variable)) {
    foreach ($variable as $var) {
      if (!isset($_FILES[$var]) || !$_FILES[$var]) {
        return false;
      }
    }
  } else {
    if (!isset($_FILES[$variable]) || !$_FILES[$variable]) {
      return false;
    }
  }

  return true;
}


function setPostKeyValueObjectByKey($keys) {
  $keyValue = array();
  foreach ($keys as $key) {
    $keyValue[$key] = $_POST[$key];
  }
  return $keyValue;
}

function formatPostVars($format, $vars)
{
  $formattedPostVars = array();
  foreach ($vars as $var) {
    $formattedPostVars[$var] = "{$format}{$var}";
  }
  return $formattedPostVars;
}

function deformatKey($format, $keyValueObject) {
  $deformattedKeyValueObject = array();
  foreach ($keyValueObject as $key => $value) {
    $deformattedKey = str_replace($format, '', $key);
    $deformattedKeyValueObject[$deformattedKey] = $value;
  }
  return $deformattedKeyValueObject;
}