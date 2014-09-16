using System;
using System.Collections.Generic;
using System.Linq;
using System.Net;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Documents;
using System.Windows.Input;
using System.Windows.Media;
using System.Windows.Media.Animation;
using System.Windows.Shapes;
using Microsoft.Phone.Controls;

using System.IO;
using System.Text;
using System.Runtime.Serialization;
using System.Runtime.Serialization.Json;
using System.Net.Browser;
using System.Windows.Media.Imaging;




namespace GooglePlusAuthentication
{
    public partial class GooglePlusLoginPage : PhoneApplicationPage
    {
        HowTo2.App thisApp = Application.Current as HowTo2.App;
        GooglePlusTokens googlePlusTokens = new GooglePlusTokens();
        GooglePlusUserInfo googlePlusUserInfo = new GooglePlusUserInfo();

        string Parameters = null;
        string ClientId = "";
        string ClientSecret = "";
        string RedirctedUri = "http://localhost";



        [DataContract]
        public class GooglePlusAccessToken
        {
            [DataMember(Name = "access_token")]
            public string AccessToken { get; set; }
            [DataMember(Name = "refresh_token")]
            public string RefreshToken { get; set; }
            [DataMember(Name = "expires_in")]
            public string ExpiresIn { get; set; }
            [DataMember(Name = "token_type")]
            public string TokenType { get; set; }

        }
        [DataContract]
        public class GooglePlusUserProfile
        {
            [DataMember(Name = "id")]
            public string id { get; set; }
            [DataMember(Name = "email")]
            public string email { get; set; }
            [DataMember(Name = "name")]
            public string name { get; set; }
            [DataMember(Name = "given_name")]
            public string given_name { get; set; }
            [DataMember(Name = "family_name")]
            public string family_name { get; set; }
            [DataMember(Name = "link")]
            public string link { get; set; }
            [DataMember(Name = "picture")]
            public string picture { get; set; }
            [DataMember(Name = "gender")]
            public string gender { get; set; }
            [DataMember(Name = "birthday")]
            public string birthday { get; set; }
            [DataMember(Name = "nickname")]
            public string nickname { get; set; }
            [DataMember(Name = "givenName")]
            public string givenName { get; set; }
            [DataMember(Name = "language")]
            public string language { get; set; }
            [DataMember(Name = "displayName")]
            public string displayName { get; set; }
        }



        public GooglePlusLoginPage()
        {
            InitializeComponent();
            this.Loaded += new RoutedEventHandler(GooglePlus_LoginPage_Loaded); //load google plus login page 
        }

        //load google plus login page 
        void GooglePlus_LoginPage_Loaded(object sender, RoutedEventArgs e)
        {

            var url = "https://accounts.google.com/o/oauth2/auth?response_type=code&redirect_uri=" + RedirctedUri +
                //"&scope=https://www.googleapis.com/auth/plus.login&client_id=" 
                "&scope=https://www.googleapis.com/auth/userinfo.email%20https://www.googleapis.com/auth/userinfo.profile&client_id=" 
                + ClientId;  //http://localhost 
            webBrowserGooglePlusLogin.Navigate(new Uri(url, UriKind.RelativeOrAbsolute));

        }

        private void webBrowserGooglePlusLogin_Navigated(object sender, System.Windows.Navigation.NavigationEventArgs e)
        {
            webBrowserGooglePlusLogin.Visibility = Visibility.Visible;
        }

        private void webBrowserGooglePlusLogin_Navigating(object sender, NavigatingEventArgs e)
        {
            if (e.Uri.Host.Equals("localhost"))
            {
                webBrowserGooglePlusLogin.Visibility = Visibility.Collapsed;

                e.Cancel = true;
                int pos = e.Uri.Query.IndexOf("=");

                //get the access code 
                string messageCode = pos > -1 ? e.Uri.Query.Substring(pos + 1) : null;

                //when code is not equeals to null get the access token
                if (messageCode != null)
                {
                    //get the access token 
                    Parameters = "code=" + messageCode + "&client_id=" + ClientId + "&client_secret=" + ClientSecret + "&redirect_uri=" + RedirctedUri + "&grant_type=authorization_code";
                    HttpWebRequest webRequest = (HttpWebRequest)WebRequest.Create("https://accounts.google.com/o/oauth2/token");
                    webRequest.Method = "POST";
                    webRequest.ContentType = "application/x-www-form-urlencoded";

                    // Start web request
                    webRequest.BeginGetRequestStream(new AsyncCallback(GetRequestStreamCallback), webRequest);


                }

            }
        }
        void GetRequestStreamCallback(IAsyncResult asynchronousResult)
        {

            try
            {
                HttpWebRequest webRequest = (HttpWebRequest)asynchronousResult.AsyncState;
                // End the stream request operation
                Stream postStream = webRequest.EndGetRequestStream(asynchronousResult);


                byte[] byteArray = Encoding.UTF8.GetBytes(Parameters);

                // Add the post data to the web request
                postStream.Write(byteArray, 0, byteArray.Length);
                postStream.Close();

                // Start the web request
                webRequest.BeginGetResponse(new AsyncCallback(GetResponseCallback), webRequest);
            }
            catch (WebException e)
            {
                System.Diagnostics.Debug.WriteLine("ERROR GetRequestStreamCallback {0} ", e);
            }

        }
        void GetResponseCallback(IAsyncResult asynchronousResult)
        {
            try
            {
                var request = (HttpWebRequest)asynchronousResult.AsyncState;

                using (var resp = (HttpWebResponse)request.EndGetResponse(asynchronousResult))
                {
                    using (var streamResponse = resp.GetResponseStream())
                    {
                        var GooglePlusSerializerData = new DataContractJsonSerializer(typeof(GooglePlusAccessToken));
                        var GooglePlusProfileData = GooglePlusSerializerData.ReadObject(streamResponse) as GooglePlusAccessToken;
                        this.Dispatcher.BeginInvoke(
                            (Action<GooglePlusAccessToken>)((GooglePlusUserData) =>
                            {

                                //save the response
                                thisApp.AccessToken = googlePlusTokens.AccessToken = GooglePlusUserData.AccessToken;
                                googlePlusTokens.RefreshToken = GooglePlusUserData.RefreshToken;
                                googlePlusTokens.ExpiresIn = GooglePlusUserData.ExpiresIn;
                                googlePlusTokens.TokenType = GooglePlusUserData.TokenType;


                                // request user profile
                                RequestForUserProfile();


                            }), GooglePlusProfileData);

                    }
                }
            }
            catch (WebException e)
            {
                System.Diagnostics.Debug.WriteLine("Access token " + thisApp.AccessToken);
                System.Diagnostics.Debug.WriteLine("ERROR GetResponseCallBack {0} ", e);

            }
        }

        //request for user prifile
        void RequestForUserProfile()
        {
            try
            {
                var urlProfile = "https://www.googleapis.com/oauth2/v1/userinfo?access_token=" + thisApp.AccessToken;

                //var urlProfile = "https://www.googleapis.com/plus/v1/people/me?access_token=" + thisApp.AccessToken;
                //var urlProfile = "https://www.googleapis.com/plusDomains/v1/people/me?access_token=" + thisApp.AccessToken;
                
                // web request user profile
                WebRequest request = WebRequest.Create(urlProfile);
                request.BeginGetResponse(new AsyncCallback(this.ResponseCallbackProfile), request);
            }
            catch (Exception e)
            {
                System.Diagnostics.Debug.WriteLine("ERROR RequestForUserProfile {0}  ", e);
            }

        }

        // user profile response 
        private void ResponseCallbackProfile(IAsyncResult asynchronousResult)
        {

            try
            {
                var request = (HttpWebRequest)asynchronousResult.AsyncState;

                using (var resp = (HttpWebResponse)request.EndGetResponse(asynchronousResult))
                {
                    using (var streamResponse = resp.GetResponseStream())
                    {
                        var GooglePlusSerializerData = new DataContractJsonSerializer(typeof(GooglePlusUserProfile));
                        var GooglePlusProfileData = GooglePlusSerializerData.ReadObject(streamResponse) as GooglePlusUserProfile;
                        this.Dispatcher.BeginInvoke(
                            (Action<GooglePlusUserProfile>)((GooglePlusUserData) =>
                            {
                                thisApp.UserName = googlePlusUserInfo.UserName = GooglePlusUserData.name;
                                thisApp.UserImage = googlePlusUserInfo.UserPicture = GooglePlusUserData.picture;
                                if (thisApp.UserImage == null)
                                {
                                    thisApp.UserImage = googlePlusUserInfo.UserPicture = "https://lh3.googleusercontent.com/-_kvINpT6jtI/AAAAAAAAAAI/AAAAAAAAAAA/IEAclp4PQbk/photo.jpg";
                                }
                                thisApp.UserEmail = googlePlusUserInfo.UserEmail = GooglePlusUserData.email;
                                googlePlusUserInfo.UserFamilyName = GooglePlusUserData.family_name;
                                thisApp.UserGender = googlePlusUserInfo.UserGender = GooglePlusUserData.gender;
                                googlePlusUserInfo.UserGivenName = GooglePlusUserData.given_name;
                                googlePlusUserInfo.UserId = GooglePlusUserData.id;
                                googlePlusUserInfo.UserLink = GooglePlusUserData.link;

                                System.Diagnostics.Debug.WriteLine("Access token " + thisApp.AccessToken);
                                Deployment.Current.Dispatcher.BeginInvoke(() =>
                                {
                                    NavigationService.GoBack();
                                });
                            }), GooglePlusProfileData);
                    }
                }
            }
            catch (WebException e)
            {
                System.Diagnostics.Debug.WriteLine("ERROR ResponseCallbackProfile {0} ", e);
            }
        }




    }
}