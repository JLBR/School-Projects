<?php

	//Connect to database
    require("sqlconnect.php");
    
    //Check for updated information form submit; if true, update happens, else form is displayed
    if(!empty($_POST))
    {
		$username = check_input($_POST['username']);
		
		//Check user exists
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
            die("Failed to run user existence check.");
        }
        
		//Retrieve username from 'users' table.
        $row = $stmt->fetch();
        if($row)
        {
			$tempPass = uniqid();
			$sender = "InventoryLoanWebmaster@gmail.com";
			$recipient = $row['email'];
			$subject = "Temporary Password";
			$message = "Here is your temporary password: " . $tempPass;
			$headers = "From: " . $sender;
			mail($recipient, $subject, $message);
			echo("<FIELDSET><LEGEND>Temporary Password</LEGEND>");
			printf("Your temporary password has been mailed to the e-mail address provided during account creation.");
			echo("</FIELDSET>");
			echo('<BR /><BR />Please return to the <A HREF="index.php">main page</A> to change your password via the Edit Profile tab.');
        }
        
		else
        {
			echo("<FIELDSET><LEGEND>Temporary Password</LEGEND>");
            print("Username not found.");
			echo("</FIELDSET>");
			echo('<BR />Please return to the <A HREF="index.php">main page</A> to try again.');
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
</HTML>