using System;
using System.Collections.Generic;
using System.Linq;
using System.Net;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Navigation;
using Microsoft.Phone.Controls;
using Microsoft.Phone.Shell;
using System.Windows.Shapes;
using System.Device.Location;
using Windows.UI;
using Microsoft.Phone.Maps.Controls;
using System.Windows.Media;
using Windows.Devices.Geolocation;

namespace FinalProject.Pages
{
    public partial class GiftPage : PhoneApplicationPage
    {
        public GiftPage()
        {
            InitializeComponent();

            DataContext = App.storeViewModel;

            markerLayer = new MapLayer();
            map1.Layers.Add(markerLayer);

            newCenter();
        }

        MapLayer markerLayer = null;

        public async void newCenter()
        {
            Geolocator geolocator = new Geolocator();
            geolocator.DesiredAccuracyInMeters = 50;
            Geoposition position = await geolocator.GetGeopositionAsync(
                maximumAge: TimeSpan.FromMinutes(1), timeout: TimeSpan.FromSeconds(30));
            System.Device.Location.GeoCoordinate newCenter = new System.Device.Location.GeoCoordinate(position.Coordinate.Latitude, position.Coordinate.Longitude);
            map1.Center = newCenter;
        }

        public void showStores()
        {
            MapLayer mapLayer = new MapLayer();

            map1.Layers.Add(mapLayer);

            System.Windows.Media.Color color = System.Windows.Media.Colors.Red;

            

            foreach (var store in App.storeViewModel.Stores)
            {
                double lat = store.Lat;
                double lon = store.Lon;
                System.Diagnostics.Debug.WriteLine("adding store"+lat +" "+ lon+"/n");
                GeoCoordinate coordinate = new GeoCoordinate();
                coordinate.Latitude = lat;
                coordinate.Longitude= lon;

                DrawMapMarker(coordinate, color, mapLayer);
            }
        }

        string eventName = "";
        string giftName;
        // Load data for the ViewModel Items
        protected async override void OnNavigatedTo(NavigationEventArgs e)
        {
            string selectedIndex = "";
            if (NavigationContext.QueryString.TryGetValue("selectedItem", out selectedIndex))
            {
                int index = int.Parse(selectedIndex);
                giftName = App.giftViewModel.Gifts[index].Name;
                NavigationContext.QueryString.TryGetValue("Name", out eventName);
                await App.storeViewModel.LoadData(eventName, giftName);
            }
            showStores();           
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

        private void refresh_stores(object sender, RoutedEventArgs e)
        {
            if (!App.storeViewModel.IsDataLoaded)
            {
                App.storeViewModel.LoadData(eventName, giftName);
            }
        }

        private void MainLongListSelector_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            //NavigationService.Navigate(new Uri("/Pages/NewStore.xaml?selectedItem=" + (StoreLongListSelector.SelectedItem as FinalProject.ViewModels.StoreViewModel).ID + "&GiftName=" + giftName + "&EventName" + eventName, UriKind.Relative));
        }

        private void addNewStore_click(object sender, RoutedEventArgs e)
        {
            NavigationService.Navigate(new Uri("/Pages/NewStore.xaml?GiftName=" + giftName + "&EventName=" + eventName, UriKind.Relative));
        }

        private void StoreLongListSelector_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {

        }

        private void refresh_sotres(object sender, RoutedEventArgs e)
        {

        }

        private void DrawMapMarker(GeoCoordinate coordinate, System.Windows.Media.Color color, MapLayer mapLayer)
        {
            // Create a map marker
            Polygon polygon = new Polygon();
            polygon.Points.Add(new Point(0, 0));
            polygon.Points.Add(new Point(0, 75));
            polygon.Points.Add(new Point(25, 0));
            polygon.Fill = new SolidColorBrush(color);

            // Enable marker to be tapped for location information
            polygon.Tag = new GeoCoordinate(coordinate.Latitude, coordinate.Longitude);
            // polygon.MouseLeftButtonUp += new MouseButtonEventHandler(Marker_Click);

            // Create a MapOverlay and add marker
            MapOverlay overlay = new MapOverlay();
            overlay.Content = polygon;
            overlay.GeoCoordinate = new GeoCoordinate(coordinate.Latitude, coordinate.Longitude);
            overlay.PositionOrigin = new Point(0.0, 1.0);
            mapLayer.Add(overlay);
        }
    }
}