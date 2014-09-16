<?php

ini_set('display_errors',1); 
 error_reporting(E_ALL);


$FName = $_POST["FName"];
$LName = $_POST["LName"];

$con=mysqli_connect("","","","");

// Check connection
if (mysqli_connect_errno())
{
	 echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
		
$sql=("SELECT * FROM Students WHERE FName ='".$FName."' AND LName ='".$LName."'");

//$sql=("SELECT * FROM Students WHERE FName = 1 AND LName =2");
				
//store results
$result = mysqli_query($con, $sql)or die(mysqli_error($con));

$row_cnt = mysqli_num_rows($result);

//loads user or goes to the regestration page *from http://stackoverflow.com/questions/15127778/mysqli-fetch-array-expects-one-parameter-to-be-mysqli-result
if ($result)
{
	if ($row_cnt > 0)
        {
		//creates a session based on the login information
        	session_start();
                //loads the user
		$row = mysqli_fetch_array($result);
		$_SESSION["FName"] = $_POST["FName"];
		$_SESSION["LName"] = $_POST["LName"];
		$_SESSION["S_ID"] = $row["STUDENT_ID"];
		$_SESSION["Term"] = $row["Term"];
		$_SESSION["SC_ID"] = $row["FK_SCHOOL_ID"];
		$_SESSION["Class"] = "";

                header ("Location: ../home/home.php");
                exit;
        }
        else
        {
		mysqli_free_result($result);
                header ("Location: ../regester/regester.html");
        	exit;
        }
}   
 else
{
	$errorMessage = "Error logging on";
}
mysqli_close($con);

//used for error checking
//var_dump( $_POST["LName"] );
?>