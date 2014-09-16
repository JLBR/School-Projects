using System;
using System.Collections.Generic;
using System.Linq;
using System.Net;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Navigation;
using Microsoft.Phone.Controls;
using Microsoft.Phone.Shell;
using FinalProject.Resources;

namespace FinalProject
{
    public partial class MainPage : PhoneApplicationPage
    {
        // Constructor
        public MainPage()
        {
            InitializeComponent();

        }

        private void buttonLogin_Click(object sender, RoutedEventArgs e)
        {
            NavigationService.Navigate(new Uri("/Pages/LoginPage.xaml", UriKind.Relative));
        }

        private void create_account_Click(object sender, RoutedEventArgs e)
        {
            System.Diagnostics.Debug.WriteLine("register clicked \n");
            NavigationService.Navigate(new Uri("/Pages/Register.xaml", UriKind.Relative));
        }

        private void viewMap_click(object sender, RoutedEventArgs e)
        {
            NavigationService.Navigate(new Uri("/Pages/NewStore.xaml", UriKind.Relative));
        }


    }
}