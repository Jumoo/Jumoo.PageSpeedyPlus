<?php include 'speedycore.php' ; ?>
<?php include 'speedy_disp.php' ; ?>
<?php include 'wapplecore.php' ; ?>
<?php include 'accesscore.php' ; ?>
<?php include 'header.php' ; ?>

<?php
	$id = $_GET["id"]; 	
	$speedy = new Speedy($id); 
	$wapple = new Wapple($id);
	$checker = new Checker($id);
	
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
				<div class="col-md-5">
					<dl class="dl-horizontal score-list">
						<dt>Desktop</dt><dd><span class="score"><?php echo ShowScore($speedy->getScore("desktop", $latest_month)) ?></span></dd>
						<dt>Mobile</dt><dd><span class="score"><?php echo ShowScore($speedy->getScore("mobile", $latest_month)) ?></span></dd>
					</dl>
					
					<?php ShowUpdates($speedy) ?>
					
				</div>
				<div class="col-md-7">
					<img class="screenshot" src="results/<?php echo $latest_month ?>/screenshots/<?php echo $siteName ?>_desktop.jpg">
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-md-6">
			<h2>Previous results</h2>
			<canvas id="results" width="500" height="200"></canvas>
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
		echo '<h3>Update detected:</h3>';

		echo '<ul>' ;
		foreach( $updates as $update )
		{
			echo '<li>' . $speedy->getMonthName($update['newMonthId']) . '</li>';
		}
		
		echo '</ul>';
	}
}
?>
<?php include 'speedychart.php' ?>
<?php include 'footer.php'; ?>
	