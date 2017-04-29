""" core wapple functions  """

import sys
import os
from urlparse import urlparse
import codecs
import requests
import PyV8

try:
    import json
except ImportError:
    import simplejson as json


W_SCRIPT = '../lib/Wappalyzer/src/wappalyzer.js'
W_DRIVER = '../config/driver.js'
W_APPS = '../lib/Wappalyzer/src/apps.json'
W_LGOVAPPS = '../config/localgov.apps.json'

class Wapple(object):
    """ does the wapple stuff """

    def __init__(self):

        self.file_dir = os.path.dirname(__file__)
        with open(os.path.join(self.file_dir, W_APPS)) as app_file:
            data = json.loads(app_file.read())

        self.categories = data['categories']
        self.apps = data['apps']

        with open(os.path.join(self.file_dir, W_LGOVAPPS)) as local_file:
            lgdata = json.loads(local_file.read())

        self.apps.update(lgdata['apps'])
        self.categories.update(lgdata['categories'])

    def scan(self, url):
        """ scan the url """

        try:
            context = PyV8.JSContext()
            context.enter()

            scriptFile = os.path.join(self.file_dir, W_SCRIPT)
            driverFile = os.path.join(self.file_dir, W_DRIVER)

            with codecs.open(scriptFile, 'r', 'utf8') as script_file:
                context.eval(script_file.read())

            with codecs.open(driverFile, 'r', 'utf8') as driver_file:
                context.eval(driver_file.read())

            host = urlparse(url).hostname

            response = requests.get(url)
            html = response.text
            headers = dict(response.headers)
            data = {'host': host, 'url': url, 'html': html, 'headers': headers}

            apps = json.dumps(self.apps)
            categories = json.dumps(self.categories)

            results = context.eval(
                "w.apps = %s; w.categories = %s; w.driver.data = %s; w.driver.init();"
                % (apps, categories, json.dumps(data)))

            answers = json.loads(results)

            #print ...
            #print 'features: {0}'.format(answers.__len__())
            return answers
        except:
            print 'unable to get homepage :('
            
