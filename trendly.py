import os
import sys
import getopt
import segment
import BeautifulSoup
import sqlite3 as lite
import speedy.speedydb 

trendlySql_insert = "INSERT INTO Textly(SiteId, MonthId, Trendyness, LinkCount, Words) VALUES({0}, {1}, {2}, {3}, {4});"
trends = ['top task', 'straight to', 'residents', 'pay it', 'report it', 'find my nearest', 'popular tasks','highlights','faq','frequently asked','Popular topics','Quick links','Do it online', 'press releases', 'fostering']
trendcounts = range(len(trends)) 

from collections import Counter

c = Counter();
segger = segment.Segmenter()


def percentage(part, whole):
  return float(part)/float(whole)

def FindTrendyString(content, search):
	substring = content.lower().find(search.lower())
	if ( substring > 0 ) :
		# print 'found [{0}]'.format(search),
		return 1

def GetTheTrendy(content):
	trendyscore = 0
	
	for i in range(len(trends)):	
		search = trends[i]		
		if FindTrendyString(content, search) == 1 :
			trendcounts[i] = trendcounts[i] + 1
			trendyscore = trendyscore+1
			
	return trendyscore

def CountTheWords(content):

	words = Counter();

	for chunk in segger.get_chunks(content):
		for word in chunk.split():
			if len(word) > 3 and len(word) < 30:
				words.update(word.lower().split())
	
	found = '"' 
	for word, count in words.most_common(5):
		found = found +  "{0},".format(word.encode('utf-8').strip().translate(None, ',!@#$"')) 
	found = found.strip(',') + '"'
	
	return found 

def linkCounter(content):
	soup = BeautifulSoup.BeautifulSoup(content)
	return len(soup.findAll('a', href=True))


	
	
def runmonth(monthid):
	# stuff...	
	here = os.path.dirname(__file__)
	folder = os.path.join(here, "results\\{0}\\html".format(monthid))

	sitecount = 0;

	con = lite.connect('speedyplus.db')
	cur = con.cursor()

	db = speedydb.SpeedyDb()
	sites = db.getSites()
	for site in sites:
		siteName = site[1]
		siteFile = "{0}\\{1}.html".format(folder, siteName)
		print "{0:<3} {1:<25}".format(site[0], site[1]),
		
		if os.path.exists(siteFile):
			sitecount = sitecount + 1 
			print "{0:25}".format(os.path.split(siteFile)[1]),
			fo = open(siteFile, 'r')
			content = fo.read()
			trendyness = GetTheTrendy(content) 
			linkcount = linkCounter(content)
			words = CountTheWords(content)
			fo.close()
			
			sql = trendlySql_insert.format(site[0], monthid, trendyness, linkcount, words)
			
			# print sql 
			cur.execute(sql)
			con.commit()		
			print '{0:<2} {1:<4} {2}'.format(trendyness, linkcount, words),
		
		print '.' 

	print ''
	for i in range(len(trends)):
		print '{0:<30}: {1}\t{2:.0%}'.format(trends[i], trendcounts[i], percentage(trendcounts[i],sitecount))

	for word, count in c.most_common(100):
		print word, count 

def main(argv):
	monthid = 0 

	try:
		opts, args = getopt.getopt(argv, "m:", ['month'])
	except getopt.GetoptError:
		print 'grabby.py -m <monthId>'
		sys.exit(2)
		
	for opt, arg in opts:
		if opt in ('-m', '--month'):
			monthid = arg 
	
	print 'MonthId [', monthid , ']'

	if monthid != 0:
		runmonth(monthid)

if __name__ == '__main__':
	main(sys.argv[1:])	
	
		

