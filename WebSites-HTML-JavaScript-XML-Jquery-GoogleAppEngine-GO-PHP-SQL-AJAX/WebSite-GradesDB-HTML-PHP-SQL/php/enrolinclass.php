<?php

session_start();

ini_set('display_errors',1); 
 error_reporting(E_ALL);

$con=mysqli_connect("","","","");

// Check connection
if (mysqli_connect_errno())
{
	 echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$SID = $_SESSION["S_ID"];
$CLID = $_POST["Enrole"];

		
$sql=("INSERT INTO Roster(FK_STUDENT_ID, FK_CLASS_ID)
	VALUES('$SID', '$CLID')");
				
//add class
mysqli_query($con, $sql)or die(mysqli_error($con));

	$Term=$_POST["term"];
	$SID=$_POST["school"];

header ("Location: ../classes/classes.php?term=$Term&school=$SID");
mysqli_close($con);

//used for error checking
//var_dump( $_POST["LName"] );
?>