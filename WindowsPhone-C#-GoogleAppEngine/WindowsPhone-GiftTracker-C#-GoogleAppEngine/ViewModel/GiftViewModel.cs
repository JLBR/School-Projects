using System;
using System.ComponentModel;

namespace FinalProject
{
    public class GiftViewModel : INotifyPropertyChanged
    {
        private int _ID;
        /// <summary>
        /// Sample ViewModel property; this property is used to identify the object.
        /// </summary>
        /// <returns></returns>
        public int ID
        {
            get
            {
                return _ID;
            }
            set
            {
                if (value != _ID)
                {
                    _ID = value;
                    NotifyPropertyChanged("ID");
                }
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

        private string _description;
        /// <summary>
        /// Sample ViewModel property; this property is used in the view to display its value using a Binding.
        /// </summary>
        /// <returns></returns>
        public string Description
        {
            get
            {
                return _description;
            }
            set
            {
                if (value != _description)
                {
                    _description = value;
                    NotifyPropertyChanged("description");
                }
            }
        }

        private string _forEmail;

        /// <summary>
        /// Sample ViewModel property; this property is used in the view to display its value using a Binding.
        /// </summary>
        /// <returns></returns>
        public string ForEmail
        {
            get
            {
                return _forEmail;
            }
            set
            {
                if (value != _forEmail)
                {
                    _forEmail = value;
                    NotifyPropertyChanged("forEmail");
                }
            }
        }
        private bool _aquired;
        /// <summary>
        /// Sample ViewModel property; this property is used in the view to display its value using a Binding.
        /// </summary>
        /// <returns></returns>
        public bool Aquired
        {
            get
            {
                return _aquired;
            }
            set
            {
                if (value != _aquired)
                {
                    _aquired = value;
                    NotifyPropertyChanged("aquired");
                }
            }
        }

        private bool _claimedByEmail;
        /// <summary>
        /// Sample ViewModel property; this property is used in the view to display its value using a Binding.
        /// </summary>
        /// <returns></returns>
        public bool ClaimedByEmail
        {
            get
            {
                return _claimedByEmail;
            }
            set
            {
                if (value != _claimedByEmail)
                {
                    _claimedByEmail = value;
                    NotifyPropertyChanged("claimedByEmail");
                }
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