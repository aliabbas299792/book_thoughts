<?php
include 'db.link.php';
include 'verify_user.php';

if(!isset($_GET['id'])) {
  echo "fail";
  exit();
}

$delete = $db->prepare('DELETE FROM `authors` WHERE `id`=?');
if($delete->execute([$_GET['id']])) {
  echo "pass";
} else {
  echo "fail";
}

?>