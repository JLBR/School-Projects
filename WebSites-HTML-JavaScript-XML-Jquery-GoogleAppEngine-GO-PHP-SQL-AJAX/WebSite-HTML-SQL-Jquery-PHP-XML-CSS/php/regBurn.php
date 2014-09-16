<?php
session_start();
include 'dbconfiguration.php';

ini_set('display_errors',1); 
 error_reporting(E_ALL);

try {  
	$DBH = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);  
}  
catch(PDOException $e) {  
	header ("Location: ../html/".$_POST["page"]);
	echo "Error:".$e->getMessage();  
	exit();
} 


if($_POST["address1"] != NULL
	and $_POST["city"] != NULL
	and $_POST["state"] != NULL )
{
	$sql;
	$STH;

	$StartH = $_POST["burnTimeH"];
	$StartM = $_POST["burnTimeM"];
	$StartT = date('Y-m-d');
	$StartT = $StartT." ".$StartH.":".$StartM.":00";

 	$Address1 = $_POST["address1"];
	$Address2 = $_POST["address2"];
	$city = $_POST["city"];
	$state = $_POST["state"];
	$zip5 = $_POST["zip5"];
	$addressID;

	$Status;
	$Lat;
	$Lon;

	$Size;
	$endT;
	$Photo;
	$PContact;
	$UserName;
	$areaCode;
	$phone;

	if($_POST["action"] != "report")
	{
		$endH = $_POST["endTimeH"];
		$endM = $_POST["endTimeM"];
		$endT = date('Y-m-d');
		$endT = $endT." ".$endH.":".$endM.":00";

		$Size = $_POST["size"];
		$PContact = $_POST["contactName"];
		$UserName = $_SESSION["UName"];
		
		if(($_FILES["photo"]["name"])!=null){
			$tmpfile = $_FILES["photo"]["tmp_name"];
			$filesize = $_FILES["photo"]["size"];
			$filetype = $_FILES["photo"]["type"];

			if(($filetype == "image/jpeg") and ($filesize < 1048576)){
				$photo = fopen($_FILES['photo']['tmp_name'], 'rb');
			}else{
				header ("Location: ../html/".$_POST["page"]);
				echo "{Error: 'Only JPEG under 1MB may be  uploaded'}";
				exit();
			}
		}
	}



	$sql = ("SELECT ADDRESS_ID FROM Address
		WHERE Address1 = :address1
			AND City = :city
			AND State = :state");

	$STH = $DBH->prepare($sql);
	
	$STH->bindParam(':address1', $Address1, PDO::PARAM_STR, 38);
	$STH->bindParam(':city', $city, PDO::PARAM_STR, 15);	
	$STH->bindParam(':state', $state, PDO::PARAM_STR, 2);

	$sqlError = $STH->execute();
	
	if(!($sqlError)){
		header ("Location: ../html/".$_POST["page"]);
		echo "SQL failed:\n";
		print_r($DBH->errorInfo());
		print_r($STH->errorInfo());
		$DBH = null;
		exit();
	}

	$columnCount = $STH->rowCount();

	//if 0 then address is not in the database
	if( $columnCount == 0 )
	{
		$sql = ("INSERT INTO Address
				(Address1, Address2, City, State, Zip5)
			VALUES (:Address1, :Address2, :City, :State, :Zip5)");
		try
		{
			$STH = $DBH->prepare($sql);
	
			$STH->bindParam(':Address1', $Address1, PDO::PARAM_STR, 38);
			$STH->bindParam(':Address2', $Address2, PDO::PARAM_STR, 38);
			$STH->bindParam(':City', $city, PDO::PARAM_STR, 15);	
			$STH->bindParam(':State', $state, PDO::PARAM_STR, 2);
			$STH->bindParam(':Zip5', $zip5, PDO::PARAM_STR, 5);

			$sqlError = $STH->execute();

			if(!($sqlError)){
				header ("Location: ../html/".$_POST["page"]);
				echo "SQL failed:\n";
				print_r($DBH->errorInfo());
				print_r($STH->errorInfo());
				$DBH = null;
				exit();
			}

		}catch (PDOException $e) {
   			echo 'Error: ' . $e->getMessage();
		}

		$sql = ("SELECT ADDRESS_ID FROM Address
			WHERE Address1 = :address1
				AND City = :city
				AND State = :state");
	
		$STH = $DBH->prepare($sql);

		$STH->bindParam(':address1', $Address1, PDO::PARAM_STR, 38);
		$STH->bindParam(':city', $city, PDO::PARAM_STR, 15);	
		$STH->bindParam(':state', $state, PDO::PARAM_STR, 2);

		$sqlError = $STH->execute();
	
		if(!($sqlError)){
			echo "SQL failed:\n";
			print_r($DBH->errorInfo());
			print_r($STH->errorInfo());
			$DBH = null;
			header ("Location: ../html/".$_POST["page"]);
			exit();
		}
	}

	$addressID = $STH->fetchColumn();

	//post a report
	if($_POST["action"]=="report")
	{
		$materials = $_POST["materials"];
		$report = 1;
		$sql = ("INSERT INTO Pile (FK_ADDRESS_ID, report, materials, start)
			VALUES (:address, :report, :materials, :start)");
	}
	else //or post a new pile
	{
		$sql = ("INSERT INTO Pile (FK_ADDRESS_ID, stop, Size, start, Photo, FK_USER_ID)
			VALUES (:address, :endT, :size, :start, :photo,
				(SELECT USER_ID FROM Users
				WHERE UserName = :UName ))");
	}

	try
	{
		$STH = $DBH->prepare($sql);

		$STH->bindParam(':address', $addressID, PDO::PARAM_INT);
		$STH->bindParam(':start', $StartT, PDO::PARAM_STR);
	
		if($_POST["action"]=="report")
		{
			$STH->bindParam(':report', $report, PDO::PARAM_INT);	
			$STH->bindParam(':materials', $materials, PDO::PARAM_STR);
		}
		else
		{
			$STH->bindParam(':photo', $photo, PDO::PARAM_LOB);
			$STH->bindParam(':UName', $UserName, PDO::PARAM_STR);
			$STH->bindParam(':endT', $endT, PDO::PARAM_STR);
			$STH->bindParam(':size', $Size, PDO::PARAM_INT);
			$STH->bindParam(':endT', $endT, PDO::PARAM_STR);


		}

		$DBH->beginTransaction();
		$sqlError = $STH->execute();

		if(!($sqlError)){
			echo "SQL failed:\n";
			print_r($DBH->errorInfo());
			print_r($STH->errorInfo());
			$DBH = null;
			header ("Location: ../html/".$_POST["page"]);
			exit();
		}

	}catch (PDOException $e) {
		echo 'Error: ' . $e->getMessage();
	}

	$DBH->commit();


}
$DBH = NULL;
//echo $user." ".$size;
header ("Location: ../html/".$_POST["page"]);
?>