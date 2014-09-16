<?php
	ini_set('display_errors',1); 
	 error_reporting(E_ALL);

	session_start();
?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<head>
	<title>Grade Tool Settings</title>
</head>
<html>
<body>

<table border="0" style='min-width:1000px'>
	<tr>
		<td rowspan="3" style="width:120px"></td>
		<td align="right" colspan="4"><a href="../login/login.php">Log Out</a></td>
	</tr>
	<tr>
		<td align="center" colspan="4">Grade Tool Settings</td>
	</tr>
	<tr>
		<td align="center" ><a href="../home/home.php">Home</a></td>
		<td align="center" ><a href="../classes/classes.php">Classes</a></td>
		<td align="center" ><a href="../school/schools.php">School</a></td>
		<td align="center" ><a href="../settings/settings.php">Settings</a></td>
	</tr>
	<tr>
		<td></td>
		<td colspan="4" rowspan="7">
			<table><form action="http://web.engr.oregonstate.edu/~brewerji/CS275/php/deleteaccount.php" method="POST">
			<tr><td>You are currently logged in as:
			</tr></td>
			<?php
				$FName = $_SESSION["FName"];
				$LName = $_SESSION["LName"];
				//$SID = $_SESSION["S_ID"];
				echo "<tr><td>$FName $LName</td></tr>";
				echo "<tr><td><button type='submit'>Delete Account</button>";

			?>
			<form></table>
		</td>
	</tr>
	<tr>
		<div style="height:100%"></div>
	</tr>
</table>


</body>
</html>