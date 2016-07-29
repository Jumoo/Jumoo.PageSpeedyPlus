#
# the pagespeedy spider 
#
#
#
#
#

import os
import datetime
import getopt
import sys

from multiprocessing import Pool

from spider.SpeedyCrawler import SpeedyCrawler
from speedy.speedydb import SpeedyDb

#
# main spider call, will spider a site. using the crawler
#
def spiderSingleSite(site):
    
    # this is how many pages we will max out on
    # limit * 2 is the number links we will try
    page_limit = 10000
    
    folder = os.path.join(os.path.dirname(__file__), 'data/links/')
    spider = SpeedyCrawler(page_limit, folder)
    print '################ Starting :        ', site[1], site[2]
    spider.process(site[1], site[2])
    print '################ Done     :        ', site[1]

#
# Multi-threaded spider crawl, will fire off multiple site crawls
# (each crawl is single threaded)
#
def SpiderSites(sites, threads):
    pool = Pool(processes=threads)
    pool.map(spiderSingleSite, sites)
    pool.close()
    pool.join()


#
# helper functions
#

#
# the nightly spider is designed to crawl all sites over 30 days
#    everynight it takes 14 sites from speedy, and crawls them.
#
def nightlySpider(dayNum, threads):
    db = SpeedyDb()
    sites = db.getSpiderSites()

    start = (dayNum-1)*14
    end = dayNum * 14

    print ''
    print '---------------------------------------------------------------------'
    print 'processing: ', start , 'to', end, ':', threads, 'threads'
    print '---------------------------------------------------------------------'
    print ''

    SpiderSites(sites[start:end], threads)

#
# respiders the broken sites.
#
def respider(count, threads):
    db = SpeedyDb()
    sites = db.getSpiderSitesInError()
    SpiderSites(sites[count:count+14, threads])



#    for site in sites[count:count+14]:
#            print site[0], site[1]

if __name__ == '__main__':

    print r'   _____                     __      _____       _     __         '
    print r'  / ___/____  ___  ___  ____/ /_  __/ ___/____  (_)___/ /__  _____'
    print r'  \__ \/ __ \/ _ \/ _ \/ __  / / / /\__ \/ __ \/ / __  / _ \/ ___/'
    print r' ___/ / /_/ /  __/  __/ /_/ / /_/ /___/ / /_/ / / /_/ /  __/ /    '
    print r'/____/ .___/\___/\___/\__,_/\__, //____/ .___/_/\__,_/\___/_/     '
    print r'    /_/                    /____/     /_/  site crawling thingy   '
    print r'------------------------------------------------------------------'
    print
    
#    day = datetime.datetime.today().day
#    nightlySpider(day, 8)

#    nightlySpider(12, 8)
#    site = ['1', 'liverpool', 'http://liverpool.gov.uk']
#    spiderSites(site)

#    respider(42, 8)
    

    db = SpeedyDb()
    sites = db.getNewSites(31)

    SpiderSites(sites, 8)