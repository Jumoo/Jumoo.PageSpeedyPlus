<?php include 'speedycore.php' ; ?>
<?php include 'wapplecore.php'; ?>
<?php include 'header.php'; ?>

<div class="site-header">
<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<?php
				$feature = $_GET["feature"];	
				$wapple = new Wapple(1);	
				$speedy = new Speedy(1); 
				$sites = $wapple->getSites($feature, $latest_month);
			?>
			<div>
				<h2> Feature : <?php echo $feature; ?> (<?php echo Count($sites); ?>)</h2>
				<small>features detected via wapplaizer scripts</small>				
			</div>
		</div>
	</div>
</div>
</div>
<div class="container">
	<div class="row">
		<div class="col-sm-6">
			<h3>Sites:</h3>	
		<ul>
			<?php
			foreach($sites as $site)
			{	
				?>
				<li><a href="speedy.php?id=<?php echo $site['SiteId'] ?>"><?php echo $site['Name'] ?></a> 
				</li>
				<?php
			}
			?>
		</ul>
	</div>
	<div class="col-md-6">
		<div class="page-header">
			<h3>Use of <?php echo $feature; ?> over time</h3>
		</div>
		<canvas id="featureChart" width="500" height="250"></canvas>
	</div>
	
	<div class="col-md-12">
		<div class="alert alert-warning">
			<strong>Programmatic data:</strong> the above data has been collected by programatically 
			analysing websites. It may not have captured all sites using this technology as many sites
			hide some of their technology stack from users. 
		</div>
	</div>
	
	<script src="js/chart.min.js"></script>
	<script>
		var data = {
			labels:	<?php MonthsDataList($speedy) ?>,
			datasets: [
				{
					label: "Site Count",
					fillColor: "rgba(220,220, 220, 0.2)",
					strokeColor: "rgba(220,220,220,1)",
					pointColor: "rgba(220,220,220,1)",
					pointStrokeColor: "#fff",
					pointHighlightFill: "#fff",
					pointHighlightStroke: "rgba(220,220,220,1)",
					data: <?php FeatureDataList($speedy, $wapple, $feature) ?>
				}
			]
		};
		
		var ctx = document.getElementById("featureChart").getContext("2d");
			Chart.defaults.global.scaleBeginAtZero = true;

		var resultChart = new Chart(ctx).Line(data);
			
	</script>
</div>
<?php
function FeatureDataList($speedy, $wapple, $feature)
{
	$months = $speedy->getProcessedMonths() ;
	print '[' ;
	foreach($months as $month)
	{
		if ($month['Id'] > 6 ) {
			$mSites = $wapple->getSites($feature, $month['Id']);
			print Count($mSites) . ',' ;
		}
	}	
	print ']' ;
}

function MonthsDataList($speedy)
{
	$months = $speedy->getProcessedMonths() ;

	print '[' ;
	foreach($months as $month)
	{
		if ($month['Id'] > 6 ) {
			print '"' . substr($month['Name'],4) . '",' ;
		}
	}
	print ']' ;
}
?>
</div>
<?php include 'footer.php'; ?>
