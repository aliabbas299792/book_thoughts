<?php
include 'db.link.php';

if($_SESSION['user']['username'] != $admin_username || $_SESSION['user']['password'] != $admin_password) {
  echo 'fail';
} else {
  echo 'pass';
}
?>