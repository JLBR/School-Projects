<?php
	ini_set('display_errors',1); 
	 error_reporting(E_ALL);

	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<head>
	<title>Grade Tool Classes</title>
</head>
<html>
<body>

<table border="0" style='min-width:1000px'>
	<tr>
		<td rowspan="3" style="width:120px"></td>
		<td align="right" colspan="4"><a href="../login/login.php">Log Out</a></td>
	</tr>
	<tr>
		<td align="center" colspan="4">Grade Tool Classes</td>
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
			<table><form action="../php/classes.php" method="POST"><input type="hidden" name="page" value="../classes/classes.php"/>
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
	$SID = $_SESSION['SC_ID'];

	$sql=("SELECT DISTINCT Term FROM Class WHERE Term='$Term'");
	
	$result = mysqli_query($con, $sql)or die(mysqli_error($con));

	
	while($row = mysqli_fetch_array($result))
	{
		$Term = $row["Term"];
		echo "<tr><td>Term: $Term</td>";
	}

	$sql=("SELECT School_Name FROM School WHERE SCHOOL_ID='$SID'");
	
	$result = mysqli_query($con, $sql)or die(mysqli_error($con));

	
	while($row = mysqli_fetch_array($result))
	{
		$School = $row["School_Name"];
		echo "<td>School: <a href='../school/schooldetails?school=$SID'>$School</a></td>";
	}
	echo "</tr><td><select name='term'>";


	$sql=("SELECT DISTINCT Term FROM Class ");
	
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

	$sql=("SELECT * FROM School");
	//store results
	$result = mysqli_query($con, $sql)or die(mysqli_error($con));
	
	while($row = mysqli_fetch_array($result))
  	{
		$SIDL = $row["SCHOOL_ID"];
		$SName = $row["School_Name"];
		$STerm = $row["Term"];

		//school names and links
		if($SIDL==$SID){
  			echo "<option value='$SIDL' selected='true'> $SName</option>";
		}else{
		  	echo "<option value='$SIDL' > $SName</option>";
		}
	}

	echo "</select>
					</td>
					<td>
						<button type='submit'>Update List</button></form>
					</td>
				</tr>
				<tr>";
	$Term =$_SESSION['Term'];
	$SID = $_SESSION['SC_ID'];

	$sql=("SELECT * FROM Class WHERE FK_SCHOOL_ID='$SID' AND Term='$Term'");
	
	$result = mysqli_query($con, $sql)or die(mysqli_error($con));
	
	

	while($row = mysqli_fetch_array($result))
	{
		$Class = $row["Class_Name"];
		$CID = $row["CLASS_ID"];
		$ST_ID = $_SESSION["S_ID"];
		echo "<tr><td><a href='classdetails.php?class=$CID'>$Class</a></td>";
		
		$sql=("SELECT * FROM Roster WHERE FK_CLASS_ID='$CID' AND FK_STUDENT_ID='$ST_ID'");
	
		$Term =$_SESSION['Term'];
		$SID = $_SESSION['SC_ID'];

		$result2 = mysqli_query($con, $sql)or die(mysqli_error($con));		
		$row_cnt = mysqli_num_rows($result2);
		if($row_cnt==0){
			echo "<form action='http://web.engr.oregonstate.edu/~brewerji/CS275/php/enrolinclass.php' method='POST'>";
			echo "<td><button name='Enrole' value=$CID type='submit'>Enrole</button></td></tr>";
			echo "<input type='hidden' name='term' value='$Term'/><input type='hidden' name='school' value='$SID'/></form>";
		}else{
			echo "<form action='http://web.engr.oregonstate.edu/~brewerji/CS275/php/disenrolinclass.php' method='POST'>";
			echo "<td><button name='Dissenrole' value=$CID type='submit'>Dissenrole</button></td></tr>";
			echo "<input type='hidden' name='term' value='$Term'/><input type='hidden' name='school' value='$SID'/></form>";
		}
	}
	

	echo "</tr>";
?>

			</table>
		</td>

	</tr>
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