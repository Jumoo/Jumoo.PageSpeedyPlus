<?php include 'header.php' ; ?>
<?php

	$db = new SQlite3('speedyplus.db');
	
	$statement = $db->prepare('SELECT * FROM SITES');
	$results = $statement->execute();
	
	print '<ul class="sitelist">';

	while ($row = $results->fetchArray()) {
	?>
		<li><a href="speedy.php?id=<?php echo $row['Id'] ?>"><?php echo $row['Name'] ?></a></li>
	<?php
	}
	
	print '</ul>';
	
	$statement->close();	
?>
<?php include 'footer.php'; ?>