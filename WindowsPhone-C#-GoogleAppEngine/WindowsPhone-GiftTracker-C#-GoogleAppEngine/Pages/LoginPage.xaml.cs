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
using System.Runtime.Serialization;
using System.Runtime.Serialization.Json;
using System.Threading;


namespace FinalProject
{
    public partial class LoginPage : PhoneApplicationPage
    {

        FinalProject.App thisApp = Application.Current as FinalProject.App;

        public LoginPage()
        {
            InitializeComponent();
        }

        string Parameters;
        string email;
        string password;

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

        
        private void login_Click(object sender, RoutedEventArgs e)
        {
            password = this.TBPass.Password;
            email = this.TBEmail.Text;
            //var uri = new Uri("http://192.168.3.100:9090/action");
            //var uri = new Uri("http://www.googe.com");
            //var uri = new Uri("http://dsjafhbvaio.appspot.com/action?Action=2&Email=test&Password=ball");
            //var uri = new Uri("http://dsjafhbvaio.appspot.com/action");
            var uri = new Uri("http://dsjafhbvaio.appspot.com/action?Action=2&Email="+email+"&Password="+password);

            errorMessage.Text = "Connecting to server";

            Parameters = "?Email=" + email + "&Password=" + password +"&Action = 2";
            
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
                postData.Append("Email="+email);
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
                        //StreamReader streamRead = new StreamReader(streamResponse);
                        //string responseString = streamRead.ReadToEnd();
                        //System.Diagnostics.Debug.WriteLine(responseString + "response String"); 
                        var LoginSerializerData = new DataContractJsonSerializer(typeof(ResultsLogin));
                        var LoginResultsData = LoginSerializerData.ReadObject(streamResponse) as ResultsLogin;
                        this.Dispatcher.BeginInvoke(
                            (Action<ResultsLogin>)((UserLoginData) =>
                            {
                                System.Diagnostics.Debug.WriteLine("Running LoginResults Inside dispatcher\n");
                                //save the response
                                if (LoginResultsData == null)
                                {
                                    this.errorMessage.Text = "Error logging in";
                                }
                                else
                                {
                                    this.errorMessage.Text = LoginResultsData.success;
                                    thisApp.sessionID = LoginResultsData.sessionID;
                                    if (int.Parse(thisApp.sessionID) < 0)
                                    {
                                        this.errorMessage.Text = "Incorect username or password";
                                    }
                                    else
                                    {
                                        thisApp.UserEmail = email;
                                        if (!App.ViewModel.IsDataLoaded)
                                        {
                                            App.ViewModel.LoadData();
                                            //while (App.ViewModel.IsDataLoaded == false) ;
                                            //App.ViewModel.processDownload();
                                        }
                                        
                                        System.Diagnostics.Debug.WriteLine("email = "+ email);
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