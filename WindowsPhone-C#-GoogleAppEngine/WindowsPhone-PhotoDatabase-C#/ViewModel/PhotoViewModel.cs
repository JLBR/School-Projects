using PhoneApp1.Model;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows;

namespace PhoneApp1.ViewModel
{
    public class PhotoViewModel : INotifyPropertyChanged
    {

        private PhotoDataContext PhotoDB;

        public PhotoViewModel(string PhotoDBConnectionString)
        {
            PhotoDB = new PhotoDataContext(PhotoDBConnectionString);
        }

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


        internal void AddNewPhoto(PhotoItem newPhoto)
        {
            // Add a to-do item to the data context.
            PhotoDB.PhotoItems.InsertOnSubmit(newPhoto);

            // Save changes to the database.
            PhotoDB.SubmitChanges();

            // Add a to-do item to the "all" observable collection.
            PhotoItems.Add(newPhoto);
        }

        // Query database and load the collections and list used by the pivot pages.
        public void LoadCollectionsFromDatabase()
        {

            // Specify the query for all to-do items in the database.
            var PhotoItemsInDB = from PhotoItem pItem in PhotoDB.PhotoItems
                                select pItem;

            // Query the database and load all to-do items.
            PhotoItems = new ObservableCollection<PhotoItem>(PhotoItemsInDB);

        }

        // Write changes in the data context to the database.
        public void SaveChangesToDB()
        {
            PhotoDB.SubmitChanges();
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



    }
}
