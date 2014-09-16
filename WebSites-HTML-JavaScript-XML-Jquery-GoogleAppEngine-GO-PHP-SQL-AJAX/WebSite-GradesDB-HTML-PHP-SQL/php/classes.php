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

if(isset($_POST["term"], $_POST["school"])){

$_SESSION["Term"] = $_POST["term"];
$_SESSION["SC_ID"] = $_POST["school"];

$Term = $_POST["term"];
$SC_ID = $_POST["school"];
$SID = $_SESSION["S_ID"];

$sql=("UPDATE Students
	SET Term='$Term', FK_SCHOOL_ID='$SC_ID'
	WHERE STUDENT_ID='$SID' ");

mysqli_query($con, $sql)or die(mysqli_error($con));
}
$Page=$_POST["page"];
header ("Location: $Page");
?>