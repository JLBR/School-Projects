#Script 1
#Your name:Jimmy 


DROP TABLE IF EXISTS airport;
DROP TABLE IF EXISTS apType;
	
#Your code goes below here

#Create the table 'apType' with an auto incrementing integer 'id' 
#field and a 255 variable length string 'type' which cannot be null
#10pts

CREATE TABLE apType
(
 id	INT		NOT NULL AUTO_INCREMENT,
 type	VARCHAR(255)	NOT NULL,
 PRIMARY KEY (id)
)ENGINE=InnoDB;

#Create the table 'airport' with an autio incrementing 'id' field as
#the primary key, a 255 character variable length string field for 'name'
#which cannot be null, an integer 'rwyCount', 'lat' and 'lon' as floats
#and an integer 'type' that corrisponds to a type id which must be from the apType table
#10pts

CREATE TABLE airport
(
 id		INT		NOT NULL AUTO_INCREMENT,
 name		VARCHAR(255)	NOT NULL,
 rwyCount 	FLOAT,
 lat		FLOAT,
 lon		FLOAT,
 type		INT,
 PRIMARY KEY (id),
 FOREIGN KEY (type) REFERENCES apType (id)
)ENGINE=InnoDB;


#Insert the types 'private', 'public' and 'military' into 'apType'
#5pts

INSERT INTO apType(type)
VALUES	('private'),
	('public'),
	('military');
 

#Insert the airport 'Kahului' with 1 runway, a type of 'public' and a lat/lon of 20.898611/-156.430556
#5pts

INSERT INTO airport (name, rwyCount, lat, lon, type)
VALUES	('Kahului',
	1,
	20.898611,
	-156.430556, 
	(SELECT id FROM apType WHERE type = 'public'));

#Insert the airport 'Portland' with 3 runways a type of 'public' and a lat/lon of 45.588611/-122.5975
#5pts

INSERT INTO airport (name, rwyCount, lat, lon, type)
VALUES	('Portland',
	3,
	45.588611,
	-122.5975,
	(SELECT id FROM apType WHERE type = 'public'));


#Update the runway count to 2 for Kahului airport
#5pts

UPDATE airport
SET rwyCount = 2 WHERE name = 'Kahului';


#Delete Portland airport
#5pts

DELETE FROM airport
WHERE name = 'Portland';

#Attempt to add an airport with an invalid type id and have it fail
#5pts

INSERT INTO airport (name, rwyCount, lat, lon, type)
VALUES	('Portland',
	3,
	45.588611,
	-122.5975,
	888);
