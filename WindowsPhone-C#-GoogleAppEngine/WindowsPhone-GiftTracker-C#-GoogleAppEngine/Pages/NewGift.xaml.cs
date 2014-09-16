using System;
using System.Collections.Generic;
using System.Linq;
using System.Net;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Navigation;
using Microsoft.Phone.Controls;
using Microsoft.Phone.Shell;
using System.Runtime.Serialization;
using System.IO;
using System.Runtime.Serialization.Json;

namespace FinalProject.Pages
{
    public partial class NewGift : PhoneApplicationPage
    {
        public NewGift()
        {
            InitializeComponent();
        }

        // Load data for the ViewModel Items
        protected override void OnNavigatedTo(NavigationEventArgs e)
        {
            NavigationContext.QueryString.TryGetValue("eventName", out thisEvent);
        }

        FinalProject.App thisApp = Application.Current as FinalProject.App;

        string name;
        string email;
        string description;
        string remail;
        string thisEvent;

        [DataContract]
        public class ResultsLogin
        {
            [DataMember(Name = "Success")]
            public string success { get; set; }
            [DataMember(Name = "SessionID")]
            public string sessionID { get; set; }
            [DataMember(Name = "ErrVal")]
            public string errVal { get; set; }
        }

        private void logout_Click(object sender, RoutedEventArgs e)
        {
            FinalProject.Logout tmp = new FinalProject.Logout();
            tmp.logout();
        }

        private void addNewGift_Click(object sender, RoutedEventArgs e)
        {
            System.Diagnostics.Debug.WriteLine("New Gift clicked \n");
            name = GiftName.Text;
            description = Description.Text;
            email = thisApp.UserEmail;
            remail = REmail.Text;

            var uri = new Uri("http://dsjafhbvaio.appspot.com/action?Action=7&Email=" + email + 
                "&Event=" + thisEvent +
                "&Name="+name+ 
                "&Description="+description +
                "&ForEmail="+remail);

            errorMessage.Text = "Connecting to server";

            HttpWebRequest webRequest = (HttpWebRequest)WebRequest.Create(uri);
            webRequest.Method = "POST";
            //webRequest.ContentType = "application/x-www-form-urlencoded";

            // Start web request
            webRequest.BeginGetRequestStream(new AsyncCallback(loginCallback), webRequest);
            App.ViewModel.IsDataLoaded = false;
        }

        private void loginCallback(IAsyncResult asynchronousResult)
        {
            try
            {

                HttpWebRequest webRequest = (HttpWebRequest)asynchronousResult.AsyncState;
                // Start the web request
                webRequest.BeginGetResponse(new AsyncCallback(LoginResults), webRequest);
            }
            catch (WebException e)
            {
                //this.errorMessage.Text = "Error in call back";
                System.Diagnostics.Debug.WriteLine("ERROR GetRequestStreamCallback {0} ", e);
            }

        }

        private void LoginResults(IAsyncResult asynchronousResult)
        {
            try
            {
                var request = (HttpWebRequest)asynchronousResult.AsyncState;

                System.Diagnostics.Debug.WriteLine("Running new event results\n");
                using (var resp = (HttpWebResponse)request.EndGetResponse(asynchronousResult))
                {
                    using (var streamResponse = resp.GetResponseStream())
                    {
                        var LoginSerializerData = new DataContractJsonSerializer(typeof(ResultsLogin));
                        var LoginResultsData = LoginSerializerData.ReadObject(streamResponse) as ResultsLogin;
                        this.Dispatcher.BeginInvoke(
                            (Action<ResultsLogin>)((UserLoginData) =>
                            {
                                System.Diagnostics.Debug.WriteLine("Running addnew gift Inside dispatcher\n");
                                //save the response
                                if (LoginResultsData == null)
                                {
                                    this.errorMessage.Text = "Error connecting to server in";
                                }
                                else
                                {
                                    this.errorMessage.Text = LoginResultsData.success;
                                    thisApp.sessionID = LoginResultsData.sessionID;
                                    if (LoginResultsData.success == "Fail")
                                    {
                                        this.errorMessage.Text = "Error new gift was not created";
                                    }
                                    else
                                    {
                                        NavigationService.GoBack();
                                    }
                                }
                            }), LoginResultsData);

                    }
                }
            }
            catch (WebException e)
            {
                //this.errorMessage.Text = "Error in login results";
                System.Diagnostics.Debug.WriteLine("ERROR GetResponseCallBack {0} ", e);

            }
            catch (System.Runtime.Serialization.SerializationException e)
            {
                System.Diagnostics.Debug.WriteLine("ERROR GetResponseCallBack {0} ", e);
            }
        }

        private void logout_click(object sender, RoutedEventArgs e)
        {
            FinalProject.Logout tmp = new FinalProject.Logout();
            tmp.logout();
        }

    }
}