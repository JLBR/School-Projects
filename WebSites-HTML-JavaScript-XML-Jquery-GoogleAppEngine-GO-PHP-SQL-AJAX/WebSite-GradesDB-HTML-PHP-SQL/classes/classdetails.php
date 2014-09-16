<?php
	ini_set('display_errors',1); 
	 error_reporting(E_ALL);

	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<head>
	<title>Grade Tool Class Details</title>
</head>
<html>
<body>

<table border="0" style='min-width:1000px'>
	<tr>
		<td rowspan="3" style="width:120px"></td>
		<td align="right" colspan="4"><a href="../login/login.html">Log Out</a></td>
	</tr>
	<tr>
		<td align="center" colspan="4">Grade Tool Class Details</td>
	</tr>
	<tr>
		<td align="center" ><a href="../home/home.php">Home</a></td>
		<td align="center" ><a href="../classes/classes.php">Classes</a></td>
		<td align="center" ><a href="../school/schools.php">School</a></td>
		<td align="center" ><a href="../settings/settings.php">Settings</a></td>
	</tr>
	<tr>
		<td><a href="assignments.php">Assignments</a></td>
		<td colspan="4" rowspan="7">
			<table>

<?php

		$CL_ID = $_GET["class"];
	//echo "<input type='hidden'  name='school_ID' value ='$School_ID'/>";
	
	$con=mysqli_connect("","","","");
	
	// Check connection
	if (mysqli_connect_errno())
 	{
 		 echo "Failed to connect to MySQL: " . mysqli_connect_error();
  	}

	$sql=("SELECT * FROM Class WHERE CLASS_ID =$CL_ID");
	//store results
	$result = mysqli_query($con, $sql)or die(mysqli_error($con));

	$row = mysqli_fetch_array($result);

	$CName=$row["Class_Name"];
	$CID=$row["Course_ID"];
	$Term=$row["Term"];
	$Units=$row["Units"];
	$ST_ID=$_SESSION["S_ID"];
	$SC_ID=$row["FK_SCHOOL_ID"];


	$sql=("SELECT School_Name FROM School WHERE  SCHOOL_ID='$SC_ID'");
	//store results
	$result = mysqli_query($con, $sql)or die(mysqli_error($con));

	$row = mysqli_fetch_array($result);

	$School= $row["School_Name"];
	
	echo "<tr><td><a href='../school/schooldetails.php?school=$SC_ID'>$School</a></td></tr>";
	echo "<tr><td>Name</td><td>$CName</td><td>Course ID</td><td>$CID</td></tr>";
	echo "<tr><td>Term</td><td>$Term</td><td>Units</td><td>$Units</td></tr>";
	
	$sql=("SELECT Final_Grade FROM Roster WHERE FK_STUDENT_ID='$ST_ID' AND FK_CLASS_ID='$CL_ID'");
	//store results
	$result = mysqli_query($con, $sql)or die(mysqli_error($con));

	$row = mysqli_fetch_array($result);

	$FGrade = $row["Final_Grade"];

	echo "<tr><td>Final Grade</td><td><form action='http://web.engr.oregonstate.edu/~brewerji/CS275/php/updategrades.php' method='POST'>
		<select name='FGrade'>";

	$sql=("SELECT Grade_Name, GRADE_STANDARD_ID FROM GradeStandard");
	//store results
	$result = mysqli_query($con, $sql)or die(mysqli_error($con));

	while($row = mysqli_fetch_array($result)){
		$GID=$row["GRADE_STANDARD_ID"];
		$GN=$row["Grade_Name"];
		
		if($GID==$FGrade){
			echo "<option value='$GID' selected='true'>$GN</option>";
		}else{
			echo "<option value='$GID'>$GN</option>";
		}
	}
	echo "</select></td>";
	echo "<input type='hidden' name='student' value='$ST_ID'/><input type='hidden' name='class' value='$CL_ID'/>";

	echo "<td><button type='submit'>Update Grade</button></form></td></tr><tr><td>Catagories</td><td>";

	echo "<form action='http://web.engr.oregonstate.edu/~brewerji/CS275/php/updateclasscat1.php' method='POST'>
		<select name='ClassCat[]' multiple>";

	$sql=("SELECT * FROM CourseCatagory");
	//store results
	$result = mysqli_query($con, $sql)or die(mysqli_error($con));

	while($row = mysqli_fetch_array($result)){
		$CAT_ID=$row["CATEGORY_ID"];
		$CAT_Name=$row["Cat_Name"];
		
		$sql=("SELECT * FROM ClassCatagories WHERE FK_CAT_ID='$CAT_ID' AND FK_CLASS_ID='$CL_ID'");
		//store results
		$result2 = mysqli_query($con, $sql)or die(mysqli_error($con));

		$row_cnt = mysqli_num_rows($result2);

		if($row_cnt>0){
			echo "<option value='$CAT_ID' selected='true'>$CAT_Name</option>";
		}else{
			echo "<option value='$CAT_ID'>$CAT_Name</option>";
		}
	}	
	
	echo "<input type='hidden' name='class' value='$CL_ID'/>";			
	echo "</select></td><td><button type='submit'>Update Class Catagories</button></form></td></tr>";


	echo "<tr><td><form action='http://web.engr.oregonstate.edu/~brewerji/CS275/php/addclasscat.php' method='POST'>";
	echo "Catagory</td><td>Weight</td></tr><tr><td><input name='catagoryAdd'/></td><td><input name='addWeight'/></td>";
	echo "<td><button name='addCat' type='submit'>Add</button></td></tr><input type='hidden' name='class' value='$CL_ID'></form>";
	
	$sql=("SELECT EVAL_CAT_ID, Eval_Catagory, Weight FROM Course_Grades WHERE FK_CLASS_ID='$CL_ID'");
	//store results
	$result = mysqli_query($con, $sql)or die(mysqli_error($con));

	echo "<form action='http://web.engr.oregonstate.edu/~brewerji/CS275/php/addclasscat.php' method='POST'>";
	echo "<input type='hidden' value='$CL_ID'>";

	while($row = mysqli_fetch_array($result)){
		$CAT_ID=$row["EVAL_CAT_ID"];
		$CAT_Name=$row["Eval_Catagory"];
		$Weight=$row["Weight"];
		
		$sql=("SELECT * FROM Course_Grades WHERE EVAL_CAT_ID='$CAT_ID' AND FK_CLASS_ID='$CL_ID'");
		//store results
		$result2 = mysqli_query($con, $sql)or die(mysqli_error($con));

		$row_cnt = mysqli_num_rows($result2);

		if($row_cnt>0){
			echo "<form action='http://web.engr.oregonstate.edu/~brewerji/CS275/php/updateclasscat.php' method='POST'>";
			echo "<tr><td><input name='Catagory' value='$CAT_Name' /></td><td>";
			echo "<input name='Weight' value='$Weight' /></td><td>";
			echo "<button name='update' value='update''>Update</button>";
			echo "</td><td><button name='update' value='delete'>Delete</button>";
			echo "<input type='hidden' name='CAT_ID' value='$CAT_ID'>";
			echo "<input type='hidden' name='class' value='$CL_ID'></form>";

		}
	}


?>
</td></tr>
			</table>
		</td>

	<tr>
		<td><a href="addassignments.php">Add Assignment</a></td>
		
	</tr>
	<tr>
		<td><a href="addclass.php">Add Class<a></td>
	</tr>
	<tr>
		<div style="height:100%"></div>
	</tr>

</table>


</body>
</html>