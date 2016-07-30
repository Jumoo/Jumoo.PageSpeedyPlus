# SpeedyDb
# 
import os
import sys
import sqlite3 as lite
import datetime 
import shutil

speedySql_find = "SELECT Id FROM Speedy WHERE SiteId = {0} AND MonthId = {1};" 
speedySql_insert = "INSERT INTO Speedy(SiteId, MonthId) VALUES({0}, {1});"
spResultSql = "INSERT INTO Speedy_Result(SpeedyId, Platform, Score, Html, Img, Css, Js, Other, Total) VALUES({0}, '{1}', {2}, {3}, {4}, {5}, {6}, {7}, {8});"

wapFeature_insert = "INSERT INTO Features(SiteId, MonthId, Application, Category, Version) VALUES({0}, {1}, '{2}','{3}','{4}');"

checker_insert = "INSERT INTO Checker(SiteId, MonthID, Status, Errors) VALUES({0}, {1}, '{2}', {3});"
mobileCheck_insert = "INSERT INTO MobileCheck(SiteId, MonthId, Pass) VALUES({0},{1},'{2}');"

linkinfo_insert = "INSERT INTO SiteLinks(SiteId, Pages, Docs, Err, Queued) VALUES({0},{1},{2},{3},{4});"
linkinfo_update = "UPDATE SiteLinks SET Pages = {1}, Docs = {2}, Err = {3}, Queued = {4} WHERE SiteId = {0};"
linkinfo_insert_domains = "INSERT INTO Domains(SiteId, Domain, Link) VALUES({0}, '{1}', '{2}');"
linkinfo_insert_features = "INSERT INTO Domain_Features(DomainId, Application, Category, Version) VALUES({0}, '{1}', '{2}', '{3}');"

class SpeedyDb(object):

	def __init__(self):
		self.file_dir = os.path.dirname(__file__)
		# print self.file_dir
		
		self.con = lite.connect(os.path.join(self.file_dir, '../data/speedyplus.db'))
		self.cur = self.con.cursor()
		
	def backup(self, monthId):
	
		source = os.path.join(self.file_dir, '../data/speedyplus.db')
		backup = os.path.join(self.file_dir, '../data/backup/{0}/'.format(monthId))
		
		if not os.path.exists(backup):
			os.makedirs(backup)
		
		shutil.copy(source, backup)
		

	def cleanClose(self):
		self.con.commit()
		self.cur.close()
		self.con.close()

	def listMonths(self):
	
		self.cur.execute("SELECT * from MONTHS WHERE Processed = 0")
		
		rows = self.cur.fetchall()
		
		print 'Months you can use: ' ,
		print "When you run a month it's marked at processed, and you can't run it again"
		print '' 
		print "Id\tName" 
		
		for row in rows:
			mId = row[0]
			mName = row[1]
			mProcessed = row[2]
			
			print mId, '\t', mName
			
		print '\nwhen you want to run a month\n'
		print 'speedyplus.py -l <monthid>' 
		
	def listProcessedMonths(self):
	
		self.cur.execute("SELECT * FROM MONTHS WHERE processed = 1")
		rows = self.cur.fetchall();
		
		print 'Months that have been ran:'
		print ''
		
		for row in rows:
			print row[0], row[1] 
			
		print '\n to get results use speedresults.py -m <monthid>' 
		
		
	def validMonth(self, monthId):
		validMonthSql = "SELECT count(*) from MONTHS WHERE processed=0 AND ID=" + str(monthId)  + ";" 
		
		self.cur.execute(validMonthSql)
		total_count = self.cur.fetchone()
		
		if total_count[0] == 1 :
			return 1
		
		return 0
		
	def closeMonth(self, monthId):
		print "Marking month " + str(monthId) + " as processed" 
		closeSql = "UPDATE Months SET Processed = 1 WHERE Id=" + str(monthId) + ";"		
		self.cur.execute(closeSql)
		self.con.commit() 
		
	def getMonthByDate(self, date):
		monthId = "{0:02d}{1}".format( date.month, date.year)
		sql = "SELECT id FROM MONTHS WHERE DateId = '{0}' AND PROCESSED = 0;".format(monthId)
		print sql
		self.cur.execute(sql)
		m = self.cur.fetchone()

		if m == None:
			return 0
		else:
			return int(m[0])
		
	def getSites(self):
		self.cur.execute("SELECT * from SITES WHERE Active = 1")		
		rows = self.cur.fetchall()		
		return rows 

	def getSpiderSites(self):
		self.cur.execute("SELECT * from SITES WHERE Spider = 1")		
		rows = self.cur.fetchall()		
		return rows

	def getSpiderSitesInError(self):
		self.cur.execute("SELECT * from SITES WHERE Spider = 1 and Spider_OK = 0;")		
		rows = self.cur.fetchall()		
		return rows
		 
	def getNewSites(self, monthId):
		siteSql = "SELECT * FROM SITES WHERE ID IN (SELECT SiteId from NewSites where NewMonthId = {0});"
		self.cur.execute(siteSql.format(monthId))
		rows = self.cur.fetchall()
		return rows

		
	def newSites(self, monthId):
		newSiteSql = "SELECT * from NewSites where NewMonthId = {0};"
		self.cur.execute(newSiteSql.format(monthId))
		rows = self.cur.fetchall()		
		return rows 
		
	def getSingleSite(self, siteId):
		self.cur.execute("SELECT * from SITES WHERE Id=" + siteId)		
		rows = self.cur.fetchall()		
		return rows 
	
		
	def saveScore(self, siteId, monthId, siteType, score, html, css, img, js, other, total):

		speedyid = 0;
		self.cur.execute(speedySql_find.format(siteId, monthId))
		id = self.cur.fetchone()	
		
		if id == None:
			self.cur.execute(speedySql_insert.format(siteId, monthId))
			self.con.commit()
			speedyid = self.cur.lastrowid
		else: 
			speedyid = id[0]

		self.cur.execute(spResultSql.format(speedyid, siteType, score, html, img, css, js, other, total))
		self.con.commit() 

	def saveFeatures(self, monthId, siteId, app, categories, version):
		if ( self.IsFeatureSet(siteId, monthId, app) == False):
			self.cur.execute( wapFeature_insert.format(siteId, monthId, app, categories, version))
			self.con.commit()
		
	def saveChecker(self, siteId, monthId, status, errors):
		self.cur.execute( checker_insert.format(siteId, monthId, status, errors) )
		self.con.commit()
		
	def saveMobileCheck(self, siteId, monthId, passfail):
		self.cur.execute( mobileCheck_insert.format(siteId, monthId, passfail))
		self.con.commit()

	#Links
	def saveLinkInfo(self, siteId, pages, docs, err, queue):
		check = "SELECT Count(*) from SiteLinks where SiteId = {0};".format(siteId)
		self.cur.execute(check)
		counter_row = self.cur.fetchone()
		counter = counter_row[0]

		sql = ""
		if counter == 0 :
			sql = linkinfo_insert.format(siteId, pages, docs, err, queue)
		else:
			sql = linkinfo_update.format(siteId, pages, docs, err, queue)

		if sql:
			print sql
			self.cur.execute(sql)
			self.con.commit()

	def setSpiderStatus(self, siteId, ok):
		ok_val = 0
		if ok:
			ok_val = 1

		sql = "UPDATE SITES SET Spider_OK = {1} WHERE ID = {0};".format(siteId, ok_val)
		self.cur.execute(sql)
		self.con.commit()

	def cleanDomainInfo(self, siteId):
		self.cur.execute ("DELETE FROM Domains where SiteId = {0};".format(siteId))
		self.con.commit()

	def cleanDomainFeatures(self, siteId):
		self.cur.execute ("DELETE FROM Domain_Features where DomainId = {0}".format(siteId))
		self.con.commit()

	def saveDomainInfo(self, siteId, domain, link):
		check = "SELECT Count(*) from Domains where SiteId = {0} and Domain = '{1}';".format(siteId, domain)
		self.cur.execute(check)
		counter_row = self.cur.fetchone()
		counter = counter_row[0]

		if counter == 0 :		
			print siteId, domain
			self.cur.execute( linkinfo_insert_domains.format(siteId, domain, link))
			self.con.commit()

	def saveDomainFeature(self, domainId, app, categories, version):
		self.cur.execute( linkinfo_insert_features.format(domainId, app, categories, version))
		self.con.commit()

	def getDomains(self, siteId):
		resultsSql = "SELECT * FROM Domains where SiteId = {0}".format(siteId)
		self.cur.execute(resultsSql)
		rows = self.cur.fetchall()
		return rows

	def getUndetectedDomains(self, siteId):
		resultsSql = "SELECT * FROM Domains where SiteId = {0} and Id not in (Select DomainId from Domain_Features);".format(siteId)
		self.cur.execute(resultsSql)
		rows = self.cur.fetchall()
		return rows
					
	def getSpeedyResult(self, siteId, monthId, platform):
		resultSql = ("SELECT "
					 " speedy_result.score, speedy_result.html, speedy_result.img, "  
					 " speedy_result.Css, speedy_result.Js, speedy_result.Other, "
					 " speedy_result.total "
					 "FROM Speedy "
					 "inner join SPEEDY_RESULT on Speedy_Result.SpeedyId = Speedy.Id "
					 "WHERE Speedy.MonthId = {1} AND Speedy.SiteId = {0} AND Speedy_Result.Platform = '{2}';")
		self.cur.execute(resultSql.format(siteId, monthId, platform))
		#print resultSql.format(siteId, monthId, platform)
		return self.cur.fetchone()

	def IsFeatureSet(self, siteId, monthId, app):
		checkSql = "SELECT COUNT(*) FROM Features where SiteID = {0} AND MonthID = {1} AND Application = '{2}';"
		self.cur.execute( checkSql.format(siteId, monthId, app))
		counter_row = self.cur.fetchone()
		counter = counter_row[0]

		if ( counter == 0 ):
			return False
		else:
			return True 
	
	def hasAppType(self, siteId, monthId, category):
		checkSql = "SELECT COUNT(*) FROM Features where SiteID = {0} AND MonthID = {1} AND Category = '{2}';"
		self.cur.execute( checkSql.format(siteId, monthId, category))
		counter_row = self.cur.fetchone()
		counter = counter_row[0]

		if ( counter == 0 ):
			return False
		else:
			return True 

	def getUncheckedMobileSites(self, monthId):
		sql = "select * from sites where active = 1 and id not in (select SiteId from MobileCheck where monthid = {0});"
		# sql = "select * from sites where active = 1 and id = 410;"
		self.cur.execute(sql.format(monthId))		
		rows = self.cur.fetchall()		
		return rows 
	
	 