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
using System.Text;
using System.Runtime.Serialization.Json;

namespace FinalProject.Pages
{
    public partial class NewEvent : PhoneApplicationPage
    {
        public NewEvent()
        {
            InitializeComponent();
        }
        FinalProject.App thisApp = Application.Current as FinalProject.App;

        string name;
        //System.DateTime date;
        string date;
        string email;
        string Parameters;

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

        private void add_event_Click(object sender, RoutedEventArgs e)
        {
            System.Diagnostics.Debug.WriteLine("New Event clicked \n");
            name = TBEventName.Text;
            date = DPDate.ValueString;
            email = thisApp.UserEmail;
            var uri = new Uri("http://dsjafhbvaio.appspot.com/action?Action=4&Email=" + email + "&date=" + date +"&name="+name);
            System.Diagnostics.Debug.WriteLine("New Event clicked "+date);
            errorMessage.Text = "Connecting to server";

            Parameters = "?Email=" + email + "&Date=" + date + "&name=" + name + "&Action = 4";

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
                Stream postStream = webRequest.EndGetRequestStream(asynchronousResult);

                StringBuilder postData = new StringBuilder();
                postData.Append("Action=4");
                postData.Append("Email=" + email);
                //postData.Append("Password=" + password);

                byte[] byteArray = Encoding.UTF8.GetBytes(postData.ToString());

                // Add the post data to the web request
                postStream.Write(byteArray, 0, byteArray.Length);
                postStream.Close();

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
                                System.Diagnostics.Debug.WriteLine("Running LoginResults Inside dispatcher\n");
                                //save the response
                                if (LoginResultsData == null)
                                {
                                    this.errorMessage.Text = "Error connecting to server in";
                                }
                                else
                                {
                                    this.errorMessage.Text = LoginResultsData.success;
                                    thisApp.sessionID = LoginResultsData.sessionID;
                                    //if (int.Parse(thisApp.sessionID) < 0)
                                    if (LoginResultsData.success == "Fail")
                                    {
                                        this.errorMessage.Text = "Error new event was not created";
                                    }
                                    else
                                    {
                                        System.Diagnostics.Debug.WriteLine("email = " + email);
                                        //thisApp.UserEmail = email;
                                        NavigationService.Navigate(new Uri("/Pages/MainPageL.xaml", UriKind.Relative));
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