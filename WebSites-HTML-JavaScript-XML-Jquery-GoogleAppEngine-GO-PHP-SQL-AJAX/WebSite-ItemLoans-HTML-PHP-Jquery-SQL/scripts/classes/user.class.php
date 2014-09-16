<?php
    
	   class User
	   {
		  private $userId;
		  private $firstName;
		  private $lastName;
		  private $streetAddress;
		  private $city;
		  private $state;
		  private $zipCode;
		  private $phoneNumber;
		  private $email;
		  private $userName;
		  private $rating;

		  const ADD_USER = 1;
		  const UPDATE_USER = 2;
		  const CHANGE_PASSWORD = 3;

		  //emptyconstuctor
		  private function __constructor()
		  {
		  }

		  private function checkExecute($STH, $DBH, $sqlError )
		  {

				if(!$sqlError)
				{
				    if(Debug::DEBUG)
				    {
					   echo 'Error: accessing user information';
					   print_r($DBH->errorInfo());
					   echo "<p>";
					   print_r($STH->errorInfo());
				    }
				    $DBH = \NULL;
				    return array('success' => false, 'error' => 'SQL error');
				}
			 return array('success' => true);
		  }

		  //username must not be blank
		  private function getUserByUserName($user)
		  {
			 $sql = "SELECT salt, id, fname, lname, address, city, state, zip, phone, email
					   FROM users
					   WHERE username = :user";
			 try
			 {
				$DBH = Utility::connectToDB();
				if($DBH == \NULL)
				{
				    return array('success' => false, 'error' => 'Error connecting to database');
				}
        
				$STH = $DBH->prepare($sql);
        
				$STH->bindParam('user', $user);

				$sqlError = $STH->execute();
				$result = User::checkExecute($STH, $DBH, $sqlError);
				if($result['success'] == false)
				{
				    $DBH = \NULL;
				    return $result;
				}

				$rowCount = $STH->rowCount();

				$results = $STH->fetch(PDO::FETCH_ASSOC);

				if($rowCount == 0)
				{
				    $DBH = \NULL;
				    return array('success' => false, 'error' => 'No such user');
				}
				$DBH = \NULL;
				return array('success' => true, 
						  'salt' => $results['salt'], 
						  'id' => $results['id'],
						  'fname' => $results['fname'],
						  'lname' => $results['lname'],
						  'address' => $results['address'],
						  'city' => $results['city'],
						  'state' => $results['state'],
						  'zip' => $results['zip'],
						  'phone' => $results['phone'],
						  'email' => $results['email'],
						  );
			}
			catch(PDOException $e) 
			{
   				if(Debug::DEBUG)
				{
				    echo 'Error: ' . $e->getMessage();
				}
				$DBH = \NULL;
				return array('success' => false, 'error' => 'unknown server error');
			}
		  }

		  //gets user properties by username
		  public function getUserProperties($userName)
		  {

				$properties = User::getUserByUserName($userName);

				if($properties['success'] == false)
				{
				    return $properties;
				}
				unset($properties['salt']);
				return $properties;

		  }

		  //gets userID by username
		  public function getUserID($userName)
		  {
			 $results = User::getUserByUserName($userName);
			 if($results['success'] = false)
			 {
				return $results;
			 }

			 return array('success' => true, 'id' => $results['id']);
		  }

		  public function logout()
		  {
			     session_start();
				session_destroy();
		  }

		  public function updateUser($userName)
		  {
			 $sql = "UPDATE users
				    SET email = :email,
					   fname = :fname,
					   lname = :lname,
					   address = :address,
					   city = :city,
					   state = :state,
					   zip = :zip,
					   phone = :phone
				    WHERE username = :username";

			 return User::userAction($sql, $email, $fname, $lname, $address, $state, $zipCode, $phoneNumber, $userName, \NULL, User::UPDATE_USER);
		  }

		  public function addUser($email, $fname, $lname, $address, $state, $zipCode, $phoneNumber, $userName, $password)
		  {
			 
			 //returns false if no such user
			 $salt = User::getUserByUserName($userName);
			 if($salt['success'] == true)
			 {
				return array('success' => false);
			 }

		  	 $sql = "INSERT INTO users (
							 fname,
							 lname,
							 address,
							 city,
							 state,
							 zip,
							 phone,
							 email,
							 username,
							 password
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
							 :password
				    )";

			 $password = User::encrypt($password, NULL);

			 return User::userAction($sql, $email, $fname, $lname, $address, $state, $zipCode, $phoneNumber, $userName, $password, User::ADD_USER);
		  }

		  public function setPassword($userName, $password)
		  {
			 $sql = "UPDATE users 
				    SET password = :password
				    WHERE username = :username";

			 $password = User::encrypt($password, NULL);

			 return User::userAction($sql, \NULL, \NULL, \NULL, \NULL, \NULL, \NULL, \NULL, $userName, $password, User::CHANGE_PASSWORD);
		  }

		  private function userAction($sql, $email, $fname, $lname, $address, $state, $zipCode, $phoneNumber, $userName, $password, $action)
		  {
			 User::sanitizeAll($email, $fname, $lname, $address, $state, $zipCode, $phoneNumber, $userName);

			 try
			 {
				$DBH = Utility::connectToDB();
				if($DBH == \NULL)
				{
				    return \FALSE;
				}
        
				$STH = $DBH->prepare($sql);
        
				if($action != User::CHANGE_PASSWORD)
				{
				    $STH->bindParam('email', $email);
				    $STH->bindParam('fname', $fname);
				    $STH->bindParam('lname', $lname);
				    $STH->bindParam('address', $address);
				    $STH->bindParam('city', $city);
				    $STH->bindParam('state', $state);
				    $STH->bindParam('zip', $zip);
				    $STH->bindParam('phone', $phone);
				}

				$STH->bindParam('username', $userName);

				if($action == User::ADD_USER || $action == User::CHANGE_PASSWORD)
				{
				    $STH->bindParam('password', $password);
				}

				$sqlError = $STH->execute();
				$result = User::checkExecute($STH, $DBH, $sqlError);
				if($result['success'] == false)
				{
				    $DBH = \NULL;
				    return \FALSE;
				}

				$DBH = \NULL;
			}
			catch(PDOException $e) 
			{
   				if(Debug::DEBUG)
				{
				    echo 'Error: ' . $e->getMessage();
				}
				$DBH = \NULL;
				return \FALSE;
			}
			return \TRUE; 

		  }

		  private function encrypt($pass, $salt)
		  {
			 for($round = 0; $round < 65536; $round++)
			 {
				$pass = hash('sha256', $pass);
			 }
			 return $pass; 
		  }

		  private function isEmailInUse($email)
		  {
			 $query = "SELECT id
					   FROM users 
					   WHERE email = :email";

			 try
			 {
				$DBH = Utility::connectToDB();
				if($DBH == \NULL)
				{
				    return \FALSE;
				}
        
				$STH = $DBH->prepare($query);
        
				$STH->bindParam('email', $email);

				$sqlError = $STH->execute();
				$result = User::checkExecute($STH, $DBH, $sqlError);
				if($result['success'] == false)
				{
				    $DBH = \NULL;
				    return \FALSE;
				}

				$results = $STH->fetch(PDO::FETCH_ASSOC);

				$rowCount = $STH->rowCount();

				if($rowCount == 0)
				{
				    $DBH = \NULL;
				    return \TRUE;
				}
				$DBH = \NULL;
			}
			catch(PDOException $e) 
			{
   				if(Debug::DEBUG)
				{
				    echo 'Error: ' . $e->getMessage();
				}
				$DBH = \NULL;
				return \FALSE;
			}
			return \FALSE;
		  }

		  private function isUserNameInUse($userName)
		  {
			 $result = User::getUserByUserName($userName);
			 if($result['success'] == false)
			 {
				return \FALSE;
			 }

			 return \TRUE;
		  }

		  private function sanitizeAll(&$email, &$fname, &$lname, &$address, &$state, &$zipCode, &$phoneNumber, &$userName)
		  {
			 $email = filter_var($email, FILTER_SANITIZE_EMAIL);
			 $fname = filter_var($fname, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
			 $lname = filter_var($lname, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
			 $address = filter_var($address, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
			 $state = filter_var($state, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
			 $zipCode = filter_var($zipCode, FILTER_SANITIZE_NUMBER_INT);
			 $phoneNumber = filter_var($phoneNumber, FILTER_SANITIZE_NUMBER_INT);
			 $userName = filter_var($userName, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
		  }

		  public function login($user, $pass)
		  {
		  	 $user = filter_var($user, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);

			 //returns false if no such user
			 $salt = User::getUserByUserName($user);
			 if($salt['success'] == false)
			 {
				return $salt;
			 }

			 $pass = User::encrypt($pass, $salt['salt']);

			 $query = "SELECT id
					   FROM users 
					   WHERE username = :username
					   AND password   = :pass";
                     
			 try
			 {
				$DBH = Utility::connectToDB();
				if($DBH == \NULL)
				{
				    return array('success' => false, 'error' => 'Error connecting to database');
				}
        
				$STH = $DBH->prepare($query);
        
				$STH->bindParam('username', $user);
				$STH->bindParam('pass', $pass);

				$sqlError = $STH->execute();
				$result = User::checkExecute($STH, $DBH, $sqlError);
				if($result['success'] == false)
				{
				    return $result;
				}
             
				$rowCount = $STH->rowCount();

				if($rowCount == 1)
				{
				    $results = $STH->fetch(PDO::FETCH_ASSOC);

				    $DBH = \NULL;
				    return array('success' => true, 'id' => $results['id']);
				}
				else //login info incorrect
				{
				    $DBH = \NULL;
				    return array('success' => false, 'error' => 'login failure');
				}
			 }  
			 catch(PDOException $e) 
			 {
   				if(Debug::DEBUG)
				{
				    echo 'Error: ' . $e->getMessage();
				}
				$DBH = \NULL;
				return array('success' => false, 'error' => 'unknown error');
			 }
			return array('success' => false, 'error' => 'unknown error');
		  }
	   }
?>