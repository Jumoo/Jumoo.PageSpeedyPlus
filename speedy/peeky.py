import os
import sys
import getopt
import urllib2
import urllib 

from urlparse import urlparse

import speedydb

month = 19


class Peeky(object):

	def __init__(self):
		self.db = speedydb.SpeedyDb()

	def goPeek(self, monthId):

		print 'peeking (looking in more detail for stuff)'

		sites = self.db.getSites()

		for site in sites:	
			try: 
				siteId = site[0]
				siteName = site[1]
				urlBits = urlparse(site[2])
				
				siteUrl = urlBits.scheme + "://" + urlBits.netloc
				
				print "." ,
			
				if (self.checkForDrupal(siteUrl)):
					print siteId, siteName, siteUrl, 'Drupal'
					self.db.saveFeatures(monthId, siteId, 'Drupal', 'cms', '')
				elif ( self.checkForUmbraco(siteUrl)):
					print siteId, siteName, siteUrl, 'Umbraco'
					self.db.saveFeatures(monthId, siteId, 'Umbraco', 'cms', '')
				elif (self.checkForWordpress(siteUrl)):
					print siteId, siteName, siteUrl, 'Wordpress'
					self.db.saveFeatures(monthId, siteId, 'WordPress', 'blogs', '')
				elif (self.checkForOrchard(siteUrl)):
					print siteId, siteName, siteUrl, 'Orchard CMS'
					self.db.saveFeatures(monthId, siteId, 'Orchard CMS', 'cms', '')
				elif (self.checkForKentico(siteUrl)):
					print siteId, siteName, siteUrl, 'Kentico'
					self.db.saveFeatures(monthId, siteId, 'Kentico CMS', 'cms', '')
			except:
				print 'failed to get site' ,		

		print 'done'
      
	def checkForUmbraco(self, url):
		umbracoFile = "/umbraco_client/application/jquery/verticalalign.js"
		umbracoText = 'fn.VerticalAlign = function(opts)'
		return self.checkForFileAndContents(url, umbracoFile, umbracoText)
		
	def checkForOrchard(self, url):
		orchardFile = "/Themes/SafeMode/Styles/ie6.css"
		orchardText = 'images/orchardLogo.gif'
		return self.checkForFileAndContents(url, orchardFile, orchardText)
  
	def checkForDrupal(self, url):
		return self.checkForFileAndContents(url, '/misc/drupal.js', 'var Drupal')
  
	def checkForWordpress(self, url):
		wordpressFile = '/wp-trackback.php'
		return self.checkForFileAndContents(url, '/wp-trackback.php', 'I really need an ID')
		
	def checkForKentico(self, url):
		sitefile = '/CMSPages/GetResource.ashx?stylesheetfile=/App_Themes/Default/DesignMode.css'
		return self.checkForFileAndContents(url, sitefile, '.default_')
  
	def checkForFileAndContents(self, url, portion, text):
		fullUrl = url + portion
		html = self.getContent(fullUrl)      
		substring = html.find(text)
		if ( substring > 0 ):
			return True 
		else: 
			return False 
  
	def getContent(self, url):
		try:
			response = urllib2.urlopen(url, timeout=10)
			return response.read()
		except:
			return ''

	def check(self):
		if (self.db.IsFeatureSet(421, 12, 'Drupal')):
			print 'set'
		else:
			print 'not set'

	def close(self):
		self.db.cleanClose()

		
		
def main(argv):
	monthid = 0 

	try:
		opts, args = getopt.getopt(argv, "m:", ['month'])
	except getopt.GetoptError:
		print 'peaky.py -m <monthId>'
		sys.exit(2)
		
	for opt, arg in opts:
		if opt in ('-m', '--month'):
			monthid = arg 
	
	print 'MonthId [', monthid , ']'

	if monthid != 0:
		peeky = Peeky()
		peeky.goPeek(monthid)
		peeky.close();


if __name__ == '__main__':
	main(sys.argv[1:])	
	
  
