<?php

include '../db/connect.php';
session_start();

$user_id = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


	$id = mysql_real_escape_string($_POST["id"]);  //no field in inventory for id(auto assigned by DB)
	$category = mysql_real_escape_string($_POST["category"]);
	$itemName = mysql_real_escape_string($_POST["name"]);

	$serialnum = mysql_real_escape_string($_POST["serialnum"]);
	$description = mysql_real_escape_string($_POST["description"]);
	$features = mysql_real_escape_string($_POST["features"]);
	$accessories = mysql_real_escape_string($_POST["accessories"]);

	$owner = $user_id; //references id from users(assigned by whose logged in i assume)
	$os = mysql_real_escape_string($_POST["os"]);
	$pages = mysql_real_escape_string($_POST["pages"]);
	$date = date('m/d/Y h:i:s a', time());

	$loanable = mysql_real_escape_string($_POST["loanable"]);
	
	if (empty($features)) $features = NULL;
	if (empty($accessories)) $accessories = NULL;
	if (empty($pages)) $pages = 'NULL';
	if (empty($os)) $os = NULL;  

	$query = '';

	if (empty($_POST['id'])) {
		$query = "INSERT INTO items (category, name, serialnum, notes, features, accessories, owner, os, pages, date, loanable) 
						VALUES ('$category', '$itemName', '$serialnum', '$description', '$features', '$accessories', $owner, '$os', $pages, '$date', $loanable);";
	} else {
		$id = $_POST['id'];

		$query = "UPDATE items 
				  SET category = '$category', name = '$itemName', serialnum = '$serialnum', 
				  		notes = '$description', features = '$features', accessories = '$accessories', 
					 	owner = $owner, os = '$os', pages = $pages, loanable = $loanable
				  WHERE id = $id";
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
		$success = true;
		$response_data = array("id" => $id);
		echo json_encode(array("success" => $success, "data" => $response_data));
	}
}