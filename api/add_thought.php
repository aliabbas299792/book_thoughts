<?php
include 'db.link.php';
include 'verify_user.php';

if(!isset($_POST['book_id']) || (!isset($_POST['quote']) && !isset($_POST['comment']) && !isset($_POST['additional_info']))) {
  echo "fail";
  exit();
}

$insert = $db->prepare('INSERT INTO `book_thoughts` (`book_id`, `quote`, `comment`, `additional_info`, `chapter`, `chapter_section`) VALUES (?, ?, ?, ?, ?, ?)');
if($insert->execute([urldecode($_POST['book_id']), urldecode($_POST['quote']), urldecode($_POST['comment']), urldecode($_POST['additional_info']), urldecode($_POST['chapter']), urldecode($_POST['chapter_section'])])) {
  echo "pass";
} else {
  echo "fail";
}

?>