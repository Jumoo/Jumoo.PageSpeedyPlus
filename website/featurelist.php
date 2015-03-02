<?php include 'wapplecore.php'; ?>
<?php include 'header.php'; ?>

<div class="row">
	<div class="col-md-12">
<?php

	$wapple = new Wapple(1);	
	$sites = $wapple->listFeatures();
	?>
	<div class="page-header">
		<h2>Site Features</h2>
	</div>
	<p>
		When pagespeedy runs, it uses a version of <a href="https://wappalyzer.com/">wappalyzer</a> to interrogate each site to see what they are running.
		this page lists the technologies found.
	</p>
	<ul>
	<?php
	$last_app = "" ;
	foreach($sites as $site)
	{	
		if ($last_app != $site['Category'] ) 
		{
				$last_app = $site['Category']; 
				?>
					</ul>
					<h3><?php echo $site['Category']; ?></h3>
					<ul>
				<?php
		}
	
		?>
		<li><a href="feature.php?feature=<?php echo $site['Application'] ?>"><?php echo $site['Application'] ?></a></li>
		<?php
	}
	?>
	</ul>
	</div>
</div>
<?php include 'footer.php'; ?>
