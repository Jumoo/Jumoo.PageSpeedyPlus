<?php include 'month.php' ;?>
<?php include 'speedycore.php' ; ?>
<?php
    $month = $latest_month;
    if (isset($_REQUEST['month']))
    {
        $month = $_GET["month"];
    }

    $speedy = new Speedy(1);
	
    $results = $speedy->getNewSites($month);
	
    foreach($results as $site)
	{
        ?>
        <div class="col-xs-4 col-sm-2">
            <a href="speedy.php?id=<?php echo $site['Id'] ?>" class="thumbnail">
		        <img src="results/<?php echo $site['newMonthId'] ?>/screenshots/<?php echo $site['Name'] ?>_desktop.jpg"
                alt="<?php echo $site['Name'] ?>" title="<?php echo $site['Name'] ?>">
                <div class="caption">
                    <?php echo $site['Name'] ?>
                </div>
                    
            </a>
        </div>
        <?php
    }
?>
