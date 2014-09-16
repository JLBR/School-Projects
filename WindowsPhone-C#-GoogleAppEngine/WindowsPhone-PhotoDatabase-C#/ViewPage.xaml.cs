using System;
using System.Collections.Generic;
using System.Linq;
using System.Net;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Navigation;
using Microsoft.Phone.Controls;
using Microsoft.Phone.Shell;
using System.Data.Linq;
using System.Data.Linq.Mapping;
using System.ComponentModel;
using System.Collections.ObjectModel;
using PhoneApp1.Model;

namespace PhoneApp1
{
    public partial class ViewPhotosPage : PhoneApplicationPage
    {

        // Data context for the local database
        private PhotoDataContext PhotoDB;

        // Define an observable collection property that controls can bind to.
        private ObservableCollection<PhotoItem> _PhotoItems;
        public ObservableCollection<PhotoItem> PhotoItems
        {
            get
            {
                return _PhotoItems;
            }
            set
            {
                if (_PhotoItems != value)
                {
                    _PhotoItems = value;
                    NotifyPropertyChanged("PhotoItems");
                }
            }
        }

        #region INotifyPropertyChanged Members

        public event PropertyChangedEventHandler PropertyChanged;

        // Used to notify the app that a property has changed.
        private void NotifyPropertyChanged(string propertyName)
        {
            if (PropertyChanged != null)
            {
                PropertyChanged(this, new PropertyChangedEventArgs(propertyName));
            }
        }
        #endregion

        public ViewPhotosPage()
        {
            InitializeComponent();

            // Data context and observable collection are children of the main page.
            this.DataContext = App.ViewModel;
        }

        private void TakePicturesButtons_Click(object sender, RoutedEventArgs e)
        {
            NavigationService.Navigate(new Uri("/MainPage.xaml", UriKind.Relative));
        }
    }
}