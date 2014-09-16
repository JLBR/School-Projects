<?php

	//Uses approach outlined at http://forums.devshed.com/php-faqs-and-stickies-167/how-to-program-a-basic-but-secure-login-system-using-891201.html

    //Connect to database
    require("sqlconnect.php");
    
    //Check for updated information form submit; if true, update happens, else form is displayed
    if(!isset($_SESSION['user']))
    {
		echo('<BR /><FIELDSET STYLE="width:775px;border:1px;border-style:solid;"><LEGEND>Edit your profile information</LEGEND>');
		echo('You must log in to edit your profile information.');
		echo('</FIELDSET>');
		exit();
    }
	else {
		//Retrieve user information
        $query = "
            SELECT *
            FROM users
            WHERE
                username = :username
        ";
		
		$username = $_SESSION['user']['username'];
        
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
            die("Failed to run user profile check.");
        }
        
		//Retrieve user profile from 'users' table.
        $row = $stmt->fetch();
        if($row)
        {
			//No action taken - actual UPDATE is in the following page
        }
        
		else
        {
            print("User profile data not found.");
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
<BR /><BR />
<FORM ID="editProfileForm" ACTION="reviewprofile.php" METHOD="POST">
	<FIELDSET style="width:775px;border:1px;border-style:solid;"><LEGEND>Edit your profile information</LEGEND>
		<INPUT TYPE="hidden" NAME="id" VALUE="<?php echo $row['id'] ?>" />
		<LABEL CLASS="field" FOR="fname">First Name&nbsp;&nbsp;</LABEL>
		<INPUT TYPE="text" NAME="fname" VALUE="<?php echo $row['fname']; ?>" />
		<BR />
		<LABEL CLASS="field" FOR="lname">Last Name&nbsp;&nbsp;</LABEL>
		<INPUT TYPE="text" NAME="lname" VALUE="<?php echo $row['lname']; ?>" />
		<BR />
		<LABEL CLASS="field" FOR="address">Address&nbsp;&nbsp;</LABEL>
		<INPUT TYPE="text" NAME="address" VALUE="<?php echo $row['address']; ?>" />
		<BR />
		<LABEL CLASS="field" FOR="city">City&nbsp;&nbsp;</LABEL>
		<INPUT TYPE="text" NAME="city" VALUE="<?php echo $row['city']; ?>" />
		<BR />
		<LABEL CLASS="field" FOR="state">State&nbsp;&nbsp;</LABEL>
		<INPUT TYPE="text" NAME="state" VALUE="<?php echo $row['state']; ?>" />
		<BR />
		<LABEL CLASS="field" FOR="zip">ZIP&nbsp;&nbsp;</LABEL>
		<INPUT TYPE="text" NAME="zip" VALUE="<?php echo $row['zip']; ?>" />
		<BR />
		<LABEL CLASS="field" FOR="phone">Phone&nbsp;&nbsp;</LABEL>
		<INPUT TYPE="text" NAME="phone" VALUE="<?php echo $row['phone']; ?>" />
		<BR />
		<LABEL CLASS="field" FOR="email">E-Mail&nbsp;&nbsp;</LABEL>
		<INPUT TYPE="text" NAME="email" VALUE="<?php echo $row['email']; ?>" />
		<BR />
		<LABEL CLASS="field" FOR="username">Username&nbsp;&nbsp;</LABEL>
		<INPUT TYPE="text" NAME="username" VALUE="<?php echo $row['username']; ?>" />
		<BR />
		<LABEL CLASS="field" FOR="password">Password&nbsp;&nbsp;</LABEL> <!-- Left blank to avoid unintended UPDATE -->
		<INPUT TYPE="password" NAME="password" VALUE="" />
		<BR />
		<INPUT TYPE="submit" VALUE="Update Information" />
	</FIELDSET>
</FORM>

<SCRIPT>
	$("#editProfileForm").validate();
</SCRIPT>

</BODY>
</HTML>