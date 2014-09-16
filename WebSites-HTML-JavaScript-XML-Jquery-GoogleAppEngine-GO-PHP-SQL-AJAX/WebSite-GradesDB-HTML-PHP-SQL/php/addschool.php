<?php

ini_set('display_errors',1); 
 error_reporting(E_ALL);

$con=mysqli_connect("","","","");

// Check connection
if (mysqli_connect_errno())
  {
  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

$SName = $_POST["SName"];
$Term = $_POST["Term"];
$GradeScale = $_POST["GradeScale"];

$sql=("INSERT INTO School (Term, School_Name)
	VALUES ('$Term','$SName')");

//Add new School
mysqli_query($con, $sql)or die(mysqli_error($con));

$sql=("SELECT SCHOOL_ID FROM School WHERE School_Name ='$SName'");

$results = mysqli_query($con, $sql)or die(mysqli_error($con));
$row = mysqli_fetch_array($results);
$SID = $row["SCHOOL_ID"];

//used for error checking
//var_dump( $SID );

//update each grade selected
if ($GradeScale){
	foreach($GradeScale as &$value){

		//add grades
		$sql=("INSERT INTO SchoolGrades (FK_GRADE_STANDARD_ID, FK_SCHOOL_ID)
		VALUES('$value', '$SID')");
		mysqli_query($con, $sql);

	}
}

header ("Location: ../school/schools.php");
//echo "done";

mysqli_close($con);
?> 