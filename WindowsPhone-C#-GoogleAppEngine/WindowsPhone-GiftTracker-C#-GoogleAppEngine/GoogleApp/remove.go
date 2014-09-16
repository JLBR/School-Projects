package finalproject

import (
    "net/http"

    "time"
    "appengine"
    "appengine/datastore"
)

func init() {
    http.HandleFunc("/remove", removeSessions)
}

func removeSessions(w http.ResponseWriter,r *http.Request){
	var temp []*Session;

	t := time.Now()
	t.Add(time.Minute*20)

	c := appengine.NewContext(r)

	q := datastore.NewQuery("Session").
		Filter("LastActive <" , t)
	keys, err := q.GetAll(c, &temp)
	if err != nil {
		return 
	}

	for _, p := range keys{
		datastore.Delete(c, p)
	}

}