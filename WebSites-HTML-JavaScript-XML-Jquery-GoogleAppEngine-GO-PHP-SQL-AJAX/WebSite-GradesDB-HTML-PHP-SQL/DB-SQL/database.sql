DROP TABLE IF EXISTS  Students ;

##Term and school ID are for the application's use in setting prefrences

CREATE TABLE  Students (
  STUDENT_ID	 int 		NOT NULL AUTO_INCREMENT,
   FName  	 varchar(20)  	NOT NULL,
   LName  	 varchar(20) 	NOT NULL,
   Term		 varchar(20),
   FK_SCHOOL_ID  int,
  PRIMARY KEY  ( STUDENT_ID )
);

DROP TABLE IF EXISTS  School ;

CREATE TABLE  School  (
   SCHOOL_ID  		 int 		NOT NULL AUTO_INCREMENT,
   Term  		 varchar(20) 	NOT NULL,
   School_Name  	 varchar(50) 	NOT NULL,
  PRIMARY KEY  ( SCHOOL_ID )
);

DROP TABLE IF EXISTS  GradeStandard ;

CREATE TABLE  GradeStandard  (
   GRADE_STANDARD_ID  	int 		NOT NULL AUTO_INCREMENT,
   Grade_Name  		varchar(20) 	NOT NULL,
   Grade_Point  	FLOAT(5,3),
   GradeCatagory  	varchar(20),
   CR_NC		ENUM('CR','NC'),
  PRIMARY KEY  ( GRADE_STANDARD_ID )
);

DROP TABLE IF EXISTS  CourseCatagory ;

CREATE TABLE  CourseCatagory  (
   CATEGORY_ID    int 		NOT NULL AUTO_INCREMENT,
   Cat_Name  	  varchar(30) 	NOT NULL,
  PRIMARY KEY  (CATEGORY_ID)
);

DROP TABLE IF EXISTS  Class ;

CREATE TABLE  Class  (
   CLASS_ID     	 int 		NOT NULL AUTO_INCREMENT,
   FK_SCHOOL_ID		 int		NOT NULL,
   Class_Name  		 varchar(60) 	NOT NULL,
   Units  		 int,
   Term			 varchar(10),
   Course_ID		 varchar(10)	NOT NULL,
  PRIMARY KEY  (CLASS_ID),
  FOREIGN KEY  (FK_SCHOOL_ID)  REFERENCES School(SCHOOL_ID)
);

DROP TABLE IF EXISTS  ClassCatagories ;

CREATE TABLE  ClassCatagories  (
   FK_CLASS_ID     	 int 		NOT NULL,
   FK_CAT_ID		 int		NOT NULL,
  FOREIGN KEY  (FK_CLASS_ID)	REFERENCES Class(CLASS_ID),
  FOREIGN KEY  (FK_CAT_ID)   	REFERENCES CourseCatagory(CATEGORY_ID)
);

DROP TABLE IF EXISTS  Course_Grades;

CREATE TABLE  Course_Grades  (
   EVAL_CAT_ID     	 int 		NOT NULL AUTO_INCREMENT,
   FK_CLASS_ID		 int		NOT NULL,
   Eval_Catagory	 varchar(30)	NOT NULL,
   Weight		 int,	
  PRIMARY KEY  (EVAL_CAT_ID),	
  FOREIGN KEY  (FK_CLASS_ID)	REFERENCES Class(CLASS_ID)
);

DROP TABLE IF EXISTS  Evaluation ;

CREATE TABLE  Evaluation  (
   EVAL_ID    		int 		NOT NULL AUTO_INCREMENT,
   FK_CLASS_ID  	int 		NOT NULL,
   FK_STUDENT_ID  	int		NOT NULL,
   FK_EVAL_CAT_ID	int,
   EvalName		varchar(30)	NOT NULL,
   ActualScore  	int,
   MaxScore  		int,
   Complete  		ENUM ( 'Yes', 'No' ),
  PRIMARY KEY  (EVAL_ID),
  FOREIGN KEY  (FK_CLASS_ID)  	REFERENCES Class(CLASS_ID),
  FOREIGN KEY  (FK_STUDENT_ID)  REFERENCES Students(STUDENT_ID),
  FOREIGN KEY  (FK_EVAL_CAT_ID) REFERENCES Course_Grades(EVAL_CAT_ID)
);

DROP TABLE IF EXISTS  Roster ;

CREATE TABLE  Roster  (
   FK_STUDENT_ID    	int NOT NULL,
   FK_CLASS_ID  	int NOT NULL,
   Final_Grade		int,
  FOREIGN KEY  (FK_CLASS_ID)  	REFERENCES Class(CLASS_ID),
  FOREIGN KEY  (FK_STUDENT_ID)  REFERENCES Students(STUDENT_ID),
  FOREIGN KEY  (Final_Grade)    REFERENCES GradeStandard(GRADE_STANDARD_ID)
);

DROP TABLE IF EXISTS  SchoolGrades;

CREATE TABLE  SchoolGrades (
   FK_GRADE_STANDARD_ID 	int NOT NULL,
   FK_SCHOOL_ID  		int NOT NULL,
  FOREIGN KEY  (FK_GRADE_STANDARD_ID)  	REFERENCES GradeStandard(GRADE_STANDARD_ID),
  FOREIGN KEY  (FK_SCHOOL_ID)  		REFERENCES School(SCHOOL_ID),
  UNIQUE (FK_GRADE_STANDARD_ID ,FK_SCHOOL_ID )
);



INSERT INTO Students (Fname,Lname)
VALUES	('Lelouch','Lamperouge'),
	('Suzaku','Kururugi'),
	('Kallen', 'Stadtfeld'),
	('Nunnally', 'Lamperouge'),
	('Milly', 'Ashford'),
	('Rivalz', 'Cardemonde'),
	('Nina', 'Einstein'),
	('Shirley', 'Fenette'),
	('Sayoko', 'Shinozaki'),
	('Kaname', 'Oogi');

INSERT INTO School (Term, School_Name)
VALUES	('Semester','Ashford Academy'),
	('Quarter','Celadon University'),
	('Quarter','University of Quahog'),
	('Quarter','South Harmon Institute of Technology');

INSERT INTO GradeStandard (Grade_Name, Grade_Point, GradeCatagory, CR_NC)
VALUES	('A',4.0,'Normal', 'CR'),
	('B',3.0,'Normal', 'CR'),
	('C',2.0,'Normal', 'CR'),
	('D',1.0,'Normal', 'CR'),
	('F',0.0,'Normal', 'CR'),
	('E',0.0,'Normal', 'CR'),
	('W',0.0,'Withdrawl', 'NC'),
	('U',0.0,'Anauthorized Incomplete', 'CR'),
	('I',0.0,'Authorized Incomplete', 'NC'),
	('Pass',0.0,'Pass/Fail', 'CR'),
	('Fail',0.0,'Pass/Fail', 'CR'),
	('Audit',0.0,'Audit', 'NC');

INSERT INTO CourseCatagory (Cat_Name)
VALUES	('General Education'),('Kinesiology'),
	('Work Experience'),('Upper Division'),
	('Lower Division'),('Math'),
	('Busness'), ('Leadership');

INSERT INTO Class(FK_SCHOOL_ID, Course_ID, Class_Name, Units, Term )
VALUES	((SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy') ,
	'HIS 234', 'Current History', 4, 'Spring2013'),
	((SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy') ,
	'BA 290', 'Business Professions', 4, 'Spring2013'),
	((SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy') ,
	'BA 200', 'Managing Diversity in Business Organizations', 4, 'Spring2013'),
	((SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy') ,
	'ECON 380', 'Gender and Diversity in the Workplace', 4, 'Spring2013'),
	((SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy') ,
	'ACCT 220', 'Introduction to Financial Reporting and Accounting', 4, 'Spring2013'),
	((SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy') ,
	'ACCT 221', 'Introduction to Managerial Accounting', 4, 'Spring2013'),
	((SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy') ,
	'ECON 201', 'Essentials of Microeconomics', 4, 'Spring2013'),
	((SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy') ,
	'ECON 202', 'Essentials of Macroeconomics', 4, 'Spring2013'),
	((SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy') ,
	'MGT599', 'Leadership Theory and Practice', 4, 'Spring2013'),
	((SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy') ,
	'MGT501', 'Management Theory and Practice', 4, 'Spring2013'),
	((SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy') ,
	'MGT594', 'Leadership and Business Ethics', 4, 'Spring2013');

INSERT INTO ClassCatagories(FK_CAT_ID, FK_CLASS_ID)
VALUES	((SELECT CATEGORY_ID FROM CourseCatagory WHERE Cat_Name = 'General Education') ,
	(SELECT CLASS_ID FROM Class WHERE FK_SCHOOL_ID = (SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')
		AND Course_ID = 'HIS 234')) ,
	((SELECT CATEGORY_ID FROM CourseCatagory WHERE Cat_Name = 'Lower Division') ,
	(SELECT CLASS_ID FROM Class WHERE FK_SCHOOL_ID = (SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')
		AND Course_ID = 'HIS 234')) ,
	((SELECT CATEGORY_ID FROM CourseCatagory WHERE Cat_Name = 'Business') ,
	(SELECT CLASS_ID FROM Class WHERE FK_SCHOOL_ID = (SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')
		AND Course_ID = 'BA 290')) ,
	((SELECT CATEGORY_ID FROM CourseCatagory WHERE Cat_Name = 'Upper Divison') ,
	(SELECT CLASS_ID FROM Class WHERE FK_SCHOOL_ID = (SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')
		AND Course_ID = 'BA 290')) ,
	((SELECT CATEGORY_ID FROM CourseCatagory WHERE Cat_Name = 'General Education') ,
	(SELECT CLASS_ID FROM Class WHERE FK_SCHOOL_ID = (SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')
		AND Course_ID = 'ECON 201')) ,
	((SELECT CATEGORY_ID FROM CourseCatagory WHERE Cat_Name = 'Buissness') ,
	(SELECT CLASS_ID FROM Class WHERE FK_SCHOOL_ID = (SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')
		AND Course_ID = 'ECON 201')) ,
	((SELECT CATEGORY_ID FROM CourseCatagory WHERE Cat_Name = 'General Education') ,
	(SELECT CLASS_ID FROM Class WHERE FK_SCHOOL_ID = (SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')
		AND Course_ID = 'HIS 234')) ;


INSERT INTO Course_Grades(FK_CLASS_ID, Eval_Catagory, Weight)
VALUES	((SELECT CLASS_ID FROM Class WHERE FK_SCHOOL_ID = (SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')
		AND Course_ID = 'HIS 234'), 'Quiz', 20) ,
	((SELECT CLASS_ID FROM Class WHERE FK_SCHOOL_ID = (SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')
		AND Course_ID = 'HIS 234'), 'Final', 15) ,
	((SELECT CLASS_ID FROM Class WHERE FK_SCHOOL_ID = (SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')
		AND Course_ID = 'HIS 234'), 'Mid-Term', 15) ,
	((SELECT CLASS_ID FROM Class WHERE FK_SCHOOL_ID = (SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')
		AND Course_ID = 'HIS 234'), 'Assignments', 30) ,
	((SELECT CLASS_ID FROM Class WHERE FK_SCHOOL_ID = (SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')
		AND Course_ID = 'HIS 234'), 'Project', 10) ,
	((SELECT CLASS_ID FROM Class WHERE FK_SCHOOL_ID = (SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')
		AND Course_ID = 'HIS 234'), 'Participation', 10) ;

INSERT INTO Evaluation(FK_CLASS_ID, FK_STUDENT_ID, FK_EVAL_CAT_ID, EvalName, ActualScore, MaxScore, Complete)
VALUES	((SELECT CLASS_ID FROM Class WHERE FK_SCHOOL_ID = (SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')
		AND Course_ID = 'HIS 234'),
	(SELECT STUDENT_ID FROM Students WHERE FName = 'Lelouch' AND LName = 'Lamperouge'),
	(SELECT EVAL_CAT_ID FROM Course_Grades WHERE FK_CLASS_ID = 
		(SELECT CLASS_ID FROM Class WHERE FK_SCHOOL_ID = (SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')
		AND Course_ID = 'HIS 234') AND Eval_Catagory = 'Assignment'), 'Assignment 1', 100, 100, 'Yes'),
	
	((SELECT CLASS_ID FROM Class WHERE FK_SCHOOL_ID = (SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')
		AND Course_ID = 'HIS 234'),
	(SELECT STUDENT_ID FROM Students WHERE FName = 'Lelouch' AND LName = 'Lamperouge'),
	(SELECT EVAL_CAT_ID FROM Course_Grades WHERE FK_CLASS_ID = 
		(SELECT CLASS_ID FROM Class WHERE FK_SCHOOL_ID = (SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')
		AND Course_ID = 'HIS 234') AND Eval_Catagory = 'Assignment'), 'Assignment 2', 100, 100, 'Yes'),

	((SELECT CLASS_ID FROM Class WHERE FK_SCHOOL_ID = (SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')
		AND Course_ID = 'HIS 234'),
	(SELECT STUDENT_ID FROM Students WHERE FName = 'Lelouch' AND LName = 'Lamperouge'),
	(SELECT EVAL_CAT_ID FROM Course_Grades WHERE FK_CLASS_ID = 
		(SELECT CLASS_ID FROM Class WHERE FK_SCHOOL_ID = (SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')
		AND Course_ID = 'HIS 234') AND Eval_Catagory = 'Assignment'), 'Assignment 3', 0, 100, 'No'),

	((SELECT CLASS_ID FROM Class WHERE FK_SCHOOL_ID = (SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')
		AND Course_ID = 'HIS 234'),
	(SELECT STUDENT_ID FROM Students WHERE FName = 'Lelouch' AND LName = 'Lamperouge'),
	(SELECT EVAL_CAT_ID FROM Course_Grades WHERE FK_CLASS_ID = 
		(SELECT CLASS_ID FROM Class WHERE FK_SCHOOL_ID = (SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')
		AND Course_ID = 'HIS 234') AND Eval_Catagory = 'Quiz'), 'Quiz 1', 10, 10, 'Yes'),

	((SELECT CLASS_ID FROM Class WHERE FK_SCHOOL_ID = (SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')
		AND Course_ID = 'HIS 234'),
	(SELECT STUDENT_ID FROM Students WHERE FName = 'Lelouch' AND LName = 'Lamperouge'),
	(SELECT EVAL_CAT_ID FROM Course_Grades WHERE FK_CLASS_ID = 
		(SELECT CLASS_ID FROM Class WHERE FK_SCHOOL_ID = (SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')
		AND Course_ID = 'HIS 234') AND Eval_Catagory = 'Quiz'), 'Quiz 2', 10, 10, 'Yes');

INSERT INTO Evaluation(FK_CLASS_ID, FK_STUDENT_ID, FK_EVAL_CAT_ID, EvalName, ActualScore, MaxScore, Complete)
VALUES	((SELECT CLASS_ID FROM Class WHERE FK_SCHOOL_ID = (SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')
		AND Course_ID = 'HIS 234'),
	(SELECT STUDENT_ID FROM Students WHERE FName = 'Lelouch' AND LName = 'Lamperouge'),
	(SELECT EVAL_CAT_ID FROM Course_Grades WHERE FK_CLASS_ID = 
		(SELECT CLASS_ID FROM Class WHERE FK_SCHOOL_ID = (SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')
		AND Course_ID = 'HIS 234') AND Eval_Catagory = 'Quiz'), 'Quiz 3', 10, 10, 'Yes'),


	((SELECT CLASS_ID FROM Class WHERE FK_SCHOOL_ID = (SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')
		AND Course_ID = 'HIS 234'),
	(SELECT STUDENT_ID FROM Students WHERE FName = 'Lelouch' AND LName = 'Lamperouge'),
	(SELECT EVAL_CAT_ID FROM Course_Grades WHERE FK_CLASS_ID = 
		(SELECT CLASS_ID FROM Class WHERE FK_SCHOOL_ID = (SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')
		AND Course_ID = 'HIS 234') AND Eval_Catagory = 'Quiz'), 'Quiz 4', 0, 10, 'No'),

	((SELECT CLASS_ID FROM Class WHERE FK_SCHOOL_ID = (SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')
		AND Course_ID = 'HIS 234'),
	(SELECT STUDENT_ID FROM Students WHERE FName = 'Lelouch' AND LName = 'Lamperouge'),
	(SELECT EVAL_CAT_ID FROM Course_Grades WHERE FK_CLASS_ID = 
		(SELECT CLASS_ID FROM Class WHERE FK_SCHOOL_ID = (SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')
		AND Course_ID = 'HIS 234') AND Eval_Catagory = 'Quiz'), 'Quiz 5', 0, 10, 'No'),

	((SELECT CLASS_ID FROM Class WHERE FK_SCHOOL_ID = (SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')
		AND Course_ID = 'HIS 234'),
	(SELECT STUDENT_ID FROM Students WHERE FName = 'Lelouch' AND LName = 'Lamperouge'),
	(SELECT EVAL_CAT_ID FROM Course_Grades WHERE FK_CLASS_ID = 
		(SELECT CLASS_ID FROM Class WHERE FK_SCHOOL_ID = (SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')
		AND Course_ID = 'HIS 234') AND Eval_Catagory = 'Project'), 'Final Project', 0, 100, 'No'),

	((SELECT CLASS_ID FROM Class WHERE FK_SCHOOL_ID = (SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')
		AND Course_ID = 'HIS 234'),
	(SELECT STUDENT_ID FROM Students WHERE FName = 'Lelouch' AND LName = 'Lamperouge'),
	(SELECT EVAL_CAT_ID FROM Course_Grades WHERE FK_CLASS_ID = 
		(SELECT CLASS_ID FROM Class WHERE FK_SCHOOL_ID = (SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')
		AND Course_ID = 'HIS 234') AND Eval_Catagory = 'Mid-Term'), 'Mid-Term', 0, 100, 'No');

INSERT INTO Evaluation(FK_CLASS_ID, FK_STUDENT_ID, FK_EVAL_CAT_ID, EvalName, ActualScore, MaxScore, Complete)
VALUES	((SELECT CLASS_ID FROM Class WHERE FK_SCHOOL_ID = (SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')
		AND Course_ID = 'HIS 234'),
	(SELECT STUDENT_ID FROM Students WHERE FName = 'Lelouch' AND LName = 'Lamperouge'),
	(SELECT EVAL_CAT_ID FROM Course_Grades WHERE FK_CLASS_ID = 
		(SELECT CLASS_ID FROM Class WHERE FK_SCHOOL_ID = (SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')
		AND Course_ID = 'HIS 234') AND Eval_Catagory = 'Final'), 'Final', 0, 100, 'No'),

	((SELECT CLASS_ID FROM Class WHERE FK_SCHOOL_ID = (SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')
		AND Course_ID = 'HIS 234'),
	(SELECT STUDENT_ID FROM Students WHERE FName = 'Lelouch' AND LName = 'Lamperouge'),
	(SELECT EVAL_CAT_ID FROM Course_Grades WHERE FK_CLASS_ID = 
		(SELECT CLASS_ID FROM Class WHERE FK_SCHOOL_ID = (SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')
		AND Course_ID = 'HIS 234') AND Eval_Catagory = 'Participation'), 'Participation', 10, 10, 'No');

INSERT INTO Roster(FK_CLASS_ID, FK_STUDENT_ID, Final_grade)
VALUES	((SELECT CLASS_ID FROM Class WHERE FK_SCHOOL_ID = (SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')
		AND Course_ID = 'HIS 234'),
	(SELECT STUDENT_ID FROM Students WHERE FName = 'Lelouch' AND LName = 'Lamperouge'),NULL ),
	((SELECT CLASS_ID FROM Class WHERE FK_SCHOOL_ID = (SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')
		AND Course_ID = 'HIS 234'),
	(SELECT STUDENT_ID FROM Students WHERE FName = 'Suzaku' AND LName = 'Kururugi'),NULL ),
	((SELECT CLASS_ID FROM Class WHERE FK_SCHOOL_ID = (SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')
		AND Course_ID = 'HIS 234'),
	(SELECT STUDENT_ID FROM Students WHERE FName = 'Kallen' AND LName = 'Stadtfeld'),NULL ),
	((SELECT CLASS_ID FROM Class WHERE FK_SCHOOL_ID = (SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')
		AND Course_ID = 'HIS 234'),
	(SELECT STUDENT_ID FROM Students WHERE FName = 'Nunnally' AND LName = 'Lamperouge'),NULL),
	((SELECT CLASS_ID FROM Class WHERE FK_SCHOOL_ID = (SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')
		AND Course_ID = 'HIS 234'),
	(SELECT STUDENT_ID FROM Students WHERE FName = 'Milly' AND LName = 'Ashford'),NULL);

INSERT INTO SchoolGrades(FK_GRADE_STANDARD_ID, FK_SCHOOL_ID)
VALUES	((SELECT GRADE_STANDARD_ID FROM GradeStandard WHERE Grade_Name = 'A') ,
	(SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')),
	((SELECT GRADE_STANDARD_ID FROM GradeStandard WHERE Grade_Name = 'B') ,
	(SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')),
	((SELECT GRADE_STANDARD_ID FROM GradeStandard WHERE Grade_Name = 'C') ,
	(SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')),
	((SELECT GRADE_STANDARD_ID FROM GradeStandard WHERE Grade_Name = 'D') ,
	(SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')),
	((SELECT GRADE_STANDARD_ID FROM GradeStandard WHERE Grade_Name = 'F') ,
	(SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')),
	((SELECT GRADE_STANDARD_ID FROM GradeStandard WHERE Grade_Name = 'U') ,
	(SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')),
	((SELECT GRADE_STANDARD_ID FROM GradeStandard WHERE Grade_Name = 'I') ,
	(SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy')),
	((SELECT GRADE_STANDARD_ID FROM GradeStandard WHERE Grade_Name = 'W') ,
	(SELECT SCHOOL_ID FROM School WHERE School_Name = 'Ashford Academy'));