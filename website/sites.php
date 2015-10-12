<?php include 'header.php' ; ?>

<p>All the sites <span class="logo"><span class="lg">Localgov</span>.pagespeedy</span> looks at:
<?php

	$db = new SQlite3('speedyplus.db');

	$statement = $db->prepare('SELECT * FROM SITES WHERE ACTIVE = 1 ORDER BY Name;');
	$results = $statement->execute();

	print '<ul class="sitelist list-unstyled">';

	while ($row = $results->fetchArray()) {
	?>
		<li><a href="speedy.php?id=<?php echo $row['Id'] ?>"><?php echo $row['Name'] ?></a></li>
	<?php
	}

	print '</ul>';

	$statement->close();
?>
<?php include 'footer.php'; ?>
</body>
</html>
