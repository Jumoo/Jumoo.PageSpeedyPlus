<?php
    include 'speedycore.php';
    include 'domain_core.php';
    include 'header.php';
?>

<?php
    $id = '-1';
        if (array_key_exists('id', $_GET)) {
            $id = $_GET['id'];
        }

	$speedy = new Speedy($id);
	$url = $speedy->getSiteUrl();
	$siteName = $speedy->getSiteName();     
    $domains = new Domains($id);
?>

<div class="site-header">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div>
                    <h2> Domain Info <a href="speedy.php?id=<?php echo $id ?>"><?php echo $siteName ?></a></h2>
                    <small>Subdomain info obtained from site crawl</small>				
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
        <div class="col-sm-12">
            <?php 
                $pages = $domains->getPages();

                if (!empty($pages)) {
                    $queued = $domains->getQueue();
                    ?>
                    <h3>Crawl Info: <?php if ($queued > 0) { ?><span class="label label-info">! this was a partial crawl</span><?php } ?></h3>
                    <table class="table">
                        <tr>
                            <th>Page Count</th>
                            <td><?php echo $pages ?></td>
                            <td><small>number of unique pages identified via the crawl</small>
                        </tr>
                        <tr>
                            <th>Documents</th>
                            <td><?php echo $domains->getDocs() ?></td> 
                            <td><small>number of documents (e.g. pdf, doc, etc) found on primary domain during crawl</small></td>
                        </tr>
                        <tr>
                            <th>Queued</th>
                            <td><?php echo $queued ?></td>
                            <td><small>number of links still in the queue, when the crawl was halted (for time, or link limits)</small>
                        </tr>
                    </table>
                    <?php
                }
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
                <?php

                $site_domains = $domains->getDomains();

                echo '<h3>' . count($site_domains) . ' Sub Domains</h3>' ;

                echo '<ul>';

                foreach($site_domains as $domain)
                {
                    ?>
                        <li><a href="<?php echo $domain[3] ?>"><?php echo $domain[2] ?></a>
                            <div class="domain_features">
                                <ul>
                                    <?php $features = $domains->getDomainFeatures($domain[0]);
                                    
                                        foreach ($features as $feature) {
                                            ?>
                                                <li><?php echo $feature[2] ?></li>
                                            <?php
                                        }
                                    ?>
                                </ul>
                            </div>
                        </li>    
                    <?php 
                }

                echo '</ul>';
            ?>
        </div>
        <div class="col-sm-6">
        <?php
            $features = $domains->getFeatures();
            
            echo '<h3>' . count($features) . ' Domain Features</h3>'; 

            echo '<ul>';

            foreach($features as $feature)
            {
                ?>
                    <li><a href="domain_feature.php?feature=<?php echo $feature[2] ?>"><?php echo $feature[2] ?></a></li>    
                <?php 
            }

            echo '</ul>';
        ?>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
