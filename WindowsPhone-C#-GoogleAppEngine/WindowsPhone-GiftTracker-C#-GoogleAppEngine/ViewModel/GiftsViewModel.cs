using FinalProject.ViewModels;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.ComponentModel;
using System.IO;
using System.Linq;
using System.Net;
using System.Text;
using System.Windows;

namespace FinalProject
{
    public class GiftsViewModel : INotifyPropertyChanged
    {
        public GiftsViewModel()
        {
            this.Gifts = new ObservableCollection<GiftViewModel>();
        }

        public ObservableCollection<GiftViewModel> Gifts { get; private set; }

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
        FinalProject.App thisApp = Application.Current as FinalProject.App;

        public async void LoadData(string name)
        {

            System.Diagnostics.Debug.WriteLine("\nLoading gift data \n");
            email = thisApp.UserEmail;
            var uri = new Uri("http://dsjafhbvaio.appspot.com/action?Action=6&Email=" + email +"&Event="+name);

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
            this.Gifts.Clear();
            System.Diagnostics.Debug.WriteLine("loading downloaded gift data (ya right it is) \n");
            using (StringReader reader = new StringReader(data))
            {

                string name;
                string description;
                string forEmail;
                bool aquired;
                string line;
                int index = 0;
                while ((line = reader.ReadLine()) != null)
                {
                    if (index == 0 && line == "Fail")
                    {
                        System.Diagnostics.Debug.WriteLine("\nGIFT Fail\n" + line + "\n");
                        return;
                    }
                    System.Diagnostics.Debug.WriteLine( "\nGIFT START\n" +line +"\n");
                    name = line;
                    description = reader.ReadLine();
                    forEmail = reader.ReadLine();
                    line = reader.ReadLine();
                    aquired = true;
                    if (line == "false")
                        aquired = false;
                    System.Diagnostics.Debug.WriteLine(description + "\n"+ forEmail +"\n"+line+ "\n");
                    //System.Diagnostics.Debug.WriteLine(date + "\n");
                    this.Gifts.Add(new GiftViewModel() { Name = name, Description = description, Aquired = aquired, ForEmail = forEmail, ID = index});
                    index++;
                }
                this.IsDataLoaded = true;
            }

        }
    }
}
