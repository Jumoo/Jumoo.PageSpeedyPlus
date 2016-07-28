import urllib
import urllib2
from urlparse import urlparse
from urlparse import urljoin

import Queue
import json
import re
import os

import requests

import robotparser


url = 'http://www.westdevon.gov.uk/'

rp = robotparser.RobotFileParser()
rp.set_url(url + 'robots.txt')
rp.read()

canGet = rp.can_fetch("*", url)

print url, canGet 
