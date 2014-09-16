<?php

    class utility
    {
	   //empty private to prevent the creation of a utility object
	   private function __constructor()
	   {
	   }


	   //returns NULL on failure
	   public function connectToDB()
	   {
		  $host = MySQL::HOST;
		  $dbname = MySQL::DBNAME;
		  $port = MySQL::PORT;
            $user = MySQL::USER;
		  $pass = MySQL::PASS;

		  try 
		  {  
			 $DBH = new PDO("mysql:host=$host;dbname=$dbname;port=$port", $user, $pass);  
		  }  
		  catch(PDOException $e) 
		  {  
                if($DEBUG)
                {
			   echo "Error:".$e->getMessage();
                }
			 $DBH = \NULL;
		  } 
		  return $DBH;
	   }

    }

?>