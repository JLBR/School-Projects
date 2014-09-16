using System;
using System.Collections.ObjectModel;
using System.ComponentModel;
using FinalProject.Resources;
using System.Windows;
using System.Net;
using System.IO;
using System.Text;
using System.Threading.Tasks;
using System.Threading;
using FinalProject.ViewModel;

namespace FinalProject.ViewModels
{
    public class HomeViewModel : INotifyPropertyChanged
    {
        public HomeViewModel()
        {
            this.Events = new ObservableCollection<EventViewModel>();
        }

        /// <summary>
        /// A collection for ItemViewModel objects.
        /// </summary>
        public ObservableCollection<EventViewModel> Events { get; private set; }
        string email;
        FinalProject.App thisApp = Application.Current as FinalProject.App;
        /// <summary>
        /// Sample property that returns a localized string
        /// </summary>

        public bool IsDataLoaded
        {
            get;
            set;
        }

        //public static processDownloadedEvents downloadDone = new processDownloadedEvents();
        /// <summary>
        /// Creates and adds a few ItemViewModel objects into the Items collection.
        /// </summary>
        public async void LoadData()
        {
            System.Diagnostics.Debug.WriteLine("\nLoading event data \n");
            email = thisApp.UserEmail;
            var uri = new Uri("http://dsjafhbvaio.appspot.com/action?Action=5&Email=" + email);
            System.Diagnostics.Debug.WriteLine("Loading event data \n");


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
            this.Events.Clear();
            System.Diagnostics.Debug.WriteLine("loading downloaded data (ya right it is) \n");
            using (StringReader reader = new StringReader(data))
            {
                
                string name;
                string date;
                string line;
                int index = 0;
                while ((line = reader.ReadLine()) != null)
                {
                    if (index == 0 && line == "Fail")
                        return;
                    System.Diagnostics.Debug.WriteLine(line + "\n");
                    name = line;
                    date = reader.ReadLine();
                    System.Diagnostics.Debug.WriteLine(date + "\n");
                    this.Events.Add(new EventViewModel() { Name = name, Date = date, ID = index });
                    index++;
                }
                this.IsDataLoaded = true;
            }

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

    }
}

//from http://stackoverflow.com/questions/14577346/converting-ordinary-http-post-web-request-with-async-and-await
public static class WebRequestAsyncExtensions
{
    public static Task<Stream> GetRequestStreamAsync(this WebRequest request)
    {
        return Task.Factory.FromAsync<Stream>(
            request.BeginGetRequestStream, request.EndGetRequestStream, null);
    }

    public static Task<WebResponse> GetResponseAsync(this WebRequest request)
    {
        return Task.Factory.FromAsync<WebResponse>(
            request.BeginGetResponse, request.EndGetResponse, null);
    }
}