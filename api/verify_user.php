<?php
include 'db.link.php';

if (!isset($_SESSION)){
  session_start();
}

if($_SESSION['user']['username'] != $admin_username || $_SESSION['user']['password'] != $admin_password) {
  echo 'fail';
  exit();
}
?>