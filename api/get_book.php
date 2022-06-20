<?php
include 'db.link.php';

$sql = "
SELECT
  author_id, authors.name AS author_name, authors.link AS author_link,
  books.id AS book_id, books.name AS book_name, books.link AS book_link
FROM `books`
INNER JOIN `authors` ON authors.id=author_id
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

if(isset($_GET['author_id'])) {
  array_push($where_conds, "`author_id`=?");
  array_push($params, $_GET['author_id']);
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