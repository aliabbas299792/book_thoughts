<?php
include 'db.link.php';

$sql = "
SELECT
  id AS author_id, name AS author_name, link AS author_link
FROM `authors`
";
$where_conds = array();
$params = array();

if(isset($_GET['id'])) {
  array_push($where_conds, "`id`=?");
  array_push($params, $_GET['id']);
}

if(isset($_GET['name'])) {
  array_push($where_conds, "`name` LIKE ?");
  array_push($params, "%" . $_GET['name'] . "%");
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