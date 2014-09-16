<?php

	/*
	
	Concerns
	bindColumn() doesn't work and returns an SQLSTATE error code HY093, no parameters are actually bound.
		->Don't understand this as all the variables used have values and I appear to be following correct syntax from Jimmy's input and from
		  http://www.php.net/manual/en/pdostatement.bindcolumn.php
		-> Have reverted to old system with query_params. Double-checked that this successfully registers a new user.
	
	*/

	//Uses approach outlined at http://forums.devshed.com/php-faqs-and-stickies-167/how-to-program-a-basic-but-secure-login-system-using-891201.html

    //Connect to database
    require("sqlconnect.php");
    
	//Check for registration form submit; if true, registration happens, else form is displayed
    if(!empty($_POST))
    {
        /*
		   Check for valid email address using PHP function filter_var
		   -> http://us.php.net/manual/en/function.filter-var.php
		   -> http://us.php.net/manual/en/filter.filters.php)
		*/
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
        {
            die("That is not an e-mail address. Please refresh the page and retry.");
        }
        
        //Check if username is already taken; ":username" is placeholder token for actual value
		//Uses approach per http://stackoverflow.com/questions/7171041/what-does-it-mean-by-select-1-from-table
        $query = "
            SELECT
                1
            FROM users
            WHERE
                username = :username
        ";
		
		$username = check_input($_POST['username']);
		$email = check_input($_POST['email']);
        
        //Value definitions for special tokens in SQL query to prevent SQL injection
		/* Possible to insert $_POST['username'] directly into $query string; however doing so v. insecure and opens code to SQL injection
           -> (http://en.wikipedia.org/wiki/SQL_injection
		*/
        $query_params = array(
            ':username' => $username
        );
        
        try
        {
            //Run query
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch(PDOException $ex)
        {
			print_r($db->errorInfo());
			echo "<P>";
			print_r($stmt->errorInfo());
            die("Failed to run check for pre-existing username.");
        }
        
		//Returns array representing the next row from the selected results, or false if there are no more rows
        $row = $stmt->fetch();
        
        //Checks for pre-existing username
        if($row)
        {
            die("This username is already in use.");
        }
        
        //Check if email is already taken; ":email" is placeholder token for actual value
        $query = "
            SELECT
                1
            FROM users
            WHERE
                email = :email
        ";
        
        $query_params = array(
            ':email' => $email
        );
        
        try
        {
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch(PDOException $ex)
        {
			print_r($db->errorInfo());
			echo "<P>";
			print_r($stmt->errorInfo());
            die("Failed to run check for pre-existing email account.");
        }
        
        $row = $stmt->fetch();
        
		//Checks for pre-existing email
        if($row)
        {
            die("This email address is already registered.");
        }
        
		//Add new user information to new database table row
        $query = "
            INSERT INTO users (
				fname,
				lname,
				address,
				city,
				state,
				zip,
				phone,
				email,
                username,
                password,
				rating,
				salt
            ) VALUES (
				:fname,
				:lname,
				:address,
				:city,
				:state,
				:zip,
				:phone,
				:email,
                :username,
                :password,
				:rating,
				:salt
            )
        ";
        
		//Email and username omitted as already assigned previously
		$fname = ucwords(strtolower(check_input($_POST['fname'])));
		$lname = ucwords(strtolower(check_input($_POST['lname'])));
		$address = check_input($_POST['address']);
		$city = ucwords(strtolower(check_input($_POST['city'])));
		$state = check_input($_POST['state']);
		$zip = check_input($_POST['zip']);
		$phone = check_input($_POST['phone']);
		$rating = 0.0;
		
		/*
		  Implementing salt, as concatenated input to hash(), for added security above and beyond hashed password per midterm demo improvement.
		  Salt consists of 2 x hexadecimal representations of randomly chosen numbers in range given to mt_rand().
		  Presence of salt defeats rainbow table attacks - blocks attempts to precompute hashes of lists of passwords.
		  
		  Security approach based on http://forums.devshed.com/php-faqs-and-stickies-167/how-to-program-a-basic-but-secure-login-system-using-891201.html
		  NOTE - Mersenne Twister (MT), basis for mt_rand(), not cryptographically secure. http://www.math.sci.hiroshima-u.ac.jp/~m-mat/MT/efaq.html
		  Use of hashing algorithm (in this case SHA-256) increases security level for MT as suggested by Hiroshima Univ link.
		  Cycling with additional hashing steps protects against brute force attack.
		  
		  Further reading
		  -> http://en.wikipedia.org/wiki/Salt_%28cryptography%29
		  -> http://en.wikipedia.org/wiki/Brute-force_attack
		  -> http://en.wikipedia.org/wiki/Rainbow_table
		*/
		$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
		$password = hash('sha256', check_input($_POST['password']) . $salt);
		for($cycle = 0; $cycle < 65536; $cycle++) {
			$password = hash('sha256', $password . $salt);
		}
		
		//Replacing placeholders with user-supplied values for SQL query
		$query_params = array(
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
			':rating' => $rating,
			':salt' => $salt
        );
		
		//$STH->bindColumn('id', $this->id); Alternative to $query_params
		//? bindColumn does not work, triggers SQLSTATE[HY093]: Invalid parameter number: no parameters were bound
		/*
		   Apparent requirement to use $this->(fieldname) rather than just $fieldname. Not readily apparent, easier to use $query_params.
		   Accordingly left as such to avoid tripping up over syntax though error message information remains for reminder.
		*/
        
        try
        {
            //Execute query to create user
            $stmt = $db->prepare($query);
			/*$stmt->bindColumn('fname', $fname);
			$stmt->bindColumn('lname', $lname);
			$stmt->bindColumn('address', $address);
			$stmt->bindColumn('city', $city);
			$stmt->bindColumn('state', $state);
			$stmt->bindColumn('zip', $zip);
			$stmt->bindColumn('phone', $phone);
			$stmt->bindColumn('email', $email);
			$stmt->bindColumn('username', $username);
			$stmt->bindColumn('password', $password);
			$stmt->bindColumn('rating', $rating);
			$stmt->execute();*/
            $result = $stmt->execute($query_params);
        }
        catch(PDOException $ex)
        {
			print_r($db->errorInfo());
			echo "<P>";
			print_r($stmt->errorInfo());
			echo "<P>";
			print_r($ex->getMessage());
            die("Failed to run new user account creation.");
        }
        
        //Redirects user to success message after registering
        header("Location: regsuccess.htm");
        die("Redirecting to regsuccess.htm");
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
<FORM style="float:left" ID="registerForm" ACTION="register.php" METHOD="POST">
	<fieldset style="width:775px;border:1px;border-style:solid;"><legend>Register for a new account</legend>
		<LABEL CLASS="field" FOR="fname">First Name&nbsp;&nbsp;</LABEL>
		<INPUT TYPE="text" CLASS="required" NAME="fname" VALUE="" />
		<BR />
		<LABEL CLASS="field" FOR="lname">Last Name&nbsp;&nbsp;</LABEL>
		<INPUT TYPE="text" CLASS="required" NAME="lname" VALUE="" />
		<BR />
		<LABEL CLASS="field" FOR="address">Address&nbsp;&nbsp;</LABEL>
		<INPUT TYPE="text" CLASS="required" NAME="address" VALUE="" />
		<BR />
		<LABEL CLASS="field" FOR="city">City&nbsp;&nbsp;</LABEL>
		<INPUT TYPE="text" CLASS="required" NAME="city" VALUE="" />
		<BR />
		<LABEL CLASS="field" FOR="state">State&nbsp;&nbsp;</LABEL>
		<INPUT TYPE="text" CLASS="required" NAME="state" VALUE="" />
		<BR />
		<LABEL CLASS="field" FOR="zip">ZIP&nbsp;&nbsp;</LABEL>
		<INPUT TYPE="text" CLASS="required" NAME="zip" VALUE="" />
		<BR />
		<LABEL CLASS="field" FOR="phone">Phone&nbsp;&nbsp;</LABEL>
		<INPUT TYPE="text" CLASS="required" NAME="phone" VALUE="" />
		<BR />
		<LABEL CLASS="field" FOR="email">E-Mail&nbsp;&nbsp;</LABEL>
		<INPUT TYPE="text" CLASS="required email" NAME="email" VALUE="" />
		<BR />
		<LABEL CLASS="field" FOR="username">Username&nbsp;&nbsp;</LABEL>
		<INPUT TYPE="text" CLASS="required" NAME="username" VALUE="" />
		<BR />
		<LABEL CLASS="field" FOR="password">Password&nbsp;&nbsp;</LABEL>
		<INPUT TYPE="password" CLASS="required" NAME="password" VALUE="" />
		<BR />
		<label class="field">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
		<INPUT TYPE="submit" VALUE="Register" />
	</fieldset>
</FORM>

<SCRIPT>
	$("#registerForm").validate();
</SCRIPT>

</BODY>
</HTML>