using Microsoft.Phone.Controls;
using System;
using System.IO;
using System.Net;
using System.Text;
using System.Windows;
using System.Windows.Navigation;
namespace FinalProject
{

    partial class Logout
    {
        FinalProject.App thisApp = Application.Current as FinalProject.App;

        string email;
        string Parameters;

        public Logout()
        {
        }

        public void logout()
        {
            email = thisApp.UserEmail;
            var uri = new Uri("http://dsjafhbvaio.appspot.com/action?Action=3&Email=" + email);

            //errorMessage.Text = "Connecting to server";

            Parameters = "?Email=" + email + "&Action = 3";

            HttpWebRequest webRequest = (HttpWebRequest)WebRequest.Create(uri);
            webRequest.Method = "POST";
            //webRequest.ContentType = "application/x-www-form-urlencoded";

            // Start web request
            webRequest.BeginGetRequestStream(new AsyncCallback(loginCallback), webRequest);
            (Application.Current.RootVisual as PhoneApplicationFrame).Navigate(new Uri("/Pages/MainPage.xaml", UriKind.Relative));
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
                postData.Append("Action=3");
                postData.Append("Email=" + email);

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
                        StreamReader streamRead = new StreamReader(streamResponse);
                        string responseString = streamRead.ReadToEnd();
                        thisApp.UserEmail = "";
                        thisApp.sessionID = "";
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