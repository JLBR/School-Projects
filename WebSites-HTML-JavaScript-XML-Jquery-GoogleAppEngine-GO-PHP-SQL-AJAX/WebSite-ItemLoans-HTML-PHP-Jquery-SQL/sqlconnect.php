<?php
	ini_set('display_errors', 'On');
	
	session_start();
	

	$host = "engr-db.engr.oregonstate.edu";
	$port = "3307";
    $dbname = "cs419group1";
    $admin = "cs419group1";
    $pw = "PDEGCYKq";
	$regUser = ("cs419group1_ro");
	$regPass = ("RP2Bpisl");

	//Uses approach outlined at http://forums.devshed.com/php-faqs-and-stickies-167/how-to-program-a-basic-but-secure-login-system-using-891201.html
	//Initial plan to use MySQLi, switched to PDO after reading through http://www.dreamincode.net/forums/topic/185726-database-error-handling-in-php-5/
	//Streamlined error handling and result configuration
	
    $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
    
    try
    {
		//Establish connection
        $db = new PDO("mysql:host={$host};port={$port};dbname={$dbname};charset=utf8", $admin, $pw, $options);
    }
    catch(PDOException $ex)
    {
		//Trap error, stop script
		echo $ex->getMessage();
        die("Failed to connect to database.");
    }
    
	//Set PDO to throw exceptions when errors encountered
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
	//Query results returned as associative array; string index values are database column names
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); 
    
    //Eliminating PHP magic quotes feature (http://php.net/manual/en/security.magicquotes.php)
    if(function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc())
    {
        function undo_magic_quotes_gpc(&$array)
        {
            foreach($array as &$value)
            {
                if(is_array($value))
                {
                    undo_magic_quotes_gpc($value);
                }
                else
                {
                    $value = stripslashes($value);
                }
            }
        }
    
        undo_magic_quotes_gpc($_GET);
        undo_magic_quotes_gpc($_POST);
        undo_magic_quotes_gpc($_COOKIE);
    }
?>