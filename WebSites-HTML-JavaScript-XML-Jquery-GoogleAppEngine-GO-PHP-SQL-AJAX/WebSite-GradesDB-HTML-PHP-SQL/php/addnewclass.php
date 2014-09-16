<?php

ini_set('display_errors',1); 
 error_reporting(E_ALL);

$con=mysqli_connect("","","","");

// Check connection
if (mysqli_connect_errno())
  {
  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

$CName = $_POST["CName"];
$CID = $_POST["CID"];
$Cat = $_POST["CCat"];
$TCat = $_POST["TCat"];
$Unit = $_POST["Units"];
$Term = $_POST["Term"];
$SID = $_POST["School"];

if(empty($TCat)){
	$TCat = $Cat;
}


$sql=("INSERT INTO Class (FK_SCHOOL_ID, Class_Name, Units, Course_ID, Term)
	VALUES('$SID','$CName','$Unit','$CID', '$Term' )");

//Add new
mysqli_query($con, $sql)or die(mysqli_error($con));



//echo "done";

mysqli_close($con);

header ("Location: ../classes/classes.php?term=Spring2013&school=1");
?> 