<?php
include 'db.link.php';
include 'verify_user.php';

if(!isset($_GET['name'])) {
  echo "fail";
  exit();
}

$insert = $db->prepare('INSERT INTO `books` (`name`, `author_id`, `link`) (?, ?, ?)');
if($insert->execute([$_GET['name'], $_GET['author_id'], $_GET['link']])) {
  echo "pass";
} else {
  echo "fail";
}

?>