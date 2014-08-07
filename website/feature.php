<?php include 'wapplecore.php'; ?>
<?php include 'header.php'; ?>

<div class="row">
	<div class="col-md-12">
<?php

	$feature = $_GET["feature"];	
	$wapple = new Wapple(1);	
	$sites = $wapple->getSites($feature);
	?>
	<div class="page-header">
		<h2> Sites Running <?php echo $feature; ?> (<?php echo Count($sites); ?>)</h2>
	</div>
	
	<ul>
	<?php
	foreach($sites as $site)
	{	
		?>
		<li><a href="speedy.php?id=<?php echo $site['SiteId'] ?>"><?php echo $site['Name'] ?></a></li>
		<?php
	}
	?>
	</ul>
	</div>
</div>
<?php include 'footer.php'; ?>
