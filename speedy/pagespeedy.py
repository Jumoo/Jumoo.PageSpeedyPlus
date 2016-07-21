# PageSpeedy
#
# Does the page speed stuff...
#
import os
import sys
import speedydb 

import urllib 
import urllib2
import json
import time

import base64 

results_folder = '{0}/data/results/{1}/{2}'

class PageSpeedy(object):

	def __init__(self):

		self.db = speedydb.SpeedyDb()
		self.file_dir = os.path.dirname(__file__)

		
		with open('config/pagespeedy.config.json') as config_file:
			config = json.load(config_file)
			self.key = config['apikey']
			
	def initMonth(self, monthId):
		self.json_results = results_folder.format(self.file_dir, monthId, 'json');
		self.checkfolder(self.json_results)

		self.screenshots = results_folder.format(self.file_dir, monthId, 'screenshots');
		self.checkfolder(self.screenshots)

	
		
	def runSpeedy(self, monthId):

		print 'Running page speedy for month ', monthId 
		
		self.initMonth(monthId) 

		sites = self.db.getSites()
		
		for site in sites:
		
			siteId = site[0]
			siteName = site[1]
			siteUrl = site[2]
			
			print '' 
			print siteId, siteName, siteUrl , 
			self.GetSpeedyResult(siteId, siteName, siteUrl, monthId, 'desktop')
			time.sleep(1)
			self.GetSpeedyResult(siteId, siteName, siteUrl, monthId, 'mobile')
			time.sleep(1)

	# just do one site
	def ProcessSingleSite(self, siteid, monthId):
	
		print 'processing just one site'
		self.initMonth(monthId) 


		sites = self.db.getSingleSite(siteid)
		for site in sites:
		
			siteId = site[0]
			siteName = site[1]
			siteUrl = site[2]
			
			print '' 
			print siteId, siteName, siteUrl , 
			self.GetSpeedyResult(siteId, siteName, siteUrl, monthId, 'desktop')
			time.sleep(1)
			self.GetSpeedyResult(siteId, siteName, siteUrl, monthId, 'mobile')
			time.sleep(1)
		
			
	# all the page speedy stuff here...			
	def GetSpeedyResult(self, siteId, siteName, siteUrl, monthId, siteType):
	
		try:
		
			print ' ' + siteType + ':' ,
			speedyResult = self.getPageSpeedJson(siteUrl, siteType)
			
			if speedyResult.__len__() > 10 :		
				self.saveScore(siteId, monthId, speedyResult, siteType)
				self.saveJson(monthId, siteName, speedyResult, siteType)
				self.saveScreenshot(monthId, siteName, speedyResult, siteType)
			else:
				print 'no resutls?' 
		
		except:
			print 'GetSpeedyResult Error: ', siteUrl, sys.exc_info()[0]
			return 

	def getPageSpeedJson(self, siteUrl, siteType):
	
		try:
		
			url_args = { 'url' : siteUrl, 'strategy' : siteType, 'key' : self.key, 'screenshot' : 'true' }
			encoded_args = urllib.urlencode(url_args)
			ps_url = 'https://www.googleapis.com/pagespeedonline/v1/runPagespeed?{0}'.format(encoded_args)
		
			response = urllib2.urlopen(ps_url, timeout = 45)
			return response.read()
			
		except Exception, e:
			print 'getPageSpeedJson Error: ', siteUrl, e
	
	
	def saveScore(self, siteId, monthId, results, siteType):
	
		try:
			data = json.loads(results)
			
			psScore = data['score']
			print psScore ,
			psDetail = data['pageStats']
			
			psHtml = self.getBytes(psDetail, 'htmlResponseBytes')
			psCss = self.getBytes(psDetail, 'cssResponseBytes')
			psImg = self.getBytes(psDetail, 'imageResponseBytes')
			psJs = self.getBytes(psDetail, 'javascriptResponseBytes')
			psOther = self.getBytes(psDetail, 'otherResponseBytes')
        
			psTotal = int(psHtml) + int(psCss) + int(psImg) + int(psJs) + int(psOther)   
			
			#print siteId, monthId, psScore, psHtml, psCss, psImg, psJs, psOther, psTotal

			self.db.saveScore(siteId, monthId, siteType, psScore, psHtml, psCss, psImg, psJs, psOther, psTotal)
		except Exception, e:
			print 'saveScore Error: ', siteId,  e
			sys.exit(1) 
		
		
	#
	# gets the 'bytes' value from the json, and does a default
	#
	def getBytes(self, json_detail, propertyName):	
		try:
			bValue = json_detail[propertyName]
			if bValue:
				return bValue
			else:
				return '0' 
		
		except:
			return '0'

	def saveJson(self, monthId, siteName, results, siteType):	
		file_name = self.json_results + '/' + siteName + '_' + siteType + '.json'
		js = open(file_name, 'w')
		js.write(results)
		js.close()
		
	def saveScreenshot(self, monthId, siteName, results, siteType):
		file_name = self.screenshots + '/' + siteName + '_' + siteType + ".jpg"
		jpg = open(file_name, 'wb')
	
		data = json.loads(results)
		screenshot_section = data['screenshot']
		jpg_data = screenshot_section['data'].replace('_', '/').replace('-', '+')


		# The google pagespeed service is returning an invalid base64. 
		# To correct it replace all '_' with '/' and all '-' with '+'.
		jpg.write(base64.b64decode(jpg_data))
		jpg.close()
    
		

	def checkfolder(self, path):
		if not os.path.exists(path):
			os.makedirs(path)

	
	