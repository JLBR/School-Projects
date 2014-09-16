<?php
session_start();
include 'dbconfiguration.php';

ini_set('display_errors',1); 
 error_reporting(E_ALL);

//if logged in, loggout
if(isset($_SESSION["login"]))
{
	session_unset();
	session_destroy();
	header ("Location: ../html/".$_POST["page"]);
	exit();
}


try {  
	$DBH = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);  
}  
catch(PDOException $e) {  
	echo "Error:".$e->getMessage();  
} 

if( ($_POST["UName"] != NULL) AND ($_POST["PWord"] != NULL) )
{
	$UName   = $_POST["UName"];
	$PWord = $_POST["PWord"];

	$sql=("SELECT UserName, Email FROM Users 
		WHERE UserName = :UName AND Password = :PWord");

	try{
		$STH = $DBH->prepare($sql);

		$STH->bindParam(':UName', $UName, PDO::PARAM_STR, 16);
		$STH->bindParam(':PWord', $PWord, PDO::PARAM_STR, 20);
	
		$sqlError = $STH->execute();
		if(!($sqlError)){
			//header ("Location: ../html/account.html?loggedin=0");
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
		//echo "Bad user name or password";
	}
	else
	{
		//echo "Good login";

		$_SESSION["login"] = 1;
		$_SESSION["UName"] = $UName;
		$_SESSION["Email"] = $STH->fetchColumn(1);
	}


}

header ("Location: ../html/".$_POST["page"]);
$DBH = null;


?>