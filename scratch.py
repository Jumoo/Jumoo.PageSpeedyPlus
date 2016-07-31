from spider.SpeedyParser import SpeedyParser
import urlparse
#p = SpeedyParser()
#ex = p.loadExclusions("elmbridge")
#print ex

base = "http://www.doncaster.gov.uk/apply-for-it/"
url = "../../../services/bins-recycling-waste/order-a-bulky-item-collection"

print urlparse.urljoin(base, url).replace('../', '')

print '{0:>4} {1:>4} {2:03}'.format(10,2,3012)