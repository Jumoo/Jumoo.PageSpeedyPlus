<?php include 'wapplecore.php'; ?>
<?php include 'header.php'; ?>
<?php include 'domain_core.php'; ?>

<div class="site-header">
<div class="container">
	<div class="row">
		<div class="col-md-12">
		<?php

			$wapple = new Wapple(1);
			$cats = $wapple->getCategories($latest_month);

		    $domains = new Domains(-1);
			
		?>
			<div>
				<h2>Site Features</h2>
			</div>
		</div>
	</div>
</div>
</div>
<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<p>
				When pagespeedy runs, it uses a version of <a href="https://wappalyzer.com/">wappalyzer</a> to interrogate each site to see what they are running.
				this page lists the technologies found.
			</p>
			<ul class="feature-list">
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
        <div class="col-sm-12">
            <?php
                $features = $domains->getAllFeatures();
            ?>
            <h3>Localgov Applications</h3>	
			<ul class="feature-list feature-list-app">
                <?php
                foreach($features as $feature)
                {	
						if ($feature['Application'] != 'error' && $feature['Category'] < 200)
						{

						?>
						<li><a href="domain_feature.php?feature=<?php echo $feature['Application'] ?>"><?php echo $feature['Application'] ?></a> 
						</li>
						<?php
						}
                }
                ?>
            </ul>
        </div>
		
	</div>
</div>
<?php include 'footer.php'; ?>
