<?php

	//Connect to database
    require("sqlconnect.php");
    
    //Check for user credential submit
    if(!empty($_POST))
    {
		$username = check_input($_POST['username']);
		
		//Set up query to retrieve user information. Test 1, using username as first factor.
        $query = "
            SELECT *
            FROM users
            WHERE
                username = :username
        ";
		
		//Search parameter
        $query_params = array(
            ':username' => $username
        );
		
		try
        {
            //Execute query
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch(PDOException $ex)
        {
			print_r($db->errorInfo());
			echo "<P>";
			print_r($stmt->errorInfo());
            die("User data not found.");
        }
		
        //Status depends on whether user credentials match information from database or not
        $delete_ok = false;
        
		//Storing retrieved information
        $row = $stmt->fetch();
        if($row)
        {
			//Hash & salt user-supplied password, then compare against version stored in database
            $check_password = hash('sha256', check_input($_POST['password']) . $row['salt']);
			
			for($cycle = 0; $cycle < 65536; $cycle++) {
				$check_password = hash('sha256', $check_password . $row['salt']);
			}
			
			if($check_password === $row['password'])
            {
                //Hashed, salted user-supplied password matches password from db? Test 2, using comparison of passwords as second factor.
                $delete_ok = true;
				$id = $row['id'];
            }
        }
        
		//Authorization of deletion.
        if($delete_ok)
        {
			//Set up query to delete user information.
			$query = "
				DELETE FROM users
				WHERE
					id = :id
			";
		
			//Search parameter
			$query_params = array(
				':id' => $id
			);
			
			try
			{
				//Execute query
				$stmt = $db->prepare($query);
				$result = $stmt->execute($query_params);
			}
			catch(PDOException $ex)
			{
				print_r($db->errorInfo());
				echo "<P>";
				print_r($stmt->errorInfo());
				die("Account deletion not performed.");
			}
        }
		else {
			die("Account deletion not performed.");
		}		
		
		//Redirects user after deleting
        header("Location: deleted.htm");
        die("Redirecting to deleted.htm");
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

<BR>
<FORM ID="deleteUserForm" ACTION="deleteuser.php" METHOD="POST">
	<FIELDSET style="width:775px;border:1px;border-style:solid;"><LEGEND>Delete Your Account</LEGEND>
		Please enter your username and password here to confirm you wish to delete your account.<BR /><BR />
		This is an irreversible process. <BR /><BR />
		<LABEL FOR="username">Username</LABEL><BR />
		<INPUT TYPE="text" NAME="username" VALUE="" />
		<BR /><BR />
		<LABEL FOR="password">Password</LABEL><BR />
		<INPUT TYPE="password" NAME="password" VALUE="" />
		<BR /><BR />
		<INPUT TYPE="submit" VALUE="Confirm Deletion" />
	</FIELDSET>
</FORM>

<SCRIPT>
	$("#deleteUserForm").validate();
</SCRIPT>

</BODY>
</HTML>