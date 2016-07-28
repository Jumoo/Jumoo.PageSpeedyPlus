#
# parser, gets the links from the pages.
#

from HTMLParser import HTMLParser
from urlparse import urljoin
from urlparse import urlparse
import requests 

import urllib2
import urllib
import json 
import re
import os

class SpeedyParser(HTMLParser):

    def setupParser(self, name, url):

        # site up the site boundries 
        urlbits = urlparse(url)
        
        self.base = urlbits.netloc
        self.root = urlbits.netloc.replace('www.', '')

        # load the exclusions (including any site specific ones)         
        self.exclusionsRegex = self.loadExclusions(name)

        #load the document stuff... 
        self.docHeaders = {
                'application/pdf': 'PDF File (media type)',
                'application/msword': 'Word Document',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document': 'Word Document'
            }

        self.docExtensions = {
            '.pdf': 'PDF file (link)',
            '.doc': 'Word Document', '.docx': 'Word Document', '.dot': 'Word Template', '.dotx': 'Word Template',
            '.xls': 'Excel Spreadsheet', '.xlsx': 'Excel Spreadsheet',
            '.ppt': 'Powerpoint', '.pptx': 'Powerpoint',
            '.csv': 'CSV File', '.txt': 'Text File',
            '.rtf': 'RTF File',
            '.ics': 'Calendar file',
            '.zip': 'Zip File',
            ".m4v": 'Video File'
        }


    def loadExclusions(self, siteName):

        spiderFolder = os.path.dirname(__file__)
        exclusionFile = os.path.join(spiderFolder, 'exclusions.json')
        with (open(exclusionFile, 'r')) as ef:
            exclusionList = json.load(ef)

        sitesFile = os.path.join(spiderFolder, 'site.exclusions.json')
        with (open(sitesFile)) as sf:
            siteExclusions = json.load(sf)

        if siteExclusions.has_key(siteName):
            exclusionList = exclusionList + siteExclusions[siteName]

        return "(" + ")|(".join(exclusionList) + ")"
        

    def handle_starttag(self, tag, attrs):
        if tag == 'a':
            self.handle_anchor(tag, attrs)

        if tag == 'base':
            self.handle_base(tag, attrs)

    # what happens when we see a base tag in the header
    def handle_base(self, tag, attrs):
        for (key, value) in attrs:
            if key == 'href':
                self.url = value

    def handle_anchor(self, tag, attrs):
        for (key, value) in attrs:
            if key == 'href':

                href = value.strip()

                if not href.startswith('#'):

                    link = urljoin(self.url, href).strip()

                    if not self.isExcluded(link):

                        if self.containsBaseUrl(link):
                            # local to this site
                            doctype = self.isDocument(link)
                            docLink = link.lower()
                            if doctype:
                                # is a document 
                                if not self.docs.has_key(docLink):
                                    # we haven't seen before.
                                    self.docs[docLink] = doctype
                            else:
                                # isn't a document

                                # we are not lower checking here... we might end
                                # up with duplicate links from a page :-(
                                if not link in self.links:
                                    self.links = self.links + [link]

                        else:
                            # isn't a local link
                            # se if its a related domain
                            linkHost = urlparse(link).netloc.lower()
                            
                            if linkHost.endswith(self.root):
                                # same base domain, so we add this to the domain list

                                if not self.domains.has_key(linkHost):
                                    self.domains[linkHost] = link

    # does the url contain 
    # something we ignore
    def isExcluded(self, url):
        if re.search(self.exclusionsRegex, url.lower()):
            return True
        else:
            return False
        

    # is the url a document 
    def isDocument(self, url):
        testUrl = url.lower()
        for ext, filetype in self.docExtensions.items():
            if ext in testUrl:
                return filetype

        return ''  


    def parsePage(self, url):
        
        self.links = []
        self.domains = {}
        self.docs = {}

        # all the links coming into the parser are clean. 
        #self.url = self.cleanUrl(url)
        self.url = url          

        # response = urllib2.urlopen(self.url, timeout=7)
        headers = requests.head(url,allow_redirects=True, 
                                    timeout = 7, headers = {'User-Agent': 'Mozilla/5.0 (SpeedySpider-Crawler)'})
        # print '---->', headers.status_code, headers.url, headers.history

        if headers.status_code == 200:
            self.url = self.cleanUrl(headers.url)
            if self.containsBaseUrl(self.url):
                contentType = headers.headers['content-type'].split(';')[0]
                if contentType == 'text/html':
                    # the response can change the url, and it 
                    # can redirect it out of this domain.
                    code, html = self.getHtml(self.url)
                    if html:
                        self.feed(html)
                        return code, self.links, self.docs, self.domains 
                else:
                    # check to see if this is a document 
                    if self.docHeaders.has_key(contentType):
                        self.docs[self.url] = self.docHeaders[contentType]
                        return 1, self.links, self.docs, self.domains

            else:
                # see if its a related domain
                linkHost = urlparse(self.url).netloc
                
                if linkHost.endswith(self.root):
                    # same base domain, so we add this to the domain list
                    if not self.domains.has_key(linkHost):
                        self.domains[linkHost] = self.url

        else: 
            # we have another status code, this one is probibly a 404
            # we need to actually pass this down as an error.
            return headers.status_code,self.links, self.docs, self.domains 

        return 0, self.links, self.docs, self.domains            

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

    # get the html, and decode it (if we can)
    def getHtml(self, url):
        
        req = urllib2.Request(url)
        req.add_header('User-Agent', 'Mozilla/5.0 (SpeedySpider-Crawler)')
        response = urllib2.urlopen(req, timeout=7)   
        
        html = ""
        if response.code == 200:
            htmlBytes = response.read()
            try:
                html = htmlBytes.decode('utf-8')
            except UnicodeDecodeError as e:
                html = htmlBytes.decode('iso-8859-1') 
        return response.code, html

    # are we one the same domain ? 
    # (lets us craw http & https) if we only compare netloc. 
    def containsBaseUrl(self, url):
        # return self.url.startswith(self.base)
        urlbits = urlparse(url) 
        return urlbits.netloc.lower() == self.base;


