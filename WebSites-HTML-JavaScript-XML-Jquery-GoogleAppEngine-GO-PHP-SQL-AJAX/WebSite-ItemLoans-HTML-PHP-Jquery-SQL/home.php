<?php
	
	require("sqlconnect.php");
		
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>

<HEAD>

<SCRIPT TYPE="text/javascript" SRC="http://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.11.1/jquery.validate.min.js"></SCRIPT>
<SCRIPT TYPE="text/javascript" SRC="http://cdnjs.cloudflare.com/ajax/libs/knockout/2.3.0/knockout-min.js"></SCRIPT>
<SCRIPT TYPE="text/javascript" SRC="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></SCRIPT>

<LINK REL="stylesheet" TYPE="text/css" HREF="css/style.css">
<STYLE>
	a {font-weight:bold;}
</STYLE>
</HEAD>

<BODY>
<DIV ID="container">

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
	
	<DIV ID="welcomeMessage">
		<p>Welcome to your personal Inventory Tracker and Community Loan Network! This is the only web app
		you need to keep track of items that you are willing to loan to others, and also find items that
		others in the network are willing to loan to you. See details below about what this app can do for
		you, or use the menu bar at the top of the page to get started!</p>
	</DIV>

	<DIV ID="lefthalf">
        <FIELDSET style="float:left;width:365px;border:1px;border-style:solid;"><LEGEND>Inventory tracker</LEGEND>
			<a href="inventory.php">Manage my inventory</a> - View details of all items that you
				own in the network, view history, edit item details, add new items
				or delete items from the system.<br><br>
			<a href="checkout.php">Check items in/out</a> - Check an item out to a borrower or
				check it back in to your available inventory.<br><br>
			<a href="editstatus.php">Edit item status</a> - Change the status of one of your items.<br><br>
			<a href="loans3.php">Items I'm lending</a> - View details about your items that are
				currently being borrowed by other people, including who is borrowing them,
				date borrowed and date due back.<br><br>
			<a href="requests.php">Item requests</a> - Respond to pending requests other people
				have made to borrow your items.
		</FIELDSET>
	</DIV>

	<DIV ID="righthalf">
		<FIELDSET style="float:right;width:365px;border:1px;border-style:solid;"><LEGEND>Community loan network</LEGEND>
			<a href="loans.php">All loanable items</a> - View details of all items that other people
				have put into the loan system, make a request to borrow an item.<br><br>
			<a href="requests.php">Item requests</a> - View the status of pending requests you have
				made to borrow items from the network, respond to owner comments about your loan requests.<br><br>
			<a href="loans2.php">Items I'm borrowing</a> - View details about other people's items
				that you are currently borrowing, including owner, date borrowed, and date due back.
		</FIELDSET>
	</DIV>
	
	<!-- inelegant formatting; for spacing: -->
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	
	<DIV>
		<FIELDSET style="float:right;width:365px;border:1px;border-style:solid;"><LEGEND>Account management</LEGEND>
			<a href="index.php">Account info</a> - Manage account, change profile information, request new password.<br><br>
			<a href="logout.php">Log out</a> - Exit Inventory Tracker and Community Loan Network.
		</FIELDSET>
	</DIV>

</DIV>
</BODY>
</HTML>