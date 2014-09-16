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
$CLID = $_POST["Dissenrole"];

$sql=("DELETE FROM Roster WHERE FK_STUDENT_ID ='$SID' AND FK_CLASS_ID='$CLID'");

//Delete
mysqli_query($con, $sql)or die(mysqli_error($con));

//echo "done";

mysqli_close($con);

$_SESSION["Class"] = "";


header ("Location: ../classes/classes.php");
?> 