<?php

ini_set('display_errors',1); 
 error_reporting(E_ALL);

$con=mysqli_connect("","","","");

// Check connection
if (mysqli_connect_errno())
  {
  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

$SID = $_POST["school_ID"];
$Term = $_POST["Term"];

$sql=("UPDATE School
 SET Term='$Term'
 WHERE School_ID = $SID");

//update
mysqli_query($con, $sql)or die(mysqli_error($con));

$GradeScale = $_POST["GradeScale"];


//used for error checking
//var_dump( $_POST["GradeScale"][1] );

//remove grades
$sql=("DELETE FROM SchoolGrades	WHERE FK_SCHOOL_ID='$SID'");
mysqli_query($con, $sql)or die(mysqli_error($con));

//update each grade selected
if ($GradeScale){
	foreach($GradeScale as &$value){

		//add grades
		$sql=("INSERT INTO SchoolGrades (FK_GRADE_STANDARD_ID, FK_SCHOOL_ID)
		VALUES('$value', '$SID')");
		mysqli_query($con, $sql);

	}
}

//echo "done";

mysqli_close($con);

header ("Location: ../school/schooldetails.php?school='$SID'");
?> 