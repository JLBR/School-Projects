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
using FinalProject.ViewModels;

namespace FinalProject
{
    public partial class MainPageL : PhoneApplicationPage
    {
        public MainPageL()
        {
            InitializeComponent();

            DataContext = App.ViewModel;
        }

        // Load data for the ViewModel Items
        protected override void OnNavigatedTo(NavigationEventArgs e)
        {
            if (!App.ViewModel.IsDataLoaded)
            {
                App.ViewModel.LoadData();
            }
        }

        private void logout_Click(object sender, RoutedEventArgs e)
        {
            FinalProject.Logout tmp= new FinalProject.Logout();
            tmp.logout();
        }

        private void add_new_event_click(object sender, RoutedEventArgs e)
        {
            System.Diagnostics.Debug.WriteLine("Add new event clicked \n");
            //App.ViewModel.IsDataLoaded = false;
            NavigationService.Navigate(new Uri("/Pages/NewEvent.xaml", UriKind.Relative));
        }

        // Handle selection changed on LongListSelector
        private void MainLongListSelector_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            // If selected item is null (no selection) do nothing
            if (MainLongListSelector.SelectedItem == null)
                return;

            // Navigate to the new page
            NavigationService.Navigate(new Uri("/Pages/EventPage.xaml?selectedItem=" + (MainLongListSelector.SelectedItem as EventViewModel).ID, UriKind.Relative));

            // Reset selected item to null (no selection)
            MainLongListSelector.SelectedItem = null;
        }

        private void refresh_click(object sender, RoutedEventArgs e)
        {
            
            App.ViewModel.LoadData();
        }


    }
}