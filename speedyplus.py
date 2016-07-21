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

import speedy.speedydb 
import speedy.pagespeedy as speedy
import speedy.speedywapple as wapple 
import speedy.speedyachecker as checker 
import speedy.peeky as peeky
import speedy.screengrabby as grabby 

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
			s = speedydb.SpeedyDb()
			s.listMonths()
			sys.exit()
		elif opt in ('-s', '--single'):
			single = arg 
			
	
	print 'MonthId [', monthid , ']'
	
	if monthid != 0:

		s = speedydb.SpeedyDb()

		if single != 0:
			#process just one site. (ignore valid month thing)			
			ps = speedy.PageSpeedy()
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
			wp = wapple.SpeedyWapple()
			wp.process(monthid)

		# peeky (extra looking)			
			pky = peeky.Peeky()
			pky.goPeek(monthid)
			pky.close();

		# screengrabs
			grab = grabby.ScreenGrabby()
			grab.runGrabby(monthid)
			
		# accessilbity check
		#	ch = checker.AChecker()
		#	ch.runChecker(monthid)
			
			s.closeMonth(monthid)
		else:
			print 'not a valid month'

if __name__ == '__main__':
	main(sys.argv[1:])
	