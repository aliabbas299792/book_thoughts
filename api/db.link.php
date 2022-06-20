<?php
$mysql_host = "localhost";
$dbname = "book_thoughts";
$charset = "utf8mb4";
$db_username = "root";
$db_password = "password";

if(!isset($db)){
    $db  = new PDO("mysql:host=$mysql_host;dbname=$dbname;charset=$charset", $db_username, $db_password);
}

$admin_username = 'admin';
$admin_password = 'admin123';
?>
