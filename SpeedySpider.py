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
import math


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
    print '>>>>> Starting :        ', site[1], site[2]
    spider.process(site[1], site[2])
    print '<<<<< Done     :        ', site[1]

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
def respider(groupsize, threads):
    db = SpeedyDb()
    sites = db.getSpiderSitesInError()

    siteCount = len(sites)
    nights = int(math.ceil(float(siteCount) / groupsize))
    size = int(math.ceil( siteCount / nights))
    day = datetime.datetime.today().day - 1

    group = (day % nights)+1
    start = group * size;
    end =  min((group * size) + size, siteCount)

    print '    Performing recrawl from sites in error ( currently', siteCount , ')'
        
    print '    Day:', day, '. Group:', group, 
    print '. Start:', start, ". End:", end
    print ''

    print r'------------------------------------------------------------------'

    SpiderSites(sites[start:end], threads)

    #for site in sites[start:end]:
    #       print site[1], site[2]



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
    
#    nightlySpider(day, 8)

#    nightlySpider(12, 8)
#    site = ['1', 'liverpool', 'http://liverpool.gov.uk']
#    spiderSites(site)

    respider(14, 7)
    

    # db = SpeedyDb()
    # sites = db.getNewSites(31)

    # SpiderSites(sites, 8)

