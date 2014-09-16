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

if(isset($_POST["assignmentCat"])){

$Class_ID = $_POST["class"];
$Student = $_SESSION["S_ID"];
$EV_Name = $_POST["assignmentName"];
$EV_Cat = $_POST["assignmentCat"];
$MX_SC = $_POST["maxscore"];

$sql=("INSERT INTO Evaluation (FK_CLASS_ID, FK_STUDENT_ID, EvalName, FK_EVAL_CAT_ID, ActualScore, MaxScore, Complete)
	VALUES('$Class_ID', '$Student', '$EV_Name', '$EV_Cat','0','$MX_SC','No' )");

//Add new
mysqli_query($con, $sql)or die(mysqli_error($con));



//echo "done";
}
mysqli_close($con);

header ("Location: ../classes/addassignments.php");
?> 