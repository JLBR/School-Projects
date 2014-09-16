<?php

ini_set('display_errors',1); 
 error_reporting(E_ALL);

session_start();

if(isset($_POST["changeclass"])){
$_SESSION["Class"]=$_POST["changeclass"];
}


header ("Location: ../classes/addassignments.php");
?>