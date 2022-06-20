<?php
include 'db.link.php';

if (!isset($_SESSION)){
  session_start();
}

if(urldecode($_POST['username']) != $admin_username || urldecode($_POST['password']) != $admin_password) {
  echo "fail";
} else {
  $_SESSION['user']['username'] = $_POST['username'];
  $_SESSION['user']['password'] = $_POST['password'];
  echo "pass";
}
?>