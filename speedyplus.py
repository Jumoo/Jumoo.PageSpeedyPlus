# 
# Jumoo.PageSpeedyPlus -
#
# A Website Speedtest, appInterigating, [AccessibilityChecking] Machine
# 
# Version	: 2.0.0.
# Date		: 30th July 2014
# Live 		: http://jumoo.uk/pagespeedy 
#

import sys, getopt 

from speedy.speedydb import SpeedyDb
from speedy.pagespeedy import PageSpeedy
from speedy.speedywapple import SpeedyWapple
from speedy.speedyachecker import AChecker
from speedy.peeky import Peeky
from speedy.screengrabby import ScreenGrabby as grabby

def main(argv):
	monthid = 0 
	single = 0;

	try:
		opts, args = getopt.getopt(argv, "lhm:s:", ['month','list', 'single'])
	except getopt.GetoptError:
		print 'SpeedyPlus.py -m <monthId> [-s <siteid>]'
		sys.exit(2)
		
	for opt, arg in opts:
		if opt == '-h':
			print 'SpeedyPlus.py -m <monthId>'
			sys.exit()
		elif opt in ('-m', '--month'):
			monthid = arg 
		elif opt in ('-l', '--list'):
			s = SpeedyDb()
			s.listMonths()
			sys.exit()
		elif opt in ('-s', '--single'):
			single = arg 
			
	
	print 'MonthId [', monthid , ']'
	
	if monthid != 0:

		s = SpeedyDb()

		if single != 0:
			#process just one site. (ignore valid month thing)			
			ps = PageSpeedy()
			ps.ProcessSingleSite(single, monthid)
			
			#wp = wapple.SpeedyWapple()
			#wp.ProcessSingleSite(single, monthid)
			
			#ch = checker.AChecker()
			#ch.ProcessSingleSite(single, monthid)
			
			sys.exit()			
		elif s.validMonth(monthid) == 1 :
			s.backup(monthid)	
		
		# pagespeed check
			ps = speedy.PageSpeedy()
			ps.runSpeedy(monthid)
			
		# wapplizer check
			wp = SpeedyWapple()
			wp.process(monthid)

		# peeky (extra looking)			
			pky = Peeky()
			pky.goPeek(monthid)
			pky.close();

		# screengrabs
			grab = ScreenGrabby()
			grab.runGrabby(monthid)
			
		# accessilbity check
		#	ch = checker.AChecker()
		#	ch.runChecker(monthid)
			
			s.closeMonth(monthid)
		else:
			print 'not a valid month'

if __name__ == '__main__':

	print r'-------------------------------------------------------------'
	print r'    ____                  _____                     __       '
	print r'   / __ \____ _____ ____ / ___/____  ___  ___  ____/ /_  __  '
	print r'  / /_/ / __ `/ __ `/ _ \\__ \/ __ \/ _ \/ _ \/ __  / / / /  '
	print r' / ____/ /_/ / /_/ /  __/__/ / /_/ /  __/  __/ /_/ / /_/ /   '
	print r'/_/    \__,_/\__, /\___/____/ .___/\___/\___/\__,_/\__, /    '
	print r'            /____/         /_/ and other tools    /____/     '
	print r'-------------------------------------------------------------'
	print r''

	main(sys.argv[1:])
	