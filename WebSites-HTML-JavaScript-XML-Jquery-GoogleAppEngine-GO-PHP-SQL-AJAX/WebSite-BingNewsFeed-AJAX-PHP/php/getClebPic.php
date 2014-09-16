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
	$CID = $_GET["CID"];

	$sql = ("SELECT Picture 
		FROM Celeb
		WHERE CELEB_ID = :CID");

	try{
		$STH = $DBH->prepare($sql);

		$STH->bindParam(':CID', $CID, PDO::PARAM_INT);

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