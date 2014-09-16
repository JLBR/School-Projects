<?php
	ini_set('display_errors',1); 
	 error_reporting(E_ALL);

	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<head>
	<title>Grade Tool Add Assignment</title>
</head>
<html>
<body>

<table border="0" style='min-width:1000px'>
	<tr>
		<td rowspan="3" style="width:120px"></td>
		<td align="right" colspan="4"><a href="../login/login.html">Log Out</a></td>
	</tr>
	<tr>
		<td align="center" colspan="4">Grade Tool Add Assignment</td>
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

	ini_set('display_errors',1); 
	 error_reporting(E_ALL);


	$con=mysqli_connect("","","","");

	// Check connection
	if (mysqli_connect_errno())
	  {
	  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  }

	$Term =$_SESSION['Term'];
	$SID = $_SESSION['S_ID'];
	$SC_ID =$_SESSION["SC_ID"];
	$SClass=$_SESSION["Class"];
	$Temp=1;

	$sql=("SELECT DISTINCT Class.Term 
		FROM Class, Roster 
		WHERE Class.CLASS_ID=Roster.FK_CLASS_ID
			AND Roster.FK_STUDENT_ID='$SID'");
	
	$result = mysqli_query($con, $sql)or die(mysqli_error($con));

	echo "<tr><td><form action='../php/classes.php' method='POST'><input type='hidden' name='school' value='$SC_ID'/>
		<select name='term'>";

	while($row = mysqli_fetch_array($result))
	{
		$TermL = $row["Term"];
		if($TermL==$Term){
			echo "<option value='$TermL' selected='true'>$TermL</option>";
			$Temp=0;
		}else{
			echo "<option value='$TermL'>$TermL</option>";
		}
	}

	if($Temp){
		$Term = $TermL;
	}

	echo "</select><input type='hidden' name='page' value='../classes/addassignments.php'/></td>";
	echo "<td><button type='submit'>Change Term</button></form></td></tr><tr><td>";

	$sql=("SELECT DISTINCT Class.CLASS_ID, Class.Class_Name, Class.Course_ID  
		FROM Class, Roster 
		WHERE Class.Term = '$Term'
			AND Class.CLASS_ID = Roster.FK_CLASS_ID
			AND Roster.FK_STUDENT_ID='$SID'	");
	
	$result = mysqli_query($con, $sql)or die(mysqli_error($con));

	echo "<tr><td><form action='../php/addassignmentsCLChange.php' method='POST'><select name='changeclass'>";
	while($row = mysqli_fetch_array($result))
	{
		$Class = $row["Class_Name"];
		$CL_ID = $row["CLASS_ID"];
		$Cour_ID=$row["Course_ID"];

		echo "<option value='$CL_ID'>$Cour_ID $Class</option>";
	}
	echo "</select></td><td><button type='submit'>Change Class</button></form></td></tr>";

	if(!empty($SClass)){
		$sql=("SELECT Course_ID, Class_Name FROM Class WHERE CLASS_ID='$SClass'");
	
		$result = mysqli_query($con, $sql)or die(mysqli_error($con));
		$row = mysqli_fetch_array($result);
		$CName=$row["Class_Name"];
		$Cour_ID=$row["Course_ID"];		

		echo "<tr><td>Class: <a href='classdetails.php?class=$SClass'>$Cour_ID $CName</a></td></tr>";
	}else{
		echo "<tr><td>Please select a class from above</td></tr>";
	}
	
	echo "<form action='../php/addnewassignment.php' method='POST'>";
	echo "<tr><td>Assignment Name <input name='assignmentName'/></td><td>Assignment Catagory</td>";
	echo "<td><select name='assignmentCat'>";

	$sql=("SELECT EVAL_CAT_ID, Eval_Catagory  
		FROM Course_Grades 
		WHERE FK_CLASS_ID = '$SClass'");
	
	$result = mysqli_query($con, $sql)or die(mysqli_error($con));

	while($row = mysqli_fetch_array($result))
	{
		$EV_ID = $row["EVAL_CAT_ID"];
		$EV_Cat = $row["Eval_Catagory"];

		echo "<option value='$EV_ID'>$EV_Cat</option>";
	}
	
	echo "</select><input type='hidden' name='class' value='$SClass'";
?>
					</select></td>
				</tr>
				<tr>
					<td>
						Max Score <input name="maxscore"/>
					</td>
				</tr>
				<tr>
					<td>
						<button type='submit'>Add new assignment</button></form>
					</td>
				</tr>
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