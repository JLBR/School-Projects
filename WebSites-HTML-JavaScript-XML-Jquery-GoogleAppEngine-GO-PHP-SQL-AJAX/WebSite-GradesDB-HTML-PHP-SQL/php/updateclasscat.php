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
$Cat_Name=$_POST["Catagory"];
$Weight=$_POST["Weight"];
$Action=$_POST["update"];
$CAT_ID=$_POST["CAT_ID"];

//used for error checking
//var_dump( $_POST["GradeScale"][1] );


if($Action=="update"){
	//update a catagory
	$sql=("UPDATE Course_Grades 
		SET Weight='$Weight', Eval_Catagory='$Cat_Name'
		WHERE EVAL_CAT_ID='$CAT_ID'");
}else{
	$sql=("DELETE FROM Course_Grades 
		WHERE EVAL_CAT_ID='$CAT_ID'");
}

mysqli_query($con, $sql)or die(mysqli_error($con));

//echo "done";

mysqli_close($con);

header ("Location: ../classes/classdetails.php?class=$CL_ID");
?> 