
<?php

include '../db/connect.php';
session_start();
$user_id = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	$id = mysql_real_escape_string($_POST["id"]);  //no field in inventory for id(auto assigned by DB)
	$item = mysql_real_escape_string($_POST['item']);
	$owner = mysql_real_escape_string($_POST['owner']);
	$borrower = mysql_real_escape_string($_POST['borrower']);
	$borrowdate = mysql_real_escape_string($_POST['borrowdate']);
	$returndate = mysql_real_escape_string($_POST['returndate']);
	$action = mysql_real_escape_string($_POST["action"]);

	$date = date('m/d/Y h:i:s a', time());

	if (!$id) {
		$query = "INSERT INTO `requests` (`item`, `owner`, `borrower`, `borrowdate`, `returndate`, `action`, `date_made`) 
						VALUES ($item, $owner, $user_id, '$borrowdate', '$returndate', 'pending', '$date');";
	} else {
		$query = "UPDATE `requests` 
				  SET `action` = '$action'
				  WHERE `id` = $id";
	}

	mysql_query($query);

	if ($action == 'accepted') {
		$query = "INSERT INTO `loans` (`borrower`, `item`, `date_out`) 
						VALUES ($borrower, $item, '$date');";

		mysql_query($query);
	}

	if(empty($id)){
		$id = mysql_insert_id();
	}
	$error = mysql_error($db);

	if (!empty($error)) {
		$success = false;
		echo json_encode(array("success" => $success, "data" => $error));
	} else {
		$success = true;
		echo json_encode(array("success" => $success, "data" => $id));
	}
}