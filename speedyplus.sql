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

/* the new sites thing - will be managed manually */
CREATE TABLE NewSites(Id INTEGER PRIMARY KEY, SiteId, INTEGER, newMonthId INTEGER);

/*
 * Load Some Months
 *
 */
 
INSERT INTO Months(Name, Processed) VALUES("02: February 14", 0);
INSERT INTO Months(Name, Processed) VALUES("03: March 14", 0);
INSERT INTO Months(Name, Processed) VALUES("04: April 14", 0);
INSERT INTO Months(Name, Processed) VALUES("05: May 14", 0);
INSERT INTO Months(Name, Processed) VALUES("06: June 14", 0);
INSERT INTO Months(Name, Processed) VALUES("07: July 14", 0);
INSERT INTO Months(Name, Processed) VALUES("08: August 14", 0);
INSERT INTO Months(Name, Processed) VALUES("09: September 14", 0);
INSERT INTO Months(Name, Processed) VALUES("10: October 14", 0);
INSERT INTO Months(Name, Processed) VALUES("11: November 14", 0);
INSERT INTO Months(Name, Processed) VALUES("12: December 14", 0);
INSERT INTO Months(Name, Processed) VALUES("01: January 15", 0);
INSERT INTO Months(Name, Processed) VALUES("02: February 15", 0);
INSERT INTO Months(Name, Processed) VALUES("03: March 15", 0);
INSERT INTO Months(Name, Processed) VALUES("04: April 15", 0);

		


