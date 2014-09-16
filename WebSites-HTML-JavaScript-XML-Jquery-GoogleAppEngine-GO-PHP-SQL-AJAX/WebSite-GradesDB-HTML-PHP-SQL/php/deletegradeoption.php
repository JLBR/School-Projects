<?php

ini_set('display_errors',1); 
 error_reporting(E_ALL);

$con=mysqli_connect("","","","");

// Check connection
if (mysqli_connect_errno())
  {
  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

$GID = $_POST["Delete"];

$sql=("DELETE FROM GradeStandard WHERE GRADE_STANDARD_ID='$GID'");

//Delete
mysqli_query($con, $sql)or die(mysqli_error($con));

//echo "done";

mysqli_close($con);

header ("Location: ../school/schoolgradeoptions.php");
?> 