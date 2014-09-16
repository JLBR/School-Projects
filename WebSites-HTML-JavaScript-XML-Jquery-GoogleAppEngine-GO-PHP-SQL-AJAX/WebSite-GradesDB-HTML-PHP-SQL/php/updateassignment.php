<?php

ini_set('display_errors',1); 
 error_reporting(E_ALL);

$con=mysqli_connect("","","","");

// Check connection
if (mysqli_connect_errno())
  {
  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

$AScore=$_POST["ascore"];
$EV_ID=$_POST["eval_id"];
$Action=$_POST["action"];

if(isset($_POST["complete"]) && $_POST["complete"]='Yes'){
	$Comp = 'Yes';
}else{
	$Comp = 'No';
}

//used for error checking
//var_dump( $_POST["GradeScale"][1] );


if($Action=="update"){
	//update a catagory
	$sql=("UPDATE Evaluation 
		SET Complete='$Comp', ActualScore='$AScore'
		WHERE EVAL_ID='$EV_ID'");
}else{
	$sql=("DELETE FROM Evaluation 
		WHERE EVAL_ID='$EV_ID'");
}

mysqli_query($con, $sql)or die(mysqli_error($con));

//echo "done";

mysqli_close($con);

$Page=$_POST["page"];
header ("Location: $Page");


?> 