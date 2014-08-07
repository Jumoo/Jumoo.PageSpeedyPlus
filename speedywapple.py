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

try:
	import json
except ImportError:
	import simplejson as json 
	
	
class SpeedyWapple(object):

	def __init__(self):
	
		self.file_dir = os.path.dirname(__file__)
		f = open(os.path.join(self.file_dir, 'apps.json'))
		data = json.loads(f.read())
		f.close()
		
		self.categories = data['categories']
		self.apps = data['apps']

		self.db = speedydb.SpeedyDb()

		
	def process(self, monthId):
	
		print 'running wapple stuff for month ', monthId 
		
		sites = self.db.getSites()
		
		for site in sites:
		
			siteId = site[0]
			siteName = site[1]
			siteUrl = site[2]
			
			print '' 
			print siteId, siteName, siteUrl , 
			
			self.wapple(siteId, siteUrl, monthId)
	
	def wapple(self, id, url, month):
	
		try:
			ctxt = PyV8.JSContext()
			ctxt.enter()

			f1 = open(os.path.join(self.file_dir, 'js/wappalyzer.js'))
			f2 = open(os.path.join(self.file_dir, 'js/driver.js'))
			ctxt.eval(f1.read())
			ctxt.eval(f2.read())
			f1.close()
			f2.close()

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
			print 'error getting thing' 
		