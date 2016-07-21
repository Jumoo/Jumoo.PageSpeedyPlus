/*
*  SQL for the LinkCounter stuff.
*
* LinkCounter.py - gathers this in text files. 
* LoadLinkData.py - loads the data into SQL
*
*/

CREATE TABLE Domains(Id INTEGER PRIMARY KEY, SiteId INTEGER, Domain TEXT, Link TEXT);
CREATE TABLE Domain_Features(Id INTEGER PRIMARY KEY, DomainId INT, Application TEXT, Category TEXT, Version TEXT);
CREATE TABLE SiteLinks(Id INTEGER PRIMARY KEY, SiteId INTEGER, Pages INT, Docs INT, Err INT, Queued INT);

