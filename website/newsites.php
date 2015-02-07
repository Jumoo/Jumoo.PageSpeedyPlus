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
		<h2>New sites this month: <?php echo $speedy->getMonthName($month); ?></h2>
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
			
			<h3><a href="speedy.php?id=<?php echo $site['Id']; ?>"><?php echo $site['Name'] ; ?></a></h3>
			<div class="siteimg">
				<strong>Old</strong>
				<img src="results/<?php echo $site['lastMonthId'] ?>/screenshots/<?php echo $site['Name'] ?>_desktop.jpg">
			</div>
			<div class="siteimg">
				<strong>New</strong>
				<img src="results/<?php echo $site['newMonthId'] ?>/screenshots/<?php echo $site['Name'] ?>_desktop.jpg">
			</div>
			<div class="clearfix"></div>
		</div>
		<?php
	}
	?>
<?php 
 }
?>

<?php include 'footer.php'; ?>
