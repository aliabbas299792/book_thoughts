<?php
if (!isset($_SESSION)){
  session_start();
}

$_SESSION['user']['username'] = '';
$_SESSION['user']['password'] = '';
$_SESSION['user'] = '';

session_destroy();
?>