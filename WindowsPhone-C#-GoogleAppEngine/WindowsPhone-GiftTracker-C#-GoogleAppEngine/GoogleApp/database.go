package finalproject

import (
    "net/http"
	"fmt"
    "time"
    "appengine"
    "appengine/datastore"
    "math/rand"
	//"encoding/json"
)

type User struct
{
	UName	string
	Email	string
	Pswrd	string
}

type Event struct
{
	Name	string
	Dates	string
	Date	time.Time
}

type Gift struct
{
	Name		string
	Description	string
	ForEmail	string
	Aquired		bool
	ClaimedByEmail	string
}

type Store struct
{
	Name	string
	Lat	string
	Long	string
}

type Location struct
{
	LID		int
	GEOcoord	string
}

type EGift struct
{
	EID	int
	GID	int
}

type EventAccess struct
{
	EID	int
	UID	string
}

type GiftStore struct
{
	SID	int
	GID	int
}

type Contacts struct
{
	UID	string
	CID	string
}

type Session struct
{
	SID		int
	LastActive	time.Time
}

type List struct
{
	UID	string
	ForUID	string
	LID	int
}

type GiftList struct
{
	LID	int
	GID	int
}

//returns false if failed, true if succeceded
func NewEvent (newEvent Event, email string, r *http.Request) (bool, error) {
	tempDate, _ := time.Parse("01/02/2006", newEvent.Dates)
	newEvent.Date = tempDate
	c := appengine.NewContext(r)
	ukey := datastore.NewKey(c, "Email", email, 0, nil)
	key := datastore.NewKey(c, "Event", newEvent.Name, 0, ukey)
	_, err := datastore.Put(c, key, &newEvent)
	if err != nil {
		return false, err	
	}
	return true, err
}


func getEvents(email string,  w http.ResponseWriter, r *http.Request) error {
	c := appengine.NewContext(r)
	uKey := datastore.NewKey(c, "Email", email, 0, nil)
	var events []*Event
	q := datastore.NewQuery("Event").Ancestor(uKey)
	_, err := q.GetAll(c, &events)
	if err != nil {
		return err
	}
	
	for _, event := range events {
		fmt.Fprintf(w, "%s\n", event.Name)
		fmt.Fprintf(w, "%s\n", event.Dates)
	}

	return err
}

//returns false if failed, true if succeceded
func newGift (newGift Gift, email string, event string, r *http.Request) (bool, error) {
	c := appengine.NewContext(r)
	ukey := datastore.NewKey(c, "Email", email, 0, nil)
	ekey := datastore.NewKey(c, "Event", event, 0, ukey)
	key := datastore.NewKey(c, "Gift", newGift.Name, 0, ekey)
	_, err := datastore.Put(c, key, &newGift)
	if err != nil {
		return false, err	
	}
	return true, err
}

func getGifts (email string, event string, w http.ResponseWriter, r *http.Request) error {
	c := appengine.NewContext(r)
	uKey := datastore.NewKey(c, "Email", email, 0, nil)
	eKey := datastore.NewKey(c, "Event", event, 0, uKey)
	var gifts []*Gift
	q := datastore.NewQuery("Gift").Ancestor(eKey)
	_, err := q.GetAll(c, &gifts)
	if err != nil {
		return err
	}
	
	for _, gift := range gifts{
		fmt.Fprintf(w, "%s\n", gift.Name)
		fmt.Fprintf(w, "%s\n", gift.Description)
		fmt.Fprintf(w, "%s\n", gift.ForEmail)
		var aqu string = "true";
		if gift.Aquired == false {
			aqu = "false"
		}
		fmt.Fprintf(w, "%s\n", aqu)
	}

	return err
}

//returns false if failed, true if succeceded
func newStore (newStore Store, email string, event string, gift string, r *http.Request, w http.ResponseWriter) (bool, error) {
	c := appengine.NewContext(r)
	ukey := datastore.NewKey(c, "Email", email, 0, nil)
	ekey := datastore.NewKey(c, "Event", event, 0, ukey)
	gkey := datastore.NewKey(c, "Gift", gift, 0, ekey)
	key := datastore.NewKey(c, "Store", newStore.Name, 0, gkey)
	_, err := datastore.Put(c, key, &newStore)
	if err != nil {
		fmt.Fprintf(w, "%s",err)
		return false, err	
	}
	return true, err
}

func getStores (email string, event string, gift string, r *http.Request, w http.ResponseWriter) error {
	c := appengine.NewContext(r)
	uKey := datastore.NewKey(c, "Email", email, 0, nil)
	eKey := datastore.NewKey(c, "Event", event, 0, uKey)
	gkey := datastore.NewKey(c, "Gift", gift, 0, eKey)
	var stores []*Store
	q := datastore.NewQuery("Store").Ancestor(gkey)
	_, err := q.GetAll(c, &stores)
	if err != nil {
		return err
	}
	
	for _, store := range stores{
		fmt.Fprintf(w, "%s\n", store.Name)
		fmt.Fprintf(w, "%s\n", store.Lat)
		fmt.Fprintf(w, "%s\n", store.Long)
	}

	return err
}

//returns false if failed, true if succeceded
func regUser (newUser User, r *http.Request) (bool, error) {

	c := appengine.NewContext(r)
	key := datastore.NewKey(c, "Email", newUser.Email, 0, nil)
	_, err := datastore.Put(c, key, &newUser)
	if err != nil {
		return false, err	
	}
	return true, err
}

//sessionID -1 on error, -2 if login failed
func userLogin(login User, r *http.Request) (int, error) {
	//var tempUser []*User
	var tempUser User

	c := appengine.NewContext(r)
	key := datastore.NewKey(c, "Email", login.Email, 0, nil)
	err := datastore.Get(c, key, &tempUser)
	if err != nil || tempUser.Pswrd != login.Pswrd {
		if err == datastore.ErrNoSuchEntity {
			return -2, err
		}
		return -1, err
	}

	sessionID, err := startSession(login.Email, r)

	return sessionID, err
}

//returns session id on success -1 on fail
func startSession(email string, r *http.Request) (int, error) {

	var newSession Session

	rand.Seed(time.Now().UTC().UnixNano())
	newSession.SID = rand.Intn(900000)
	newSession.LastActive = time.Now()

	c := appengine.NewContext(r)

	sessionKey := datastore.NewKey(c, "Session", email, 0, nil)

	_, err := datastore.Put(c, sessionKey, &newSession)
	if err!= nil {
		newSession.SID = -1
	}
	return newSession.SID, err
}

//returns true if exist, or false if not, check error if false
func userExists (email string, r *http.Request) (bool, error) {
	var tempUser User
	c := appengine.NewContext(r)
	key := datastore.NewKey(c, "Email", email, 0, nil)
	err := datastore.Get(c, key, &tempUser)
	if err != nil {
		if err == datastore.ErrNoSuchEntity {
			return false, nil
		}
		return false, err
	}	
	return true, err
}


func userLogout(email string, r *http.Request){
	c := appengine.NewContext(r)
	sessionKey := datastore.NewKey(c, "Session", email, 0, nil)
	datastore.Delete(c, sessionKey)
}



//////test function /////////
func getUser(email string, w http.ResponseWriter, r *http.Request) {
	var tempUser []*User
	c := appengine.NewContext(r)
	q := datastore.NewQuery("User").
		Filter("Email =" , email)
	_, err := q.GetAll(c, &tempUser)
	if err != nil {
		if err == datastore.ErrNoSuchEntity {
			fmt.Fprintf(w, " \nNo such user")
			return
		}
		fmt.Fprintf(w, "\n%s", err.Error())
	}

	fmt.Fprintf(w, " \ntemp user %s", tempUser )

}