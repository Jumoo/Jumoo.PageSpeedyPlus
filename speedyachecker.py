#
# Speedy.AChecker
#

import os
import sys
import urllib
import urllib2
from urlparse import urlparse

import speedydb
import json


import xml.etree.ElementTree as ET

results_folder = '{0}/results/{1}/{2}'

class AChecker(object):
	def __init__(self):
		self.db = speedydb.SpeedyDb()
		self.file_dir = os.path.dirname(__file__)
		
		with open('achecker.config.json') as config_file:
			config = json.load(config_file)
			self.key = config['apikey']
			self.host = config['host']

			
	def initMonth(self, monthId):
		self.checkerfolder = results_folder.format(self.file_dir, monthId, 'checker');
		if not os.path.exists(self.checkerfolder):
			os.makedirs(self.checkerfolder)
			
	def runChecker(self, monthId):
	
		print 'Running AChecker for month', monthId
		
		self.initMonth(monthId)
		
		sites = self.db.getSites()
		
		for site in sites:
		
			siteId = site[0]
			siteName = site[1]
			siteUrl = site[2]
			
			print '' 
			print siteId, siteName, siteUrl , 					
			self.getCheckerResult(siteId, siteName, siteUrl, monthId)
			
	def getCheckerResult(self, siteId, siteName, siteUrl, monthId):
	
		try:
						
			url_args = {
				'uri' : siteUrl,
				'guide' : 'WCAG2-A',
				'output' : 'REST',
				'id' : self.key }

			encoded_args = urllib.urlencode(url_args)
			checker_url = '{0}?{1}'.format(self.host, encoded_args)
			print "Fetching...",

			response = urllib2.urlopen(checker_url, timeout=45)
			checker_xml = response.read()

			root = ET.fromstring(checker_xml)
	
			test_status = root.find("./summary/status")
			test_errors = root.find("./summary/NumOfErrors")
			
			# new need db to change -->
			test_likely = root.find("./summary/NumOfLikelyProblems")
			test_potential = root.find("./summary/NumOfPotentialProblems")
			
			print "Status:", test_status.text, 
			print "Error Count:", test_errors.text, 
			self.db.saveChecker(siteId, monthId, test_status.text, test_errors.text);
			
			self.saveResult(monthId, siteName, checker_xml)
			
		except Exception, e:
			print 'Error: ', siteUrl, e


	def saveResult(self, monthId, siteName, xml):
		file_name = self.checkerfolder + '/' + siteName + ".xml"
		x = open(file_name, 'w')
		x.write(xml)
		x.close()