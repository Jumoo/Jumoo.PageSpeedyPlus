<?php 
    include 'speedycore.php'; 
    include 'month.php';
#    include 'speedy_disp.php';
#    include 'wapplecore.php' ;
#    include 'accesscore.php' ;
#   include 'textlycore.php' ;
    
	$monthId = $latest_month;
	if (isset($_GET["month"])) {
		$monthId = $_GET["month"];
	}
    
	$id = "-1";
	
	if (array_key_exists("id", $_GET))	
	{
		$id = $_GET["id"];
	}

	$speedy = new Speedy($id);

	if ($id == "-1") {
	  $gss = $_GET["gss"];
	  if ($gss != null)
	  {
		  $id = $speedy->getByGSS($gss);
	  }
	} 
    else {
        
    }
	
	
	$speedy = new Speedy($id);
#	$wapple = new Wapple($id);
#	$checker = new Checker($id);
#	$textly = new Textly($id);

	$url = $speedy->getSiteUrl();
	$siteName = $speedy->getSiteName();
	$siteShort = $speedy->getSiteShortName();
	$siteCode = $speedy->getSiteCode();

	$mobile = '/results/' . $monthId . '/screenshots/' . $siteShort . '_mobile.jpg';
	$desktop = '/results/' . $monthId . '/screenshots/' .  $siteShort . '_desktop.jpg';
    
    $newSites = GetUpdates($speedy);
    
    $score_desktop = $speedy->getScore("desktop", $monthId);
    $score_mobile = $speedy->getScore("desktop", $monthId);
    
    header('Content-type: application/json');

    $things = array(
           'id' => $id,
           'month' => substr($speedy->getMonthName($monthId),4),
           'name' => $siteName, 
           'url' => $url,
           'desktop' => $desktop,
           'mobile' => $mobile,
           'updates' => $newSites,
           'score_desktop' => $score_desktop,
           'scope_mobile' => $score_mobile);

    echo json_encode($things,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES  );

function GetUpdates($speedy)
{
	$updates = $speedy->getSiteUpdates();
    
    $newSites = array(); 

	if ( count($updates) > 0 )
	{
		$siteShort = $speedy->getSiteShortName();
		
		foreach( $updates as $update )
		{           
			$newMonthId = $update['newMonthId'];
			
			$mobile = '/results/' . ($newMonthId -1) . '/screenshots/' . $siteShort . '_mobile.jpg';
			$desktop = '/results/' . ($newMonthId -1) . '/screenshots/' .  $siteShort . '_desktop.jpg';
			
			$newSite = array( 
				'monthName' => substr($speedy->getMonthName($newMonthId), 4),
				'before_desktop' => $desktop,
				'before_mobile' => $mobile
			);
            $newSites[$newMonthId] = $newSite; 
		}
	}
    
    return $newSites;
}
?>