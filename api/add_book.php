<?php
include 'db.link.php';
include 'verify_user.php';

if(!isset($_POST['name'])) {
  echo "fail";
  exit();
}

$insert = $db->prepare('INSERT INTO `books` (`name`, `author_id`, `link`) VALUES (?, ?, ?)');
if($insert->execute([urldecode($_POST['name']), urldecode($_POST['author_id']), urldecode($_POST['link'])])) {
  echo "pass";
} else {
  echo "fail";
}

?>