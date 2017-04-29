"""single peek"""

import getopt
import sys
from app.wapple import Wapple
from speedy.speedydb import SpeedyDb
from speedy.peeky import Peeky

MONTHID = 36
THREAD_COUNT = 1
  
def checkSite(site):
    siteId = site[0]
    url = site[2]
    print siteId, url

    db = SpeedyDb()
    wapple = Wapple()
    answers = wapple.scan(url)

    if answers is None or answers.__len__() == 0:
        return

    db.clearFeatures(siteId, MONTHID)


    try:
        for app in answers:
            categories = ""
            version = answers[app]["version"]
            for cat in answers[app]["categories"]:
                categories = cat["name"] + ","

            print ">> {0:<20} {1: <30} {2: <6}".format(app, categories.strip(","), version)
            db.saveFeatures(MONTHID, siteId, app, categories.strip(","), version)
    except:
        print 'failed: ', answers


#        categories = ""
#        version = thing["version"]
#        for c in thing["categories"]:
#            categories = c + ","


def CheckSites(sites, threads):
    for site in sites:
        checkSite(site)

    pky = Peeky()
    pky.goPeek(MONTHID)
    pky.close()


def main(argv):
    db = SpeedyDb()
    sites = db.getSites()

    CheckSites(sites, THREAD_COUNT)

if __name__ == '__main__':
    main(sys.argv[1:])
	