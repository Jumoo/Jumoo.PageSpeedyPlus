#
# ScreenGrabby - A Screengrabing script. 
# ======================================
#
# goes through the page speedy database, and goes and gets screenshots of each 
# website, using phamtom drivers. 
# 
import os
import sys
import getopt

from selenium import webdriver

import speedydb

screenshot_folder = '{0}/data/results/{1}/screengrabs/'
source_folder = '{0}/data/results/{1}/html/'

class ScreenGrabby(object):

	def __init__(self):	
		self.db = speedydb.SpeedyDb()
		
		self.file_dir = os.path.dirname(__file__)
		phantompath = os.path.join(self.file_dir, 'lib/phantomjs/bin/phantomjs') 
		
		self.driver = webdriver.PhantomJS(executable_path='lib/phantomjs/bin/phantomjs')
		#self.driver = webdriver.Firefox()
		#self.driver = webdriver.Chrome()
		
	def runGrabby(self, monthId):
		self.setupFolders(monthId)
	
		print 'Getting screenshots... for month', monthId
		sites = self.db.getSites()
		
		for site in sites:		
			siteId = site[0]
			siteName = site[1]
			siteUrl = site[2]
			print siteId, siteName, siteUrl, 
			self.grabScreen(siteUrl, siteName)			
		
		#self.grabScreen("http://blog.jumoo.co.uk/", "jumoo");
		
	def grabScreen(self, url, name):	
		try:
			file_name = self.screenfolder + '/' + name + '.png'
			
			if not os.path.exists(file_name):
				self.driver.set_window_size(1280, 1024)
				self.driver.get(url)
			
				self.driver.save_screenshot(file_name)
			
				self.saveSource(name, self.driver.page_source);
				print 'grabbed' 
			else:
				print 'already got'
				
		except Exception, e:
			print 'error getting screenshot', e
			return
	
	def setupFolders(self, monthId):		
		self.screenfolder = screenshot_folder.format(os.path.dirname(__file__), monthId)
		if not os.path.exists(self.screenfolder):
			os.makedirs(self.screenfolder)
			
		self.sourcefolder = source_folder.format(os.path.dirname(__file__), monthId)
		if not os.path.exists(self.sourcefolder):
			os.makedirs(self.sourcefolder)
			
	def saveSource(self, name, content):
		source_file = self.sourcefolder + '/' + name + '.html'
		source = open(source_file, 'w')
		source.write(content.encode('utf-8'))
		source.close()

def main(argv):
	monthid = 0 

	try:
		opts, args = getopt.getopt(argv, "m:", ['month'])
	except getopt.GetoptError:
		print 'grabby.py -m <monthId>'
		sys.exit(2)
		
	for opt, arg in opts:
		if opt in ('-m', '--month'):
			monthid = arg 
	
	print 'MonthId [', monthid , ']'

	if monthid != 0:
		grab = ScreenGrabby()
		grab.runGrabby(monthid)


if __name__ == '__main__':
	main(sys.argv[1:])	
	
