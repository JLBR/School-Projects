package homework2

import (
    "fmt"
    "net/http"

    "appengine"
    "appengine/datastore"
    "strconv"
)

func init() {
    http.HandleFunc("/image", showImage)
}

func showImage(w http.ResponseWriter, r *http.Request) {

	var Schools []School
	//var nextSchool school

	c := appengine.NewContext(r)

	//key, err :=datastore.DecodeKey(r.FormValue("img_id"))
	
	schoolID, err := strconv.Atoi(r.FormValue("img_id"))

	q := datastore.NewQuery("School").Filter("SchoolID =", schoolID )
	_, err = q.GetAll(c, &Schools)
	if err != nil {
		fmt.Fprintf(w, "Error in query: %s", err)
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return 
	}

	//nextSchool = Schools.Next()	

	//w.Header().Set("Content-Type", "image/jpeg")
	for _, p := range Schools{
		//if r.FormValue("img_id")==p.SchoolID{
			fmt.Fprintf(w, "%s", p.SchoolPic)
			//fmt.Fprintf(w, "%s and next %s", p.SchoolID, r.FormValue("img_id"))
		//}
	}
        
	return 

}