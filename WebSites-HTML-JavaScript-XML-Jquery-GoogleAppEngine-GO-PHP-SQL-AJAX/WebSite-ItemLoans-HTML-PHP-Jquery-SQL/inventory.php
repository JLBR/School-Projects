<!DOCTYPE html>
<html>
	<head> 
		<!--Character encoding & title needed for HTML5 validation-->
		<meta charset="UTF-8">
		<title>Manage My Inventory</title>

		<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js" type="text/javascript"></script>
		<script src="http://cdnjs.cloudflare.com/ajax/libs/knockout/2.3.0/knockout-min.js" type="text/javascript"></script>
		
		<script src="javascript/inventory.js" type="text/javascript"></script>
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

		<fieldset style="float: left" data-bind="visible: items().length > 0"><legend>Manage my items</legend>

			<div style="float: left; width:100%">
					<ul><li>
					<label>Search:&nbsp;&nbsp;</label><input type="text" id="searchTerm" name="searchTerm">
					<input type="submit" value="Submit" name="search"  id="search">
					<span id="errorMessage"></span>
					</li></ul>
			</div>

			Click an item name to view, or edit, its info:
			<table id="itemslist" data-bind="foreach: items">
				<tr>
					<td style="width:230px"><a href="#" data-bind="text: name, click: loadme"></a></td>
					&nbsp;&nbsp;
					<td><a href="#" style="color:black;font-size:12px" data-bind="text: history().length == 0 ? '[no history]' : show_history() ? '[hide history]' : '[view history]', click: togglehistory"></a>
					&nbsp;
					<a href="#" style="color:red;font-size:12px;text-align:right" data-bind="click: deleteme">[delete]</a></td>
				</tr>
				<tr data-bind="visible: show_history() && history().length > 0">
					<td colspan="2">
						<ul data-bind="foreach: history">
							<li><span data-bind="text: fname + ' ' + lname"></span> checked out on <span data-bind="text: date_out"></span> and returned on <span data-bind="text: date_in"></span></li>
						</ul>
					</td>
				</tr>
			</table>
		</fieldset>
		
		<fieldset style="float: left" data-bind="visible: items().length < 1"><legend>Manage my items</legend>
			You currently have no items in your inventory.<br><br>
			You can add one using the form to the right.
		</fieldset>

		<form style="float: right" name="Create New Item" action="inventory.php" method="post" data-bind="with: activeItem">
			<fieldset><legend data-bind="text: id() == '' ? 'Create new item' : 'Edit this item'"></legend>
				<label class="field">Item Category:&nbsp;&nbsp;</label><select name="category" required data-bind="value: category">
					<option></option>
					<option>Hardware</option>
					<option>Electronic Component</option>
					<option>Mobile Device</option>
					<option>Game</option>
					<option>Book</option>
					<option>Miscellaneous</option>
				</select><br>
				<label class="field">Item Name:&nbsp;&nbsp;</label><input type="text" name="itemName" required data-bind="value: name"><br>
				<label class="field">Serial Number:&nbsp;&nbsp;</label><input type="number" name="serialnum" required data-bind="value: serialnum"><br>
				<label class="field">Brief Description:&nbsp;&nbsp;</label><input type="text" name="description" required data-bind="value: notes"><br>
				<!--<label class="field">Loan Requirements:&nbsp;&nbsp;</label><input type="text" name="Loan Requirements" required><br>-->
				<label class="field">Features:&nbsp;&nbsp;</label><input type="text" name="features" data-bind="value: features"><br>
				<label class="field">Accessories:&nbsp;&nbsp;</label><input type="text" name="accessories" data-bind="value: accessories"><br>
				<label class="field">Operating System:&nbsp;&nbsp;</label><select name="os" data-bind="value: os">
					<option></option>
					<option>Windows</option>
					<option>Linux</option>
					<option>Mac</option>
				</select><br>				
				<label class="field">Number Pages:&nbsp;&nbsp;</label><input type="number" name="pages" data-bind="value: pages"><br><br>
<!--
I hope we can change how this "inactive/active" thing works. The default position should be "NULL",
and then the user should be required to consciously set it every time he adds/edits an item
-->
				<label class="field">Loan system status:&nbsp;&nbsp;</label><select name="loan_toggle" required data-bind="value: loan_toggle">
					<option>Inactive</option>
					<option>Active</option>
				</select>
				<br><br>

				If loan system status is set to "active" it will be placed in the loan network, and will be searchable by other users!<br><br>
				If it is set to "inactive" it will only be placed in your inventory.
				<br><br>
				<label class="field">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>

				<input type="hidden" name="id" data-bind="value: id"> <!-- value is empty for new items...needs to be populated for items your editing-->
				<input type="hidden" name="owner" data-bind="value: owner"> <!--owner is determined by login...-->
				<input type="submit" value="Submit" data-bind="click: $parent.submitItem">
				<button type="button" value="Reset" data-bind="visible: id() !== '', click: $parent.resetItem">Reset</button>
				
			</fieldset>
		</form>

		<!-- inelegant formatting to help position section break correctly -->
		<fieldset style="width:90%;border:1px;border-style:none;"></fieldset>		

	</body>
</html>