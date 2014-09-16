<?php

include '../../db/connect.php';
session_start();

$request = $_GET['request'];

if ($request == '') {
	die('Invalid Request: Please provide a valid request id.');
}

$query = "SELECT comments.comment, comments.date, users.fname, users.lname FROM comments
		  LEFT JOIN users ON comments.commenter = users.id 
		  WHERE comments.request = $request";

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