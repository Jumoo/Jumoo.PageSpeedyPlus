<?php // speedy_disp ?>

<?php 		
function ShowSpeedy($sp, $month, $platform, $latest_month)
{
	$results = $sp->getResults($platform, $month);

	foreach($results as $line)
	{
	?>
		<div class="col-md-6">
			<div class="result">
				<div class="row">
					<div class="col-xs-12">
						<h3 class="platform"><?php echo $line['platform'] ?> : <?php echo $line['Score'] ?>
						</h3>
					</div>
						<div class="col-sm-5">
							<table class="size-data">
								<tr><td class="data-key data-html"></td><th>Html:</th><td><?php echo number_format(($line['Html'] / 1024), 2, '.', ',') ?>Kb</td></tr>
								<tr><td class="data-key data-css"></td><th>Css:</th><td><?php echo number_format(($line['Css'] / 1024), 2, '.', ',') ?>Kb</td></tr>
								<tr><td class="data-key data-img"></td><th>Images:</th><td><?php echo number_format(($line['Img'] / 1024), 2, '.', ',') ?>Kb</td></tr>
								<tr><td class="data-key data-js"></td><th>Scripts:</th><td><?php echo number_format(($line['Js'] / 1024), 2, '.', ',') ?>Kb</td></tr>
								<tr><td class="data-key data-other"></td><th>Other:</th><td><?php echo number_format(($line['Other'] / 1024), 2, '.', ',') ?>Kb</td></tr>
							</table>
						</div>
						<div class="col-sm-7">
							<canvas id="piechart_<?php echo $line['MonthId'] ?>_<?php echo $line['platform'] ?>" >
							</canvas>
						</div>
						<div class="col-sm-12">
							<h3><strong>Total PageSize:</strong> <?php echo number_format(($line['Total'] / 1024), 2, '.', ',') ?> Kb</h3>
						</div>
					
					<div class="col-sm-12">
						<div class="screenshot">
						<?php if ($line['MonthId'] > 2) {?>
							<img src="results/<?php echo $line['MonthId'] ?>/screenshots/<?php echo $line['Site'] ?>_<?php echo $line['platform'] ?>.jpg">
						<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php
	}	
}

function GetChartData($MonthId, $platform, $data)
{

}


function ShowLatestSpeedy($sp, $month, $platform, $latest_month)
{
	$monthName = $sp->getMonthName($month);
	
	$results = $sp->getResults($platform, $monthName);

	foreach($results as $line)
	{
		?>
		<table class="size-data">
			<tr><td class="data-key data-html"></td><th>Html:</th><td><?php echo number_format(($line['Html'] / 1024), 2, '.', ',') ?>Kb</td></tr>
			<tr><td class="data-key data-css"></td><th>Css:</th><td><?php echo number_format(($line['Css'] / 1024), 2, '.', ',') ?>Kb</td></tr>
			<tr><td class="data-key data-img"></td><th>Images:</th><td><?php echo number_format(($line['Img'] / 1024), 2, '.', ',') ?>Kb</td></tr>
			<tr><td class="data-key data-js"></td><th>Scripts:</th><td><?php echo number_format(($line['Js'] / 1024), 2, '.', ',') ?>Kb</td></tr>
			<tr><td class="data-key data-other"></td><th>Other:</th><td><?php echo number_format(($line['Other'] / 1024), 2, '.', ',') ?>Kb</td></tr>
			<tr><td colspan="3">
				<strong>Total:</strong> <?php echo number_format(($line['Total'] / 1024), 2, '.', ',') ?> Kb
			</td></tr>
		</table>
		<?php
	}
}

?>