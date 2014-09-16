<?php

ini_set('display_errors',1); 
 error_reporting(E_ALL);

$con=mysqli_connect("","","","");

// Check connection
if (mysqli_connect_errno())
  {
  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

$CL_ID=$_POST["class"];
$Cat_Name=$_POST["catagoryAdd"];
$Weight=$_POST["addWeight"];


//used for error checking
//var_dump( $_POST["GradeScale"][1] );

//add a new catagory
$sql=("INSERT INTO Course_Grades (FK_CLASS_ID, Eval_Catagory, Weight)
	VALUES('$CL_ID','$Cat_Name','$Weight') ");
mysqli_query($con, $sql)or die(mysqli_error($con));

//echo "done";

mysqli_close($con);

header ("Location: ../classes/classdetails.php?class=$CL_ID");
?> 