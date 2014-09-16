<?php

	//Uses approach outlined at http://forums.devshed.com/php-faqs-and-stickies-167/how-to-program-a-basic-but-secure-login-system-using-891201.html

    //Connect to database
    require("sqlconnect.php");
    
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
				salt,
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
			//Hash & salt user-supplied password, then compare against version stored in database
            $check_password = hash('sha256', check_input($_POST['password']) . $row['salt']);
			
			for($cycle = 0; $cycle < 65536; $cycle++) {
				$check_password = hash('sha256', $check_password . $row['salt']);
			}
            
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
			
            //Redirect user to home page
            header("Location: home.php");
            die("Redirecting to home.php");
        }
        else
        {
            print("Login failed.");
            
            /*
			   Automatically enter username to avoid user having to retype
			   htmlentities(), like htmlspecialchars() prevents cross-site scripting via HTML tag conversion to equivalent character entities (HTML escaping)
			   Should be used before displaying user-submitted values to users
			   -> http://en.wikipedia.org/wiki/XSS_attack
			   -> http://www.php.net/manual/en/function.htmlentities.php 
			   -> http://stackoverflow.com/questions/3129899/what-are-the-common-defenses-against-xss
			*/
            $submitted_username = htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8');
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
<SCRIPT TYPE="text/html" ID="login">
	//If login info checks out, run dologin()
</SCRIPT>

<BR>
<FORM ID="loginForm" ACTION="login.php" METHOD="POST">
	<FIELDSET style="width:775px;border:1px;border-style:solid;"><LEGEND>Enter your login information</LEGEND>
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

<SCRIPT TYPE="text/javascript" SRC="javascript/login.js"></SCRIPT>

</BODY>
</HTML>