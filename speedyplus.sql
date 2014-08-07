/*
 * Init the Speedy Tables
 */
 
DROP TABLE IF EXISTS Sites;
DROP TABLE IF EXISTS Months;
DROP TABLE IF EXISTS Features;
DROP TABLE IF EXISTS Speedy;
DROP TABLE IF EXISTS Speedy_Result;
DROP TABLE IF EXISTS Checker;
 
CREATE TABLE Sites(Id INTEGER PRIMARY KEY, Name TEXT, Url TEXT, Active BOOLEAN);
CREATE TABLE Months(Id INTEGER PRIMARY KEY, Name TEXT, Processed BOOLEAN);
CREATE TABLE Features(Id INTEGER PRIMARY KEY, SiteId INTEGER, MonthID INTEGER, Application TEXT, Category TEXT, Version TEXT);
CREATE TABLE Speedy(Id INTEGER PRIMARY KEY, SiteId INTEGER, MonthID INTEGER);
CREATE TABLE Speedy_Result(Id INTEGER PRIMARY KEY, SpeedyID INTEGER, platform TEXT,
		Score REAL, Html REAL, Img REAL, Css REAL, Js REAL, Other REAL, Total REAL);
		
CREATE TABLE Checker(Id INTEGER PRIMARY KEY, SiteId INTEGER, MonthID INTEGER, Status TEXT, Errors INTEGER);


/*
 * Load Some Months
 *
 */
 
INSERT INTO Months(Name, Processed) VALUES("February 14", 0);
INSERT INTO Months(Name, Processed) VALUES("March 14", 0);
INSERT INTO Months(Name, Processed) VALUES("April 14", 0);
INSERT INTO Months(Name, Processed) VALUES("May 14", 0);
INSERT INTO Months(Name, Processed) VALUES("June 14", 0);
INSERT INTO Months(Name, Processed) VALUES("July 14", 0);
INSERT INTO Months(Name, Processed) VALUES("August 14", 0);
INSERT INTO Months(Name, Processed) VALUES("September 14", 0);
INSERT INTO Months(Name, Processed) VALUES("October 14", 0);
INSERT INTO Months(Name, Processed) VALUES("November 14", 0);
INSERT INTO Months(Name, Processed) VALUES("December 14", 0);
INSERT INTO Months(Name, Processed) VALUES("January 15", 0);
INSERT INTO Months(Name, Processed) VALUES("February 15", 0);
INSERT INTO Months(Name, Processed) VALUES("March 15", 0);
INSERT INTO Months(Name, Processed) VALUES("April 15", 0);

		


