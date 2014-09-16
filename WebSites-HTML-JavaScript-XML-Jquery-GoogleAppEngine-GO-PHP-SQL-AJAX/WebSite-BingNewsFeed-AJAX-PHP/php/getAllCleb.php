<?php

ini_set('display_errors',1); 
 error_reporting(E_ALL);

$host   = ("");
$dbname = ("");
$user   = ("");
$pass   = ("");

try {  
	$DBH = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);  
}  
catch(PDOException $e) {  
	echo "Error:".$e->getMessage();  
} 

	$sql=("SELECT FName, LName, Twitter, CELEB_ID
	       FROM Celeb
	       ORDER BY LName");

	try{
		$STH = $DBH->prepare($sql);

		if($STH->execute()){
			echo json_encode($STH->fetchAll(PDO::FETCH_ASSOC));
		}else{
			echo 'Error: retreaving data';
			print_r($DBH->errorInfo());
			print_r($STH->errorInfo());
		}

	}catch (PDOException $e) {
   		echo 'Error: ' . $e->getMessage();
	}

$DBH = null;

?>