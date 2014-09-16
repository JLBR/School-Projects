<?php
	ini_set('display_errors',1); 
	 error_reporting(E_ALL);

	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<head>
	<title>Grade Tool Assignments</title>
</head>
<html>
<body>

<table border="0" style='min-width:1000px'>
	<tr>
		<td rowspan="3" style="width:120px"></td>
		<td align="right" colspan="4"><a href="../login/login.html">Log Out</a></td>
	</tr>
	<tr>
		<td align="center" colspan="4">Grade Tool Assignments</td>
	</tr>
	<tr>
		<td align="center" ><a href="../home/home.php">Home</a></td>
		<td align="center" ><a href="../classes/classes.php">Classes</a></td>
		<td align="center" ><a href="../school/schools.php">School</a></td>
		<td align="center" ><a href="../settings/settings.php">Settings</a></td>
	</tr>
	<tr>
		<td><a href="assignments.php">Assignments</a></td>
		<td colspan="5" rowspan="7">
			<table border='0'><form action="http://web.engr.oregonstate.edu/~brewerji/CS275/php/classes.php" method="POST">
				<tr>
					
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
	$Temp = 1;

	$sql=("SELECT DISTINCT SCHOOL_ID, School_Name
		FROM School, Class, Roster 
		WHERE Class.Term = '$Term'
			AND Class.CLASS_ID = Roster.FK_CLASS_ID
			AND Roster.FK_STUDENT_ID='$SID'
			AND Class.FK_SCHOOL_ID=School.SCHOOL_ID	");
	//store results
	$result = mysqli_query($con, $sql)or die(mysqli_error($con));
	
	while($row = mysqli_fetch_array($result))
  	{
		$SIDL = $row["SCHOOL_ID"];
		$SName = $row["School_Name"];
		
		//check that the current school is on the list for this term
		if($SIDL==$SC_ID){
  			$Temp =0;
		}
	}


	if($Temp){
		if(isset($SIDL)){
			$SC_ID = $SIDL;
		}else{
			$SC_ID=0;
		}
	}	

	echo "<tr><td>Term: $Term</td>";

	$sql=("SELECT School_Name FROM School WHERE SCHOOL_ID='$SC_ID'");
	
	$result = mysqli_query($con, $sql)or die(mysqli_error($con));
	
	while($row = mysqli_fetch_array($result))
	{
		$School = $row["School_Name"];
		echo "<td>School: <a href='../school/schooldetails?school=$SC_ID'>$School</a></td>";
	}

	echo "</tr><td><select name='term'>";

	$sql=("SELECT DISTINCT Class.Term 
		FROM Class, Roster 
		WHERE Class.CLASS_ID=Roster.FK_CLASS_ID
			AND Roster.FK_STUDENT_ID='$SID'");
	
	$result = mysqli_query($con, $sql)or die(mysqli_error($con));

	
	while($row = mysqli_fetch_array($result))
	{
		$TermL = $row["Term"];
		if($TermL==$Term){
			echo "<option value='$TermL' selected='true'>$TermL</option>";
		}else{
			echo "<option value='$TermL'>$TermL</option>";
		}
	}

	echo " </select></td><td><select name='school'>";

	$sql=("SELECT DISTINCT SCHOOL_ID, School_Name
		FROM School, Class, Roster 
		WHERE Class.Term = '$Term'
			AND Class.CLASS_ID = Roster.FK_CLASS_ID
			AND Roster.FK_STUDENT_ID='$SID'
			AND Class.FK_SCHOOL_ID=School.SCHOOL_ID	");
	//store results
	$result = mysqli_query($con, $sql)or die(mysqli_error($con));

	while($row = mysqli_fetch_array($result))
  	{
		$SIDL = $row["SCHOOL_ID"];
		$SName = $row["School_Name"];
		
		//school names and links
		if($SIDL==$SC_ID){
  			echo "<option value='$SIDL' selected='true'> $SName</option>";
		}else{
		  	echo "<option value='$SIDL' > $SName</option>";
		}
	}

	echo "</select>	<input type='hidden' name='page' value='../classes/assignments.php'/>
		</td><td><button type='submit'>Update List</button></form></td>	</tr><tr><td colspan='3'>";

	$sql=("SELECT Class.CLASS_ID, Class.Class_Name
		FROM Class, Roster 
		WHERE Class.CLASS_ID=Roster.FK_CLASS_ID
			AND Roster.FK_STUDENT_ID='$SID'
			AND Class.Term='$Term'
		ORDER BY Class.Class_Name");
	
	$result = mysqli_query($con, $sql)or die(mysqli_error($con));

	while($row = mysqli_fetch_array($result))
  	{
		$Cls_ID = $row["CLASS_ID"];
		$CName = $row["Class_Name"];
		
		//Class names
		
  		echo "<table><tr><td colspan='3'><a href='classdetails.php?class=$Cls_ID'>$CName</a></td></tr><tr>";
		echo "<td>Completed</td><td> Assignment</td><td>Catagory</td><td>Max</td><td>Actual</td></tr><tr>";


		$sql=("SELECT *
			FROM Evaluation 
			WHERE FK_STUDENT_ID = '$SID'
				AND FK_CLASS_ID ='$Cls_ID'
			ORDER BY FK_EVAL_CAT_ID	");
		//store results
		$result2 = mysqli_query($con, $sql)or die(mysqli_error($con));
			
		$row_cnt = mysqli_num_rows($result2);

		if($row_cnt==0){
			echo "<td colspan='5'>no assignments in this class</td>";
		}

		while($row2 = mysqli_fetch_array($result2))
  		{
			$EV_Name= $row2["EvalName"];
			$EV_Cat_ID =$row2["FK_EVAL_CAT_ID"];
			$EV_ID=$row2["EVAL_ID"];
			$AScore=$row2["ActualScore"];
			$MScore=$row2["MaxScore"];
			$Comp=$row2["Complete"];

			$sql=("SELECT Eval_Catagory
				FROM Course_Grades 
				WHERE EVAL_CAT_ID='$EV_Cat_ID'");
			//store results
			$result3 = mysqli_query($con, $sql)or die(mysqli_error($con));
			$row3 = mysqli_fetch_array($result3);
	
			$Cat_Name =$row3["Eval_Catagory"];			
		
			echo "<form action='http://web.engr.oregonstate.edu/~brewerji/CS275/php/updateassignment.php' method='POST'>";
			echo "<input type='hidden' value='../classes/assignments.php' name='page'/>";
			if($Comp=="Yes"){
				echo "<td><input type='checkbox' checked name='complete' value='Yes'/>";
			}else{
				echo "<td><input type='checkbox' name='complete' value='Yes'/>";
			}
			echo "</td><td> $EV_Name</td>";
			echo "<td>$Cat_Name</td><td>$MScore </td><td><input name='ascore' size='4' value='$AScore'/></td>";
			echo "<td><button type='submit' name='action' value='update'>Update</button></td>";
			echo "<td><button type='submit' name='action' value='delete'>Delete</button></td></tr>";
			echo "<input type='hidden' name='eval_id' value='$EV_ID'></form>";

		}
		echo "</table></td></tr><tr><td colspan='3'>";
	}

?>
			</td><tr></table>
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