using System.ComponentModel;
using System.Data.Linq;
using System.Data.Linq.Mapping;

namespace PhoneApp1.Model
{
    //from http://msdn.microsoft.com/en-us/library/windowsphone/develop/hh202876%28v=vs.105%29.aspx#BKMK_CreatingtheApplicationUI
    public class PhotoDataContext : DataContext
    {
        // Specify the connection string as a static, used in main page and app.xaml.
        //public static string DBConnectionString = "Data Source=isostore:/HW3.sdf";

        // Pass the connection string to the base class.
        public PhotoDataContext(string connectionString)
            : base(connectionString) 
        { }

        // Specify a single table for the photo information.
        public Table<PhotoItem> PhotoItems;
    }

    //from http://msdn.microsoft.com/en-us/library/windowsphone/develop/hh202876%28v=vs.105%29.aspx#BKMK_CreatingtheApplicationUI
    [Table]
    public class PhotoItem : INotifyPropertyChanged, INotifyPropertyChanging
    {
        // Define ID: private field, public property and database column.
        private int _PhotoItemId;

        [Column(IsPrimaryKey = true, IsDbGenerated = true, DbType = "INT NOT NULL Identity", CanBeNull = false, AutoSync = AutoSync.OnInsert)]
        public int PhotoItemId
        {
            get
            {
                return _PhotoItemId;
            }
            set
            {
                if (_PhotoItemId != value)
                {
                    NotifyPropertyChanging("PhotoItemId");
                    _PhotoItemId = value;
                    NotifyPropertyChanged("PhotoItemId");
                }
            }
        }

        // Define item name: private field, public property and database column.
        private string _PhotoCaption;

        [Column]
        public string PhotoCaption
        {
            get
            {
                return _PhotoCaption;
            }
            set
            {
                if (_PhotoCaption != value)
                {
                    NotifyPropertyChanging("PhotoCaption");
                    _PhotoCaption = value;
                    NotifyPropertyChanged("PhotoCaption");
                }
            }
        }

        // Define completion value: private field, public property and database column.
        private string _PhotoFileName;

        [Column]
        public string PhotoFileName
        {
            get
            {
                return _PhotoFileName;
            }
            set
            {
                if (_PhotoFileName != value)
                {
                    NotifyPropertyChanging("IsComplete");
                    _PhotoFileName = value;
                    NotifyPropertyChanged("IsComplete");
                }
            }
        }
        // Version column aids update performance.
        [Column(IsVersion = true)]
        private Binary _version;

        #region INotifyPropertyChanged Members

        public event PropertyChangedEventHandler PropertyChanged;

        // Used to notify the page that a data context property changed
        private void NotifyPropertyChanged(string propertyName)
        {
            if (PropertyChanged != null)
            {
                PropertyChanged(this, new PropertyChangedEventArgs(propertyName));
            }
        }

        #endregion

        #region INotifyPropertyChanging Members

        public event PropertyChangingEventHandler PropertyChanging;

        // Used to notify the data context that a data context property is about to change
        private void NotifyPropertyChanging(string propertyName)
        {
            if (PropertyChanging != null)
            {
                PropertyChanging(this, new PropertyChangingEventArgs(propertyName));
            }
        }

        #endregion
    }
}