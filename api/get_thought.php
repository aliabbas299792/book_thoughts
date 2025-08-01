<?php
include 'db.link.php';

$sql = "
SELECT
  book_thoughts.id AS thought_id,
  author_id, authors.name AS author_name, authors.link AS author_link,
  books.id AS book_id, books.name AS book_name, books.link AS book_link,
  quote, comment, additional_info, chapter, chapter_section, date_added
FROM `book_thoughts`
LEFT JOIN books ON books.id=book_thoughts.book_id
LEFT JOIN authors ON authors.id=books.author_id
";
$sqlEnd = "
ORDER BY date_added desc
";
$where_conds = array();
$params = array();

if(isset($_POST['author_id'])) {
  array_push($where_conds, "`author_id`=?");
  array_push($params, $_POST['author_id']);
}

if(isset($_POST['book_id'])) {
  array_push($where_conds, "`book_id`=?");
  array_push($params, $_POST['book_id']);
}

if(isset($_POST['quote'])) {
  array_push($where_conds, "`quote` LIKE ?");
  array_push($params, "%" . $_POST['quote'] . "%");
}

if(isset($_POST['comment'])) {
  array_push($where_conds, "`comment` LIKE ?");
  array_push($params, "%" . $_POST['comment'] . "%");
}

if(isset($_POST['additional_info'])) {
  array_push($where_conds, "`additional_info` LIKE ?");
  array_push($params, "%" . $_POST['additional_info'] . "%");
}

if(isset($_POST['at_or_after'])) {
  array_push($where_conds, "`date_added`>=FROM_UNIXTIME(?)");
  array_push($params, $_POST['at_or_after']);
}

if(isset($_POST['before'])) {
  array_push($where_conds, "`date_added`<FROM_UNIXTIME(?)");
  array_push($params, $_POST['before']);
}

if(isset($_POST['chapter'])) {
  array_push($where_conds, "`chapter`=?");
  array_push($params, $_POST['chapter']);
}

if(isset($_POST['chapter_section'])) {
  array_push($where_conds, "`chapter_section`=?");
  array_push($params, $_POST['chapter_section']);
}

if(!empty($where_conds)) {
  $sql .= " WHERE ";
  $sql .= implode(" AND ", $where_conds);
}

$sql .= $sqlEnd;

$get = $db->prepare($sql);
$status = $get->execute($params);

if(!$status) {
  echo "[]";
  exit();
}

echo json_encode($get->fetchAll(PDO::FETCH_ASSOC));
?>
