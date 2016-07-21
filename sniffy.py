import os
import sys
import getopt
import urllib2
import urllib 

from urlparse import urlparse

import speedy.speedydb
class Sniffy(object):
	def __init__(self):
		self.db = speedydb.SpeedyDb()
	
	def findBetas(self):
		sites = self.db.getSites()
		
		for site in sites:
			siteId = site[0]
			siteName = site[1]
			urlBits = urlparse(site[2])
		
			sitebit = urlBits.netloc.strip('www')
		
			self.checkSite('beta', sitebit)
			self.checkSite('new', sitebit)
			# self.checkSite('alpha', sitebit)

	def checkSite(self, type, sitebit):
	
		url = 'http://' + type + sitebit
		code = self.getSite(url)
			
		if (code == 200):
			print url
		
			
	def getSite(self, url):
		try:
			response = urllib2.urlopen(url, timeout=10)
			return response.code
		except:
			return 500
		

def main(argv):

	sniff = Sniffy()
	sniff.findBetas()
	print sniff.getSite('http://jumoo.uk')

if __name__ == '__main__':
	main(sys.argv[1:])	
	