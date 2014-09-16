<?php

    //Connect to database
    require("sqlconnect.php");
    
	//Check for updating form submit
    if(!empty($_POST))
    {
		$id = check_input($_POST['id']);
		$fname = ucwords(strtolower(check_input($_POST['fname'])));
		$lname = ucwords(strtolower(check_input($_POST['lname'])));
		$address = check_input($_POST['address']);
		$city = ucwords(strtolower(check_input($_POST['city'])));
		$state = check_input($_POST['state']);
		$zip = check_input($_POST['zip']);
		$phone = check_input($_POST['phone']);
		$email = check_input($_POST['email']);
		$username = check_input($_POST['username']);
		$password = check_input($_POST['password']);
		
		if(empty($password)) {
			//Update all but $password and $salt.
			//$password must be omitted, otherwise value in database overwritten with empty string from empty field in form from editprofile.php
			//$salt must be unchanged, otherwise login functionality compromised
			$query = "
				UPDATE users
				SET fname = :fname,
				lname = :lname,
				address = :address,
				city = :city,
				state = :state,
				zip = :zip, 
				phone = :phone,
				email = :email,
				username = :username
				WHERE id = :id
			";
		
			//Replacing placeholders with user-supplied values for SQL query
			$query_params = array(
				':id' => $id,
				':fname' => $fname,
				':lname' => $lname,
				':address' => $address,
				':city' => $city,
				':state' => $state,
				':zip' => $zip,
				':phone' => $phone,
				':email' => $email,
				':username' => $username
			);
		
			try
			{
				//Execute query to update user information
				$stmt = $db->prepare($query);
				$result = $stmt->execute($query_params);
			}
			catch(PDOException $ex)
			{
				print_r($db->errorInfo());
				echo "<P>";
				print_r($stmt->errorInfo());
				echo "<P>";
				print_r($ex->getMessage());
				die("Failed to update user profile information.");
			}
		}
		else {
			//Recompute salt - exact same approach as in register.php
			$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
			$password = hash('sha256', $password . $salt);
			for($cycle = 0; $cycle < 65536; $cycle++) {
				$password = hash('sha256', $password . $salt);
			}

			//Update ALL user information
			$query = "
				UPDATE users
				SET fname = :fname,
				lname = :lname,
				address = :address,
				city = :city,
				state = :state,
				zip = :zip, 
				phone = :phone,
				email = :email,
				username = :username,
				password = :password,
				salt = :salt
				WHERE id = :id
			";
		
			//Replacing placeholders with user-supplied values for SQL query
			$query_params = array(
				':id' => $id,
				':fname' => $fname,
				':lname' => $lname,
				':address' => $address,
				':city' => $city,
				':state' => $state,
				':zip' => $zip,
				':phone' => $phone,
				':email' => $email,
				':username' => $username,
				':password' => $password,
				':salt' => $salt
			);
		
			try
			{
				//Execute query to update user information
				$stmt = $db->prepare($query);
				$result = $stmt->execute($query_params);
			}
			catch(PDOException $ex)
			{
				print_r($db->errorInfo());
				echo "<P>";
				print_r($stmt->errorInfo());
				echo "<P>";
				print_r($ex->getMessage());
				die("Failed to update user profile information.");
			}
		}
	}
	
    //Prevent cross-site scripting, trim whitespace, remove backslashes
	function check_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlentities($data, ENT_QUOTES, 'UTF-8');
		return $data;
	}
    
?>

<HTML>
<HEAD>
<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/HTML; charset=iso-8859-1" />

<SCRIPT TYPE="text/javascript" SRC="http://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.11.1/jquery.validate.min.js"></SCRIPT>
<SCRIPT TYPE="text/javascript" SRC="http://cdnjs.cloudflare.com/ajax/libs/knockout/2.3.0/knockout-min.js"></SCRIPT>
<SCRIPT TYPE="text/javascript" SRC="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></SCRIPT>

<LINK REL="stylesheet" TYPE="text/css" HREF="css/style.css">
</HEAD>

<BODY>
	<h1> Inventory Tracker & Community Loan Network </h1>
	<div>
		<table style="border:none" align="center">
		<tr><td width="200px" class="menubar">
			<a href="home.php" style="color:inherit; text-decoration: none">Home</a>
		</td><td width="200px" class="menubar">
			<a href="inventory.php" style="color:inherit; text-decoration: none">Manage my inventory</a>
		</td><td width="200px" class="menubar">
			<a href="loans3.php" style="color:inherit; text-decoration: none">Items I'm lending</a>
		</td><td width="200px" class="menubar">
			<a href="index.php" style="color:inherit; text-decoration: none">Account info</a>
		</td></tr>
		<tr><td width="200px" class="menubar">
			<a href="loans.php" style="color:inherit; text-decoration: none">All loanable items</a>
		</td><td width="200px" class="menubar">
			<a href="requests.php" style="color:inherit; text-decoration: none">Item requests</a>
		</td><td width="200px" class="menubar">
			<a href="loans2.php" style="color:inherit; text-decoration: none">Items I'm borrowing</a>
		</td><td width="200px" class="menubar">
			<a href="logout.php" style="color:inherit; text-decoration: none">Log out</a>
		</td></tr>
		</table>
	</div>
	
	<br>
	
	<BR>
	<FIELDSET style="width:775px;border:1px;border-style:solid;"><LEGEND>Updated Profile</LEGEND>
	<!-- Data displayed here by echo already made safe above by htmlentities() -->
		<LABEL FOR="fname">First Name</LABEL> - <?php echo $fname; ?><BR /><BR />
		<LABEL FOR="lname">Last Name</LABEL> - <?php echo $lname; ?><BR /><BR />
		<LABEL FOR="address">Address</LABEL> - <?php echo $address; ?><BR /><BR />
		<LABEL FOR="city">City</LABEL> - <?php echo $city; ?><BR /><BR />
		<LABEL FOR="state">State</LABEL> - <?php echo $state; ?><BR /><BR />
		<LABEL FOR="zip">ZIP</LABEL> - <?php echo $zip; ?><BR /><BR />
		<LABEL FOR="phone">Phone</LABEL> - <?php echo $phone; ?><BR /><BR />
		<LABEL FOR="email">E-Mail</LABEL> - <?php echo $email; ?><BR /><BR />
		<LABEL FOR="username">Username</LABEL> - <?php echo $username; ?>
	</FIELDSET>

	<BR /><BR />Please click <A HREF="index.php">here</A> to return to the main page.
	
</BODY>
</HTML>