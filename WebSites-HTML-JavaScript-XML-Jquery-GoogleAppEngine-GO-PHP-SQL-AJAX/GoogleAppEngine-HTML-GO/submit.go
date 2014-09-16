package homework2

import (
    "fmt"
    "net/http"

    "image"
    "image/jpeg"
    "time"
    "appengine"
    "appengine/datastore"
    "io"
    "appengine/urlfetch"
    "io/ioutil"
    "math/rand"
)

type School struct 
{
	SchoolName	string
	SchoolURL	string
	SchoolPic	[]byte
	SchoolID	int
	TimeAdded	time.Time
}

func init() {
    http.HandleFunc("/submit", submit)
}

func submit(w http.ResponseWriter, r *http.Request) {

	err := addSchool(w, r)
	if err != nil {
		fmt.Fprintf(w, "There were errors in the submission")
	} else {
		http.Redirect(w, r, "/showSchools", http.StatusFound)
	}
	
}

func schoolKey(c appengine.Context) *datastore.Key {
    return datastore.NewKey(c, "School", "default_school", 0, nil)
}

//works!!!
func addSchool(w http.ResponseWriter, r *http.Request) error {

	c := appengine.NewContext(r)

	img, err := fetchImage(w, r)
	if err != nil {
		return err
	}
	
	rand.Seed(time.Now().UTC().UnixNano())

	newSchool := School {
		SchoolName: r.FormValue("schoolName"),
		SchoolURL: r.FormValue("schoolURL"),
		SchoolPic: img,
		SchoolID: rand.Intn(900000),
		TimeAdded: time.Now(),
	}
	
	key := datastore.NewIncompleteKey(c, "School", schoolKey(c))
	//newSchool.SchoolID = key.Encode()
	_, err = datastore.Put(c, key, &newSchool)
	if err != nil {
		fmt.Fprintf(w, "Error adding the school: %s", err)
		http.Error(w, err.Error(), http.StatusInternalServerError)
	}
	
	return err
}

//from https://developers.google.com/appengine/docs/go/urlfetch/
func fetchImage(w http.ResponseWriter, r *http.Request) ([]byte, error) {
	c := appengine.NewContext(r)
	client := urlfetch.Client(c)
	resp, err := client.Get(r.FormValue("schoolURL"))
	if err != nil {	
		fmt.Fprintf(w, "Error getting image")
		http.Error(w, err.Error(), http.StatusInternalServerError)	
		return nil, err
	}

	img, err := ioutil.ReadAll(resp.Body)
	resp.Body.Close()
	if err != nil {
		return nil, err
	}

	return img, err
}

//http://blog.golang.org/go-image-package
func convertImageToJPEG(convertImage io.Writer, original io.Reader) error {

	img, _, err := image.Decode(original)
	if err != nil {
		return err
	}

	return jpeg.Encode(convertImage, img, nil)
}