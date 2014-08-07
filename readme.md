# PageSpeedyPlus #

Automated mass website observation - or a tool to see how fast council websites are. 

Pagespeedy is a series of scripts to run the Google PageSpeed Inights against a list of websites - it is used by us to do the Council Page Speedyindex thing

[http://jumoo.uk/speedy/sites.php](http://jumoo.uk/speedy/sites.php)

## Installation 
PageSpeedyPlus comes in two parts

**Part One - The Analyser**
The Python Scripts, do background runs against websites. 

You will need. 

1. A List of sites you want to run it against (see sites.txt)
2. To Initialize the DB
3. an [installation of AChecker](https://github.com/atutor/AChecker) to to the Accessibility Checks. (or you can turn them off in the code)
3. Some time to run it (it takes about an hour on 400 sites)

Setup:

1. Initialize your DB (run `SpeedyPlusInit.py` )
2. You need 3rd Party APIs 
	1. A Google Page Speed API Key goes in `pagespeed.config.json` (see `pagespeedy.config.sample.json`)  
	2. If you run AChecker - `achecker.config.json` needs keys too
2. Decide what month your going to do
	1. `speedyplus.py -l` will list what months you can run against
	2. `speedyplus.py -m <id>` will run the process against a month
	3. at the end the month is marked as processed and can't be ran again (without some sqlite hackery)

*By default pagespeedy will do:*

- a Google PageInsights check, 
- a Wappalizer check 
- an Accessiblity check

To turn off one of these just comment the two lines out in speedyplus.py.

**Part 2 is the site** 

The second part is the website (in website folder) - it's a PHP site.

to run the site you need to copy a few things from your analyser

1. Copy the speedyplus.db to the root of your site
2. Copy/Move the results folder to the site

The results folder can get quite large that's why we move it (in our case we ftp it up to our site). 
 
## Notes

1. The website is intended to be a read only copy of everything, So set it so if you put it on a web server.
2.  You will need to poke around in the SQL at some point. just run `Sqlite3 speedyplus.db` in the directory.
3. This is very rough , things aren't separated properly in the code (it sort of grew) - feel free to contribute improve and feedback.   