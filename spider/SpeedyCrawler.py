#
# the pageSpeedySpider
#
#

import os
import sys
import urllib2
import socket

from urlparse import urlparse
import robotparser

import Queue 
import SpeedyParser

import requests 

from timeit import default_timer as timer  

class SpeedyCrawler(object):

    def __init__(self, maxPages, folder):
        self.maxPages = maxPages
        self.rootFolder = folder 

        self.dontIndex = ["downloads/file", "downloads/download"]

    def process(self, name, url):

        self.name = name
        self.folder = self.__getSiteFolder(name)

        timer_start = timer()

        urlbits = urlparse(url)

        base = '{uri.scheme}://{uri.netloc}'.format(uri=urlbits)
        root = urlbits.netloc.replace('www.', '')

        queue = Queue.Queue()
        queue.put({"page": url, "parent": ""})

        pages = [url]
        domains = []
        docs = {}
        errors = {}

        discovered = []

        # load in robots.txt
        try:
            rp = robotparser.RobotFileParser()
            rp.set_url(url + '/robots.txt')
            rp.read()
        except:
            print "--- can't read robots", url
            return 

        counters = { "pages": 0, "domains": 0, "documents": 0, "links": 0, "errors": 0, "queued": 0}

        linksParsed = 0

        # all the files
        f_links = self.__openFile('links')
        f_docs = self.__openFile('docs')
        f_err = self.__openFile('err')
        f_domain = self.__openFile('domain')

        maxLinks = self.maxPages*2
        maxErrors = 500

        parser =  SpeedyParser.SpeedyParser()
        parser.setupParser(name, url)

        while counters['pages'] < self.maxPages and linksParsed < maxLinks and counters['errors'] < maxErrors and not queue.empty():

            linksParsed += 1

            next = queue.get()

            page = next['page']
            parent = next['parent']

            # when we are running in loads of threads, writing every line doesn't really help us in any way
            if linksParsed == 1 or linksParsed % 25 == 0:
                print '{0}/{1} [P:{2} D:{3} S:{4} X:{5}]'.format(linksParsed, queue.qsize(), counters['pages'], counters['documents'], counters['domains'], counters['errors']), 
                print page.encode('utf-8').ljust(120)[:120]

            try:
                if not rp.can_fetch("*", page):
                    print '>> robots.txt blocked page', page.encode('utf-8')
                    continue

                pageCode, page_links, page_docs, page_domains = parser.parsePage(page)

                # parser may sometimes, return a doc or a domain, the page
                # may not really be a 'page'
                if pageCode == 200: 
                    bits = urlparse(page)
                    page_url = '{uri.scheme}://{uri.netloc}{uri.path}'.format(uri=bits).lower().encode('utf-8')
        
                    if not page_url.lower() in pages and self.isIndexed(page_url):
                        counters["pages"] += 1
                        pages = pages + [page_url.lower()]
                        f_links.write(page_url + '\n')
                
                elif pageCode > 1:
                    # it's an error code. add it to errors
                    counters['errors'] += 1
                    self.__saveError(page, parent, f_err, pageCode)

                # process the domains, links and docs from the parse. 
                for domain, link in page_domains.items():
                    if not domain in domains:
                        counters["domains"] += 1
                        domains = domains + [domain.lower()]
                        f_domain.write(domain.encode('utf-8') + ',' + link.encode('utf-8') + '\n')

                # Links
                # =====
                # Everything else comes from the parser lower cased, 
                # but links do not, because sometimes it matters what the 
                # case it. 
                #
                # for comparison and checking purposes we lower case things 
                # but when putting stuff in the queue, it goes in in the case
                # the link is.
                for link in page_links:
                    cleanLink = parser.cleanUrl(link)
                    if not cleanLink.lower() in discovered:
                        discovered = discovered + [cleanLink.lower()]
                        # we check lower case everywhere but
                        #   -  we maintain the case in the queue, for sites where that matters
                        #
                        queue.put({'page': cleanLink, 'parent': page}) 

                for doc, docType in page_docs.items():
                    if not docs.has_key(doc):
                        counters["documents"] += 1
                        docs[doc] = docType
                        f_docs.write(doc.encode('utf-8') + ' , ' + docType + '\n')
        
            except requests.exceptions.Timeout as e:
                counters['errors'] += 1
                self.__saveError(page, parent, f_err, e)
            except urllib2.HTTPError as e:
                counters['errors'] += 1
                self.__saveError(page, parent, f_err, e)
            except urllib2.URLError as e:
                counters['errors'] += 1
                self.__saveError(page, parent, f_err, e)
            except socket.timeout as e:
                counters['errors'] += 1
                self.__saveError(page, parent, f_err, e)
            except:
                counters['errors'] += 1
                e = sys.exc_info()[0] 
                self.__saveError(page, parent, f_err, e)

        # end while loop
        
        f_docs.close()
        f_links.close()
        f_domain.close()
        f_err.close()

        if not queue.empty():
            print 'Empting Queue' , queue.qsize()
            f_queue = self.__openFile('queue')
            
            while not queue.empty():
                i = queue.get()
                f_queue.write(i['page'].encode('utf-8') + ',' + i['parent'].encode('utf-8') + '\n')
                counters["queued"] += 1
            f_queue.close();

        timer_elapsed = timer() - timer_start

        f_info = self.__openFile('info')
        f_info.write('{' + '\n')
        f_info.write(' "site": "' + self.name + '",\n')
        f_info.write(' "url": "' + url + '",\n')
        f_info.write(' "pages": "' + str(counters['pages']) + '",\n')
        f_info.write(' "links": "' + str(linksParsed) + '",\n')
        f_info.write(' "domains": "' + str(counters['domains']) + '",\n')
        f_info.write(' "docs": "' + str(counters['documents']) + '",\n')
        f_info.write(' "queued": "' + str(counters['queued']) + '",\n')
        f_info.write(' "broken": "' + str(counters['errors']) + '",\n')
        f_info.write(' "timer": "' + str(timer_elapsed) + '"\n')
        f_info.write('}' + '\n')
        f_info.close()

        print 'site finished', self.name, str(counters['pages']), ' in ', str(timer_elapsed/60), 'mins'


    def isIndexed(self, url):
        for exc in self.dontIndex:
            if exc in url:
                return False

        return True

    def __getSiteFolder(self, name):
        folder = os.path.join(self.rootFolder, name + '/')
        if not os.path.exists(folder):
            os.makedirs(folder)
        return folder
        
    def __openFile(self, type):
        filepath = '{0}{1}_{2}.txt'.format(self.folder, self.name, type)
        f = open(filepath, 'w', 0)
        return f;

    def __saveError(self, page, parent, file, err):
        try:
            print '                        !!', err, page.encode('utf-8')
            file.write( str(err) + ',' + page.encode('utf-8') + ' , ' + parent.encode('utf-8') + '\n')
        except:
            file.write('unable to log error')
