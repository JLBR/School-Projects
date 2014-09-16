<?php



    //returns NULL on failure
    function connectToDB()
    {
        include 'dbConfig.php';
    
	   $host =	    $HOST;
	   $dbname =       $DBNAME;
	   $port =	    $PORT;
        $user =	    $USER;
	   $pass =	    $PASS;

	   try 
	   {  
		  $DBH = new PDO("mysql:host=$host;dbname=$dbname;port=$port", $user, $pass);  
	   }  
	   catch(PDOException $e) 
	   {  
		  echo "Error:".$e->getMessage();
		  $DBH = \NULL;
	   } 
	   return $DBH;
    }
    
?>