<?php
include 'db.link.php';
include 'verify_user.php';

if(!isset($_GET['thought_id']) || !isset($_GET['book_id']) || (!isset($_GET['quote']) && !isset($_GET['comment']) && !isset($_GET['additional_info']))) {
  echo "fail";
  exit();
}

$insert = $db->prepare('UPDATE `book_thoughts` SET `book_id`=?, `quote`=?, `comment`=?, `additional_info`=?, `chapter`=?, `chapter_section`=? WHERE id=?');
if($insert->execute([urldecode($_GET['book_id']), urldecode($_GET['quote']), urldecode($_GET['comment']), urldecode($_GET['additional_info']), urldecode($_GET['chapter']), urldecode($_GET['chapter_section']), urldecode($_GET['thought_id'])])) {
  echo "pass";
} else {
  echo "fail";
}

?>