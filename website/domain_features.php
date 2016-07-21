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
                <div>
                    <h2> Features</h2>
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
                    Data on this page has been gatherd from a full site crawl, of the main council site, this feature is still underdevelopment, so some data may not be accurate. 
                    </p>
                </div>
        </div>
    
        <div class="col-sm-6">
            <?php
                $features = $domains->getAllFeatures();
            ?>
            <h3>Localgov Applications</h3>	
			<ul class="feature-list">
                <?php
                foreach($features as $feature)
                {	
                    ?>
                    <li><a href="domain_feature.php?feature=<?php echo $feature['Application'] ?>"><?php echo $feature['Application'] ?></a> 
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
