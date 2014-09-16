
<?php

include '../db/connect.php';
session_start();
$user_id = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	$id = mysql_real_escape_string($_POST["id"]);  //no field in inventory for id(auto assigned by DB)
	
	$item_id = mysql_real_escape_string($_POST["item_id"]);
	$date = date('m/d/Y h:i:s a', time());

	$rating = mysql_real_escape_string($_POST["rating"]);
	$notes = mysql_real_escape_string($_POST["notes"]);
	
	if (empty($rating)) $rating = '';
	if (empty($notes)) $notes = 'NULL';

	$query = '';

	if (!$id) {
		$query = "INSERT INTO `loans` (`borrower`, `item`, `date_out`, `notes`) 
						VALUES ($user_id, $item_id, '$date', '$notes');";
	} else {
		$completed = mysql_real_escape_string($_POST["completed"]);

		$query = "UPDATE `loans` 
				  SET `date_in` = '$date', `rating` = '$rating', `notes` = '$notes'
				  WHERE `id` = $id";
	}

	mysql_query($query);


	if(empty($id)){
		$id = mysql_insert_id();
	}
	$error = mysql_error($db);

	if (!empty($error)) {
		$success = false;
		echo json_encode(array("success" => $success, "data" => $error));
	} else {
		// unset the loanable status of the item
		$query = "UPDATE `items`
				  SET `loanable` = 0
				  WHERE `id` = $item_id";

		mysql_query($query);

		$error = mysql_error($db);

		if (!$error) {
			$success = true;
			$response_data = array("id" => $id);
			echo json_encode(array("success" => $success, "data" => $response_data));
		} else {
			// something went wrong here, we should roll back the changes to the loan
			// table, because the item is still set as loanable.
		}
	}
}