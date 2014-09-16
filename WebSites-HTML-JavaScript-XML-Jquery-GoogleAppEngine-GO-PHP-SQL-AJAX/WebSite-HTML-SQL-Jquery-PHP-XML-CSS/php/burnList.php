<?php
session_start();
include 'dbconfiguration.php';

ini_set('display_errors',1); 
error_reporting(E_ALL);

try {  
	$DBH = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);  
}  
catch(PDOException $e) {  
	echo "Error:".$e->getMessage(); 
	exit(); 
} 

	$sql=("SELECT Address.Address1, Address.City, Address.State, 
		Users.Email,
		Pile.PILE_ID
	       FROM Address, Users, Pile
	       WHERE Pile.FK_USER_ID = Users.USER_ID
		AND Pile.FK_ADDRESS_ID = Address.ADDRESS_ID ");

	try{
		$STH = $DBH->prepare($sql);

		if($STH->execute()){
			echo json_encode($STH->fetchAll(PDO::FETCH_ASSOC));
		}else{
			echo 'Error: retreaving data';
			print_r($DBH->errorInfo());
			print_r($STH->errorInfo());
			$DBH = null;
			exit();
		}

	}catch (PDOException $e) {
   		echo 'Error: ' . $e->getMessage();
	}

$DBH = null;


?>