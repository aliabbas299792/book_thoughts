<?php
include 'db.link.php';
include 'verify_user.php';

if(!isset($_GET['name'])) {
  echo "fail";
  exit();
}

$insert = $db->prepare('INSERT INTO `books` (`name`, `author_id`, `link`) VALUES (?, ?, ?)');
if($insert->execute([urldecode($_GET['name']), urldecode($_GET['author_id']), urldecode($_GET['link'])])) {
  echo "pass";
} else {
  echo "fail";
}

?>