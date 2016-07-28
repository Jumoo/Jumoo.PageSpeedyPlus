#
# Speedy.Wapple (For Domains)
#
#

import os
import sys
import PyV8
import requests 
from urlparse import urlparse 
import speedydb 
import codecs
import urllib

try:
	import json
except ImportError:
	import simplejson as json 
	
wappal_script = '../lib/Wappalyzer/src/wappalyzer.js'
wappal_driver = '../lib/Wappalyzer/src/drivers/php/js/driver.js'

# for the subdomains, we are just looking for the localgov specific stuff.
#wappal_apps = 'lib/Wappalyzer/src/apps.json'
localgov_apps = '../config/localgov.services.json'
	
class DomainWapple(object):
    def __init__(self):
	
        self.file_dir = os.path.dirname(__file__)
        with open(os.path.join(self.file_dir, localgov_apps)) as f:
            data = json.loads(f.read())
		
        self.categories = data['categories']
        self.apps = data['apps']

        #with open(os.path.join(self.file_dir, localgov_apps)) as lgfile:
        #    lgdata = json.loads(lgfile.read())
		#
        #self.apps.update(lgdata['apps'])
        #self.categories.update(lgdata['categories'])		

        self.db = speedydb.SpeedyDb()
		
    def process(self):
        print 'running wapple stuff for Domains ' 

        sites = self.db.getSites()

        for site in sites:
            siteId = site[0]
            siteName = site[1]
            siteUrl = site[2]
            domains = self.db.getUndetectedDomains(siteId)
            for domain in domains:
                print domain[0], domain[3]
                # self.db.cleanDomainFeatures(domain[0])
                self.wapple(domain[0], domain[3])
            
    def test(self, url):
        self.wapple(-1, url)
	
    def wapple(self, id, url):

        try:
            ctxt = PyV8.JSContext()
            ctxt.enter()

            wappleScriptFile = os.path.join(self.file_dir, wappal_script);
            wapplyDriverFile = os.path.join(self.file_dir, wappal_driver) 

            with codecs.open(wappleScriptFile, 'r', 'utf8') as f:
                ctxt.eval(f.read())
                
            with codecs.open(wapplyDriverFile, 'r', 'utf8') as f:
                ctxt.eval(f.read())

            host = urlparse(url).hostname

            headers = requests.head(url,allow_redirects=True, timeout = 7)
            if headers.status_code == 200:
                url = self.cleanUrl(headers.url)
                contentType = headers.headers['content-type'].split(';')[0]
                if contentType == 'text/html':
                    response = requests.get(url, timeout=7)
                    html = response.text
                    headers = dict(response.headers)
                    data = {'host': host, 'url': url, 'html': html, 'headers': headers}
                    apps = json.dumps(self.apps)
                    categories = json.dumps(self.categories)

                    results = ctxt.eval("w.apps = %s; w.categories = %s; w.driver.data = %s; w.driver.init();" % (apps, categories, json.dumps(data)))

                    #print results 
                    answers = json.loads(results) 
                    print "{0} Feature Count {1}".format(url, answers.__len__())
                    for app, thing in answers.items():
                        categories = "" 
                        version = thing["version"]
                        for c in thing["categories"]: 
                            categories = str(c) + "," 

                        if id > 0:
                            self.db.saveDomainFeature(id, app, categories.strip(","), version) ;
                        else:
                            print app, '-', categories.strip(","), '-', version

            # print ''

        except:
            print 'error getting url: {0}'.format(url)
            self.db.saveDomainFeature(id, "error", "bad url", "0")		

    def cleanUrl(self, url):

        urlbits = urlparse(url)
        clensedUrl = '{0}://{1}'.format(urlbits.scheme, urlbits.netloc)

        if urlbits.path:
            if not '%20' in urlbits.path:
                clensedUrl += urllib.quote(urlbits.path.encode('utf-8'))
            else:
                clensedUrl += urlbits.path.encode('utf-8')

        if urlbits.query:
            clensedUrl += '?' + urlbits.query

        # we don't add the fragment, it's just a page anchor to the same page.
        return clensedUrl
