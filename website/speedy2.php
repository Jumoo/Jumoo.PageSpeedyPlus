<?php
    include 'speedycore.php';
    include 'speedy_disp.php' ; 
    include 'wapplecore.php';
    include 'accesscore.php';
    include 'textlycore.php';
    include 'header.php'
?>

<?php
    
    $id = '-1';
    if (array_key_exists('id', $_GET)) {
        $id = $_GET['id'];
    }
    
    $speedy = new Speedy($id);
    
    if ($id == '-1') {
        $gss = $_GET['gss'];
        if ($gss != null) {
            $id = $speedy.getByGss($gss);
        }
    }
    
    $speedy = new Speedy($id);
    $wapply = new Wapple($id);
    $checker = new Checker($id);
    $textly = new Textly($id);
    
	$url = $speedy->getSiteUrl();
	$siteName = $speedy->getSiteName();
	$siteShort = $speedy->getSiteShortName();
	$siteCode = $speedy->getSiteCode();

	$monthId = $latest_month;
	if (isset($_GET["month"])) {
		$monthId = $_GET["month"];
	}
	$monthName = $speedy->getMonthName($monthId);
	$monthDisplayName = $monthName;
?>

<div class="site-summary">
    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <h2><?php echo $siteName; ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-8 col-xs-12">
            <div class="row">
                <div class="col-xs-12">
                    <h3>Desktop</h3>
                    <div class="row">
                        <div class="col-sm-3">
                            <img src="results/<?php echo $monthId ?>/screenshots/<?php echo $siteShort ?>_desktop.jpg" style="max-width:100%">
                        </div>
                        <div class="col-sm-6">
							<?php ShowLatestSpeedy($speedy, $monthId, "desktop", $latest_month); ?>
                        </div>
                        <div class="col-sm-3">
							<canvas id="piechart_<?php echo $monthId ?>_desktop" ></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <h3>Mobile</h3>
                    <img src="results/<?php echo $monthId ?>/screenshots/<?php echo $siteShort ?>_mobile.jpg" style="max-width:100%">
                </div>
            </div>
        </div>
    </div>
</div> 

<canvas id="results" width="500" height="200"></canvas>


<?php include 'speedychart.php' ?>
<?php include 'footer.php'; ?>
