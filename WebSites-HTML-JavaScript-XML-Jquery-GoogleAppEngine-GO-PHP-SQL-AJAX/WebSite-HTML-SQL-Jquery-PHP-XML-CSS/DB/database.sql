DROP TABLE IF EXISTS  Address;

CREATE TABLE  Address(
   ADDRESS_ID	 int 		NOT NULL AUTO_INCREMENT,
   FirmName	 varchar(38),
   Address1  	 varchar(38),
   Address2  	 varchar(38),
   City 	 varchar(15),
   State	 varchar(2),
   Urbanization	 varchar(28),
   Zip5		 varchar(5),
   Zip4		 varchar(4),
  PRIMARY KEY  ( ADDRESS_ID )
);

DROP TABLE IF EXISTS  Contact;

CREATE TABLE  Contact(
   CONTACT_ID	 int 		NOT NULL AUTO_INCREMENT,
   Phone  	 varchar(14),
   Email  	 varchar(100),
   Name 	 varchar(60),
  PRIMARY KEY  ( CONTACT_ID )
);

#UserType values:
# Admin: 1
# Backup: 2
# Police: 3
# Fire: 4
# Normal: 5
# Special: 6
DROP TABLE IF EXISTS  Users;

CREATE TABLE  Users(
   USER_ID	 int 		NOT NULL AUTO_INCREMENT,
   UserName	 varchar(16)	NOT NULL UNIQUE,
   Email	 varchar(100)	NOT NULL UNIQUE,
   FirstName	 varchar(16)    NOT NULL,
   LastName  	 varchar(16) 	NOT NULL,
   Verrified	 int		NOT NULL,
   UserType	 int		NOT NULL,
   Password	 varchar(20)	NOT NULL,
  PRIMARY KEY  ( USER_ID )
);

DROP TABLE IF EXISTS  Pile;

#report 0 false
#       1 true
#size 	0 small
#	1 med
#	2 large
CREATE TABLE  Pile(
   PILE_ID	 int 		NOT NULL AUTO_INCREMENT,
   Start  	 DATETIME,
   Stop  	 DATETIME,
   Size 	 int,
   Photo	 MEDIUMBLOB,
   Status	 int,
   Lat		 int,
   FK_ADDRESS_ID int,
   Lon		 int,
   FK_PCONTACT	 int,
   Verrified	 int,
   FK_USER_ID	 int,
   report	 int,
   request	 varchar(20),
   materials	 varchar(20),
   FK_PICK_UP_ID int,
  PRIMARY KEY ( PILE_ID ),
  FOREIGN KEY ( FK_USER_ID ) 	REFERENCES Users ( USER_ID ),
  FOREIGN KEY ( FK_PICK_UP_ID ) REFERENCES Users ( USER_ID ),
  FOREIGN KEY ( FK_PCONTACT ) 	REFERENCES Contact ( CONTACT_ID ),
  FOREIGN KEY ( FK_ADDRESS_ID )	REFERENCES Address ( ADDRESS_ID )
);

DROP TABLE IF EXISTS PileContact;

CREATE TABLE  PileContact(
   FK_PILE_ID	 int 		NOT NULL,
   FK_CONTACT_ID int		NOT NULL,
  FOREIGN KEY ( FK_CONTACT_ID ) REFERENCES Contact ( CONTACT_ID ),
  FOREIGN KEY ( FK_PILE_ID ) 	REFERENCES Pile (PILE_ID)
);

DROP TABLE IF EXISTS FireDepartment;

CREATE TABLE  FireDepartment(
   DEPARTMENT_ID int 		NOT NULL,
   FK_PCONTACT_ID int		NOT NULL,
  PRIMARY KEY ( DEPARTMENT_ID ),
  FOREIGN KEY ( FK_PCONTACT_ID ) REFERENCES Contact ( CONTACT_ID )
);
