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
using System.Windows.Media.Imaging;

namespace GooglePlusAuthentication
{
    public partial class MainPage : PhoneApplicationPage
    {
        HowTo2.App thisApp = Application.Current as HowTo2.App;
        // Constructor
        public MainPage()
        {
            InitializeComponent();
        }

        //launch google plus login page
        private void buttonLogin_Click(object sender, RoutedEventArgs e)
        {
            NavigationService.Navigate(new Uri("/GooglePlusLoginPage.xaml", UriKind.RelativeOrAbsolute));
        }

        //display user profile
        protected override void OnNavigatedTo(System.Windows.Navigation.NavigationEventArgs e)
        {

            base.OnNavigatedTo(e);

            if (thisApp.UserName != "" && thisApp.UserImage != "" && thisApp.UserEmail != "" && thisApp.UserGender != "")
            {

                this.textBlockGooglePlusUserName.Text = thisApp.UserName;
                this.textBlockGooglePlusUserEmail.Text = thisApp.UserEmail;
                this.textBlockGooglePlusUserGender.Text = thisApp.UserGender;
                BitmapImage bitmap = new BitmapImage(new Uri(thisApp.UserImage, UriKind.RelativeOrAbsolute));
                this.imageGooglePlusUserPicture.Source = bitmap;
                buttonLogin.Visibility = System.Windows.Visibility.Collapsed;
            }

        }
    }
}