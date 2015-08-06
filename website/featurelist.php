<?php include 'wapplecore.php'; ?>
<?php include 'header.php'; ?>

<div class="row">
	<div class="col-md-12">
<?php

	$wapple = new Wapple(1);
	$cats = $wapple->getCategories($latest_month);
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
	foreach($cats as $cat)
	{	
		?>
		<li>
			<a href="category.php?cat=<?php echo$cat['Category'] ?>"><?php echo $cat['Category'] ?></a>
		</li>
		<?php
	}
	?>
	</ul>
	</div>
</div>
<?php include 'footer.php'; ?>
