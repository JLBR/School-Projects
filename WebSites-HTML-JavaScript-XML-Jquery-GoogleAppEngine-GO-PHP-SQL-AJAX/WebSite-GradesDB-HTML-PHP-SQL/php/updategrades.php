<?php

ini_set('display_errors',1); 
 error_reporting(E_ALL);

$con=mysqli_connect("","","","");

// Check connection
if (mysqli_connect_errno())
  {
  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

$SID = $_POST["student"];
$Class = $_POST["class"];
$Grade= $_POST["FGrade"];

$sql=("UPDATE Roster
 SET Final_Grade='$Grade'
 WHERE FK_CLASS_ID = '$Class' AND FK_STUDENT_ID = '$SID'");

//update
mysqli_query($con, $sql)or die(mysqli_error($con));

//used for error checking
//var_dump( $_POST["GradeScale"][1] );

//echo "done";

mysqli_close($con);

header ("Location: ../classes/classdetails.php?class=$Class");
?> 