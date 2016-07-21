#
# Speedy.Wapple 
#
#

import os
import sys
import PyV8
import requests 
from urlparse import urlparse 
import speedydb 
import codecs 

try:
	import json
except ImportError:
	import simplejson as json 
	
wappal_script = 'lib/Wappalyzer/src/wappalyzer.js'
wappal_driver = 'lib/Wappalyzer/src/drivers/php/js/driver.js'
wappal_apps = 'lib/Wappalyzer/src/apps.json'

localgov_apps = 'config/localgov.apps.json'
	
class SpeedyWapple(object):

	def __init__(self):
	
		self.file_dir = os.path.dirname(__file__)
		with open(os.path.join(self.file_dir, wappal_apps)) as f:
			data = json.loads(f.read())
		
		self.categories = data['categories']
		self.apps = data['apps']
		
		with open(os.path.join(self.file_dir, localgov_apps)) as lgfile:
			lgdata = json.loads(lgfile.read())
		
		self.apps.update(lgdata['apps'])
		self.categories.update(lgdata['categories'])		

		self.db = speedydb.SpeedyDb()
		
	def process(self, monthId):
	
		print 'running wapple stuff for month ', monthId 
		
		sites = self.db.getSites()
		
		for site in sites:
		
			siteId = site[0]
			siteName = site[1]
			siteUrl = site[2]
			
			print '' 
			print "{0: <3}: {1: <35} {2:<45} ".format(siteId, siteName, siteUrl) , 
			
			self.wapple(siteId, siteUrl, monthId)
	
	def wapple(self, id, url, month):
	
		try:
			ctxt = PyV8.JSContext()
			ctxt.enter()
			
			wappleScriptFile = os.path.join(self.file_dir, wappal_script);
			wapplyDriverFile = os.path.join(self.file_dir, wappal_driver) 

			with codecs.open(wappleScriptFile, 'r', 'utf8') as f:
				ctxt.eval(f.read())
				
			with codecs.open(wapplyDriverFile, 'r', 'utf8') as f:
				ctxt.eval(f.read())

			host = urlparse(url).hostname
			response = requests.get(url)
			html = response.text
			headers = dict(response.headers)
			data = {'host': host, 'url': url, 'html': html, 'headers': headers}
			apps = json.dumps(self.apps)
			categories = json.dumps(self.categories)

			results = ctxt.eval("w.apps = %s; w.categories = %s; w.driver.data = %s; w.driver.init();" % (apps, categories, json.dumps(data)))
			
			#print results 
			answers = json.loads(results) 
			print "Feature Count {0}".format(answers.__len__()) ,
			for app, thing in answers.items():
				categories = "" 
				version = thing["version"]
				for c in thing["categories"]: 
					categories = c + "," 

				self.db.saveFeatures(month, id, app, categories.strip(","), version) ;
		except:
			print 'error getting thing',
		
	def ProcessSingleSite(self, siteid, monthId):
		sites = self.db.getSingleSite(siteid)
		for site in sites:
		
			siteId = site[0]
			siteName = site[1]
			siteUrl = site[2]
			
			print '' 
			print siteId, siteName, siteUrl , 
			self.wapple(siteId, siteUrl, monthId)
		
		