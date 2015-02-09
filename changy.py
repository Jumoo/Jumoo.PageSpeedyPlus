# changy.py
# codifiying changes

import os
import sys
import speedydb

class Changey(object):

	def __init__(self):
		self.db = speedydb.SpeedyDb()
		
	def checkSizes(self, monthId):	
	
		sp = 19.4
		sz = 5.3
	
		print '' 
		print 'month', monthId, sp, sz
		print '--------------------------------'
		
		sites = self.db.getSites()
		found = 0;
		
		for site in sites:		
			siteId = site[0]
			
			latestResult = self.db.getSpeedyResult(siteId, monthId, 'desktop')		
			previousResult = self.db.getSpeedyResult(siteId, monthId -1, 'desktop')
			
			if previousResult is not None and latestResult is not None :
			
				speeddiff = previousResult[0] - latestResult[0]
				sizediff = previousResult[6] - latestResult[6]
				speeddelta = 0
				sizedelta = 0
				
				if  previousResult[0]  > 0 :
					speeddelta = abs((speeddiff / previousResult[0] ) *100)
				
				if previousResult[6] > 0 :
					sizedelta = abs((sizediff / previousResult[6] ) *100)
			
				if speeddelta > sp and sizedelta > sz :
					found = found + 1 
					print site[1], speeddelta, sizedelta
	
		print 'FOUND: ', found 
	
	def newSites(self, monthId):
	
		print '' 
		print 'month', monthId
		print '--------------------------------'
		
		minsize = 100;
		minspeed = 100;
		
		newSites = self.db.newSites(monthId)
		for site in newSites:
			siteId = site[1]
			
			latestResult = self.db.getSpeedyResult(siteId, monthId, 'desktop')		
			previousResult = self.db.getSpeedyResult(siteId, monthId -1, 'desktop')
			
			if previousResult is not None and latestResult is not None :
			
				speeddiff = previousResult[0] - latestResult[0]
				sizediff = previousResult[6] - latestResult[6]
				speeddelta = 0
				sizedelta = 0
				
				if  previousResult[0]  > 0 :
					speeddelta = abs((speeddiff / previousResult[0] ) *100)
				
				if previousResult[6] > 0 :
					sizedelta = abs((sizediff / previousResult[6] ) *100)
					
				if ( sizedelta > 0 and sizedelta < minsize ):
					minsize = sizedelta
					
				if ( speeddelta > 0 and speeddelta < minspeed) :
					minspeed = speeddelta
			
				print '{0:3} : {1:2.2f} {2:0.2f}'.format(siteId, speeddelta, sizedelta)
	
		print 'min', minspeed, minsize
	
if __name__ == '__main__':
	changey = Changey()
	
	for x in range(3, 10):
		changey.checkSizes(x)
	
	
	