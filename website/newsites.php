<?php include 'speedycore.php' ; ?>
<?php include 'wapplecore.php'; ?>

<?php include 'header.php'; ?>

<div class="row">
	<div class="col-md-12">
<?php

  $month = $latest_month;
	if (isset($_REQUEST['month']))
	{
		$month = $_GET["month"];
	}

	$speedy = new Speedy(1);

	?>
	<div class="col-md-8">
			<h2>New sites detected in <?php echo substr($speedy->getMonthName($month),4); ?> - <small>will have changed during <?php echo substr($speedy->getMonthName(intval($month)-1),4); ?> </small></h2>
	</div>
	<div class="col-md-4">
		<?php MonthsList($speedy) ?>
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
	print '<ul class="nav nav-pills monthlist">' ;
	print '<li role="presentation" class="dropdown">' ;
	print '<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-expanded="false">';
	print 'previous months <span class="caret"></span></a>';
	print '<ul class="dropdown-menu" role="menu">';

	foreach($months as $month)
	{
		print '<li><a href="newsites.php?month=' . $month['Id'] . '">' . substr($month['Name'],4) . '</a></li>' ;
	}
	print '</ul></li></ul>';
}

function DisplayNewSites($speedy, $id) {

	$wapple = new Wapple(1);

	$results = $speedy->getNewSites($id);
	foreach($results as $site)
	{

		$ls = new Speedy($site['Id']);
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
								<small class="text-muted">
									Desktop: <?php echo $ls->getScore('desktop', $site['lastMonthId']) ?>
									Mobile: <?php echo $ls->getScore('mobile', $site['lastMonthId']) ?>
									<br/>
									CMS: <?php echo $wapple->GetApp($site['Id'], $site['lastMonthId'], 'cms') ?>
								</small>
						</div>
						<div class="col-xs-6">
							<h4>New</h4>
								<span class="thumbnail">
									<img src="results/<?php echo $site['newMonthId'] ?>/screenshots/<?php echo $site['Name'] ?>_desktop.jpg">
								</span>
								<small class="text-muted">
									Desktop: <?php echo $ls->getScore('desktop', $site['newMonthId']) ?>
									Mobile: <?php echo $ls->getScore('mobile', $site['newMonthId']) ?>
									<br/>
									CMS: <?php echo $wapple->GetApp($site['Id'], $site['newMonthId'], 'cms') ?>
								</small>
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
