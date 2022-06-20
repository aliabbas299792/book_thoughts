<?php
include 'db.link.php';
include 'verify_user.php';

if(!isset($_GET['book_id']) || (!isset($_GET['quote']) && !isset($_GET['comment']) && !isset($_GET['additional_info']))) {
  echo "fail";
  exit();
}

$insert = $db->prepare('INSERT INTO `book_thoughts` (`name`, `book_id`, `quote`, `comment`, `additional_info`, `chapter`, `chapter_section`) (?, ?, ?, ?, ?, ?, ?, ?)');
if($insert->execute([$_GET['quote'], $_GET['book_id'], $_GET['quote'], $_GET['comment'], $_GET['additional_info']], $_GET['chapter'], $_GET['chapter_section'])) {
  echo "pass";
} else {
  echo "fail";
}

?>