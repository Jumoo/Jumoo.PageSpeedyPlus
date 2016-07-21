<?php include 'wapplecore.php'; ?>
<?php include 'header.php'; ?>

<div class="site-header">
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<?php
				$category = $_GET["cat"];
				$month = $latest_month;
				if (isset($_REQUEST['month']))
				{
					$month = $_GET["month"];
				}
					
				$wapple = new Wapple(1);
				$apps = $wapple->listApps($month, $category);
			?>


			<div>
				<h2>Application Category: <?php echo $category ?></h2>
				<small>features detected via wapplaizer scripts</small>				
			</div>
		</div>
	</div>
</div>
</div>
<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<p>
				<a href="featurelist.php">&lt; back to app list</a>
			</p>
			<table class="table table-hover">
				<thead>
					<tr>
						<th>Application</th>
						<th>Num Sites</th>
						<th>Ave Score</th>
						<th>Top Speed</th>
						<th>Low Speed</th>
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
</div>
<?php include 'footer.php'; ?>
