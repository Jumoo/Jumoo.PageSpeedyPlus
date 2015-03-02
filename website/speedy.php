<?php include 'speedycore.php' ; ?>
<?php include 'speedy_disp.php' ; ?>
<?php include 'wapplecore.php' ; ?>
<?php include 'accesscore.php' ; ?>
<?php include 'textlycore.php' ; ?>
<?php include 'header.php' ; ?>

<?php
	$id = $_GET["id"]; 	
	$speedy = new Speedy($id); 
	$wapple = new Wapple($id);
	$checker = new Checker($id);
	$textly = new Textly($id); 
	
	$url = $speedy->getSiteUrl();
	$siteName = $speedy->getSiteName();
?>
<div class="row">
	<div class="col-xs-12">
		<div class="page-header">
			<h1><?php echo $siteName; ?>
				<small><a href="<?php echo $url ?>"><?php echo $url ?></a></small>
			</h1>
		</div>
	</div>
</div>

<div class="summary">
	<div class="row">
		<div class="col-xs-12 col-md-6">
			<h2>Latest results:</h2>
			<div class="row">
				<div class="col-sm-5">
					<dl class="dl-horizontal score-list">
						<dt>Desktop</dt><dd>
								<a href="https://developers.google.com/speed/pagespeed/insights/?url=<?php echo $url ?>&tab=desktop" class="score">
									<?php echo ShowScore($speedy->getScore("desktop", $latest_month)) ?>
								</a>
							</dd>
						<dt>Mobile</dt><dd>
								<a href="https://developers.google.com/speed/pagespeed/insights/?url=<?php echo $url ?>" class="score">
									<?php echo ShowScore($speedy->getScore("mobile", $latest_month)) ?>
								</a>
							</dd>
					</dl>
					<?php ShowTextly($textly, $latest_month) ?>
				</div>
				<div class="col-sm-7">
					<img class="screenshot" src="results/<?php echo $latest_month ?>/screenshots/<?php echo $siteName ?>_desktop.jpg">
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-md-6">
			<h2>Previous results</h2>
			<canvas id="results" width="500" height="200"></canvas>
			<?php ShowUpdates($speedy) ?>
		</div>
	</div>
</div>
		
	<?php
		$months = $speedy->getMonths(); 
		
		foreach ($months as $month) {
			?>
			<div class="month-result">
				<div class="page-header">
					<h2><?php echo $month; ?></h2></div>
				<div class="result-row">
					<div class="row">
						<div class="col-xs-12">
							<h3>GooglePageSpeed Insight Scores</h3>
						</div>
					<?php
						ShowSpeedy($speedy, $month, "desktop", $latest_month);
						ShowSpeedy($speedy, $month, "mobile", $latest_month);
					?>
					</div>
				</div>
				<div class="result-row">
					<div class="row">			
					<?php
						ShowWapple($wapple, $month);
						ShowChecker($checker, $month, $speedy);
					?>
					</div>
				</div>

			</div>
			
			<?php
		}
	?>
	
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
		echo '<div><strong>New Site:</strong>';

		foreach( $updates as $update )
		{
			echo $speedy->getMonthName($update['newMonthId']) . ' ' ;
		}
		
		echo '</div>';
		
	}
}

function ShowTextly($textly, $monthId)
{
	$results = $textly->getResults($monthId);
	echo '<dl class="dl-horizontal textly">';
	foreach ($results as $t) {
		echo '<dt>Links:</dt><dl>' .  $t['LinkCount'] . '</dl>';
		echo '<dt>Top words:</dt><dl>' .  str_replace(',', '<br/>', $t['WORDS']) . '</dl>';
		echo '<dt>Trendyness: <a href="http://blog.jumoo.co.uk/2015/the-speedy-trendyness-score/" target="_blank" title="what does this mean?">?</a></dt><dl>'  . $t['Trendyness'] . "</dl>";
	}
	echo '</dl>';

}
?>
<?php include 'speedychart.php' ?>
<?php include 'footer.php'; ?>
	