
import os
import sys
import getopt
import urllib2
import urllib 

from urlparse import urlparse

url = 'http://www.antrimandnewtownabbey.gov.uk/CMSPages/GetResource.ashx?stylesheetfile=/App_Themes/Default/CMSDesk.css'

headers = {'User-agent': 'Mozilla/5.0'}
request = urllib2.Request(url, None, headers)
response = urllib2.urlopen(request, timeout=10)
print response.read()