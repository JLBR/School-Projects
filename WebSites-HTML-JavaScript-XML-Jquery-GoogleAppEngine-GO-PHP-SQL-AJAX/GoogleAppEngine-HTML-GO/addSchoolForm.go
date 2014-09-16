package homework2

import (
    "fmt"
    "net/http"
)

func init() {
    http.HandleFunc("/", schoolForm)
}

func schoolForm(w http.ResponseWriter, r *http.Request) {
    fmt.Fprint(w, addSchoolPage)

}

const addSchoolPage = `
<!DOCTYPE html>
<html lang="en">
	<meta charset="UTF-8">
	<title>Homework 2 Add a School</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css" />
	<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
</head>
<body>
	<div data-role="page">
		<div data-theme="a" data-role="header">
			<h1>Add a School</h1>
		</div><!-- /header -->
		<div data-role="content">
			<div data-theme="b" data-role="header">
				<h1>Add new school picture</h1>
			</div><!-- /header -->
			<form action="http://x12345ffffff.appspot.com/submit" method="post" class="ui-body ui-body-a ui-corner-all">
				<fieldset>
					<div data-role="fieldcontain" class="ui-hide-label">
						<label for="schoolName">School Name:</label>
						<input type="text" name="schoolName" id="schoolName" value="" placeholder="School Name"/>
					</div>
					<div data-role="fieldcontain" class="ui-hide-label">
						<label for="schoolURL">School picture URL:</label>
						<input type="text" name="schoolURL" id="schoolURL" value="" placeholder="School picture URL"/>
					</div>
				</fieldset>
				<fieldset class="ui-grid-a">
					<div class="ui-block-a"><button type="reset" data-theme="d" data-mini="true">Cancel</button></div>
					<div class="ui-block-b"><button type="submit" data-theme="a" data-mini="true">Submit</button></div>
				</fieldset>
			</form>
		</div>
		<div data-role="content">
			<ul data-role="listview" data-theme="b" data-dividertheme="d">
				<li><a href="http://x12345ffffff.appspot.com/showSchools">View photos</a></li>
			</ul>
		</div>
	</div>
</body>
</html>
			
`

