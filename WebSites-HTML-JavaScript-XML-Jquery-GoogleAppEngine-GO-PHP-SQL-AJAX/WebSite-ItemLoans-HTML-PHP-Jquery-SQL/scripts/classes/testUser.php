<?php
     ini_set('display_errors',1); 
  	error_reporting(E_ALL);
  	include '../autoloader.php';

	echo "hi";

	$email = "test@test.tst";
	$fname = "ftest";
	$lname = "ltest";
	$address = "test street";
	$state = "test";
	$zipCode = "999999";
	$phoneNumber ="sdfjk&7897897783";
	$userName = "utest";
	$password = "ptest";
	$passwordUpdate = "upass";

	echo "<p>add user<p>";
	$result = User::addUser($email, $fname, $lname, $address, $state, $zipCode, $phoneNumber, $userName, $password);
	echo "result : ".$result["success"]."<p>";
	var_dump($result);


	echo "<p>get user<p>";

	$results = User::getUserProperties($userName);

	echo "<p>";
	var_dump($results);

	echo "<p>login<p>";

	$results =  User::login($userName, $password);

		echo "<p>";
	var_dump($results);

	echo "<p> set password<p>";

	$results =  User::setPassword($userName, $passwordUpdate);

	echo "<p>";
	var_dump($results);

	$results =  User::login($userName, $passwordUpdate);

		echo "<p>";
	var_dump($results);

		$results =  User::login($userName, $password);

		echo "<p>";
	var_dump($results);

		$results =  User::setPassword($userName, $password);

?>