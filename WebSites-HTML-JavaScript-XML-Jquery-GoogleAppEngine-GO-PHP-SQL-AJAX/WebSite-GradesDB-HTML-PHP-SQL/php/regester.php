<?php

ini_set('display_errors',1); 
 error_reporting(E_ALL);

$con=mysqli_connect("","","","");

// Check connection
if (mysqli_connect_errno())
  {
  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

$FName1 = $_POST["FName"];
$LName1 = $_POST["LName"];

$sql=("SELECT * FROM Students WHERE FName = '".$FName1."' AND LName = '".$LName1."'");

//store results
$result = mysqli_query($con, $sql)or die(mysqli_error($con));

$row_cnt = mysqli_num_rows($result);

//loads user or goes to the login page if allready exist *from http://stackoverflow.com/questions/15127778/mysqli-fetch-array-expects-one-parameter-to-be-mysqli-result
if($result)
{

	if ($row_cnt > 0)
        {
		//creates a session based on the login information
		echo "ERROR student allready exists <a href='../login.php'>Log In</a>";
		mysqli_free_result($result);
                exit;
        }
       else
        {
		//start the session
		session_start();		
		
		$_SESSION["FName"] = $_POST["FName"];
		$_SESSION["LName"] = $_POST["LName"];
		$_SESSION["Term"] = "Spring2013";
		$_SESSION["SC_ID"] = 1;
		

		$sql=("INSERT INTO Students (FName, LName, FK_SCHOOL_ID, Term)
			VALUES ('$FName1', '$LName1', '1', 'Spring2013')");
		
		//print error on failure
		if (!mysqli_query($con,$sql))
		  {
		  	die('Error: ' . mysqli_error());
		  }
		//echo "1 record added";

		//get new ID
		$sql=("SELECT STUDENT_ID FROM Students WHERE FName ='".$FName1."' AND LName ='".$LName1."'");
		$result = mysqli_query($con, $sql)or die(mysqli_error($con));

		$row = mysqli_fetch_array($result);
		$_SESSION["S_ID"] = $row["STUDENT_ID"];

                header ("Location: ../home/home.php");
        	exit;
        }
 

}
mysqli_close($con);
?> 