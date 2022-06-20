<?php
include 'db.link.php';

$sql = "
SELECT
  book_thoughts.id AS thought_id,
  author_id, authors.name AS author_name, authors.link AS author_link,
  books.id AS book_id, books.name AS book_name, books.link AS book_link,
  quote, comment, additional_info, chapter, chapter_section, date_added
FROM `book_thoughts`
INNER JOIN books ON books.id=book_thoughts.book_id
INNER JOIN authors ON authors.id=books.author_id
";
$where_conds = array();
$params = array();

if(isset($_GET['author_id'])) {
  array_push($where_conds, "`author_id`=?");
  array_push($params, $_GET['author_id']);
}

if(isset($_GET['book_id'])) {
  array_push($where_conds, "`book_id`=?");
  array_push($params, $_GET['book_id']);
}

if(isset($_GET['quote'])) {
  array_push($where_conds, "`quote` LIKE ?");
  array_push($params, "%" . $_GET['quote'] . "%");
}

if(isset($_GET['comment'])) {
  array_push($where_conds, "`comment` LIKE ?");
  array_push($params, "%" . $_GET['comment'] . "%");
}

if(isset($_GET['additional_info'])) {
  array_push($where_conds, "`additional_info` LIKE ?");
  array_push($params, "%" . $_GET['additional_info'] . "%");
}

if(isset($_GET['at_or_after'])) {
  array_push($where_conds, "`date_added`>=FROM_UNIXTIME(?)");
  array_push($params, $_GET['at_or_after']);
}

if(isset($_GET['before'])) {
  array_push($where_conds, "`date_added`<FROM_UNIXTIME(?)");
  array_push($params, $_GET['before']);
}

if(isset($_GET['chapter'])) {
  array_push($where_conds, "`chapter`=?");
  array_push($params, $_GET['chapter']);
}

if(isset($_GET['chapter_section'])) {
  array_push($where_conds, "`chapter_section`=?");
  array_push($params, $_GET['chapter_section']);
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