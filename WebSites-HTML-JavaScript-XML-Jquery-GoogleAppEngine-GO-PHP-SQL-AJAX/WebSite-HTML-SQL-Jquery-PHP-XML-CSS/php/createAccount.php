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
} 


if(($_POST["fName"] != NULL )
	and ($_POST["lName"] != NULL)
	and ($_POST["uName"] != NULL )
	and ($_POST["password"] != NULL )
	and ($_POST["email"] != NULL) )
{
	$Fname   = $_POST["fName"];
	$Lname   = $_POST["lName"];
	$Uname   = $_POST["uName"];
	$Password = $_POST["password"];
	$Email   = $_POST["email"];

	$sql=("SELECT UserName FROM Users 
		WHERE UserName = :UName AND Password = :PWord");

	try{
		$STH = $DBH->prepare($sql);

		$STH->bindParam(':UName', $UName, PDO::PARAM_STR, 16);
		$STH->bindParam(':PWord', $PWord, PDO::PARAM_STR, 20);
	
		$sqlError = $STH->execute();
		if(!($sqlError)){
			header ("Location: ../html/account.html?loggedin=0");
			echo "SQL failed:\n";
			print_r($DBH->errorInfo());
			print_r($STH->errorInfo());
			$DBH = null;
			exit();
		}

	}catch (PDOException $e) {
   		echo 'Error: ' . $e->getMessage();
	}

	$columnCount = $STH->rowCount();


	if($columnCount == 0)
	{

		$sql=("INSERT INTO Users ( FirstName, LastName, UserName, Email, Password)
			VALUES (:Fname, :Lname, :Uname, :Email, :Password)");

		try
		{
			$STH = $DBH->prepare($sql);

			$STH->bindParam(':Fname', $Fname, PDO::PARAM_STR, 16);
			$STH->bindParam(':Lname', $Lname, PDO::PARAM_STR, 16);
			$STH->bindParam(':Uname', $Uname, PDO::PARAM_STR, 16);
			$STH->bindParam(':Email', $Email, PDO::PARAM_STR, 100);
			$STH->bindParam(':Password', $Password, PDO::PARAM_STR, 20);
	
			$DBH->beginTransaction();
			$sqlError = $STH->execute();
			if(!($sqlError))
			{
				header ("Location: ../html/account.html?loggedin=0");
				echo "SQL failed:\n";
				print_r($DBH->errorInfo());
				print_r($STH->errorInfo());
				$DBH = null;
				exit();
			}
			$DBH->commit();
		}
		catch (PDOException $e)
		{
   			echo 'Error: ' . $e->getMessage();
		}

		$DBH = null;
		$_SESSION['Uname'] = $Uname;
		header ("Location: ../html/account.html");
		
		$DBH = null;
		exit();
		
	}

	$DBH = null;
	header ("Location: ../html/account.html");
}


?>