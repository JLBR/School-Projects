using System;
using System.Net;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Documents;
using System.Windows.Ink;
using System.Windows.Input;
using System.Windows.Media;
using System.Windows.Media.Animation;
using System.Windows.Shapes;

namespace GooglePlusAuthentication
{
    public class GooglePlusData
    {

    }

    public class GooglePlusTokens
    {
        public string AccessToken { get; set; }
        public string RefreshToken { get; set; }
        public string ExpiresIn { get; set; }
        public string TokenType { get; set; }
    }
    public class GooglePlusUserInfo
    {
        public string UserId { get; set; }
        public string UserEmail { get; set; }
        public string UserName { get; set; }
        public string UserGivenName { get; set; }
        public string UserFamilyName { get; set; }
        public string UserLink { get; set; }
        public string UserPicture { get; set; }
        public string UserGender { get; set; }
        public string UserBidthday { get; set; }


    }

}
