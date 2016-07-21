<?php include 'speedycore.php' ; ?>
<?php include 'header.php'; ?>
<div class="site-header">
<div class="container">
	<?php
		$month = $latest_month;
		if (isset($_REQUEST['month']))
		{
			$month = $_GET["month"];
		}
		
		
		$speedy = new Speedy(1); 
	?>
	<div class="row">
		<div class="col-md-8">
			<h2>Speedy Table: <?php echo $speedy->getMonthName($month); ?></h2>
		</div>
		<div class="col-md-4">
			<div class="pull-right">
				<?php MonthsList($speedy) ?> 
			</div>
		</div>
	</div>
</div>
</div>
<div class="container">
	<div class="row">
	
	<div class="col-sx-12">
	
	<ul id="myTab" class="nav nav-tabs" role="tablist">
		<li><a href="#desktop" role="tab" data-toggle="tab">Desktop</a></li>
		<li class="active"><a href="#mobile" role="tab" data-toggle="tab">Mobile</a></li>
    </ul>
	
	<div id="myTabContent" class="tab-content">
      <div class="tab-pane fade" id="desktop">
        <?php DisplayTable($speedy, $month, "desktop") ?>
      </div>
      <div class="tab-pane fade active in" id="mobile">
        <?php DisplayTable($speedy, $month, "mobile") ?>
      </div>
    </div>

	</div>
</div>
</div>
<?php 
function MonthsList($speedy)
{

	$months = $speedy->getMonthsWithNewSites() ;
	print '<ul class="nav nav-pills monthlist">' ;
	print '<li role="presentation" class="dropdown">' ;
	print '<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-expanded="false">';
	print 'previous months <span class="caret"></span></a>';
	print '<ul class="dropdown-menu" role="menu">';
    
	foreach($months as $month)
	{
		print '<li><a href="speedytable.php?month=' . $month['Id'] . '">' . substr($month['Name'],4) . '</a></li>' ;
	}
	print '</ul></li></ul>';
}

function DisplayTable($speedy, $id, $platform) {	

	$doom = isset($_REQUEST['doom']);
	$floppy = $doom ;
	
	$results = $speedy->getTable($platform, $id);
	
	?>
	<table class="table">
		<tr>
			<th></th>
			<th>Name</th>
			<th>Score</th>
			<th>Html</th>
			<th>Img</th>
			<th>Css</th>
			<th>Js</th>
			<th>Other</th>
			<th>Total</th>
			<?php if ($doom) { ?>
				<th>Sizes</th>
			<?php } ?>
		</tr>
	<?php
	$x = 1;

	foreach($results as $site)
	{	
		?>
		<tr>
			<td><?php echo $x ?></td>
			<td><a href="speedy.php?id=<?php echo $site['SiteId']; ?>"><?php echo $site['Site'] ; ?></a></td>
			<td><?php echo $site['Score'] ; ?></td>
			<td><?php echo $site['Html'] ; ?></td>
			<td><?php echo $site['Img'] ; ?></td>
			<td><?php echo $site['Css'] ; ?></td>
			<td><?php echo $site['Js'] ; ?></td>
			<td><?php echo $site['Other'] ; ?></td>
			<td><?php echo number_format(($site['Total'] / 1024), 2, '.', ',') ?> Kb</td>
			
			<?php 
			if ($doom == true) 
			{ ?>
			<td>
				<?php
  				    floppycount($site['Total']);
					doomSize($site['Total']);	
				?>	
			</td>
			<?php } ?>
		</td>
		<?php
		$x = $x + 1;
	}
	?>
	</table>
<?php 
 }
?>

<?php include 'footer.php'; ?>

<?php
function doomSize($size)
{
	$dooms = floor($size / 1024 / 2393); 
	if ($dooms > 0) {
		for ($i = 1; $i <= $dooms; $i++) {
			print '<img src="img/doom.gif" alt="doom head" title="' . $dooms . ' copies of doom" style="margin: -20px -15px;" >';
		}
	}
}

function floppycount($size)
{
	$count = ceil($size / 1024 / 1440);
	
	for ($i = 1; $i <= $count; $i++) {
    	print '<img src="img/floppy.png" title="'. $count . ' floppy disks">';
	}
}

?>