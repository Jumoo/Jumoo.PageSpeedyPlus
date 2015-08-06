<?php include 'wapplecore.php'; ?>
<?php include 'header.php'; ?>

<div class="row">
	<div class="col-md-12">
<?php
	$category = $_GET["cat"];	
	$wapple = new Wapple(1);
	$apps = $wapple->listApps($latest_month, $category);
?>

	<a href="featurelist.php">&lt; Features</a>

	<div class="page-header">
		<h2>Category: <?php echo $category ?></h2>
	</div>
	<table class="table table-hover">
		<thead>
			<tr>
				<th>Application</th>
				<th>Num Sites</th>
				<th>Ave Score</th>
				<th>Top Score</th>
				<th>Low Score</th>
			</tr>		
		</thead>
		<tbody>
	<?php
	foreach($apps as $app)
	{	
		?>
		<tr>
			<td><a href="feature.php?feature=<?php echo $app['Application'] ?>"><?php echo $app['Application'] ?></td>
			
			<td><?php echo $app['SiteCount'] ?></td>
			<td><?php echo number_format($app['AveScore'],1) ?></td>
			<td><?php echo $app['TopScore'] ?></td>
			<td><?php echo $app['LowScore'] ?></td>
		</tr>
		<?php
	}
	?>
		</tbody>
	</table>
	</div>
</div>
<?php include 'footer.php'; ?>
