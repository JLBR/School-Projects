using System;
using System.Collections.Generic;
using System.Linq;
using System.Net;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Navigation;
using Microsoft.Phone.Controls;
using Microsoft.Phone.Shell;
using System.Runtime.Serialization;
using System.IO;

namespace FinalProject.Pages
{
    public partial class MapPage : PhoneApplicationPage
    {
        public MapPage()
        {
            InitializeComponent();
        }

        [DataContract]
        public class Place
        {
            [DataMember(Name = "summary")]
            public string Summary { get; set; }

            [DataMember(Name = "distance")]
            public string Distance { get; set; }

            [DataMember(Name = "rank")]
            public string Rank { get; set; }

            [DataMember(Name = "title")]
            public string Title { get; set; }

            [DataMember(Name = "wikipediaUrl")]
            public string WikipediaUrl { get; set; }

            [DataMember(Name = "elevation")]
            public string Elevation { get; set; }

            [DataMember(Name = "lng")]
            public string Longitude { get; set; }

            [DataMember(Name = "feature")]
            public string Feature { get; set; }

            [DataMember(Name = "lang")]
            public string Langauge { get; set; }

            [DataMember(Name = "lat")]
            public string Latitude { get; set; }
        }

        [DataContract]
        public class PlacesList
        {
            [DataMember(Name = "geonames")]
            public List<Place> PlaceList { get; set; }
        }

        private void startGeoNamesAPICall()
        {
            try
            {
                HttpWebRequest httpReq = (HttpWebRequest)HttpWebRequest.Create(new Uri("http://api.geonames.org/findNearbyWikipediaJSON?" + "&lat=" + currentLatitude + "&lng=" + currentLongitude + "&username=jlbrewer&radius=" + AppConstants.strDefaultRadiusForWikiAPI + "&maxRows=" + AppConstants.strDefaultResultRowsForWikiAPI));
                httpReq.BeginGetResponse(HTTPWebRequestCallBack, httpReq);
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message);
            }
        }

        private void HTTPWebRequestCallBack(IAsyncResult result)
        {
            string strResponse = "";

            try
            {
                Dispatcher.BeginInvoke(() =>
                {
                    try
                    {
                        HttpWebRequest httpRequest = (HttpWebRequest)result.AsyncState;
                        WebResponse response = httpRequest.EndGetResponse(result);
                        Stream stream = response.GetResponseStream();
                        StreamReader reader = new StreamReader(stream);
                        strResponse = reader.ReadToEnd();

                        parseResponseData(strResponse);
                    }
                    catch (Exception ex)
                    {
                        MessageBox.Show(ex.Message);
                    }
                });
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message);
            }
        }

        private void parseResponseData(String aResponse)
        {
            placesListObj = new PlacesList();

            MemoryStream ms = new MemoryStream(Encoding.UTF8.GetBytes(aResponse));
            DataContractJsonSerializer ser = new DataContractJsonSerializer(placesListObj.GetType());
            placesListObj = ser.ReadObject(ms) as PlacesList;
            ms.Close();

            // updating UI
            if (placesListObj != null)
            {
                updateMap(placesListObj);
            }
        }

        private void updateMap(PlacesList aWiKIAPIResponse)
        {
            int totalRecords = aWiKIAPIResponse.PlaceList.Count();
            mapControl.CredentialsProvider = new ApplicationIdCredentialsProvider(AppConstants.strBingMapCredentialKey);
            mapControl.Visibility = System.Windows.Visibility.Visible;
            mapControl.ZoomLevel = 10;
            mapControl.ZoomBarVisibility = Visibility.Visible;

            try
            {
                for (int index = 0; index < totalRecords; index++)
                {
                    double latitude = Convert.ToDouble(aWiKIAPIResponse.PlaceList[index].Latitude, CultureInfo.InvariantCulture);
                    double longitude = Convert.ToDouble(aWiKIAPIResponse.PlaceList[index].Longitude, CultureInfo.InvariantCulture);

                    mapControl.Center = new GeoCoordinate(latitude, longitude);

                    Pushpin pushpinObj = new Pushpin();
                    pushpinObj.Content = aWiKIAPIResponse.PlaceList[index].Title + "\n" + aWiKIAPIResponse.PlaceList[index].Feature;
                    pushpinObj.Background = new SolidColorBrush(Colors.Red);
                    pushpinObj.Location = mapControl.Center;
                    pushpinObj.Opacity = 0.5;
                    mapControl.Children.Add(pushpinObj);
                }
            }
            catch (Exception)
            {
            }
        }

    }
}