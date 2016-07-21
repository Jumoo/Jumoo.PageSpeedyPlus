<?php include 'header.php' ; ?>
<div class="site-header">
<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<h2><span class="logo"><span class="lg">Localgov</span>.pagespeedy</span> sites</h2>
			<p>All the sites that localgov pagespeedy looks at each month </p>
		</div>
	</div>
</div>
</div>		
<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<?php

				$db = new SQlite3('speedyplus.db');

				$statement = $db->prepare('SELECT * FROM SITES WHERE ACTIVE = 1 ORDER BY DisplayName COLLATE NOCASE;');
				$results = $statement->execute();

				print '<ul class="sitelist list-unstyled">';

				while ($row = $results->fetchArray()) {
				?>
					<li><a href="speedy.php?id=<?php echo $row['Id'] ?>"><?php echo $row['DisplayName'] ?></a></li>
				<?php
				}

				print '</ul>';

				$statement->close();
			?>
		</div>
	</div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
