import os
import sys
import LinkParser
from urlparse import urlparse
import urllib2

import speedydb
from multiprocessing import Pool
import datetime
import robotparser 

from timeit import default_timer as timer

def setupFolder(name):
    folder = os.path.join(os.path.dirname(__file__), 'data/links/' + name + '/')
    if not os.path.exists(folder):
        os.makedirs(folder)
    return folder 

def openFile(folder, name, type):
    filepath = '{0}{1}_{2}.txt'.format(folder, name, type)
    f = open(filepath, 'w', 0)
    return f

def spider(name, url, maxPages):

    timer_start = timer()

    urlbits = urlparse(url)

    base = '{uri.scheme}://{uri.netloc}'.format(uri=urlbits)
    root = urlbits.netloc.replace('www.', '')

    print '========================'
    print ' site ::', name
    print ' spidering :: ', url
    print '========================'

    site_pages = [url]
    site_domains = [root]
    site_docs = []
    site_brokenLinks = []

    rp = robotparser.RobotFileParser()
    rp.set_url(url + '/robots.txt')
    rp.read()

    visited = [url]
    queue = [url]

    pageCount = 0
    linkCount = 0
    docCount = 0
    domainCount = 0
    brokenLinkCount = 0

    folder = setupFolder(name)

    f_links = openFile(folder, name, 'links')
    f_docs = openFile(folder, name, 'docs')
    f_err = openFile(folder, name, 'err')
    f_domain = openFile(folder, name, 'domain')

    maxLinks = maxPages*2

    while pageCount < maxPages and linkCount < maxLinks and brokenLinkCount < 1000 and queue != [] :

        linkCount += 1

        page = queue[0]
        queue = queue[1:]

        print '{0}/{1}: [P:{2}, D:{3}, S:{4}, X:{5}]'.format(linkCount, len(queue), pageCount, docCount, domainCount, brokenLinkCount),
        print page.encode('utf-8').ljust(100)[:100]

        try:
            if not rp.can_fetch("*", page) :
                print '* robots blocked', page.encode('utf-8')
                continue;
            
            parser = LinkParser.PageParser()
            page_links, page_docs, page_domains, page_domainlinks = parser.getLinks(page, root)

            bits = urlparse(page)
            page_url = '{uri.scheme}://{uri.netloc}{uri.path}'.format(uri=bits).lower()
            pagg_url = page_url.encode('utf-8')
            
            #if page_url in site_pages:
            #    continue


            if not page_url in site_pages:
                pageCount += 1
                site_pages = site_pages + [page_url]
                f_links.write(page_url + '\n')

            
            for idx, domain in enumerate(page_domains):
                if not domain in site_domains:
                    site_domains = site_domains + [domain]
                    domainCount += 1 
                    f_domain.write(domain.encode('utf-8') + ',' + page_domainlinks[idx] + '\n')
            
            for link in page_links:
                clink = parser.cleanUrl(link)
                if not clink in visited and not clink in queue:
                    queue = queue + [clink]
                    visited = visited + [clink]

            for doc in page_docs:
                if not doc in site_docs:
                    site_docs = site_docs + [doc]
                    docCount += 1
                    f_docs.write(doc.encode('utf-8') + '\n')

        except urllib2.HTTPError as e:
            try:
                print '!', e, page.encode('utf-8')
                f_err.write( str(e) + ',' + page.encode('utf-8') + '\n')
                brokenLinkCount += 1
            except:
                f_err.write( 'unable to log url ')
        except:
            try:
                e = sys.exc_info()[0]
                print '!', e, page.encode('utf-8')
                f_err.write( str(e) + ',' + page.encode('utf-8') + '\n')
                brokenLinkCount += 1
            except:
                f_err.write( 'unable to log url ')
    
    f_docs.close()
    f_links.close()
    f_domain.close()
    f_err.close()

    if queue != [] :
        # queue isn't empty (we must have hit buffers)
        f_queue = openFile(folder, name, 'queue')
        for q in queue:
            f_queue.write(q.encode('utf-8') + '\n')
        f_queue.close()

    print '>>', name, url, 'Indexed'

    timer_elapsed = timer() - timer_start

    f_info = openFile(folder, name, 'info')
    f_info.write('{')
    f_info.write('"site": "' + name + '",')
    f_info.write('"url": "' + url + '",')
    f_info.write('"pages": ' + str(pageCount) + ',')
    f_info.write('"links": ' + str(linkCount) + ',')
    f_info.write('"domains": ' + str(domainCount) + ',')
    f_info.write('"docs": ' + str(docCount) + ',')
    f_info.write('"queued": ' + str(len(queue)) + ',')
    f_info.write('"broken": ' + str(brokenLinkCount) + ',')
    f_info.write('"timer": "' + str(timer_elapsed) + '"')   
    f_info.write('}')
    
    f_info.close()

def spiderSpeedySite(site):
    try:
        siteId = site[0]
        siteName = site[1]
        siteUrl = site[2]

        spider(siteName, siteUrl, 10000)
    except:
        print '! site error', site[1]

def nightySites():
    pool = Pool(processes=8)

    db = speedydb.SpeedyDb()
    sites = db.getSites()

    # we split by day - so if we run everyday, we do all sites in a month...
    dayNo = datetime.datetime.today().day
    # dayNo = 8
    start = (dayNo-1) * 14
    end = dayNo * 14

    pool.map(spiderSpeedySite, sites[start:end])

    pool.close()
    pool.join()

    print '>>>> completed for now'

if __name__ == '__main__':
    nightySites()
    # spider("_test", "http://www.allerdale.gov.uk/", 10)
