using FinalProject.ViewModels;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.ComponentModel;
using System.IO;
using System.Linq;
using System.Net;
using System.Text;
using System.Threading.Tasks;
using System.Windows;

namespace FinalProject
{
    public class StoresViewModel : INotifyPropertyChanged
    {
        public StoresViewModel()
        {
            this.Stores = new ObservableCollection<StoreViewModel>();
        }

        public ObservableCollection<StoreViewModel> Stores { get; private set; }

        public bool IsDataLoaded
        {
            get;
            set;
        }

        public event PropertyChangedEventHandler PropertyChanged;
        private void NotifyPropertyChanged(String propertyName)
        {
            PropertyChangedEventHandler handler = PropertyChanged;
            if (null != handler)
            {
                handler(this, new PropertyChangedEventArgs(propertyName));
            }
        }


        private string _name;
        /// <summary>
        /// Sample ViewModel property; this property is used to identify the object.
        /// </summary>
        /// <returns></returns>
        public string Name
        {
            get
            {
                return _name;
            }
            set
            {
                if (value != _name)
                {
                    _name = value;
                    NotifyPropertyChanged("name");
                }
            }
        }

        string email;
        //string gift;
        FinalProject.App thisApp = Application.Current as FinalProject.App;

        public async Task LoadData(string name, string gift)
        {

            System.Diagnostics.Debug.WriteLine("\nLoading store data \n");
            email = thisApp.UserEmail;
            var uri = new Uri("http://dsjafhbvaio.appspot.com/action?Action=9&Email=" + email + "&Event=" + name +"&Gift="+gift);

            HttpWebRequest webRequest = (HttpWebRequest)WebRequest.Create(uri);
            webRequest.Method = "POST";
            webRequest.ContentType = "application/x-www-form-urlencoded";

            StringBuilder postData = new StringBuilder();
            byte[] byteArray = Encoding.UTF8.GetBytes(postData.ToString());
            string responseString;
            using (var postStream = await webRequest.GetRequestStreamAsync())
            {
                // Write to the request stream.
                // ASYNC: writing to the POST stream can be slow
                await postStream.WriteAsync(byteArray, 0, byteArray.Length);
            }

            try
            {
                // ASYNC: using awaitable wrapper to get response
                var response = (HttpWebResponse)await webRequest.GetResponseAsync();
                if (response != null)
                {
                    var reader = new StreamReader(response.GetResponseStream());
                    // ASYNC: using StreamReader's async method to read to end, in case
                    // the stream i slarge.
                    responseString = await reader.ReadToEndAsync();
                    processDownload(responseString);
                }

            }
            catch (WebException we)
            {
                var reader = new StreamReader(we.Response.GetResponseStream());
                responseString = reader.ReadToEnd();
                System.Diagnostics.Debug.WriteLine(responseString);
            }
        }

        public void processDownload(string data)
        {
            this.Stores.Clear();
            System.Diagnostics.Debug.WriteLine("loading downloaded gift data (ya right it is) \n");
            using (StringReader reader = new StringReader(data))
            {

                string name;
                string line;
                int index = 0;
                while ((line = reader.ReadLine()) != null)
                {
                    if (index == 0 && line == "Fail")
                    {
                        System.Diagnostics.Debug.WriteLine("\nSTORE Fail\n" + line + "\n");
                        return;
                    }
                    System.Diagnostics.Debug.WriteLine("\nSTORE START\n" + line + "\n");
                    name = line;
                    var lat = reader.ReadLine();
                    System.Diagnostics.Debug.WriteLine( lat + "\n");
                    var lon = reader.ReadLine();
                    System.Diagnostics.Debug.WriteLine(lon + "\n");

                    this.Stores.Add(new StoreViewModel() { ID = index, Name = name, Lon = System.Convert.ToDouble(lon), Lat = System.Convert.ToDouble(lat) });

                    index++;
                }
                this.IsDataLoaded = true;
            }

        }
    }
}
