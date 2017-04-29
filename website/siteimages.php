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
	
    $count = 0;

    foreach($results as $site)
	{
        $count += 1;

        ?>
        <div class="col-xs-4 col-sm-3">
            <a href="speedy.php?id=<?php echo $site['Id'] ?>" class="thumbnail">
		        <img src="results/<?php echo $site['newMonthId'] ?>/screenshots/<?php echo $site['Name'] ?>_desktop.jpg"
                alt="<?php echo $site['Name'] ?>" title="<?php echo $site['Name'] ?>">
                <div class="caption">
                    <?php echo $site['DisplayName'] ?>
                </div>
                    
            </a>
        </div>
        <?php

        if ($count == 4) {
            $count = 0;
        ?>
           <div class="clearfix hidden-xs-block"></div>
        <?php
        }

    }
?>
