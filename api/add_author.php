<?php
include 'db.link.php';
include 'verify_user.php';

if(!isset($_POST['name'])) {
  echo "fail";
  exit();
}

$insert = $db->prepare('INSERT INTO `authors` (`name`, `link`) VALUES (?, ?)');
if($insert->execute([urldecode($_POST['name']), urldecode($_POST['link'])])) {
  echo "pass";
} else {
  echo "fail";
}

?>