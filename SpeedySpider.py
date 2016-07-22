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

from spider.SpeedyCrawler import SpeedyCrawler
from speedy.speedydb import SpeedyDb

def spiderSites(site):
    folder = os.path.join(os.path.dirname(__file__), '../data/links/')
    spider = SpeedyCrawler(10000, folder)
    print 'Starting :        ', site[1], site[2]
    spider.process(site[1], site[2])
    print 'Done : ', site[1]

def nightlySpider(dayNum, threads):
    db = SpeedyDb()
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

    print r'   _____                     __      _____       _     __         '
    print r'  / ___/____  ___  ___  ____/ /_  __/ ___/____  (_)___/ /__  _____'
    print r'  \__ \/ __ \/ _ \/ _ \/ __  / / / /\__ \/ __ \/ / __  / _ \/ ___/'
    print r' ___/ / /_/ /  __/  __/ /_/ / /_/ /___/ / /_/ / / /_/ /  __/ /    '
    print r'/____/ .___/\___/\___/\__,_/\__, //____/ .___/_/\__,_/\___/_/     '
    print r'    /_/                    /____/     /_/  site crawling thingy   '
    print r'------------------------------------------------------------------'
    print 

    dayNum = datetime.datetime.today().day
    # nightlySpider(dayNum, 8)
    # nightlySpider(10, 8)
