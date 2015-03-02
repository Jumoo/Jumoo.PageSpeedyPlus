<?php include 'speedycore.php' ; ?>
<?php include 'header.php'; ?>

<div class="row">
	<div class="col-md-12">
<?php

	$month = $_GET["month"];
	$speedy = new Speedy(1); 
		
	?>
	<br/>
	<div class="alert alert-info">
		<p>
		As page speedy runs every month it gives us a good opportunity to spot when things change, this page
		lists the new websites (we have noticed) as we've been running page speedy. 
		</p>
		<p>
		The Month is the month in which the change was detected, so the site probably changed sometime in the previous month.
		</p>
	</div>
	
	<div class="previous">
		<strong>New Sites By Month:</strong>
		<?php MonthsList($speedy) ?> 
	</div>

	<div class="page-header">
		<h2>New sites detected in <?php echo substr($speedy->getMonthName($month),4); ?> - <small>will have changed during <?php echo substr($speedy->getMonthName(intval($month)-1),4); ?> </small></h2>
	</div>
	</div>
</div>

<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<?php DisplayNewSites($speedy, $month) ?>
	</div>
</div>


<?php 
function MonthsList($speedy)
{
	$months = $speedy->getMonthsWithNewSites() ;

	print '<ul class="monthlist">' ;
	foreach($months as $month)
	{
		print '<li><a href="newsites.php?month=' . $month['Id'] . '">' . substr($month['Name'],4) . '</a></li>' ;
	}
	print '</ul>' ;
}

function DisplayNewSites($speedy, $id) {	
	
	$results = $speedy->getNewSites($id);
	foreach($results as $site)
	{	
		?>
			<div class="new-site result">
				<div class="row">
						<div class="col-xs-12">
									<h3><a href="speedy.php?id=<?php echo $site['Id']; ?>"><?php echo $site['Name'] ; ?></a></h3>
						</div>
						<div class="col-xs-6">
								<h4>Old</h4>
								<span class="thumbnail">
									<img src="results/<?php echo $site['lastMonthId'] ?>/screenshots/<?php echo $site['Name'] ?>_desktop.jpg">
								</span>
						</div>
						<div class="col-xs-6">
							<h4>New</h4>
								<span class="thumbnail">
									<img src="results/<?php echo $site['newMonthId'] ?>/screenshots/<?php echo $site['Name'] ?>_desktop.jpg">
									</span>
						</div>
				</div>
			</div>
		<?php
	}
	?>
<?php 
 }
?>

<?php include 'footer.php'; ?>
