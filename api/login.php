<?php
include 'db.link.php';

session_start();
if(urldecode($_GET['username']) != $admin_username || urldecode($_GET['password']) != $admin_password) {
  echo "fail";
} else {
  $_SESSION['user']['username'] = $_GET['username'];
  $_SESSION['user']['password'] = $_GET['password'];
  echo "pass";
}
?>