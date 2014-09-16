package finalproject

import (
    "net/http"

    "time"
    "appengine"
    "appengine/datastore"
)

func init() {
    http.HandleFunc("/clean", removeEvent)
}

func removeEvent(w http.ResponseWriter,r *http.Request){
	var temp []*Event;
	var tempG []*Gift;
	var temS []*Store;

	t := time.Now()

	c := appengine.NewContext(r)

	q := datastore.NewQuery("Event").
		Filter("Date <" , t)
	keys, err := q.GetAll(c, &temp)
	if err != nil {
		return 
	}

	for _, p := range keys{

		q = datastore.NewQuery("Gift").
			Ancestor(p)
		gkeys, err := q.GetAll(c, &tempG )
		if err != nil {
			return 
		}

		for _, x := range gkeys{
			q = datastore.NewQuery("Store").
				Ancestor(x)
			gkeys, err := q.GetAll(c, &temS )
			if err != nil {
				return 
			}

			for _, y := range gkeys{
				datastore.Delete(c, y)
			}		
			datastore.Delete(c, x)
		}
		datastore.Delete(c, p)
	}

}