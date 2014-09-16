<?php

include '../db/connect.php';

// at this point, we're assuming that users can only delete their
// own items.

session_start();
$user_id = $_SESSION['user']['id'];

if (isset($_POST['id'])) {
	$item_id = $_POST['id'];

	// delete the item from the loans table (foreign key constraint)

	$query = "DELETE FROM `loans` WHERE item = $item_id";

	$result = mysql_query($query);

	if (!$result) {
		die('Invalid query: ' . mysql_error());
	}

	// delete the item from the items table

	$query = "DELETE FROM `items` WHERE id = $item_id 
									AND owner = $user_id";

	$result = mysql_query($query);

	if (!$result) {
		die('Invalid query: ' . mysql_error());
	}

	echo json_encode(array('success' => mysql_affected_rows() > 0));

}

?>