<?php

ini_set('display_errors',1); 
 error_reporting(E_ALL);

$con=mysqli_connect("","","","");

// Check connection
if (mysqli_connect_errno())
  {
  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

$GName = $_POST["GName"];
$GP = $_POST["GP"];
$Cat = $_POST["Catagory"];
$SCat = $_POST["slectCat"];

if(isset($_POST["CR"]) && $_POST["CR"]='CR'){
	$CR = 'CR';
}else{
	$CR = 'NC';
}

if(empty($Cat)){
	$Cat = $SCat;
}


$sql=("INSERT INTO GradeStandard (Grade_Name, Grade_Point, GradeCatagory, CR_NC)
	VALUES('$GName','$GP','$Cat','$CR' )");

//Add new
mysqli_query($con, $sql)or die(mysqli_error($con));



//echo "done";

mysqli_close($con);

header ("Location: ../school/schoolgradeoptions.php");
?> 