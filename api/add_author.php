<?php
include 'db.link.php';
include 'verify_user.php';

if(!isset($_GET['name'])) {
  echo "fail";
  exit();
}

$insert = $db->prepare('INSERT INTO `authors` (`name`, `link`) (?, ?)');
if($insert->execute([$_GET['name'], $_GET['link']])) {
  echo "pass";
} else {
  echo "fail";
}

?>