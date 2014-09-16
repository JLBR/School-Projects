<?php

include '../db/connect.php';
session_start();

$user_id = $_SESSION['user']['id'];

$query = "SELECT * FROM `items` WHERE `owner` = $user_id";

$result = mysql_query($query);

if (!$result) {
	die('Invalid query: ' . mysql_error());
}

$items = array();

while($row = mysql_fetch_assoc($result)) {
	array_push($items, $row);
}

echo json_encode($items);

?>