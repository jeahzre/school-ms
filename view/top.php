<?php
// require_once($_SERVER['DOCUMENT_ROOT'] . '/init/index.php');
?>
<html>

<head>
  <style>
    <?php
    $path = $_SERVER['DOCUMENT_ROOT'] . "/vendor/scssphp/scssphp/scss.inc.php";
    require_once $path;

    use ScssPhp\ScssPhp\Compiler;

    $compiler = new Compiler();

    echo $compiler->compileString(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/index.scss'))->getCss();

    ?>
  </style>
</head>

<body>
  <?php
  require_once  $_SERVER['DOCUMENT_ROOT'] . '/view/header.php';
  ?>
  <section class="main-middle">
  <?php
  require_once  $_SERVER['DOCUMENT_ROOT'] . '/view/sidebar.php';
  ?>