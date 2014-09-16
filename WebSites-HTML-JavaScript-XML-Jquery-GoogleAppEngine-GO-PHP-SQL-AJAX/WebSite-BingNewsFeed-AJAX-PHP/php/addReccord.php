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

$Fname   = null;
$Lname   = null;
$twitter = null;
if(isset($_POST["CID"]))
	$cID     = $_POST["CID"];

$sql    = null;
$fp	= null;

if($_SERVER['REQUEST_METHOD']=="POST") {

	if(isset($_POST["Lname"]) and isset($_POST["Fname"])){
		$Fname = $_POST["Fname"];
		$Lname = $_POST["Lname"];
	}else{
		$DBH = null;
		echo "Error:error no name was provided";
		exit();
	}

	if(isset($_POST["twitter"]))
		$twitter = $_POST["twitter"];


	if(($_FILES["cPic"]["name"])!=null){
		$tmpfile = $_FILES["cPic"]["tmp_name"];
		$filesize = $_FILES["cPic"]["size"];
		$filetype = $_FILES["cPic"]["type"];

		if(($filetype == "image/jpeg") and ($filesize < 1048576)){
			$fp = fopen($_FILES['cPic']['tmp_name'], 'rb');
		}else{
			echo "{Error: 'Only JPEG under 1MB may be  uploaded'}";
			exit();
		}
	}
	

	if($_POST["action"]=="addNew"){
		$sql=("INSERT INTO Celeb (FName, LName, Picture, Twitter)
			VALUES (:Fname, :Lname, :Picture, :Twitter)");
	}else{
		$sql=("UPDATE Celeb
			SET     FName=:Fname,
				LName=:Lname,
				Twitter=:Twitter ");

		if(($_FILES["cPic"]["name"])!=null)
			$sql .= (", Picture=:Picture ");

		$sql .= ("WHERE CELEB_ID=:CID");
	}


	try{
		$STH = $DBH->prepare($sql);

		$STH->bindParam(':Fname', $Fname, PDO::PARAM_STR);
		$STH->bindParam(':Lname', $Lname, PDO::PARAM_STR);
		$STH->bindParam(':Twitter', $twitter, PDO::PARAM_STR);
		if($_POST["action"]!="addNew"){
			$STH->bindParam(':CID', $cID, PDO::PARAM_INT);
			if(($_FILES["cPic"]["name"])!=null)
				$STH->bindParam(':Picture', $fp, PDO::PARAM_LOB);
		}else{
			$STH->bindParam(':Picture', $fp, PDO::PARAM_LOB);
		}
	
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
header ("Location: ../Homework3.html");

?>