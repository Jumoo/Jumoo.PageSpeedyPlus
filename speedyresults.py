#
# speedyresults.py
#
#
#

import sys, getopt, os

import speedydb 


def GetSpeedyResults(monthId):

	s = speedydb.SpeedyDb()
	
	sites = s.getSites()
	
	for site in sites:
	
		
		print site[1], ',', site[2], ',', 
		result = s.getSpeedyResult(site[0], monthId, 'desktop')
		if result != None:
			print ",".join(map(str, result)), ',',

		result = s.getSpeedyResult(site[0], monthId, 'mobile')
		if result != None:
			print ",".join(map(str, result))
		else:
			print '' 

def main(argv):

	monthId = 0

	try:
		opts, args = getopt.getopt(argv, 'lm:', ['month','list'])
	except getopt.GetoptError:
		print 'SpeedyResults.py -m <monthId>' 
		print '					-l (list months)'
		
	for opt, arg in opts:
	
		if opt in ('-m', '--month'):
			monthId = arg 
		elif opt in ('-l', '--list'):
			s = speedydb.SpeedyDb()
			s.listProcessedMonths()
			sys.exit()
	
	if	monthId != 0: 
		print 'Getting Results for Month: ', monthId 	
		
		GetSpeedyResults(monthId) 
	
if __name__ == '__main__':
	main(sys.argv[1:])
	
	
	
