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
?>
<div class="row">
	<div class="col-xs-12">
		<h2>Speedy Reviews</h2>	
		<p>
		Once a month, pageSpeedy runs against each site, this page show what page speedy has found for this one.
		</p>
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-md-6">
		<div class="page-header">
			<h1 class="sitename"><?php echo $speedy->getSiteName(); ?> 
				<?php $url = $speedy->getSiteUrl(); ?><br/>
				<small><a href="<?php echo $url ?>"><?php echo $url ?></a></small>
			</h1>
		</div>
	</div>
	<div class="col-xs-12 col-md-6">
		<canvas id="results" width="500" height="200"></canvas>
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

<?php include 'speedychart.php' ?>
<?php include 'footer.php'; ?>
	