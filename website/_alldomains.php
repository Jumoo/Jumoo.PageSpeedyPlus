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
    				$d = $domains->getAllDomains();
    			?>
                <div>
                    <h2> Domains (<?php echo Count($d); ?>)</h2>
                    <small>features detected via wapplaizer scripts on subdomains</small>				
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="alert alert-danger">
                <h2>Missing Data</h2>
                <p>
                    this page lists all the domains where we haven't worked out what they running yet.
                </p>
                <p>
                    if you can know what one of these domains is running, and think you can help us to detect it, <a href="https://github.com/Jumoo/Jumoo.PageSpeedyPlus/issues">please go over to our github page, submit an issue</a>, we 
                    will then add it to the detection script
                </p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <h3>Sites:</h3>	
            <ul>
                <?php
                foreach($d as $d)
                {	
                    ?>
                    <li><a href="<?php echo $d['Link'] ?>"><?php echo $d['Domain'] ?></a></li>
                    <?php
                }
                ?>
            </ul>        
        </div>
    </div>
</div>
<?php include 'footer.php' ?>