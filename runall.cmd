@Echo off

ECHO +--------------------------------------+
ECHO 	RUNNING PAGESPEEDY FOR %1           
ECHO +--------------------------------------+

python speedyplus.py -m %1

ECHO +--------------------------------------+
ECHO 	RUNNING SCREENGRABBY FOR %1         
ECHO +--------------------------------------+

python screengrabby.py -m %1

ECHO +--------------------------------------+
ECHO 	RUNNING Peeky FOR %1                
ECHO +--------------------------------------+

python peeky.py -m %1

ECHO +--------------------------------------+
ECHO 	RUNNING Trendly FOR %1              
ECHO +--------------------------------------+

python trendly.py -m %1

