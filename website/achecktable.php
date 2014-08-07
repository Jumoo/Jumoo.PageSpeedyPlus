<?php include 'speedycore.php' ; ?>
<?php include 'accesscore.php' ; ?>
<?php include 'header.php'; ?>

<div class="row">
	<div class="col-md-12">
<?php

	$month = $_GET["month"];	
	$speedy = new Speedy(1); 
	$checker = new Checker(1);
		
	?>
	
	<div class="alert alert-info">
		Experimental: Accessibility Checks against WCAG2.0 - (Single A) for all Sites. 
		<em>All sites are checked against a local install of AChecker</em>
	</div>
	
	<div class="page-header">
		<h2>WCAG 2.0 Single A Tests: <?php echo $speedy->getMonthName($month); ?></h2>
	</div>
	

    <?php DisplayAccessTable($checker, $month) ?>
	</div>
</div>

<?php 
function MonthsList($speedy)
{
	$months = $speedy->getProcessedMonths() ;

	print '<ul class="monthlist">' ;
	foreach($months as $month)
	{
		print '<li><a href="speedytable.php?month=' . $month['Id'] . '">' . substr($month['Name'],4) . '</a></li>' ;
	}
	print '</ul>' ;
}

function DisplayAccessTable($checker, $monthId) {	
	
	$results = $checker->getTable($monthId);
	
	?>
	<table class="table">
		<tr>
			<th></th>
			<th>Name</th>
			<th>Status</th>
			<th>WCAG2.0 (A) Errors</th>
		</tr>
	<?php
	$x = 1;

	foreach($results as $site)
	{	
		?>
		<tr>
			<td><?php echo $x ?></td>
			<td><a href="speedy.php?id=<?php echo $site['Id']; ?>"><?php echo $site['Name'] ; ?></a></td>
			<td><?php echo $site['Status'] ; ?></td>
			<td><?php echo $site['Errors'] ; ?></td>
		</td>
		<?php
		$x = $x + 1;
	}
	?>
	</table>
<?php 
 }
?>

<?php include 'footer.php'; ?>
