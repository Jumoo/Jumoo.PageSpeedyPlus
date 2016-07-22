import urllib
import urllib2
from urlparse import urlparse
from urlparse import urljoin

import Queue
import json
import re

url = "http://www.colchester.gov.uk/article/14241/article/10739/article/10740/article/10736/what-can-you-see"
url = "http://www.cotswold.gov.uk/about-the-council/councillors-committees/meetings,-minutes-agendas/"
url = "http://www.renfrewshire.gov.uk/article/3010/building-standards-register"
url = "http://www.rochdale.gov.uk/"
url = "http://www.devon.gov.uk/"
url = "http://www.east-northamptonshire.gov.uk/download/meetings/id/2906/item_1_-_minutes_of_the_meeting_held_on_030915"
bits = urlparse(url)

req = urllib2.Request(url, headers={ 'User-Agent': 'Mozilla/5.0' })
response = urllib2.urlopen(req)

response = urllib2.urlopen(url)
print response.code
print response.info()
print response.url

 