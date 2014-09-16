<?php
	ini_set('display_errors',1); 
	 error_reporting(E_ALL);

	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<head>
	<title>Grade Tool Home</title>
</head>
<html>
<body>

<table border="0" style='min-width:1000px'>
	<tr>
		<td rowspan="3" style="width:120px"></td>
		<td align="right" colspan="4"><a href="../login/login.php">Log Out</a></td>
	</tr>
	<tr>
		<td align="center" colspan="4">Grade Tool Home</td>
	</tr>
	<tr>
		<td align="center" ><a href="../home/home.php">Home</a></td>
		<td align="center" ><a href="../classes/classes.php">Classes</a></td>
		<td align="center" ><a href="../school/schools.php">School</a></td>
		<td align="center" ><a href="../settings/settings.php">Settings</a></td>
	</tr>
	<tr>
		<td><a href="../classes/assignments.php">Assignments</a></td>
		<td colspan="4" rowspan="7">
		<table border='0'><form action="http://web.engr.oregonstate.edu/~brewerji/CS275/php/classes.php" method="POST">
				<input type='hidden' name='page' value='home.php'/>
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

	$SIDL=1;
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
		$SC_ID = $SIDL;
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

	echo "</select>	<input type='hidden' name='page' value='../home/home.php'/>
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
		
		echo "<table border='0'><tr><td colspan='14'><a href='../classes/classdetails.php?class=$Cls_ID'>$CName</a></td></tr>";
		
		echo "<td colspan='2'>";
		echo "<table><tr><td colspan='2'></p></tr>
			<tr><td>Catagory</td><td>Weight</td></tr>";

//catagory1
		$sql=("SELECT Eval_Catagory, Weight, EVAL_CAT_ID
			FROM Course_Grades
			WHERE FK_CLASS_ID='$Cls_ID'");
		$result2 = mysqli_query($con, $sql)or die(mysqli_error($con));

		//used for debugging
		//$row_cnt = mysqli_num_rows($result2);

		$index=0;

		while($row2 = mysqli_fetch_array($result2)){
			$EV_CAT=$row2["Eval_Catagory"];
			$Weight=$row2["Weight"];
			$EV_ID=$row2["EVAL_CAT_ID"];
			
			$AWeight[$index]=$Weight;

			$sql=("SELECT SUM(ActualScore) AS ASc2
				FROM Evaluation
				WHERE FK_CLASS_ID='$Cls_ID'
					AND FK_STUDENT_ID='$SID'
					AND Complete='Yes'
					AND FK_EVAL_CAT_ID='$EV_ID'");

			$result3 = mysqli_query($con, $sql)or die(mysqli_error($con));
			$row3= mysqli_fetch_array($result3);
			
			$row_cnt = mysqli_num_rows($result3);

			if($row_cnt>0){
				$CAS[$index]= $row3["ASc2"];//Completed catagory
			}else{
				$CAS[$index]=0;
			}
			
			$sql=("SELECT SUM(MaxScore) AS CMax
				FROM Evaluation
				WHERE FK_CLASS_ID='$Cls_ID'
					AND FK_STUDENT_ID='$SID'
					AND Complete='Yes'
					AND FK_EVAL_CAT_ID='$EV_ID'");

			$result3 = mysqli_query($con, $sql)or die(mysqli_error($con));
			$row3= mysqli_fetch_array($result3);

			$row_cnt = mysqli_num_rows($result3);

			if($row_cnt>0){
				$CMax[$index] =	$row3["CMax"];//Max Completed
			}else{
				$CMax[$index]=0;
			}
			
			

			$sql=("SELECT SUM(ActualScore) AS ASc2
				FROM Evaluation
				WHERE FK_CLASS_ID='$Cls_ID'
					AND FK_STUDENT_ID='$SID'
					AND Complete='No'
					AND FK_EVAL_CAT_ID='$EV_ID'");

			$result4 = mysqli_query($con, $sql)or die(mysqli_error($con));
			$row4= mysqli_fetch_array($result4);
			

			$row_cnt = mysqli_num_rows($result4);

			if($row_cnt>0){
				$UAS[$index]= $row4["ASc2"];//uncompleted catagory
			}else{
				$UAS[$index]=0;
			}	

			$sql=("SELECT SUM(MaxScore) AS CMax
				FROM Evaluation
				WHERE FK_CLASS_ID='$Cls_ID'
					AND FK_STUDENT_ID='$SID'
					AND Complete='No'
					AND FK_EVAL_CAT_ID='$EV_ID'");

			$result4 = mysqli_query($con, $sql)or die(mysqli_error($con));
			$row4= mysqli_fetch_array($result4);
			

			$row_cnt = mysqli_num_rows($result4);

			if($row_cnt>0){
				$UMax[$index] =	$row4["CMax"];//max uncompleted
			}else{
				$UMax[$index] =0;
			}

			$index = $index+1;
			echo "<tr><td>$EV_CAT </td><td>$Weight</td></tr>";
		}

		$sql=("SELECT SUM(Weight) AS TotalWeight
			FROM Course_Grades
			WHERE FK_CLASS_ID='$Cls_ID'");
		$result2 = mysqli_query($con, $sql)or die(mysqli_error($con));

		$row2 = mysqli_fetch_array($result2);
		$TWeight=$row2["TotalWeight"];
		echo "<tr><td>Total </td><td>$TWeight</td></tr></table>";

//current
		$SMax=0;		
		$SAct=0;
		$SWeighted =0;

		echo "<td colspan='3'>";
		echo "<table><tr><td colspan='3'>Current</td></tr>
			<tr><td>Max</td><td>Actual</dt><td>Weighted</td></tr>";

		for($index2=0; $index2<$index;$index2 +=1){

			$SMax= $SMax +$CMax[$index2];//total max
			$SAct= $SAct +$CAS[$index2];//completed score

			if($SMax>0){
				$SWeighted =(($CAS[$index2]/$CMax[$index2])*$AWeight[$index2]);
				$SWeighted =round($SWeighted, 2);
			}else{
				$SWeighted=0;
			}

			echo "<tr><td>$CMax[$index2]</td><td>$CAS[$index2]</td><td>$SWeighted</td></tr>";
		}

		if($SMax>0){
			$SWeighted =(($SAct/$SMax)*100);
			$SWeighted =round($SWeighted, 2);
		}else{
			$SWeighted=0;
		}

		echo "<tr><td>$SMax </td><td>$SAct</td><td>$SWeighted</td></tr></table>";

//Current+Max	

		$SMax=0;		
		$SAct=0;
		$SWeighted =0;

		echo "<td colspan='3'>";
		echo "<table border ='0'><tr><td colspan='3'>Currrent + Max Remaining</td></tr>
			<tr><td>Max</td><td>Min Remaining</td><td>Weighted</td></tr>";

		
		for($index2=0; $index2<$index;$index2 +=1){

			$TMax= $CMax[$index2] + $UMax[$index2];//total max
			$SMax= $SMax + $TMax;
			$TAS = $CAS[$index2]+$UMax[$index2];//completed + max incomplete score
			$SAct= $SAct +$TAS;

			if($SMax>0){
				$SWeighted =((($CAS[$index2]+$UMax[$index2])/($CMax[$index2] + $UMax[$index2]))*$AWeight[$index2]);
				$SWeighted =round($SWeighted, 2);
			}else{
				$SWeighted=0;
			}

			echo "<tr><td>$TMax</td><td>$TAS</td><td>$SWeighted</td></tr>";
		}

		if($SMax>0){
			$SWeighted =(($SAct/$SMax)*100);
			$SWeighted =round($SWeighted, 2);
		}else{
			$SWeighted=0;
		}

		echo "<tr><td>$SMax </td><td>$SAct</td><td>$SWeighted</td></tr></table>";


//curren+0

		$SMax=0;		
		$SAct=0;
		$SWeighted =0;

		echo "<td colspan='3'>";
		echo "<table><tr><td colspan='3'>Current + 0 Remaining</td></tr>
			<tr><td>Max</td><td>Max Remaining</td><td>Weighted</td></tr>";

		for($index2=0; $index2<$index;$index2 +=1){

			$TMax= $CMax[$index2] + $UMax[$index2];//total max
			$SMax= $SMax + $TMax;
			$TAS = $CAS[$index2];//completed 
			$SAct= $SAct +$TAS;			

			if($SMax>0){
				$SWeighted =(($TAS/$TMax)*$AWeight[$index2]);
				$SWeighted =round($SWeighted, 2);
			}else{
				$SWeighted=0;
			}

			echo "<tr><td>$TMax</td><td>$TAS</td><td>$SWeighted</td></tr>";
		}

		if($SMax>0){
			$SWeighted =(($SAct/$SMax)*100);
			$SWeighted =round($SWeighted, 2);
		}else{
			$SWeighted=0;
		}

		echo "<tr><td>$SMax </td><td>$SAct</td><td>$SWeighted</td></tr></table>";

//Current + Expected

		$SMax=0;		
		$SAct=0;
		$SWeighted =0;

		echo "<td colspan='3'>";
		echo "<table><tr><td colspan='3'>Current + Expected</td></tr>
			<td>Max</td><td>Expected</td><td>Weighted</td></tr>";

		for($index2=0; $index2<$index;$index2 +=1){

			$TMax= $CMax[$index2] + $UMax[$index2];//total max
			$SMax= $SMax + $TMax;
			$TAS = $CAS[$index2]+$UAS[$index2];//completed + incomplete expected
			$SAct= $SAct +$TAS;			

			if($SMax>0){
				$SWeighted =(($TAS/$TMax)*$AWeight[$index2]);
				$SWeighted =round($SWeighted, 2);
			}else{
				$SWeighted=0;
			}

			echo "<tr><td>$TMax</td><td>$TAS</td><td>$SWeighted</td></tr>";
		}

		if($SMax>0){
			$SWeighted =(($SAct/$SMax)*100);
			$SWeighted =round($SWeighted, 2);
		}else{
			$SWeighted=0;
		}

		echo "<tr><td>$SMax </td><td>$SAct</td><td>$SWeighted</td></tr></table>";



		$sql=("SELECT *
			FROM Evaluation 
			WHERE FK_STUDENT_ID = '$SID'
				AND FK_CLASS_ID ='$Cls_ID'
			ORDER BY FK_EVAL_CAT_ID	");
		//store results
		$result2 = mysqli_query($con, $sql)or die(mysqli_error($con));
			
		$row_cnt = mysqli_num_rows($result2);

		echo "<tr><td colspan='14'><table>";

		if($row_cnt==0){
			echo "<td colspan='1'>no assignments in this class</td>";
		}else{
			echo "<tr><td>Completed</td><td> Assignment</td><td>Catagory</td><td>Max</td><td>Actual/Expected</td></tr><tr>";
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
			if($Comp=="Yes"){
				echo "<td><input type='checkbox' checked name='complete' value='Yes'/>";
			}else{
				echo "<td><input type='checkbox' name='complete' value='Yes'/>";
			}
			echo "</td><td> $EV_Name</td>";
			echo "<td>$Cat_Name</td><td>$MScore </td><td><input name='ascore' size='4' value='$AScore'/></td>";
			echo "<td><button type='submit' name='action' value='update'>Update</button></td>";
			echo "<td><button type='submit' name='action' value='delete'>Delete</button></td></tr>";
			echo "<input type='hidden' name='eval_id' value='$EV_ID'><input type='hidden' name='page' value='../home/home.php'></form>";

		}

		echo "</tr></table>";
		echo "</table></td></tr><tr><td colspan='3'>";
	}

?>
			</tr></table>
		</td>

	</tr>
	<tr>
		<td><a href="../school/schools.php">My Schools</td>
	</tr>
	<tr>
		<div style="height:100%"></div>
	</tr>
</table>


</body>
</html>