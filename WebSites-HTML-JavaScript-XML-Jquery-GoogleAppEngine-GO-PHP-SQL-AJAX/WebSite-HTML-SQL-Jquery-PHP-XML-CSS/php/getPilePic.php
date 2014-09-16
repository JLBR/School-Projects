<?php
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


if(isset($_GET["PID"])) {
	$PID = $_GET["PID"];

	$sql = ("SELECT Photo
		FROM Pile
		WHERE PILE_ID = :PID");

	try{
		$STH = $DBH->prepare($sql);

		$STH->bindParam(':PID', $PID, PDO::PARAM_INT);

		if($STH->execute()){

			$STH->bindColumn(1, $image, PDO::PARAM_LOB);
			$STH->fetch(PDO::FETCH_BOUND);

			header("Content-Type: image/jpeg");
			//fpassthru($image);
			echo $image;
			
		}else{
			echo 'Error: retreaving data\n';
			print_r($DBH->errorInfo());
			print_r($STH->errorInfo());
		}
		
	}catch (PDOException $e) {
   		echo 'Error: ' . $e->getMessage();
	}

}


$DBH = null;

?>