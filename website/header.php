<?php 
	$latest_month = 10;
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Localgov.PageSpeedy by Jumoo</title>
		<link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css"> 
		<link rel="stylesheet" href="css/speedy.css" type="text/css">
		<link rel="alternate" type="application/rss+xml" title="RSS" href="newsitesfeed.php" />

		
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
	
	<nav class="navbar navbar-default" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#ps-navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="index.html">PageSpeedy</a>
			</div>
			<div class="collapse navbar-collapse" id="ps-navbar">
				<ul class="nav navbar-nav">
					<li><a href="sites.php">Sites</a></li>
					<li><a href="featurelist.php">App list</a></li>
					<li><a href="speedytable.php?month=<?php echo $latest_month ?>">Speeds</a></li>
					<li><a href="achecktable.php?month=<?php echo $latest_month ?>">Accessibility</a></li>
					<li><a href="newsites.php?month=<?php echo $latest_month ?>">New Sites</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">About <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="http://blog.jumoo.co.uk/2014/the-council-speedy-indexish/">What is Pagespeedy?</a></li>
							<li class="divider"></li>
							<li><a href="https://github.com/KevinJump/Jumoo.PageSpeedyPlus/">Get the code</a></li>
						</ul>
					</li>		
				</ul>
			</div>
		</div>
	</nav>

	<div class="container">
	