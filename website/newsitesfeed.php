<?php header('Content-type: application/xml'); 
include 'speedycore.php';
$speedy = new Speedy(1); 
$siteUrl = 'http://jumoo.uk/speedy/' ;

$xml = '<?xml version="1.0" encoding="utf-8"?>';
$xml .= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">';
$xml .= '<channel>';
$xml .= '<title>LocalGovPageSpeedy NewSites</title>';
$xml .= '<link>'. $siteUrl .'</link>';
$xml .= '<description>New localgov sites by pagespeedy</description>';
$xml .= '<atom:link href="'. $siteUrl . 'newsitesfeed.php" rel="self" type="application/rss+xml" />';

	
$results = $speedy->getLatestSites();

foreach($results as $site)
{ 
	$monthName = substr($speedy->getMonthName($site['newMonthId']),4);
	
	$xml .= '<item>' ;
	
	$xml .= '<title>New Site ' . $site['Name'] . ' ' . $monthName . '</title>';
	
	$url =  $siteUrl . 'speedy.php?id=' . $site['Id'] ;
	
	
	$xml .= '<link>'. $url . '</link>';

	$oldUrl =  $siteUrl . 'results/' . $site['lastMonthId'] . '/screenshots/' . $site['Name'] . '_desktop.jpg';
	$newUrl =  $siteUrl . 'results/' . $site['newMonthId'] . '/screenshots/' . $site['Name'] . '_desktop.jpg';
	
	$desc = '<div class="site-name"><h2> New website for ' . $site['Name'] . ' in ' . $monthName . '</h2></div>' ;
	
	$desc .= '<div class="site-image"><img src="' . $oldUrl . '"></div>' ;
	$desc .= '<div class="site-image"><img src="' . $newUrl . '"></div>' ;
	
	$xml .= '<description><![CDATA[' . $desc . ']]></description>';
	$xml .= '<guid>' . $url . '#' . $site['newMonthId'] .'</guid>' ;
	$xml .= '</item>';
}

$xml .= '</channel>';
$xml .= '</rss>';

echo $xml ;
?>
