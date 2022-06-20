<?php
include 'db.link.php';
include 'verify_user.php';

if(!isset($_GET['book_id']) || (!isset($_GET['quote']) && !isset($_GET['comment']) && !isset($_GET['additional_info']))) {
  echo "fail";
  exit();
}

$insert = $db->prepare('INSERT INTO `book_thoughts` (`book_id`, `quote`, `comment`, `additional_info`, `chapter`, `chapter_section`) VALUES (?, ?, ?, ?, ?, ?)');
if($insert->execute([urldecode($_GET['book_id']), urldecode($_GET['quote']), urldecode($_GET['comment']), urldecode($_GET['additional_info']), urldecode($_GET['chapter']), urldecode($_GET['chapter_section'])])) {
  echo "pass";
} else {
  echo "fail";
}

?>