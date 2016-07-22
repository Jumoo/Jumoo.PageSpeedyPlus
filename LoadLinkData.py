import os
import json 
import csv 
from urlparse import urlparse

from speedy.speedydb import SpeedyDb
from speedy.domainwapple import DomainWapple

#
# Gets the folder (that will have all the files in.)
#
def getSiteFolder(name):
    folder = os.path.join(os.path.dirname(__file__), 'data/links/' + name + '/')
    if not os.path.exists(folder):
        return None
    return folder 

def getSiteInfo(name):
    folder = getSiteFolder(name)
    if folder is None:
        return None 

    info = '{0}{1}_info.txt'.format(folder, name)
    if not os.path.exists(info):
        return None
    
    with open(info) as data_file:
        data = json.load(data_file)

    if data['pages'] <= 1: 
        return None

    return data

def getDomains(id, url, name, db):

    purl = urlparse(url)
    domain = purl.netloc;

    folder = getSiteFolder(name)
    if folder is None:
        return None

    domains = '{0}{1}_domain.txt'.format(folder, name)
    if not os.path.exists(domains):
        return None

    with open(domains) as data_file:
        r = csv.reader(data_file)
        for row in r:
            if row[0].lower() != domain.lower():
                db.saveDomainInfo(id, row[0].replace("'", " "), row[1].replace("'", " "))

def loaddata():
    db = SpeedyDb()
    sites = db.getSites()

    for site in sites:
        name = site[1]
        print name, 
        data = getSiteInfo(name)
        if not (data is None):
            print 'Loading....', name, data['pages'] 
            db.saveLinkInfo(site[0], data['pages'], data['docs'], data['broken'], data['queued'])

        domains = getDomains(site[0], site[2], name, db)
   

if __name__ == '__main__':

    loaddata()

    wp = DomainWapple()
    wp.process()

#    wp.test('http://democracy.allerdale.gov.uk/ielistdocuments.aspx?cid=11&mid=3351')
#    wp.test('https://democracy.basingstoke.gov.uk/mgfindmember.aspx')