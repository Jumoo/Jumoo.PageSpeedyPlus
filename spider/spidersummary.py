import os
import json

def loadInfo(file):

    with open(file, 'r') as f:
        info = json.load(f)

    return info

def printInfo(info):
    # return "[p:{1} l:{2} X:{3} q:{4}] {0}".format(info['site'], info['pages'], info['links'], info['broken'], info['queued'])
    return "{0}, {1}".format(info['site'], info['url'])




for dirname, dirnames, filenames in os.walk('..\data\links'):
    
    hasInfo = False 

    for filename in filenames:
        if '_info' in filename:
            info = loadInfo(os.path.join(dirname, filename))

            #if info['pages'] == 0 and info['domains'] == 1:
            #    print 'no page, one domain', printInfo(info)

            #if info['pages'] == 10000:
            #    print '[max pages]  ', printInfo(info)
            
            if info['broken'] == "1000":
                print '[max errors] ', printInfo(info)
            
            #if info['links'] == 20000:
            #    print '[max links]  ', printInfo(info)

            hasInfo = True
    
    
    #if not hasInfo :
        # print 'no info', dirname
