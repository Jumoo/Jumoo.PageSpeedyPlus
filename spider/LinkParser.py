#
# Site Link Counter
#
#
#
#

from HTMLParser import HTMLParser
from urlparse import urljoin
from urlparse import urlparse

import urllib
import urllib2

import sys
import os

class PageParser(HTMLParser):

    docHeaders = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']
    docExtensions = ['.doc', '.pdf', '.xls', '.xlsx', '.docx', '.ppt', '.pptx', '.ics', '.csv', '.dot']
    exclusions = ['.gif', '.jpg', '.jpeg', '.png', 
                    '/external', '@', 'javascript:', 
                    '?page=100', '?p=100', 
                    'authenticate.aspx', 'search.aspx', 'search?=q'
                    '/localviewext']

    def handle_starttag(self, tag, attrs):
        if tag == 'a':
            for (key, value) in attrs:
                if key == 'href':
                    if not value.startswith('#'):

                        # urljoin will add the base site on if this is a local
                        # link
                        linkUrl = urljoin(self.cUrl, value).lower()

                        if not self.isExcluded(linkUrl):

                            if linkUrl.startswith(self.base):
                                # is local

                                if self.isDocument(linkUrl):
                                    if not linkUrl in self.docs:
                                        # is a document
                                        self.docs = self.docs + [linkUrl]
                                else:
                                    # isn't a document
                                    if not linkUrl in self.links:
                                        self.links = self.links + [linkUrl]
                            else:
                                # isn't local
                                bits = urlparse(linkUrl)
                                if bits.netloc.endswith(self.root):
                                    # same base domain
                                    if not bits.netloc in self.domains:
                                        self.domains = self.domains + [bits.netloc]
                                        self.domain_links = self.domain_links + [linkUrl]
                                

    # gets a single page, the parser gets called for all the tags
    # and we fill our three arrays, for links, docs and domains
    def getLinks(self, url, root):

        urlbits = urlparse(url)

        self.base = '{uri.scheme}://{uri.netloc}/'.format(uri=urlbits)
        self.root = root

        self.links = []
        self.domains = []
        self.domain_links = []
        self.docs = []

        self.cUrl = self.cleanUrl(url)

        # get the Page 
        response = urllib2.urlopen(self.cUrl, timeout=7)
        headers = response.info()

        if headers.type == 'text/html':
            htmlBytes = response.read()
            html = htmlBytes.decode('utf-8')

            if response.code == 200:
                self.feed(html)

                return self.links, self.docs, self.domains, self.domain_links
        
        else:
            if headers.type in self.docHeaders:
                self.docs = self.docs + [self.cUrl] 
                return self.links, self.docs, self.domains, self.domain_links

        return [], [], [], []

    # is the url in the exclusions ?
    def isExcluded(self, url):
        for ex in self.exclusions:
            if ex in url:
                return True
        return False

    # is the url a document ?
    def isDocument(self, url):
        for doc in self.docExtensions:
            if doc in url:
                return True
        return False

    # clean the url (sometimes we get badlinks) ?
    def cleanUrl(self, url):
        urlbits = urlparse(url)

        clensedUrl = '{0}://{1}{2}'.format(urlbits.scheme, urlbits.netloc, urllib.quote(urlbits.path))
        if urlbits.query:
            clensedUrl = clensedUrl + '?' + urlbits.query

        # we don't index the fragment bits (this effectively removes fragments)
        #if urlbits.fragment:
        #    clensedUrl = clensedUrl + '#' + urlbits.fragment

        return clensedUrl
        