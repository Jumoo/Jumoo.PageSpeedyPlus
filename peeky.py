import os
import sys
import speedydb
import urllib2
import urllib 

month = 16


class Peeky(object):

	def __init__(self):
		self.db = speedydb.SpeedyDb()

	def goPeek(self, monthId):

		print 'peeking (looking in more detail for stuff)'

		sites = self.db.getSites()

		for site in sites:
			siteId = site[0]
			siteName = site[1]
			siteUrl = site[2]

			if (self.checkForDrupal(siteUrl)):
				print siteId, siteName, siteUrl, 'Drupal'
				self.db.saveFeatures(monthId, siteId, 'Drupal', 'cms', '')
			elif ( self.checkForUmbraco(siteUrl)):
				print siteId, siteName, siteUrl, 'Umbraco s'
				self.db.saveFeatures(monthId, siteId, 'Umbraco', 'cms', '')
			elif (self.checkForWordpress(siteUrl)):
				print siteId, siteName, siteUrl, 'Wordpress'
				self.db.saveFeatures(monthId, siteId, 'WordPress', 'blogs', '')

		print 'done'
      
	def checkForUmbraco(self, url):
		umbracoFile = "/umbraco_client/application/jquery/verticalalign.js"
		umbracoText = 'fn.VerticalAlign = function(opts)'
		return self.checkForFileAndContents(url, umbracoFile, umbracoText)
  
	def checkForDrupal(self, url):
		return self.checkForFileAndContents(url, '/misc/drupal.js', 'var Drupal')
  
	def checkForWordpress(self, url):
		wordpressFile = '/wp-trackback.php'
		return self.checkForFileAndContents(url, '/wp-trackback.php', 'I really need an ID')
  
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

if __name__ == '__main__':
	peeky = Peeky()
	peeky.goPeek(month)
	# peeky.checkForUmbraco('http://www.liverpool.gov.uk/')
	peeky.close();
  
