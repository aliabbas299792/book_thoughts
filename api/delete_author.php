<?php
include 'db.link.php';
include 'verify_user.php';

if(!isset($_POST['id'])) {
  echo "fail";
  exit();
}

$delete = $db->prepare('DELETE FROM `authors` WHERE `id`=?');
if($delete->execute([$_POST['id']])) {
  echo "pass";
} else {
  echo "fail";
}

?>