# calls google for the mobile check.
# https://www.googleapis.com/pagespeedonline/v3beta1/mobileReady?url={site}

import os;
import sys;
import getopt;

import urllib;
import urllib2;
import json;
import time;

import base64

import speedy.speedydb;

class MobileCheck(object):

    def __init__(self):
        self.db = speedydb.SpeedyDb();
        

    def runCheck(self, monthId):
    
        print 'running mobile check'
        
        sites = self.db.getUncheckedMobileSites(monthId)
        
        print 'Processing ', len(sites), ' sites' 
        
        for site in sites:
        
            siteId = site[0]
            siteName = site[1]
            siteUrl = site[2]
            
            print ''
            print siteId, siteName, siteUrl, 
            self.getMobileResult(siteUrl, siteId, monthId)

    def getMobileResult(self, url, siteId, monthId):
    
        try:
            result = self.getMobileJson(url)
                        
            if result.__len__() > 10 :
                data = json.loads(result)
               
                usability = data['ruleGroups']['USABILITY']
                print usability['pass'],

                self.db.saveMobileCheck(siteId,monthId, usability['pass']);
        except Exception, e:
            print 'get mobile pass error:', url, e

    def getMobileJson(self, url):
        
        try:
            ps_url = "https://www.googleapis.com/pagespeedonline/v3beta1/mobileReady?url={0}".format(url)
            # print ps_url 
            
            response = urllib2.urlopen(ps_url, timeout=60)
            return response.read()
        except Exception, e:
            
            print 'get mobile result error:', url, e

def main(argv):
    monthid = 0 

    try:
        opts, args = getopt.getopt(argv, "m:", ['month'])
    except getopt.GetoptError:
        print 'mobilecheck.py -m <monthId>'
        sys.exit(2)

    for opt, arg in opts:
        if opt in ('-m', '--month'):
            monthid = arg 

    print 'MonthId [', monthid , ']'

    if monthid != 0:
        mc = MobileCheck()
        mc.runCheck(monthid)  

if __name__ == '__main__':
    main(sys.argv[1:])                          