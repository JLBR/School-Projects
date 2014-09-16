<?php

include '../../db/connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	$commenter = $_SESSION['user']['id'];

	$request = mysql_real_escape_string($_POST['request']);
	$comment = mysql_real_escape_string($_POST['comment']);
	$date = date('m/d/Y h:i:s a', time());

	if ($request == '') {
		die('Invalid Request: Please provide a valid request id.');
	}

	$query = "INSERT INTO comments (commenter, request, comment, date) 
				   VALUES ($commenter, $request, '$comment', '$date');";

	$result = mysql_query($query);

	if (!$result) {
		die('Invalid query: ' . mysql_error());
	}
	
	$error = mysql_error($db);
	if (!empty($error)) {
		$success = false;
		echo json_encode(array("success" => $success, "data" => $error));
	} else {
		$success = true;
		$response_data = array("id" => $id);
		echo json_encode(array("success" => $success, "data" => $response_data));
	}
}

?>