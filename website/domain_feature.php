<?php include 'speedycore.php' ; ?>
<?php include 'domain_core.php'; ?>
<?php include 'header.php'; ?>
<?php
    $domains = new Domains(-1);
?>
<div class="site-header">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <?php
                    $feature = $_GET["feature"];	
    				$sites = $domains->getSites($feature);
    			?>
                <div>
                    <h2> Feature : <?php echo $feature; ?> (<?php echo Count($sites); ?>)</h2>
                    <small>features detected via wapplaizer scripts on subdomains</small>				
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-xs-12">
                <div class="alert alert-warning">
                    <h2>Experimental Data</h2>
                    <p>
                    Data on this page has been gatherd from a full site crawl, of the main council site, this feature is still underdevelopment, and <strong>not all council sites have been crawled</strong>. Some data may not be accurate.
                    </p>
                    <p>
                    we are still missing applications for <a href="_alldomains.php">all of these domains</a> if you can help us with that.
                    </p>
                </div>
        </div>
			<p>
				<a href="featurelist.php">&lt; back to app list</a>
			</p>
    
        <div class="col-sm-6">
            <h3>Sites:</h3>	
            <ul>
                <?php
                foreach($sites as $site)
                {	
                    ?>
                    <li><a href="speedy.php?id=<?php echo $site['Id'] ?>"><?php echo $site['Name'] ?></a> 
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
