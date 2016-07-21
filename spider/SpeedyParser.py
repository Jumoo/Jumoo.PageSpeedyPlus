#
# parser, gets the links from the pages.
#

from HTMLParser import HTMLParser
from urlparse import urljoin
from urlparse import urlparse

import urllib2
import urllib
import json 
import re

class SpeedyParser(HTMLParser):

    def setupParser(self, url):

        # site up the site boundries 
        urlbits = urlparse(url)
        self.base = '{uri.scheme}://{uri.netloc}'.format(uri=urlbits)
        self.root = urlbits.netloc.replace('www.', '')

        # load the exclusions         
        with open('spider/exclusions.json') as ef:
            regexes = json.load(ef)

        self.exclusionsRegex = "(" + ")|(".join(regexes) + ")"

        #load the document stuff... 
        self.docHeaders = {
                'application/pdf': 'PDF File',
                'application/msword': 'Word Document',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document': 'Word Document'
            }

        self.docExtensions = {
            '.pdf': 'PDF file',
            '.doc': 'Word Document', '.docx': 'Word Document', '.dot': 'Word Template', '.dotx': 'Word Template',
            '.xls': 'Excel Spreadsheet', '.xlsx': 'Excel Spreadsheet',
            '.ppt': 'Powerpoint', '.pptx': 'Powerpoint',
            '.csv': 'CSV File', '.txt': 'Text File',
            '.rtf': 'RTF File',
            '.ics': 'Calendar file',
            '.zip': 'Zip File'
        }

    def handle_starttag(self, tag, attrs):
        if tag == 'a':
            self.handle_anchor(tag, attrs)

        if tag == 'base':
            self.handle_base(tag, attrs)

    def handle_base(self, tag, attrs):
        for (key, value) in attrs:
            if key == 'href':
                self.url = value.lower()  

    def handle_anchor(self, tag, attrs):
        for (key, value) in attrs:
            if key == 'href':

                if not value.startswith('#'):

                    link = urljoin(self.url, value).lower()

                    if not self.isExcluded(link):

                        if link.startswith(self.base):
                            # local to this site
                            doctype = self.isDocument(link)
                            if doctype and not self.docs.has_key(link):
                                # is a document 
                                self.docs[link] = doctype
                            else:
                                # isn't a document
                                if not link in self.links:
                                    self.links = self.links + [link]

                        else:
                            # isn't a local link
                            # se if its a related domain
                            linkHost = urlparse(link).netloc
                            
                            if linkHost.endswith(self.root):
                                # same base domain, so we add this to the domain list

                                if not self.domains.has_key(linkHost):
                                    self.domains[linkHost] = link

    # does the url contain 
    # something we ignore
    def isExcluded(self, url):
        if re.search(self.exclusionsRegex, url):
            return True
        else:
            return False
        

    # is the url a document 
    def isDocument(self, url):
        for ext, filetype in self.docExtensions.items():
            if ext in url:
                return filetype

        return ''  


    def parsePage(self, url):
        
        self.links = []
        self.domains = {}
        self.docs = {}

        # all the links coming into the parser are clean. 
        #self.url = self.cleanUrl(url)
        self.url = url          

        response = urllib2.urlopen(self.url, timeout=7)
        headers = response.info()
        # print response.code 

        if response.code == 200:
            self.url = self.cleanUrl(response.url)
            if self.url.startswith(self.base):
                if headers.type == 'text/html':
                    # the response can change the url, and it 
                    # can redirect it out of this domain.
                    htmlBytes = response.read()
                    html = htmlBytes.decode('utf-8')
                    self.feed(html)
                    return True, self.links, self.docs, self.domains 
                else:
                    # check to see if this is a document 
                    if self.docHeaders.has_key(headers.type):
                        self.docs[self.url] = self.docHeaders[headers.type]
                        return False, self.links, self.docs, self.domains

            else:
                # ideally we want to do the domain check here...
                # se if its a related domain
                linkHost = urlparse(self.url).netloc
                
                if linkHost.endswith(self.root):
                    # same base domain, so we add this to the domain list
                    if not self.domains.has_key(linkHost):
                        self.domains[linkHost] = self.url


        return False, self.links, self.docs, self.domains            

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
