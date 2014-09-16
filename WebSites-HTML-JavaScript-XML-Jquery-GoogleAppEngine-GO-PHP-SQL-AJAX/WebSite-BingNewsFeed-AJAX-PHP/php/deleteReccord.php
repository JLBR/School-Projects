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

if(isset($_GET["CID"])){

	$cID    = $_GET["CID"];

	$sql=("DELETE FROM Celeb 
		WHERE CELEB_ID=:CID");
	
	try{
		$STH = $DBH->prepare($sql);

		$STH->bindParam(':CID', $cID, PDO::PARAM_INT);
	
		$DBH->beginTransaction();
		$sqlError = $STH->execute();
		if(!($sqlError)){
			echo "SQL failed:\n";
			print_r($DBH->errorInfo());
			print_r($STH->errorInfo());
		}
		$DBH->commit();
	}catch (PDOException $e) {
   		echo 'Error: ' . $e->getMessage();
	}
}

$DBH = null;

?>