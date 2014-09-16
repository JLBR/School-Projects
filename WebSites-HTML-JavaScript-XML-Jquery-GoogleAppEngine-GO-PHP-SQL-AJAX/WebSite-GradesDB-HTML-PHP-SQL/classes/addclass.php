<?php
	ini_set('display_errors',1); 
	 error_reporting(E_ALL);

	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<head>
	<title>Grade Tool Add Class</title>
</head>
<html>
<body>

<table border="0" style='min-width:1000px'>
	<tr>
		<td rowspan="3" style="width:120px"></td>
		<td align="right" colspan="4"><a href="../login/login.html">Log Out</a></td>
	</tr>
	<tr>
		<td align="center" colspan="4">Grade Tool Add Class</td>
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
			<table><form action="http://web.engr.oregonstate.edu/~brewerji/CS275/php/addnewclass.php" method="POST">
				<tr>
					<td>
						Name
					</td>
					<td>
						<input name="CName" type="text"/>
					</td>
					<td>
						Course ID
					</td>
					<td>
						<input name="CID" type="text"/>
					</td>
				</tr>
				<tr>
					<td>
						Term
					</td>
					<td>
						<input name="Term" type="text"/>
					</td>
					<td>
						Units
					</td>
					<td>
						<input name="Units" type="text"/>
					</td>
				</tr>
				<tr>
					<td>Add a new catagory or use the dropdown selection</td>
					<td><input name="TCat"></input>
					</td>
				</tr>
				<tr>
					<td>
						Catagories
					</td>
					<td>
						<select name="CCat">
						
<?php

	ini_set('display_errors',1); 
	 error_reporting(E_ALL);

	$con=mysqli_connect("","","","");

	// Check connection
	if (mysqli_connect_errno())
	  {
	  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  }

	$sql=("SELECT DISTINCT Cat_Name FROM CourseCatagory ");
	
	$result = mysqli_query($con, $sql)or die(mysqli_error($con));

	
	while($row = mysqli_fetch_array($result))
	{
		$GCat = $row["Cat_Name"];
		echo "<option value='$GCat'>$GCat</option>";
	}

	echo "</select></td><td><button type='submit'>Add Class</button></td></tr><tr>";
	echo "<td>School</td><td><select name='School'>";

	$sql=("SELECT * FROM School");
	//store results
	$result = mysqli_query($con, $sql)or die(mysqli_error($con));
	
	while($row = mysqli_fetch_array($result))
  	{
		$SID = $row["SCHOOL_ID"];
		$SName = $row["School_Name"];
		$STerm = $row["Term"];

		//school names and links
  		echo "<option value='$SID'> $SName</option>";
	}
?>
						
						
					
					
			</select></td></tr>
			</form></table>
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