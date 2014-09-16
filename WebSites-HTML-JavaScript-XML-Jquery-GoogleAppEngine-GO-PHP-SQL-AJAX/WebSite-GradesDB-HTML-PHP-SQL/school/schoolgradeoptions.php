<?php
	ini_set('display_errors',1); 
	 error_reporting(E_ALL);

	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<head>
	<title>Grade Tool Grade Options</title>
</head>
<html>
<body>

<table border="0" style='min-width:1000px'>
	<tr>
		<td rowspan="3" style="width:120px"></td>
		<td align="right" colspan="4"><a href="../login/login.html">Log Out</a></td>
	</tr>
	<tr>
		<td align="center" colspan="4">Grade Tool Grade Options</td>
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
			<table><form action="http://web.engr.oregonstate.edu/~brewerji/CS275/php/addnewgradeoption.php" method="POST"> 
				<tr>
					<td>
						Grade Name
					</td>
					<td>
						<input name='GName' type="text"/>
					</td>
					<td>
						Grade Point
					</td>
					<td>
						<input name='GP' type="text"/>
					</td>
				</tr>
				<tr>
					<td>
						Add New Catagory
					</td>
					<td>
						<input name='Catagory' type="text"/>
					</td>
				</tr>
				<tr>
					<td>
						Or select from the following	
					</td>
					<td>
						<select name="slectCat">
<?php
	ini_set('display_errors',1); 
	 error_reporting(E_ALL);

	$con=mysqli_connect("","","","");

	// Check connection
	if (mysqli_connect_errno())
	  {
	  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  }
	//get all catagories
	$sql=("SELECT DISTINCT GradeCatagory FROM GradeStandard");
	
	$result = mysqli_query($con, $sql)or die(mysqli_error($con));
	
	while($row = mysqli_fetch_array($result))
	{
		$GCat = $row["GradeCatagory"];
		echo "<option value='$GCat'>$GCat</option>";
	}


?>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						Credit or No Credit
					</td>
					<td>
						<input name="CR" type="checkbox" value="CR"/>
					</td>
					<td>
						<button type="submit">Add</button>
					</td>
				</tr>
				<tr>

				</tr>
			</form></table>
			<table><form action="http://web.engr.oregonstate.edu/~brewerji/CS275/php/deletegradeoption.php" method="POST">
				<tr>
				</tr>
<?php
	$con=mysqli_connect("oniddb.cws.oregonstate.edu","brewerji-db","C3tF5wo5tOCKiNk3","brewerji-db");

	if (mysqli_connect_errno())
 	{
 		 echo "Failed to connect to MySQL: " . mysqli_connect_error();
  	}

	$sql=("SELECT * FROM GradeStandard");
	//store results
	$result = mysqli_query($con, $sql)or die(mysqli_error($con));
	
	while($row = mysqli_fetch_array($result))
  	{
		$GName = $row["Grade_Name"];
		$GP = $row["Grade_Point"];
		$Cat = $row["GradeCatagory"];
		$CR = $row["CR_NC"];
		$GID= $row["GRADE_STANDARD_ID"];

		//Grade options
  		echo "<tr><td>$GName $GP $Cat $CR</td>";
		//delete button
		echo "<td><button name='Delete' value='$GID' type='submit'>Delete</button></td></tr>";
	}
	
?>
			</form></table>
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