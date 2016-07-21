#
# the pagespeedy spider 
#
#
#
#
#

import os
import datetime
from multiprocessing import Pool

import spider.SpeedyCrawler
import spider.speedydb

def spiderSites(site):
    folder = os.path.join(os.path.dirname(__file__), 'data/links/')
    spider = SpeedyCrawler.SpeedyCrawler(10000, folder)
    print 'Starting :        ', site[1], site[2]
    spider.process(site[1], site[2])
    print 'Done : ', site[1]

def nightlySpider(dayNum, threads):
    db = speedydb.SpeedyDb()
    sites = db.getSites()

    start = (dayNum-1)*14
    end = dayNum * 14

    print ''
    print '---------------------------------------------------------------------'
    print 'processing: ', start , 'to', end, ':', threads, 'threads'
    print '---------------------------------------------------------------------'
    print ''

    pool = Pool(processes=threads)
    pool.map(spiderSites, sites[start:end])
    pool.close()
    pool.join()

if __name__ == '__main__':
    dayNum = datetime.datetime.today().day
    nightlySpider(dayNum, 8)
    # nightlySpider(9, 8)
