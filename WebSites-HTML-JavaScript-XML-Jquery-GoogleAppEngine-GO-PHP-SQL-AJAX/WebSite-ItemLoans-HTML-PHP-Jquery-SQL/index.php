<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd">

<?php

session_start();
$user_id = $_SESSION['user']['id'];

?>

<HTML XMLNS="http://www.w3.org/1999/xhtml" xml:lang="en">

<HEAD>
<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/HTML; charset=iso-8859-1" />

	<SCRIPT TYPE="text/javascript" SRC="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></SCRIPT>
	<SCRIPT TYPE="text/javascript" SRC="http://cdnjs.cloudflare.com/ajax/libs/knockout/2.3.0/knockout-min.js"></SCRIPT>
	<SCRIPT TYPE="text/javascript" SRC="ajaxtabs/ajaxtabs.js">
	
	/***********************************************
	* Ajax Tabs CONTENT SCRIPT v2.2- © Dynamic Drive DHTML code library (www.dynamicdrive.com)
	* This notice MUST stay intact for legal use as described by http://www.dynamicdrive.com/notice.htm
	* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
	***********************************************/
	
	</SCRIPT>
	
	<LINK REL="stylesheet" TYPE="text/css" HREF="ajaxtabs/ajaxtabs.css" />
	<LINK REL="stylesheet" TYPE="text/css" HREF="css/style.css">
	
</HEAD>

<BODY>
	<h1> Inventory Tracker & Community Loan Network </h1>
	<div>
		<table style="border:none" align="center">
		<tr><td width="200px" class="menubar">
			<a href="home.php" style="color:inherit; text-decoration: none">Home</a>
		</td><td width="200px" class="menubar">
			<a href="inventory.php" style="color:inherit; text-decoration: none">Manage my inventory</a>
		</td><td width="200px" class="menubar">
			<a href="loans3.php" style="color:inherit; text-decoration: none">Items I'm lending</a>
		</td><td width="200px" class="menubar">
			<a href="index.php" style="color:inherit; text-decoration: none">Account info</a>
		</td></tr>
		<tr><td width="200px" class="menubar">
			<a href="loans.php" style="color:inherit; text-decoration: none">All loanable items</a>
		</td><td width="200px" class="menubar">
			<a href="requests.php" style="color:inherit; text-decoration: none">Item requests</a>
		</td><td width="200px" class="menubar">
			<a href="loans2.php" style="color:inherit; text-decoration: none">Items I'm borrowing</a>
		</td><td width="200px" class="menubar">
			<a href="logout.php" style="color:inherit; text-decoration: none">Log out</a>
		</td></tr>
		</table>
	</div>
	
	<br>

	<DIV CLASS="indentmenu" ID="lrtabs" ALIGN="center">
		<UL>
			<LI><A HREF="register.php" REL="lrdivcontainer">Register</A></LI>
			<LI><A HREF="login.php" REL="lrdivcontainer" CLASS="selected">Login</A></LI>
			<LI><A HREF="editprofile.php" REL="lrdivcontainer">Edit Profile</A></LI>
			<LI><A HREF="deleteuser.php" REL="lrdivcontainer">Delete Account</A></LI>
			<LI><A HREF="tempPass.htm" REL="lrdivcontainer">Forgot Password?</A></LI>
		</UL>
		<BR STYLE="clear: left" />
	</DIV>

	<DIV ID="lrdivcontainer" STYLE="border:none"></DIV>

	<SCRIPT TYPE="text/javascript">
		var lr=new ddajaxtabs("lrtabs", "lrdivcontainer")
		lr.setpersist(true)
		lr.setselectedClassTarget("link")
		lr.init()
	</SCRIPT>
</BODY>
</HTML>