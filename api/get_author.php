<?php
include 'db.link.php';

$sql = "
SELECT
  id AS author_id, name AS author_name, link AS author_link
FROM `authors`
";
$where_conds = array();
$params = array();

if(isset($_POST['id'])) {
  array_push($where_conds, "`id`=?");
  array_push($params, $_POST['id']);
}

if(isset($_POST['name'])) {
  array_push($where_conds, "`name` LIKE ?");
  array_push($params, "%" . $_POST['name'] . "%");
}

if(!empty($where_conds)) {
  $sql .= " WHERE ";
  $sql .= implode(" AND ", $where_conds);
}

$get = $db->prepare($sql);
$status = $get->execute($params);

if(!$status) {
  echo "[]";
  exit();
}

echo json_encode($get->fetchAll(PDO::FETCH_ASSOC));
?>