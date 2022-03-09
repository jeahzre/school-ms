<?php

namespace SQL;

class ExecSql
{
  function execSql($sql, $dataTypes, $paramValues, $autoFillField = false)
  {
    // autoFillField -> database fill/doesn't fill the field automatically
    // return boolean if sql statement is 'UPDATE', INSERT INTO', and 'DELETE' statement without auto field.
    // return rows if sql statement is 'SELECT'
    if ($dataTypes && $paramValues) {
      // bind param and execute for usr and teacher/student/admin table
      $statement = $GLOBALS['conn']->prepare($sql);
      // $paramValues -> In order
      $statement->bind_param($dataTypes, ...$paramValues);
      $statement->execute();
      if (!$autoFillField) {
        $result = $statement->get_result(); // object 
        if (is_object($result) && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
          }
  
          if (isset($rows)) {
            return $rows;
          }
        } else {
          $result = !($statement->error);
          if ($result) {
            return true;
          } else {
            return false;
          }
        }
      } else {
        if (!$statement->error) {
          $insertId = $statement->insert_id;
          $result = array(
            'insert_id' => $insertId
          );
          return $result;
        } else {
          return false;
        }
      }
    } else {
      $result = $GLOBALS['conn']->query($sql);

      if (is_object($result) && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          $rows[] = $row;
        }

        if (isset($rows)) {
          return $rows;
        }
      } else {
        if ($result) {
          return true;
        } else {
          return false;
        }
      }
    }
  }
}
