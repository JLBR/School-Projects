package homework2

import (
    "fmt"
    "net/http"

    "time"
    "appengine"
    "appengine/datastore"
)

func init() {
    http.HandleFunc("/remove", remove)
}

func remove(w http.ResponseWriter, r *http.Request) {
	
	var Schools []School
	c := appengine.NewContext(r)

	t := time.Now()
	t.Add(time.Minute*5)

	q := datastore.NewQuery("School").Filter("TimeAdded <", t)
	keys, err := q.GetAll(c, &Schools)
	if err != nil {
		fmt.Fprintf(w, "Error in query: %s", err)
		http.Error(w, err.Error(), http.StatusInternalServerError)
		return 
	}
        
	for _, p := range keys{
		datastore.Delete(c, p)
	}

	return 
}