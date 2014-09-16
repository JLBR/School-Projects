<?php

include '../db/connect.php';
session_start();

$user_id = $_SESSION['user']['id'];

if ($_GET['direction'] == 'incoming') {
	$query = "SELECT items.id AS item, items.name, users.id AS borrower, users.fname, users.lname, requests.* FROM requests
			  LEFT JOIN items ON requests.item = items.id
			  LEFT JOIN users ON requests.borrower = users.id
			  WHERE requests.owner = $user_id
			  AND requests.action = 'pending'";
} elseif ($_GET['direction'] == 'outgoing') {
	$query = "SELECT items.id AS item, items.name, users.fname, users.lname, requests.* FROM requests
			  LEFT JOIN items ON requests.item = items.id
			  LEFT JOIN users ON requests.owner = users.id
			  WHERE requests.borrower = $user_id
			  AND requests.action = 'pending'";
} else {
	die("Unknown direction: Please choose either 'incoming' or 'outgoing'.");
}

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