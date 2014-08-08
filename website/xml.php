<?php include 'speedycore.php' ; ?>
<?php include 'accesscore.php' ; ?>
<?php include 'header.php' ; ?>
<?php
	$id = $_GET["id"]; 	
	$monthId = $_GET["month"]; 	
	$speedy = new Speedy($id); 
	$checker = new Checker($id);
?>
<?php 

	$xml = $checker->getXml($monthId, $speedy->getSiteName());

?>

<div class="page-header">
	<h1>Accessbility Check: 
		<span class="sitename"><?php echo $speedy->getSiteName(); ?></span>
		<small>
			<?php echo substr($speedy->getMonthName($monthId),4); ?>
		</small>
	</h1>
	<em>
		Site checked using localized version of <a href="http://achecker.ca/checker/index.php">aChecker</a>
	</em>
</div>

<div class="summary">
	<table class="table">
		<tr><th>Status</th><td><?php echo $xml->summary->status ?></td></tr>
		<tr><th>Errors</th><td><?php echo $xml->summary->NumOfErrors ?></td></tr>
		<tr><th>Likely Problems</th><td><?php echo $xml->summary->NumOfLikelyProblems ?></td></tr>
		<tr><th>Potential Problems</th><td><?php echo $xml->summary->NumOfPotentialProblems ?></td></tr>
		<tr><th>Guidelines</th><td><?php echo $xml->summary->guidelines->guideline ?></td></tr>
	</table>
</div>

<div class="results">
	<div class="page-header">
		<h2>Errors:</h2>
	</div>
	<?php displayResults($xml, "Error");	?>
	
	<div class="page-header">
		<h2>Likely Problems</h2>
	</div>
	<?php displayResults($xml, "Likely Problem");	?>

	<div class="page-header">
		<h2>Potential Problems</h2>
	</div>
	<?php displayResults($xml, "Potential Problem");	?>
	
</div>

<?php
function displayResults($xml, $type)
{
	$results = $xml->xpath('./results/result[./resultType="' . $type . '"]');
	foreach($results as $result) {
	?>
			<table class="table">
				<tr><th>Location</th><td>
					Line: <?php echo $result->lineNum; ?> 
					Col: <?php echo $result->columnNum; ?>
				</td></tr>
				<tr><th>Message</th><td><?php echo $result->errorMsg; ?></td></tr>
				<tr><th>Code:</th><td><?php echo htmlspecialchars($result->errorSourceCode); ?>				</td></tr>
					
				<?php if (isset($result->decisionPass)) { ?>
					<tr><th>Pass:</th><td><?php echo $result->decisionPass; ?></td></tr>
				<?php } ?>
				<?php if (isset($result->decisionFail)) { ?>
					<tr><th>Fail:</th><td><?php echo $result->decisionFail; ?></td></tr>
				<?php } ?>
			</table>
		<?php
	}
}
?>
<!--
 <status>FAIL</status>
    <sessionID>977554a03cbf986930d0ab7d80b314afbdb42174</sessionID>
    <NumOfErrors>4</NumOfErrors>
    <NumOfLikelyProblems>5</NumOfLikelyProblems>
    <NumOfPotentialProblems>466</NumOfPotentialProblems>

    <guidelines>
      <guideline>WCAG 2.0 (Level A)</guideline>

    </guidelines> -->


<?php include 'footer.php' ; ?>
	