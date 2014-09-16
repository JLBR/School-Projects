<?php

ini_set('display_errors',1); 
 error_reporting(E_ALL);

session_start();

$con=mysqli_connect("","","","");

// Check connection
if (mysqli_connect_errno())
  {
  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

$SID = $_POST["Delete"];

$sql=("DELETE FROM School WHERE School_ID ='$SID'");

//Delete
mysqli_query($con, $sql)or die(mysqli_error($con));

//echo "done";
$_SESSION["Class"] = "";
mysqli_close($con);

header ("Location: ../school/allschools.php");
?> 