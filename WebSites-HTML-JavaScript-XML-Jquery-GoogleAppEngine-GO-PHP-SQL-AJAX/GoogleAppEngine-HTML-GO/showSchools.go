package homework2

import (
	"fmt"
	"net/http"
	"html/template"
	"appengine/datastore"
	"appengine"

)

func init() {
	http.HandleFunc("/showSchools", showSchools)
}

func showSchools(w http.ResponseWriter, r *http.Request) {

	var schools []School
	schoolQuery("School", &schools, w, r)
		
	err := showSchoolPageTemplate.Execute(w, schools)
	if err != nil {
		fmt.Fprintf(w, "Error in template: %s", err)
		http.Error(w, err.Error(), http.StatusInternalServerError)
	}

	//fmt.Fprintf(w, "%s", schools.TimeAdded)
}


func schoolQuery(searchTerm string, tempStruct *[]School, w http.ResponseWriter, r *http.Request) error{
	c := appengine.NewContext(r)

	q := datastore.NewQuery(searchTerm)

	_, err := q.GetAll(c, tempStruct)
	if err != nil {
		fmt.Fprintf(w, "Error in query: %s", err)
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return err
	}	
	
	return err
}

var showSchoolPageTemplate = template.Must(template.New("schoolList").Parse(showSchoolPageTepmlateHTML))

const showSchoolPageTepmlateHTML = 
`<!DOCTYPE html>
<html lang="en">
	<meta charset="UTF-8">
	<title>Homework 2 Show Schools</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css" />
	<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
</head>
<body>
	<div data-role="page">
		<div data-theme="a" data-role="header">
			<h1>School Pictures</h1>
		</div><!-- /header -->
		<div data-role="content">
			<div data-theme="b" data-role="header">
				<h1>Current Schools</h1>
			</div><!-- /header -->
			{{range .}}
			<div data-role="content">
				<div data-theme="c" data-role="header">
					<h2>{{.SchoolName}}</h2>
				</div><!-- /header -->
				<div data-role="content">
					<img src="/image?img_id={{.SchoolID}}" alt="{{.SchoolURL}}">
				</div>
			</div>
			{{end}}
		</div>
		<div data-role="content">
			<ul data-role="listview" data-theme="b" data-dividertheme="d">
				<li><a href="http://x12345ffffff.appspot.com/">Add more schools</a></li>
			</ul>
		</div>
	</div>
</body>
</html>
`