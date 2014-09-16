using System;
using System.Collections.Generic;
using System.Linq;
using System.Net;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Navigation;
using Microsoft.Phone.Controls;
using Microsoft.Phone.Shell;
using System.IO;
using System.Text;
using System.Runtime.Serialization.Json;
using System.Runtime.Serialization;

namespace FinalProject
{
    public partial class Regester : PhoneApplicationPage
    {
        public Regester()
        {
            InitializeComponent();
        }
        FinalProject.App thisApp = Application.Current as FinalProject.App;

        string password;
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

        private void submit_Click(object sender, RoutedEventArgs e)
        {
            password = this.TBPassword.Password;
            email = this.TBemail.Text;
            //var uri = new Uri("http://192.168.3.100:9090/action");
            //var uri = new Uri("http://www.googe.com");
            //var uri = new Uri("http://dsjafhbvaio.appspot.com/action?Action=2&Email=test&Password=ball");
            //var uri = new Uri("http://dsjafhbvaio.appspot.com/action");
            var uri = new Uri("http://dsjafhbvaio.appspot.com/action?Action=1&Email=" + email + "&Password=" + password);

            errorMessage.Text = "Connecting to server";

            Parameters = "?Email=" + email + "&Password=" + password + "&Action = 2";

            HttpWebRequest webRequest = (HttpWebRequest)WebRequest.Create(uri);
            webRequest.Method = "POST";
            //webRequest.ContentType = "application/x-www-form-urlencoded";

            // Start web request
            webRequest.BeginGetRequestStream(new AsyncCallback(loginCallback), webRequest);

        }

        private void loginCallback(IAsyncResult asynchronousResult)
        {
            try
            {
                //var password = this.TBPass.Password;
                //var email = this.TBEmail.Text;

                HttpWebRequest webRequest = (HttpWebRequest)asynchronousResult.AsyncState;
                Stream postStream = webRequest.EndGetRequestStream(asynchronousResult);

                StringBuilder postData = new StringBuilder();
                postData.Append("Action=2");
                postData.Append("Email=" + email);
                postData.Append("Password=" + password);

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

                System.Diagnostics.Debug.WriteLine("Running LoginResults\n");
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
                                    if (int.Parse(thisApp.sessionID) < 0)
                                    {
                                        this.errorMessage.Text = "Email is allready in use";
                                    }
                                    else
                                    {
                                        System.Diagnostics.Debug.WriteLine("email = "+ email);
                                        thisApp.UserEmail = email;
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

    }
}