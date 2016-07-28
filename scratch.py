import urllib
import urllib2
from urlparse import urlparse
from urlparse import urljoin

import Queue
import json
import re
import os

import requests


url = 'http://www.liverpool.gov.uk'

print '>> START'

print '    HEAD', url,
request_header = {'User-Agent': 'Mozilla/5.0 (SpeedySpider-Crawler)'}
headers = requests.head(url,allow_redirects=True, 
                            timeout = 7, headers = request_header)

print headers.status_code

print '    GET', headers.url,
if headers.status_code == 200:
    req = urllib2.Request(headers.url)
    req.add_header('User-Agent', 'Mozilla/5.0 (SpeedySpider-Crawler)')
    response = urllib2.urlopen(req, timeout=7)
    print response.code

print '<< DONE'
