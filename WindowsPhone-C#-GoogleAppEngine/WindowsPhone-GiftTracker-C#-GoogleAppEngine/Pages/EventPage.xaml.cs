using System;
using System.Collections.Generic;
using System.Linq;
using System.Net;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Navigation;
using Microsoft.Phone.Controls;
using Microsoft.Phone.Shell;

namespace FinalProject.Pages
{
    public partial class EventPage : PhoneApplicationPage
    {
        public EventPage()
        {
            InitializeComponent();

            DataContext = App.giftViewModel;
        }

        string eventName = "";

        // Load data for the ViewModel Items
        protected override void OnNavigatedTo(NavigationEventArgs e)
        {
                string selectedIndex = "";
                if (NavigationContext.QueryString.TryGetValue("selectedItem", out selectedIndex))
                {
                    int index = int.Parse(selectedIndex);
                    eventName = App.ViewModel.Events[index].Name;
                    App.giftViewModel.LoadData(eventName);
                }
        }

        private void logout_click(object sender, RoutedEventArgs e)
        {
            FinalProject.Logout tmp = new FinalProject.Logout();
            tmp.logout();
        }

        private void addNewGift_click(object sender, RoutedEventArgs e)
        {
            NavigationService.Navigate(new Uri("/Pages/NewGift.xaml?eventName=" + eventName, UriKind.Relative));
        }

        private void refresh_gifts(object sender, RoutedEventArgs e)
        {
            if (!App.giftViewModel.IsDataLoaded)
            {
                App.giftViewModel.LoadData(eventName);
            }
        }

        private void MainLongListSelector_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            NavigationService.Navigate(new Uri("/Pages/GiftPage.xaml?selectedItem=" + (MainLongListSelector.SelectedItem as GiftViewModel).ID +"&Name="+eventName, UriKind.Relative));
        }
    }
}