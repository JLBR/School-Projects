<!DOCTYPE html>
<html>
	<head> 
		<!--Character encoding & title needed for HTML5 validation-->
		<meta charset="UTF-8">
		<title>Manage Item Requests</title>

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

		<fieldset style="float:left" data-bind="visible: incoming().length > 0"><legend>Incoming requests for my items</legend>
			Click an item name to see request details and respond:
			<table id="itemslist" data-bind="foreach: incoming">
				<tr>
					<td><a href="#" data-bind="text: name, click: toggleme"></a>
					<div data-bind="visible: selected">
						<table>
							<tr><td class="fieldname">Borrower:</td><td data-bind="text: username"></td></tr>
							<tr><td class="fieldname">Borrow Date:</td><td data-bind="text: borrowdate"></td></tr>
							<tr><td class="fieldname">Return Date:</td><td data-bind="text: returndate"></td></tr>

							<b><tr><td class="fieldname">Status:</td><td data-bind="text: action"></td></tr></b>
						</table>
						
						<div data-bind="visible: comments().length > 0">
							<span data-bind="text: comments().length + ' Comments.'"></span>
							<table data-bind="foreach: comments">
								<tr>
									<td>
										<span data-bind="text: username"></span> - <span data-bind="text: date"></span>
										<p data-bind="text: comment"></p>
									</td>
								</tr>
							</table>
						</div>

						<textarea rows="5" cols="40" data-bind="value: newcomment, valueUpdate: 'afterkeydown'" placeholder="Add a comment to update the request status."></textarea>

						<span data-bind="visible: newcomment() !== ''">
							<button data-bind="click: acceptLoan">Accept</button>
							<button data-bind="click: rejectLoan">Reject</button>
							<button data-bind="click: pendLoan">Leave Pending</button>
						</span>
					</div>
					</td>
				</tr>
			</table>
		</fieldset>
		
		<fieldset style="float:left" data-bind="visible: incoming().length < 1"><legend>Incoming requests for my items</legend>
			There are currently no pending requests to borrow any of your items.
		</fieldset>

		<fieldset style="float:right" data-bind="visible: outgoing().length > 0"><legend>Outgoing requests for other people's items</legend>
			Click an item name to see request details and respond:
			<table id="itemslist" data-bind="foreach: outgoing">
				<tr>
					<td><a href="#" data-bind="text: name, click: toggleme"></a>
					
					<div data-bind="visible: selected">
						<table>
							<tr><td class="fieldname">Owner:</td><td data-bind="text: username"></td></tr>
							<tr><td class="fieldname">Borrow Date:</td><td data-bind="text: borrowdate"></td></tr>
							<tr><td class="fieldname">Return Date:</td><td data-bind="text: returndate"></td></tr>

							<b><tr><td class="fieldname">Status:</td><td data-bind="text: action"></td></tr></b>
						</table>

						<div data-bind="visible: comments().length > 0">
							<span data-bind="text: comments().length + ' Comments.'"></span>
							<table data-bind="foreach: comments">
								<tr>
									<td>
										<span data-bind="text: username"></span> - <span data-bind="text: date"></span>
										<p data-bind="text: comment"></p>
									</td>
								</tr>
							</table>
						</div>

						<textarea rows="5" cols="40" data-bind="value: newcomment, valueUpdate: 'afterkeydown'" placeholder="Add a comment to update the request status."></textarea>

						<br><br>
						Use the "Comments" field for back-and-forth communication with item owner
						<br><br>

						<span data-bind="visible: newcomment() !== ''">
							<button data-bind="click: postComment">Post Comment</button>
						</span>
					</div>
					</td>
				</tr>
			</table>
		</fieldset>		

		<fieldset style="float:right" data-bind="visible: outgoing().length < 1"><legend>Outgoing requests for other people's items</legend>
			You currently have no outstanding requests to borrow other people's items.
		</fieldset>
		
		<!-- inelegant formatting to help any additional content to display correctly -->
		<fieldset style="width:90%;border:1px;border-style:none;"></fieldset>
		
		<script src="javascript/requests.js" type="text/javascript"></script>
	</body>
</html>