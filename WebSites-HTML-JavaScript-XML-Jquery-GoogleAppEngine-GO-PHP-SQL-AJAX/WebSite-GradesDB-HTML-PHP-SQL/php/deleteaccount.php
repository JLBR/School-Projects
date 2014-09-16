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

$SID = $_SESSION["S_ID"];

$sql=("DELETE FROM Students WHERE STUDENT_ID ='$SID'");

//Delete
mysqli_query($con, $sql)or die(mysqli_error($con));

//echo "done";

mysqli_close($con);

session_destroy();
header ("Location: ../login/login.php");
?> 