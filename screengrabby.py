import os
import sys
import speedydb

from selenium import webdriver

screenshot_folder = '{0}/results/{1}/screengrabs/'
source_folder = '{0}/results/{1}/html/'

class ScreenGrabby(object):

	def __init__(self):	
		self.db = speedydb.SpeedyDb()		
		self.driver = webdriver.PhantomJS(executable_path='phantom/phantomjs')
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
			self.driver.set_window_size(1280, 1024)
			self.driver.get(url)
			
			file_name = self.screenfolder + '/' + name + '.png'
			self.driver.save_screenshot(file_name)
			
			self.saveSource(name, self.driver.page_source);
			
			print 'grabbed' 
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
			
if __name__ == '__main__':
	grab = ScreenGrabby()
	grab.runGrabby(11)
	
