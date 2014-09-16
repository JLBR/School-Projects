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
$CAT_ID=$_POST["ClassCat"];

//used for error checking
//var_dump( $_POST["GradeScale"][1] );


//remove catagories
$sql=("DELETE FROM ClassCatagories
	WHERE FK_CLASS_ID='$CL_ID'");

mysqli_query($con, $sql)or die(mysqli_error($con));

//update each catagory selected
if ($CAT_ID){
	foreach($CAT_ID as &$value){

		//add catagories
		$sql=("INSERT INTO ClassCatagories (FK_CAT_ID, FK_CLASS_ID)
		VALUES('$value', '$CL_ID')");
		mysqli_query($con, $sql);

	}
}

mysqli_query($con, $sql)or die(mysqli_error($con));

//echo "done";

mysqli_close($con);

header ("Location: ../classes/classdetails.php?class=$CL_ID");
?> 