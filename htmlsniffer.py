#
# trying to get htmlcodesniffer to run via python
#
#
#
#

import os
import sys
import getopt
import time
from os import walk

from selenium import webdriver

checker = 'lib/codesniffer/HTMLCS.js'
runner = 'lib/codesniffer/Contrib/PhantomJS/runner.js'
standard = 'lib/codesniffer/standards/WCAG2AA/ruleset.js'
standards_folder = 'lib/codesniffer/standards'

class htmlChecker(object):

    def __init__(self):
        
        self.dir = os.path.dirname(__file__)
        
        phantompath = os.path.join(self.dir, 'lib/phantomjs/bin/phantomjs')
        self.driver = webdriver.PhantomJS(executable_path='lib/phantomjs/bin/phantomjs')

    def page(self, url):
    
        print 'checking', url 
        
        self.driver.set_window_size(1280, 1024)
        self.driver.get(url)
        
        #print 'waiting....'
        #time.sleep(5)
        
        javaScript = ''
        
        javaScript += self.loadStandards(standards_folder);
        
        javaScript += self.getScript(checker)        
        javaScript += self.getScript(runner)        
        
        javaScript += self.getScript('sniffer/loader.js')        
        print "loaded javascript: ", len(javaScript)
       
        result = self.driver.execute_script(javaScript)
        
        print 'Result: ', result

        time.sleep(2)

        logs = self.driver.get_log("browser");
        for log in logs:
            print "ConsoleLog:", log['message']

        
        
       
    def getScript(self, script):
        with open(os.path.join(self.dir, script), 'r') as file:
            return file.read()    
   
    def loadStandards(self, folder):
        
        standardsScript = ""
        
        fullpath = os.path.join(self.dir, folder)
   
        for(dirpath, dirnames, filenames) in walk(fullpath):
            for file in filenames:
                standardsScript += self.getScript(os.path.join(dirpath, file))
                
        return standardsScript
                
                    
 
def main(argv):
    check = htmlChecker()
    check.page('http://jumoo.uk')
    
    
if __name__ == '__main__':
    main(sys.argv[1:])