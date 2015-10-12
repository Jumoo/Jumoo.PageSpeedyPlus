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

	$monthId = $latest_month;
	if (isset($_GET["month"])) {
		$monthId = $_GET["month"];
	}
	$monthName = $speedy->getMonthName($monthId);
	$monthDisplayName = $monthName;


?>
<div class="site-summary">
<div class="row">
	<div class="col-xs-12 col-sm-6">
		<h2 class="page-header"><?php echo $siteName; ?>
			<small><a href="<?php echo $url ?>"><?php echo $url ?></a></small>
		</h2>
	</div>
	<div class="col-sm-3">
		<h2 class="page-header"><?php echo $monthDisplayName ?></h2>
	</div>
	<div class="col-sm-3">
		<?php ShowMonthPicker($id, $speedy) ?>
	</div>

</div>

<div class="summary">
	<div class="row">
		<div class="col-xs-12 col-md-6">
			<div class="row">
				<div class="col-sm-4">
					<h3 class="page-header">Summary</h3>
					<dl class="dl-horizontal score-list">
						<dt>Desktop</dt><dd>
								<a href="https://developers.google.com/speed/pagespeed/insights/?url=<?php echo $url ?>&tab=desktop" class="score">
									<?php echo ShowScore($speedy->getScore("desktop", $monthId)) ?>
								</a>
							</dd>
						<dt>Mobile</dt><dd>
								<a href="https://developers.google.com/speed/pagespeed/insights/?url=<?php echo $url ?>" class="score">
									<?php echo ShowScore($speedy->getScore("mobile", $monthId)) ?>
								</a>
							</dd>
					</dl>
					<?php ShowTextly($textly, $monthId) ?>
				</div>
				<div class="col-sm-8">
					<div class="row">
						<div class="col-sm-12">
							<h3 class="page-header">Desktop</h3>
						</div>
						<div class="col-sm-4">
							<?php ShowLatestSpeedy($speedy, $monthId, "desktop", $latest_month); ?>
						</div>
						<div class="col-sm-6">
							<canvas id="piechart_<?php echo $monthId ?>_desktop" ></canvas>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4">
				</div>
				<div class="col-sm-8">
					<div class="row">
						<div class="col-sm-12">
							<h3 class="page-header">Mobile</h3>
						</div>
						<div class="col-sm-4">
							<?php ShowLatestSpeedy($speedy, $monthId, "mobile", $monthId); ?>
						</div>
						<div class="col-sm-6">
							<canvas id="piechart_<?php echo $monthId ?>_mobile" ></canvas>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<?php
					ShowWapple($wapple, $monthName);
				?>
			</div>
		</div>
		<div class="col-xs-12 col-md-6">
			<div class="row">
				<div class="col-xs-12">
					<h3 class="page-header">Speed over time</h3>
					<canvas id="results" width="500" height="200"></canvas>
					<?php ShowUpdates($speedy) ?>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<h3 class="page-header">Screenshots</h3>
				</div>
				<div class="col-sm-8">
					<div class="screenshot">
						<img src="results/<?php echo $monthId ?>/screenshots/<?php echo $siteName ?>_desktop.jpg">
					</div>
				</div>
				<div class="col-sm-4">
					<div class="screenshot">
						<img src="results/<?php echo $monthId ?>/screenshots/<?php echo $siteName ?>_mobile.jpg">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="pull-right" style="margin-top:2em;">
						<a href="reports.php" class="btn btn-lg btn-success">
							Order a SiteSpeedy Report for the whole site
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<?php

function ShowMonthPicker($id, $speedy)
{
	$months = $speedy->getProcessedMonths();

	print '<ul class="nav nav-pills monthlist">' ;
	print '<li role="presentation" class="dropdown">' ;
	print '<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-expanded="false">';
	print 'previous months <span class="caret"></span></a>';
	print '<ul class="dropdown-menu" role="menu">';

	foreach($months as $month)
	{
		print '<li><a href="speedy.php?id=' . $id . '&month=' . $month['Id'] . '">' . substr($month['Name'],4) . '</a></li>' ;
	}
	print '</ul></li></ul>';
}

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
		echo '<div><strong>New Site</strong>';

		foreach( $updates as $update )
		{
			echo ': ' . substr($speedy->getMonthName($update['newMonthId']), 4) . ' ' ;
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
?>
<?php include 'speedychart.php' ?>
<?php include 'footer.php'; ?>
