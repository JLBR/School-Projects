<?php

	//Uses approach outlined at http://forums.devshed.com/php-faqs-and-stickies-167/how-to-program-a-basic-but-secure-login-system-using-891201.html

    //Connect to database
    include '../db/connectPDO.php';
    
    //Maintain username even if password is incorrect, to prevent user having to retype
    $submitted_username = '';
    
    //Check for login form submit; if true, login happens, else form is displayed
    if(!empty($_POST))
    {
        //Retrieve user information
        $query = "
            SELECT
                id,
                username,
                password,
                email
            FROM users
            WHERE
                username = :username
        ";
		
		$username = check_input($_POST['username']);
        
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
            die("Failed to run query 1.");
        }
        
		//Status depends on whether user logged in successfully or not
        $login_ok = false;
        
		//Retrieve user info from database table. A return value of false == username/password not recognized
        $row = $stmt->fetch();
        if($row)
        { 
			//Hash user-supplied password, then compare against version stored in database
            $check_password = hash('sha256', check_input($_POST['password']));
            
            if($check_password === $row['password'])
            {
                //Hashed user-supplied password matches password from db
                $login_ok = true;
            }
        }
        
		//Successful login == user allowed to profile; else return to login form with a "login failed" message
        if($login_ok)
        {
			//Remove password information, before putting rest of $row values into $_SESSION
			unset($row['password']);
            $_SESSION['user'] = $row;
			
            //Redirect user to inventory page
            header("Location: http://web.engr.oregonstate.edu/~malonjoh/CS419/inventory.html");
            die("Redirecting to: inventory.html");
        }
        else
        {
            print("Login Failed.");
            
            //Automatically enter username to avoid user having to retype
			//htmlentities(), like htmlspecialchars() prevents cross-site scripting and should be used before displaying user-submitted values to users
			//(http://en.wikipedia.org/wiki/XSS_attack)
            $submitted_username = htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8');
        }
    }
	
	function check_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		return $data;
	}
    
?>
<HTML>
<HEAD>
<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/HTML; charset=iso-8859-1" />

<SCRIPT TYPE="text/javascript" SRC="jquery-1.10.2.min.js"></SCRIPT>
<SCRIPT TYPE="text/javascript" SRC="jquery.validate.min.js"></SCRIPT>
<SCRIPT TYPE="text/javascript" SRC="http://cdnjs.cloudflare.com/ajax/libs/knockout/2.3.0/knockout-min.js"></SCRIPT>

<LINK HREF="http://web.engr.oregonstate.edu/~malonjoh/css/bootstrap.min.css" REL="stylesheet" />
</HEAD>

<BODY>
<SCRIPT TYPE="text/html" ID="login">
	//If login info checks out, run dologin() - IMPLEMENTATION AND ASSOC PARTS INCORRECTLY DISPLAY ERROR MESSAGE WITHOUT USERNAME AND PASSWORD
</SCRIPT>

<FORM ID="loginForm" ACTION="login.php" METHOD="POST">
	<FIELDSET><LEGEND>Login</LEGEND>
		<LABEL FOR="username">Username</LABEL><BR />
		<INPUT TYPE="text" NAME="username" VALUE="<?php echo $submitted_username; ?>" />
		<BR /><BR />
		<LABEL FOR="password">Password</LABEL><BR />
		<INPUT TYPE="password" NAME="password" VALUE="" />
		<BR /><BR />
		<INPUT TYPE="submit" VALUE="Login" DATA-BIND="click: login" />
	</FIELDSET>
</FORM>

<SCRIPT>
	$("#loginForm").validate();
</SCRIPT>

<SCRIPT TYPE="text/javascript" SRC="http://web.engr.oregonstate.edu/~malonjoh/CS419/javascript/login.js"></SCRIPT>

</BODY>
</HTML>