#
# Setup the Sqlite DB For Speedy
#

import sqlite3 as lite
import sys
con = None 

site_insert = "INSERT INTO Sites(Name, Url, Active) VALUES('{0}','{1}', 1);"
site_file = "data/sites.txt"

try:	
	con = lite.connect("data/speedyplus.db")
	cur = con.cursor()
	
	with open('sql/speedyplus.sql', 'r') as f:
		cur.executescript(f.read())
		con.commit()
		
	with open('sql/speedyviews.sql', 'r') as f:
		cur.executescript(f.read())
		con.commit()
	
	with open(site_file, 'r') as f:
		all_lines = f.read().splitlines()

	total = len(all_lines)
	current = 0
	
	print 'loading websites',

	for council in all_lines:
		current = current + 1 
    
		if council[0] <> '#': # not a comment
			council_info = council.split(',')
        
			if len(council_info) == 2:
				council_name = council_info[0]
				council_site = council_info[1]
            
				print '.' ,
				cur.execute( site_insert.format(council_name, council_site))

	con.commit()
	lid = cur.lastrowid
	print ''
	print "inserted %d Sites" % lid


except lite.Error, e:

	print "Error %s:" % e.args[0]
	sys.exit(1)
	
finally:

	if con:
		con.close()
	