<?php
	ini_set('display_errors',1); 
	 error_reporting(E_ALL);

	session_start();
?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<head>
	<title>Grade Tool Schools</title>
</head>
<html>
<body>

<table border="0" style='min-width:1000px'>
	<tr>
		<td rowspan="3" style="width:120px"></td>
		<td align="right" colspan="4"><a href="../login/login.php">Log Out</a></td>
	</tr>
	<tr>
		<td align="center" colspan="4">Grade Tool Schools</td>
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
			<table>
				<tr><td>My Schools</td></tr>
			<?php
				$con=mysqli_connect("","","","");

				// Check connection
				if (mysqli_connect_errno())
 				{
 					 echo "Failed to connect to MySQL: " . mysqli_connect_error();
  				}
				$SID = $_SESSION["S_ID"];
				
				$sql=("SELECT DISTINCT SCHOOL_ID, School_Name 
					FROM School, Class, Roster
					WHERE SCHOOL_ID=FK_SCHOOL_ID 
						AND CLASS_ID=FK_CLASS_ID 
						AND FK_STUDENT_ID='$SID'
					ORDER BY School_Name");
					
									
				//store results
				$result = mysqli_query($con, $sql)or die(mysqli_error($con));
				while($row = mysqli_fetch_array($result))
  				{
					$SID = $row["SCHOOL_ID"];
					$SName = $row["School_Name"];
					
					//lists schools
  					echo "<tr><td align='center'><a href='schooldetails.php?school=$SID'> $SName </a></td></tr>";
  				}

			?>
			</table>
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