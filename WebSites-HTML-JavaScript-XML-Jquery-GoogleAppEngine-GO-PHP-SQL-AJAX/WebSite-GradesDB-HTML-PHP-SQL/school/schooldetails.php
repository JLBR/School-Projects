<?php
	ini_set('display_errors',1); 
	 error_reporting(E_ALL);

	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<head>
	<title>Grade Tool School Details</title>
</head>
<html>
<body>


<table border="0" style='min-width:1000px'>
	<tr>
		<td rowspan="3" style="width:120px"></td>
		<td align="right" colspan="4"><a href="../login/login.php">Log Out</a></td>
	</tr>
	<tr>
		<td align="center" colspan="4">Grade Tool School Details</td>
	</tr>
	<tr>
		<td align="center" ><a href="../home/home.php">Home</a></td>
		<td align="center" ><a href="../classes/classes.php">Classes</a></td>
		<td align="center" ><a href="../school/schools.php">School</a></td>
		<td align="center" ><a href="../settings/settings.php">Settings</a></td>
	</tr>
	<tr>
		<td><a href="schools.php">My Schools</td>
		<td colspan="4" rowspan="7">
			<form action="http://web.engr.oregonstate.edu/~brewerji/CS275/php/updateschool.php" method="POST">
			 <table>
				
<?php
	$School_ID = $_GET["school"];
	echo "<input type='hidden'  name='school_ID' value ='$School_ID'/>";
	
	$con=mysqli_connect("","","","");
	
	// Check connection
	if (mysqli_connect_errno())
 	{
 		 echo "Failed to connect to MySQL: " . mysqli_connect_error();
  	}

	$sql=("SELECT * FROM School WHERE SCHOOL_ID =$School_ID");
	//store results
	$result = mysqli_query($con, $sql)or die(mysqli_error($con));
	
	$sql=("SELECT DISTINCT Term FROM School");
	//store results
	$result2 = mysqli_query($con, $sql)or die(mysqli_error($con));

	$sql=("SELECT * FROM GradeStandard");
	//store results
	$result3 = mysqli_query($con, $sql)or die(mysqli_error($con));

	while($row = mysqli_fetch_array($result))
  	{
		$SID = $row["SCHOOL_ID"];
		$SName = $row["School_Name"];
		$STerm = $row["Term"];

		//school details
  		echo "<tr><td align='center'><a href='schooldetails.php?school=$SID'> $SName </a></td></tr>";
		
		////Term Drop down box
		echo "<tr><td align='center'>Term</td>
			<td><select name='Term'>";
		
		while($row2 = mysqli_fetch_array($result2)){
			$tempTerm = $row2["Term"];
			echo "<option value='$tempTerm' ";
			
			if($tempTerm == $STerm){
				echo 	"selected='selected'";
			}
			echo ">$tempTerm</option>";
		}

		echo "</select></td></tr>";
		

		////////Grade Option box
		$row_cnt = mysqli_num_rows($result3);
		echo "<tr><td align='center'>Grade Scale</td>
			<td><select name='GradeScale[]' size = '$row_cnt' multiple>";
		
		while($row3 = mysqli_fetch_array($result3)){
			$ST_ID = $row3["GRADE_STANDARD_ID"];
			$GName = $row3['Grade_Name'];
			$GP = $row3['Grade_Point'];
			$CR = $row3['CR_NC'];
			
			$sql=("SELECT * FROM SchoolGrades WHERE FK_SCHOOL_ID = '$SID' AND FK_GRADE_STANDARD_ID = '$ST_ID'");
			//store results
			$b = mysqli_query($con, $sql)or die(mysqli_error($con));
			
			$row_cnt = mysqli_num_rows($b);

			echo "<option  value='$ST_ID' ";
			if($row_cnt>0){
				echo "selected='selected' ";
			}
			echo ">$GName $GP $CR</option>";
		}

		echo ">$tempTerm</option>";
		echo "</select></td></tr>";
  	}

?>
				<tr><td><button type="submit">Update School</button></td></tr>
			</table></form>
		</td>
	</tr>
	<tr>
		<td><a href="allschools.php">All Schools</a></td>
		
	</tr>
	<tr>
		<td><a href="schoolsadd.php">Add New School</a></td>
	</tr>
	<tr>
		<td><a href="schoolgradeoptions.php">Grade Options</a></td>
	</tr>
	<tr>
		<div style="height:100%"></div>
	</tr>
</table>


</body>
</html>