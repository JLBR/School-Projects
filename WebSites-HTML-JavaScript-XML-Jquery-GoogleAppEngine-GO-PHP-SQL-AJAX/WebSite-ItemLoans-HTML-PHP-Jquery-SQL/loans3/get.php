<?php

include '../db/connect.php';
session_start();

$me = $_SESSION['user']['id'];

if ($_GET['to'] == 'me') {
	$query = "SELECT loans.id AS loan_id, loans.*, users.*, items.* FROM `loans`
			  LEFT JOIN `items` ON loans.item = items.id
			  LEFT JOIN `users` ON items.owner = users.id
			  WHERE loans.borrower = $me AND loans.date_in IS NULL";
} elseif ($_GET['to'] == 'others') {
	$query = "SELECT loans.id AS loan_id, loans.*, users.*, items.* FROM `loans`
			  LEFT JOIN `items` ON loans.item = items.id
			  LEFT JOIN `users` ON loans.borrower = users.id
			  WHERE items.owner = $me AND loans.date_in IS NULL";
} else {
	$query = "SELECT * FROM `items` WHERE loanable = 1";
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