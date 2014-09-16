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

if(isset($_GET["CID"])) {

	$Fname   = null;
	$Lname   = null;
	$twitter = null;
	$cID     = $_GET["CID"];

	$results = null;

	$sql=("SELECT FName, LName, Twitter, CELEB_ID
	       FROM Celeb
	       WHERE CELEB_ID = :CID
	       ORDER BY LName");

	try{
		$STH = $DBH->prepare($sql);

		$STH->bindParam(':CID', $cID, PDO::PARAM_INT);

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
}

$DBH = null;

?>