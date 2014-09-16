package finalproject

import (
	"fmt"
    	"net/http"
	"encoding/json"
)

const Regester = "1"
const Login = "2"
const Logout = "3"
const AddEvent = "4"
const GetEvents = "5"
const GetGifts = "6"
const AddGift = "7"
const AddStore = "8"
const GetStores = "9"

func init() {
    http.HandleFunc("/action", action)
    http.HandleFunc("/", handler)
}

type Results struct{
	ErrVal 		int
	Success		string
	SessionID	int
}

func handler(w http.ResponseWriter, r *http.Request) {
    //fmt.Fprint(w, "Hello, world!\n")

	//result := Results {0, "Fail", -1}
	//newJSON, _ := json.Marshal(result)
	//fmt.Fprintf(w, "%s", newJSON)

	//fmt.Fprintf(w, "Success\n")
	//result := testAddUser(w, r)
	//fmt.Fprintf(w, "%s", result)
	//getUser("dsfhad", w, r)
	//testJSON(w, r)
	//testLogin(w,r)
	//testAddEvent(w, r)
	//testGetEvents(w, r)
	//testAddGift(w,r)
	//testGetGifts(w,r)

}

func action(w http.ResponseWriter, r *http.Request) {

	switch action := r.FormValue("Action"); action{
		case Regester:
			newUser := User {
				UName:	r.FormValue("UName"),
				Email:	r.FormValue("Email"),
				Pswrd:	r.FormValue("Password"),
			}

			if userE, _ := userExists(newUser.Email, r); userE == true{
				result := Results {1, "Fail", -1}
				newJSON, _ := json.Marshal(result)
				fmt.Fprintf(w, "%s", newJSON)
				return
			}
			
			if userE, _ :=  regUser(newUser, r); userE == false {
				result := Results {1, "Fail", -1}
				newJSON, _ := json.Marshal(result)
				fmt.Fprintf(w, "%s", newJSON)
				return
			}
			fallthrough		
		case Login:
			newUser := User {
				Email:	r.FormValue("Email"),
				Pswrd:	r.FormValue("Password"),
			}
			sessionID, err := userLogin(newUser, r)
			if err != nil {
				result := Results {1, "Fail", -1}
				newJSON, _ := json.Marshal(result)
				fmt.Fprintf(w, "%s", newJSON)
				return
			}
			result := Results {0, "Success", sessionID}
			newJSON, _ := json.Marshal(result)
			fmt.Fprintf(w, "%s", newJSON)
		case Logout:
			userLogout(r.FormValue("Email"), r)
		case AddEvent:
			newEvent := Event { 	
				Name: 	r.FormValue("name"),
				Dates:	r.FormValue("date"),	
			}
			success, err := NewEvent (newEvent, r.FormValue("Email"), r)
			var result = Results{}
			if success == false || err != nil {
				result = Results {1, "Fail", -1}
			} else {
				result = Results {1, "Success", -1}
			}
			newJSON, _ := json.Marshal(result)
			fmt.Fprintf(w, "%s", newJSON)
		case GetEvents:
			err := getEvents (r.FormValue("Email"), w, r)
			if err != nil {
				fmt.Fprintf(w, "Fail")
			}
		case GetGifts:
			err := getGifts (r.FormValue("Email"), r.FormValue("Event"), w,r)
			if err != nil {
				fmt.Fprintf(w, "Fail")
			}
		case AddGift:
			NewGift := Gift { 	
				Name: 		r.FormValue("Name"),
				Description:	r.FormValue("Description"),
				ForEmail:	r.FormValue("ForEmail"),
			}
			NewGift.Aquired = true;
			if r.FormValue("Aquired") == "false" {
				NewGift.Aquired = false;
			}

			success, err := newGift (NewGift, r.FormValue("Email"), r.FormValue("Event"), r)
			var result = Results{}
			if success == false || err != nil {
				result = Results {1, "Fail", -1}
			} else {
				result = Results {1, "Success", -1}
			}
			newJSON, _ := json.Marshal(result)
			fmt.Fprintf(w, "%s", newJSON)	
		case AddStore:
			NewStore := Store { 	
				Name: 		r.FormValue("Name"),
				Lat:		r.FormValue("Lat"),
				Long:		r.FormValue("Long"),
			}
			success, err := newStore (NewStore, r.FormValue("Email"), r.FormValue("Event"), r.FormValue("Gift"), r, w)
			var result = Results{}
			if success == false || err != nil {
				result = Results {1, "Fail", -1}
			} else {
				result = Results {1, "Success", -1}
			}
			newJSON, _ := json.Marshal(result)
			fmt.Fprintf(w, "%s", newJSON)	
		case GetStores:
			err := getStores (r.FormValue("Email"), r.FormValue("Event"), r.FormValue("Gift"), r, w)
			if err != nil {
				fmt.Fprintf(w, "Fail")
			}
	}
}

func testJSON(w http.ResponseWriter, r *http.Request){
	testJ := Results {9, "Fail", -1}
	newJSON, err := json.Marshal(testJ)
	if err != nil {
		fmt.Fprintf(w, "\n", err)
	}
	fmt.Fprintf(w, "\n%s",newJSON)
}

func testAddUser(w http.ResponseWriter, r *http.Request) bool {
	newUser := User {
		UName:	"test",
		Email:	"test",
		Pswrd:	"ball",
	}

	if userE, err := userExists(newUser.Email, r); userE == true{
		fmt.Fprintf(w, "\n RegFail User exist %s", err)
		return false
	}
		
	result, _ := regUser(newUser, r)
	return result

}

func testLogin(w http.ResponseWriter, r *http.Request) {
	newUser := User {
		Email: "test",
		Pswrd:	"ball",
	}
	
	if sessionID, err := userLogin(newUser, r) ; sessionID > 0 {
		fmt.Fprintf(w, "\n uerLogin fail %d %s", sessionID, err)
	} else	{
		fmt.Fprintf(w, "\n uerLogin success %d %s", sessionID, err)
	}
}

func testAddEvent(w http.ResponseWriter, r *http.Request){


	newEvent := Event { 	
		Name: 	"TestEvent",
		Dates:	"10/22/2013",	
	}
	success, err := NewEvent (newEvent, "TestUser", r)
	var result = Results{}
	if success == false || err != nil {
		result = Results {1, "Fail", -1}
	} else {
		result = Results {1, "Success", -1}
	}
	newJSON, _ := json.Marshal(result)
	fmt.Fprintf(w, "%s", newJSON)
}

func testGetEvents(w http.ResponseWriter, r *http.Request){

	err := getEvents ("TestUser", w, r)
	var result = Results{}
	if err != nil {
		result = Results {1, "Fail", -1}
		newJSON, _ := json.Marshal(result)
		fmt.Fprintf(w, "%s", newJSON)
	} else {
		//result = Results {1, "Success", -1}
	}
	//newJSON, _ := json.Marshal(success)
	//fmt.Fprintf(w, "%s", newJSON)
}

func testAddGift(w http.ResponseWriter, r *http.Request){
			NewGift := Gift { 	
				Name: 		"TestGift",
				Description:	"TestDsi",
				ForEmail:	"TestUser2",
			}
			NewGift.Aquired = true;
			if "false" == "false" {
				NewGift.Aquired = false;
			}

			success, err := newGift (NewGift, "TestUser", "TestEvent", r)
			var result = Results{}
			if success == false || err != nil {
				result = Results {1, "Fail", -1}
			} else {
				result = Results {1, "Success", -1}
			}
			newJSON, _ := json.Marshal(result)
			fmt.Fprintf(w, "%s", newJSON)
}

func testGetGifts(w http.ResponseWriter, r *http.Request){
			err := getGifts ("TestUser", "TestEvent", w,r)
			if err != nil {
				fmt.Fprintf(w, "Fail")
			}
}