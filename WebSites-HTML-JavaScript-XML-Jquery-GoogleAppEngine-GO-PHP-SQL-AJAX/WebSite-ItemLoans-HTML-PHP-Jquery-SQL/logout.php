<?php
	//This logout file, logout.php, is for a logout performed when the user selects
	//"Log out" directly from the main menu tabs at the top of every page on the site

	//Uses approach outlined at http://forums.devshed.com/php-faqs-and-stickies-167/how-to-program-a-basic-but-secure-login-system-using-891201.html

    //Connect to database
    require("sqlconnect.php");
    
    //Remove user data from session
    unset($_SESSION['user']);
    
    //Redirect user to index page
    header("Location: index.php");
    die("Redirecting to index.php");
?>