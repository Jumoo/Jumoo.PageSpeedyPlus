<?php include 'header.php' ?>
<div class="site-header">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h1 class="logo"><span class="lg">LocalGov</span>.PageSpeedy</h1>
                <p>
                    localgov Pagespeedy is an automated thingymabob by <a href="/" class="logo">jumoo</a>, that scans over 400 localgov websites,
                    runs some tests and tries to work some stuff out.
                </p>
            </div>
        </div>
    </div>
</div>
<div class="search-bar">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12">
                <h2>Council Speedy Reports</h2>
                <form class="form-inline" method="get" action="speedy.php">
                    <div class="form-group">
                        <label for="councilName">Reports:</label>
                        <?php include 'sitelist.php' ?>
                    </div>
                    <button type="submit" class="btn btn-default btn-lg btn-primary">Go</button>
                </form>      
                <a href="sites.php" class="search-all">view all councils</a> 
            </div>
        </div>
    </div>
</div>
<?php include 'stats.php'; ?>
<div class="home-info">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <h2 class="page-header"><a href="speedytable.php">Speedy Reports</a></h2>
                <table class="home-summary">
                    <tbody>
                        <tr>
                            <th>Last Report</th><td><a href="speedytable.php"><?php echo $latest_name ?></a></td>
                        </tr>
                        <tr>
                            <th>Average Score</th><td><?php echo $ave_score ?></td>
                        </tr>
                        <tr>
                            <th>Average Size</th><td><?php echo number_format($ave_size, 2, '.', ',') ?>k 
                            <?php echo floppycount($ave_size) ?></td>
                        </tr>
                        <tr>
                            <th>Mobile Friendly</th>
                            <td>
                            <?php echo number_format(($mobilepass / ($mobilepass + $mobilefail)*100), 2, '.', '')?> %
                            </td>
                        </tr>
                        <tr>
                            <th>Total Tests</th><td><?php echo number_format($test_count) ?></td>
                        </tr>
                        <tr>
                            <th>Features detected</th><td><?php echo number_format($features) ?></td>
                        </tr>
                      
                    </tbody>
                </table>
            </div>
            <div class="col-sm-6">
                <h2 class="page-header"><a href="newsites.php">New Sites</a></h2>
                <p>
                    This month pagespeedy detected <a href="newsites.php"><strong><?php echo $newsites ?></strong>
                    redesigned sites</a>,
                    We have now tracked <strong><?php echo $yearsites ?></strong> site changes in the last 12 months, and a 
                    total of <strong><?php echo $newtotal ?></strong> changes since March 2014.
                </p>
                <div class="info well">
                    <h3>Site Crawls</h3>
                    <p>
                        The Experimental <em>pageSpeedySpider<sup>&trade;</sup></em> has also performed full site crawls of <strong><?php echo $crawled ?></strong> of 410 local gov sites, and detected
                        <strong><?php echo number_format($c_pages) ?></strong> pages and <strong><?php echo number_format($c_docs) ?></strong> documents. 
                        We have also found <strong><?php echo number_format($c_domains) ?></strong> domains 
                        and identified <strong><?php echo number_format($c_dapps)?></strong> applications so far. <a href="#">help us find more....</a> 
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <h3 class="page-header">Latest</h3>
                <div class="newsite-images">
                    <?php include 'siteimages.php' ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
function floppycount($size)
{
	$count = ceil($size / 1440);
	
	for ($i = 1; $i <= $count; $i++) {
    	print '<img src="img/floppy.png" title="'. $count . ' floppy disks">';
	}
}
?>
<?php include 'footer.php' ?>