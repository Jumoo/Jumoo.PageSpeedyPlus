# SpeedyDb
# 
import os
import sys
import sqlite3 as lite

speedySql_find = "SELECT Id FROM Speedy WHERE SiteId = {0} AND MonthId = {1};" 
speedySql_insert = "INSERT INTO Speedy(SiteId, MonthId) VALUES({0}, {1});"
spResultSql = "INSERT INTO Speedy_Result(SpeedyId, Platform, Score, Html, Img, Css, Js, Other, Total) VALUES({0}, '{1}', {2}, {3}, {4}, {5}, {6}, {7}, {8});"

wapFeature_insert = "INSERT INTO Features(SiteId, MonthId, Application, Category, Version) VALUES({0}, {1}, '{2}','{3}','{4}');"

checker_insert = "INSERT INTO Checker(SiteId, MonthID, Status, Errors) VALUES({0}, {1}, '{2}', {3});"


class SpeedyDb(object):

	def __init__(self):
		self.file_dir = os.path.dirname(__file__)
		
		self.con = lite.connect('speedyplus.db')
		self.cur = self.con.cursor()


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
		validMonthSql = "SELECT count(*) from MONTHS WHERE processed=0 AND ID=" + monthId  + ";" 
		
		self.cur.execute(validMonthSql)
		total_count = self.cur.fetchone()
		
		if total_count[0] == 1 :
			return 1
		
		return 0
		
	def closeMonth(self, monthId):
		print "Marking month " + monthId + " as processed" 
		closeSql = "UPDATE Months SET Processed = 1 WHERE Id=" + monthId + ";"		
		self.cur.execute(closeSql)
		self.con.commit() 
		
		
	def getSites(self):
		self.cur.execute("SELECT * from SITES WHERE Active = 1")		
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
