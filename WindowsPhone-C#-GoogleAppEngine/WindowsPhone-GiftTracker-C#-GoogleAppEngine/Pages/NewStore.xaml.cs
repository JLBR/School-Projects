using System;
using System.Collections.Generic;
using System.Linq;
using System.Net;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Navigation;
using Microsoft.Phone.Controls;
using Microsoft.Phone.Shell;
using Windows.Devices.Geolocation;
using System.Device.Location;
using System.Windows.Input;
using Microsoft.Phone.Maps.Controls;
using Microsoft.Phone.Maps.Services;
using System.Diagnostics;
using System.Windows.Shapes;
using System.Windows.Media;
using System.Runtime.Serialization.Json;
using System.Runtime.Serialization;

namespace FinalProject.Pages
{
    public partial class NewStore : PhoneApplicationPage
    {

        public NewStore()
        {
            
            InitializeComponent();
            
            markerLayer = new MapLayer();
            map2.Layers.Add(markerLayer);

            //geoQ = new GeocodeQuery();
            //geoQ.QueryCompleted += geoQ_QueryCompleted;
            //Debug.WriteLine("All construction done for GeoCoding");

            System.Windows.Input.Touch.FrameReported += Touch_FrameReported;

            map2.Tap += map2_Tap;

            resultList.SelectionChanged += resultList_SelectionChanged;

            watcher = new GeoCoordinateWatcher(GeoPositionAccuracy.Default);
            watcher.MovementThreshold = 20; // 20 meters

            watcher.StatusChanged += new EventHandler<GeoPositionStatusChangedEventArgs>(OnStatusChanged);
            watcher.PositionChanged += new EventHandler<GeoPositionChangedEventArgs<GeoCoordinate>>(OnPositionChanged);
            newCenter();
            watcher.Start();
        }
        public async void newCenter()
        {
            Geolocator geolocator = new Geolocator();
            geolocator.DesiredAccuracyInMeters = 50;
            Geoposition position = await geolocator.GetGeopositionAsync(
            maximumAge: TimeSpan.FromMinutes(1), timeout: TimeSpan.FromSeconds(30));
            System.Device.Location.GeoCoordinate newCenter = new System.Device.Location.GeoCoordinate(position.Coordinate.Latitude, position.Coordinate.Longitude);
            map2.Center = newCenter;
        }
        FinalProject.App thisApp = Application.Current as FinalProject.App;

        GeoCoordinateWatcher watcher = null;
        GeoCoordinate myPosition = null;
        MapPolygon PolyCircle = null;

        bool draggingNow = false;
        MapLayer markerLayer = null;
        MapOverlay oneMarker = null;
        GeoCoordinate storeLoc = null;

        //string giftName;
       // string eventName;

        IList<MapLocation> GeoResuls = null;

        void resultList_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            if (sender == resultList && (oneMarker != null))
            {
                int indexx = resultList.SelectedIndex;
                if (indexx >= 0 && indexx < GeoResuls.Count())
                {
                    oneMarker.GeoCoordinate = GeoResuls[indexx].GeoCoordinate;
                    map2.Center = oneMarker.GeoCoordinate;
                }
            }
        }


        void map2_Tap(object sender, System.Windows.Input.GestureEventArgs e)
        {
            if (oneMarker != null)
            {
                Point markPoint = map2.ConvertGeoCoordinateToViewportPoint(oneMarker.GeoCoordinate);

                if ((markPoint.X < 0 || markPoint.Y < 0)
                || (map2.ActualWidth < markPoint.X) || (map2.ActualHeight < markPoint.Y))
                {
                    // tap event when we do not have the marker visible, so lets move it here
                    oneMarker.GeoCoordinate = map2.ConvertViewportPointToGeoCoordinate(e.GetPosition(map2));
                }
            }
        }

        void Touch_FrameReported(object sender, TouchFrameEventArgs e)
        {
            if (draggingNow == true)
            {
                TouchPoint tp = e.GetPrimaryTouchPoint(map2);

                if (tp.Action == TouchAction.Move)
                {
                    if (oneMarker != null)
                    {
                        oneMarker.GeoCoordinate = map2.ConvertViewportPointToGeoCoordinate(tp.Position);
                    }
                }
                else if (tp.Action == TouchAction.Up)
                {
                    draggingNow = false;
                    map2.IsEnabled = true;
                }
            }
        }

        protected override void OnNavigatedTo(System.Windows.Navigation.NavigationEventArgs e)
        {
            NavigationContext.QueryString.TryGetValue("EventName", out thisEvent);
            NavigationContext.QueryString.TryGetValue("GiftName", out giftName);
            System.Diagnostics.Debug.WriteLine("New store navigated to\n");
            AddResultToMap(map2.Center);
        }

        void textt_MouseLeftButtonDown(object sender, MouseButtonEventArgs e)
        {
            Debug.WriteLine("textt_MouseLeftButtonDown");
            if (oneMarker != null)
            {
                draggingNow = true;
                map2.IsEnabled = false;
            }
        }

        private void AddResultToMap(GeoCoordinate location)
        {
            if (markerLayer != null)
            {
                map2.Layers.Remove(markerLayer);
                markerLayer = null;
            }

            markerLayer = new MapLayer();
            map2.Layers.Add(markerLayer);

            oneMarker = new MapOverlay();
            oneMarker.GeoCoordinate = location;

            Ellipse Circhegraphic = new Ellipse();
            Circhegraphic.Fill = new SolidColorBrush(Colors.Green);
            Circhegraphic.Stroke = new System.Windows.Media.SolidColorBrush(System.Windows.Media.Colors.Blue);
            Circhegraphic.StrokeThickness = 30;
            Circhegraphic.Opacity = 0.8;
            Circhegraphic.Height = 80;
            Circhegraphic.Width = 80;

            oneMarker.Content = Circhegraphic;

            oneMarker.PositionOrigin = new Point(0.5, 0.5);
            Circhegraphic.MouseLeftButtonDown += textt_MouseLeftButtonDown;

            markerLayer.Add(oneMarker);
            map2.Center = oneMarker.GeoCoordinate;
        }

        private void DrawMapMarker(GeoCoordinate coordinate, Color color, MapLayer mapLayer)
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

        void OnPositionChanged(object sender, GeoPositionChangedEventArgs<GeoCoordinate> e)
        {
            myPosition = e.Position.Location;
            MylocDot.Fill = new SolidColorBrush(Colors.Green);
            UpDateMyPositionCircle();
        }

        void OnStatusChanged(object sender, GeoPositionStatusChangedEventArgs e)
        {
            switch (e.Status)
            {
                case GeoPositionStatus.Ready:
                    break;
                case GeoPositionStatus.Disabled:
                case GeoPositionStatus.Initializing:
                case GeoPositionStatus.NoData:
                default:
                    {
                        myPosition = null;
                        MylocDot.Fill = new SolidColorBrush(Colors.Gray);
                        UpDateMyPositionCircle();
                    }
                    break;
            }
        }

        void UpDateMyPositionCircle()
        {
            if (myPosition != null)
            {
                double accuracy = myPosition.HorizontalAccuracy;

                if (accuracy < myPosition.VerticalAccuracy)
                {
                    accuracy = myPosition.VerticalAccuracy;
                }

                if (PolyCircle == null)
                {
                    PolyCircle = new MapPolygon();

                    PolyCircle.FillColor = Color.FromArgb(0x55, 0x00, 0xFF, 0x00);
                    PolyCircle.StrokeColor = Color.FromArgb(0xFF, 0x00, 0xFF, 0x00);
                    PolyCircle.StrokeThickness = 1;

                    map2.MapElements.Add(PolyCircle);
                }

                if (accuracy < 50)
                {
                    accuracy = 50; // to be able to show the polygon
                }

                PolyCircle.Path = CreateCircle(myPosition, accuracy);
            }
            else if (PolyCircle != null)
            {
                map2.MapElements.Remove(PolyCircle);
                PolyCircle = null;
            }
        }

        public static double ToRadian(double degrees)
        {
            return degrees * (Math.PI / 180);
        }

        public static double ToDegrees(double radians)
        {
            return radians * (180 / Math.PI);
        }

        public static GeoCoordinateCollection CreateCircle(GeoCoordinate center, double radius)
        {
            var earthRadius = 6367000; // radius in meters
            var lat = ToRadian(center.Latitude); //radians
            var lng = ToRadian(center.Longitude); //radians
            var d = radius / earthRadius; // d = angular distance covered on earth's surface
            var locations = new GeoCoordinateCollection();

            for (var x = 0; x <= 360; x++)
            {
                var brng = ToRadian(x);
                var latRadians = Math.Asin(Math.Sin(lat) * Math.Cos(d) + Math.Cos(lat) * Math.Sin(d) * Math.Cos(brng));
                var lngRadians = lng + Math.Atan2(Math.Sin(brng) * Math.Sin(d) * Math.Cos(lat), Math.Cos(d) - Math.Sin(lat) * Math.Sin(latRadians));

                locations.Add(new GeoCoordinate(ToDegrees(latRadians), ToDegrees(lngRadians)));
            }

            return locations;
        }

        void myLocation_MouseLeftButtonUp(object sender, MouseButtonEventArgs e)
        {
            if (myPosition != null)
            {
                map2.Center = myPosition;
            }
        }

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


        string thisEvent;
        string giftName;

        private void addStore_click(object sender, RoutedEventArgs e)
        {
            storeLoc =  oneMarker.GeoCoordinate;
            System.Diagnostics.Debug.WriteLine("New Gift clicked \n");
            var name = giftName;
            var email = thisApp.UserEmail;

            var uri = new Uri("http://dsjafhbvaio.appspot.com/action?Action=8&Email=" + email +
                "&Event=" + thisEvent +
                "&Gift=" + name +
                "&Name=" + StoreName.Text +
                "&Lat=" + storeLoc.Latitude +
                "&Long=" +storeLoc.Longitude);

            errorMessage.Text = "Connecting to server";

            HttpWebRequest webRequest = (HttpWebRequest)WebRequest.Create(uri);
            webRequest.Method = "POST";
            //webRequest.ContentType = "application/x-www-form-urlencoded";

            // Start web request
            webRequest.BeginGetRequestStream(new AsyncCallback(loginCallback), webRequest);
            App.ViewModel.IsDataLoaded = false;
        }

        private void loginCallback(IAsyncResult asynchronousResult)
        {
            try
            {

                HttpWebRequest webRequest = (HttpWebRequest)asynchronousResult.AsyncState;
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

                System.Diagnostics.Debug.WriteLine("Running new store results\n");
                using (var resp = (HttpWebResponse)request.EndGetResponse(asynchronousResult))
                {
                    using (var streamResponse = resp.GetResponseStream())
                    {
                        var LoginSerializerData = new DataContractJsonSerializer(typeof(ResultsLogin));
                        var LoginResultsData = LoginSerializerData.ReadObject(streamResponse) as ResultsLogin;
                        this.Dispatcher.BeginInvoke(
                            (Action<ResultsLogin>)((UserLoginData) =>
                            {
                                System.Diagnostics.Debug.WriteLine("Running addnew store Inside dispatcher\n");
                                //save the response
                                if (LoginResultsData == null)
                                {
                                    this.errorMessage.Text = "Error connecting to server in";
                                }
                                else
                                {
                                    this.errorMessage.Text = LoginResultsData.success;
                                    thisApp.sessionID = LoginResultsData.sessionID;
                                    if (LoginResultsData.success == "Fail")
                                    {
                                        this.errorMessage.Text = "Error new store was not created";
                                    }
                                    else
                                    {
                                        NavigationService.GoBack();
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

        private void myLoc_click(object sender, RoutedEventArgs e)
        {
            newCenter();
        }
    }
}