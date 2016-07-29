<?php

	$id = "-1";
	
	if (array_key_exists("id", $_GET))	
	{
		$id = $_GET["id"];
	}
	
	if ($id == "0")
	{
	    header('Location: ' . 'sites.php' , true, 302);
		exit();
	}
?>
<?php include 'speedycore.php' ; ?>
<?php include 'speedy_disp.php' ; ?>
<?php include 'wapplecore.php' ; ?>
<?php include 'accesscore.php' ; ?>
<?php include 'textlycore.php' ; ?>
<?php include 'mobilecheck.php' ; ?>
<?php include 'header.php' ; ?>
<?php include 'domain_core.php'; ?>
<?php

	$speedy = new Speedy($id);

	if ($id == "-1") {
	  $gss = $_GET["gss"];
	  if ($gss != null)
	  {
		  $id = $speedy->getByGSS($gss);
	  }
	}
	
	
	$speedy = new Speedy($id);
	$wapple = new Wapple($id);
	$checker = new Checker($id);
	$textly = new Textly($id);
	$mobile = new MobileCheck($id);
    $domains = new Domains($id);


	$url = $speedy->getSiteUrl();
	$siteName = $speedy->getSiteName();
	$siteShort = $speedy->getSiteShortName();
	$siteCode = $speedy->getSiteCode();

	$monthId = $latest_month;
	if (isset($_GET["month"])) {
		$monthId = $_GET["month"];
	}
	$monthName = $speedy->getMonthName($monthId);
	$monthDisplayName = $monthName;

	$site_domains = $domains->getDomains();

?>
<div class="site-summary">
	<div class="site-header">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-8">
					<h2><?php echo $siteName; ?>
						<br>
						<small><a href="<?php echo $url ?>"><?php echo $url ?></a> [<?php echo $siteCode ?>]</small>
					</h2>
				</div>
				<div class="col-sm-4">
					<div class="pull-right">
					<div class="speedy-scores">
						Desktop: 
						<a href="https://developers.google.com/speed/pagespeed/insights/?url=<?php echo $url ?>&tab=desktop" target="_blank">
							<?php echo ShowScore($speedy->getScore("desktop", $monthId)) ?></a>
						Mobile: 
						<a href="https://developers.google.com/speed/pagespeed/insights/?url=<?php echo $url ?>" target="_blank">
							<?php echo ShowScore($speedy->getScore("mobile", $monthId)) ?></a>
					</div>					
					<div class="month"><?php echo $monthDisplayName ?></div>						
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-6">
				<div class="row">
					<div class="col-xs-12">
						<h3 class="page-header">Desktop</h3>
					</div>
					<div class="col-xs-12">					
						<div class="screenshot">
							<img src="results/<?php echo $monthId ?>/screenshots/<?php echo $siteShort ?>_desktop.jpg"
								width="320" height="240"
							>
						</div>
					</div>
					<div class="col-xs-4">
						<?php ShowLatestSpeedy($speedy, $monthId, "desktop", $latest_month); ?>
					</div>
					<div class="col-xs-6">
						<canvas id="piechart_<?php echo $monthId ?>_desktop" ></canvas>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-6">
				<div class="row">
					<div class="col-xs-12">
						<h3 class="page-header">Mobile</h3>
					</div>
					<div class="col-xs-6">
						<div class="screenshot">
							<img src="results/<?php echo $monthId ?>/screenshots/<?php echo $siteShort ?>_mobile.jpg">
						</div>
					</div>
					<div class="col-xs-6">
						<a href="https://www.google.co.uk/webmasters/tools/mobile-friendly/?url=<?php echo $url ?>" target="_blank">
							<?php echo ShowMobile($mobile->getMobileCheck($monthId)) ?>
						</a>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-4">
						<?php ShowLatestSpeedy($speedy, $monthId, "mobile", $latest_month); ?>
					</div>
					<div class="col-xs-6">
						<canvas id="piechart_<?php echo $monthId ?>_mobile" ></canvas>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-6">
				<h3 class="page-header">Speed over time</h3>
				<canvas id="results" width="500" height="200"></canvas>
				<h3 class="page-header">Previously on pagespeedy...</h3>
				<div class="row">
					<?php ShowScreenshots($latest_month, $siteShort, $id, $speedy) ?>
				</div>
			</div>
			<div class="col-xs-12 col-sm-6">
				<h3 class="page-header">Info</h3>
				<?php ShowUpdates($speedy) ?>
				<?php ShowTextly($textly, $monthId); ?>
				<?php ShowPageStuff($id, $domains); ?>
				<?php ShowWapple($wapple, $monthName); ?>

				<hr>
				<div class="text-center">
					<a href="reports.php" class="btn btn-warning">Order a full report</a>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
function ShowScore($score)
{
	$q = 'score-low';
	if ( $score > 70 ) {
		$q = 'score-high';
	}
	else if ( $score > 50 ) {
		$q = 'score-med';
	}

	return '<span class="score ' . $q . '">' . $score . '</span>' ;
}

function ShowUpdates($speedy)
{
	$updates = $speedy->getSiteUpdates();

	if ( count($updates) > 0 )
	{
		echo '<div class="new-site-box"><strong>New site detected</strong>';

		foreach( $updates as $update )
		{
			echo '<div>' . substr($speedy->getMonthName($update['newMonthId']), 4) . '</div>' ;
		}

		echo '</div>';

	}
}

function ShowTextly($textly, $monthId)
{
	$results = $textly->getResults($monthId);
	echo '<dl class="dl-horizontal textly">';
	foreach ($results as $t) {
		echo '<dt>Link count:</dt><dl>' .  $t['LinkCount'] . '</dl>';
		echo '<!-- <dt>Top words:</dt><dl>' .  str_replace(',', '<br/>', $t['WORDS']) . '</dl> -->';
		echo '<dt>Trendyness: <a href="http://blog.jumoo.co.uk/2015/the-speedy-trendyness-score/" target="_blank" title="what does this mean?">?</a></dt><dl>'  . $t['Trendyness'] . "</dl>";
	}
	echo '</dl>';

}

function ShowMobile($mobileResult)
{
	$text = "we don't know if this site is mobile friendly";
	if ($mobileResult == 'True') {
		$text = 'Google marks this site as mobile friendly';
	} 
	else if ($mobileResult == 'False') {
		$text = 'Google does not think this site is mobile friendly!';
	}
	
	return "<span class='mbf mbf-" . $mobileResult . "'>" . $text . "</span>";
}


function ShowScreenshots($monthId, $siteShort, $siteId, $speedy) 
{
	for($i = $monthId; $i > $monthId - 4; $i--) 
	{
		$month = $speedy->getMonthName($i);
		$imgUrl = 'results/' . $i . '/screenshots/' . $siteShort . '_desktop.jpg';
		?>
		<div class="col-xs-4 col-sm-3">
			<a href="speedy.php?id=<?php echo $siteId ?>&month=<?php echo $i ?>" 
				title="<?php echo $month ?>"
				class="thumbnail">
				<img src="<?php echo $imgUrl ?>">
				<div class="caption">
					<?php echo substr($month,4) ?>
				</div>
			</a>
		</div>
		<?php
	}	
}

function ShowPageStuff($id, $domains)
{
	$pages = $domains->getPages();

	if (!empty($pages)) {
		?>
			<?php
			$q = $domains->getQueue();
			?>
		<table class="table">
			<tr>
				<th>Page Count 
				<?php if ( $q > 0) { ?>
					<small><span class="label label-info" title="this crawl, didn't complete, there where <?php echo $q ?> remaining links in the queue when it ended">!Partial Crawl</span></small>
				<?php } ?> 

				</th>
				<td><?php echo $pages ?></td>
			</tr>
			<tr>
				<th>Documents</th>
				<td><?php echo $domains->getDocs() ?></td>
			</tr>
			<tr>
				<th>Domains</th>
				<td><a href="site_domain.php?id=<?php echo $id ?>"><?php echo $domains->getDomainCount(); ?></a></td>
			</tr>
			<tr>
				<th>Applications</th>
				<td><a href="site_domain.php?id=<?php echo $id ?>"><?php echo $domains->getSiteFeatureCount(); ?></a></td>
			</tr>
			
		</table>
		<?php
	}
}
?>
<?php include 'speedychart.php' ?>
<?php include 'footer.php'; ?>
