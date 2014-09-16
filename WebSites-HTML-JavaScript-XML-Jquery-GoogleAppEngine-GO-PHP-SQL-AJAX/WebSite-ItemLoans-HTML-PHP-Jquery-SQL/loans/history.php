<?php

include '../db/connect.php';
session_start();

$me = $_SESSION['user']['id'];

$item_id = $_GET['id'];

$query = "SELECT loans.date_out, loans.date_in, users.fname, users.lname
		  FROM loans 
		  LEFT JOIN users ON loans.borrower = users.id 
		  WHERE loans.item = $item_id";

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