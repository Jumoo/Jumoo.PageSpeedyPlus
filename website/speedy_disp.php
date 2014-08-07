<?php // speedy_disp ?>

<?php 		
function ShowSpeedy($sp, $month, $platform)
{
	$results = $sp->getResults($platform, $month);

	foreach($results as $line)
	{
	?>
		<div class="col-md-6">
			<div class="result">
				<div class="row">
					<div class="col-xs-12">
						<h3 class="platform"><?php echo $line['platform'] ?> : <?php echo $line['Score'] ?></h3>
					</div>
					<div class="col-sm-4">
						<ul>
							<li><strong>Html:</strong> <?php echo $line['Html'] ?> bytes</li>
							<li><strong>Css:</strong> <?php echo $line['Css'] ?></li>
							<li><strong>Images:</strong> <?php echo $line['Img'] ?></li>
							<li><strong>Javascript:</strong> <?php echo $line['Js'] ?></li>
							<li><strong>Other:</strong> <?php echo $line['Other'] ?></li>
							<li><h3><strong>Total PageSize:</strong><br/><?php echo number_format(($line['Total'] / 1024), 2, '.', ',') ?> Kb</h3></li>
						</ul>
					</div>
					<div class="col-sm-8">
						<div class="screenshot">
							<img src="results/<?php echo $line['MonthId'] ?>/screenshots/<?php echo $line['Site'] ?>_<?php echo $line['platform'] ?>.jpg">
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php
	}	
}
?>