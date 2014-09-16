<!DOCTYPE html>
<html>
	<head> 
		<!--Character encoding & title needed for HTML5 validation-->
		<meta charset="UTF-8">
		<title>View & Use Loan System</title>

		<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js" type="text/javascript"></script>
		<script src="http://cdnjs.cloudflare.com/ajax/libs/knockout/2.3.0/knockout-min.js" type="text/javascript"></script>

		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>

	<body>
		<script id="login" type="text/html">
			//if the login is passed/ok then do dologin()->which will display the
		</script>

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

		
		<fieldset style="float:left; width:775px" data-bind="visible: myloaned().length > 0"><legend>My items that are currently being borrowed</legend>
			Click an item to view item and loan details:
			<table id="itemslist" data-bind="foreach: myloaned">
				<tr>
					<td>
						<a href="#" data-bind="text: name, click: loadme"></a>
						<a href="#" style="color: green" data-bind="click: checkin" title="Check in this item after the borrower has returned it.">[check in]</a>
						<div data-bind="visible: selected">
							<table>
								<tr><td class="fieldname">Borrower:</td><td data-bind="text: borrower"></td></tr>
								<tr><td class="fieldname">Item category:</td><td data-bind="text: category"></td></tr>
								<tr><td class="fieldname">Item serial:</td><td data-bind="text: serialnum"></td></tr>
								<tr><td class="fieldname">Description:</td><td data-bind="text: description"></td></tr>
								<tr><td class="fieldname">Features:</td><td data-bind="text: features"></td></tr>
								<tr><td class="fieldname">Accessories:</td><td data-bind="text: accessories"></td></tr>
								<tr><td class="fieldname">Operating System:</td><td data-bind="text: os"></td></tr>
								<tr><td class="fieldname">Number of Pages:</td><td data-bind="text: pages"></td></tr>
							<!-- comments will be copied from the pending-loan "request" dialogue between owner and borrower -->
								<tr><td class="fieldname">Comments(?):</td><td data-bind="text: pages"></td></tr>
								<tr><td class="fieldname">Notes(?):</td><td data-bind="text: pages"></td></tr>
								<tr><td class="fieldname">Date borrowed:</td><td data-bind="text: date_out"></td></tr>
								<tr><td class="fieldname">Date due:</td><td data-bind="text: date_due"></td></tr>
								<tr><td>&nbsp;</td></tr>
							</table>
						</div>
					</td>
				</tr>
			</table>
		</fieldset>
		
		<fieldset style="float:left; width:775px" data-bind="visible: myloaned().length < 1"><legend>My items that are currently being borrowed</legend>
			None of your items are currently being borrowed.
		</fieldset>

		<!-- inelegant formatting to help any additional content to display correctly -->
		<fieldset style="width:90%;border:1px;border-style:none;"></fieldset>		

		<script src="javascript/loans3.js" type="text/javascript"></script>
	</body>
</html>