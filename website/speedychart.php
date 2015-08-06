<script src="js/chart.min.js"></script>
<script>
	var data = {
		labels: <?php MonthsDataList($speedy) ?>,
		datasets: [
			{
				label: "Desktop Score",
				fillColor: "rgba(220,220, 220, 0.2)",
				strokeColor: "rgba(220,220,220,1)",
				pointColor: "rgba(220,220,220,1)",
				pointStrokeColor: "#fff",
				pointHighlightFill: "#fff",
				pointHighlightStroke: "rgba(220,220,220,1)",
				data: <?php ScoreDataList($speedy, $id, "desktop") ?>
			},
			{
				label: "Mobile Score",
				fillColor: "rgba(220,160, 220, 0.2)",
				strokeColor: "rgba(220,160,220,1)",
				pointColor: "rgba(220,160,220,1)",
				pointStrokeColor: "#fff",
				pointHighlightFill: "#fff",
				pointHighlightStroke: "rgba(220,160,220,1)",
				data: <?php ScoreDataList($speedy, $id, "mobile") ?>
			}			
			
		]
	};
		
	var ctx = document.getElementById("results").getContext("2d");


	var resultChart = new Chart(ctx).Line(data);
	
	<?php GetMonthySizeCharts($speedy); ?>
	
</script>

<?php
function MonthsDataList($speedy)
{
	$months = $speedy->getMonthsWithScores() ;

	print '[' ;
	foreach($months as $month)
	{
		print '"' . substr($month['Name'],4) . '",' ;
	}
	print ']' ;
}

function ScoreDataList($speedy, $id, $type)
{
	$scores = $speedy->getScores($id, $type);

	print '[' ;
	foreach($scores as $score)
	{
		print $score['score'] . ',' ;
	}
	print ']' ;
}

function GetMonthySizeCharts($speedy)
{
	$months = $speedy->getMonths(); 
		
	foreach ($months as $month) {
		RenderSizeChart($speedy, $month, "desktop");
		RenderSizeChart($speedy, $month, "mobile");
	}
}

function RenderSizeChart($speedy, $month, $platform)
{	
	$results = $speedy->getResults($platform, $month);

	foreach($results as $line)
	{
		$pieId = $line['MonthId'] . "_" . $line['platform'] ;

		?> 
			var data_<?php echo $pieId ?> = [			
				{
					value: <?php echo $line['Html'] ?>,
					color: "#F24D40",
					hightlight: "#FF5A5E",
					label: "Html"
				},
				{
					value: <?php echo $line['Css'] ?>,
					color: "#C78B7B",
					hightlight: "#5AD3D1",
					label: "Css"
				},
				{
					value: <?php echo $line['Img'] ?>,
					color: "#46BFBD",
					hightlight: "#FFC870",
					label: "Images"
				},
				{
					value: <?php echo $line['Js'] ?>,
					color: "#4097F2",
					hightlight: "#70FFC8",
					label: "Javascript"
				},
				{
					value: <?php echo $line['Other'] ?>,
					color: "#444E59",
					hightlight: "#FF5A5E",
					label: "Other"
				}
			];
			
			if ( document.getElementById("piechart_<?php echo $pieId ?>") != null )
			{
				var canvas<?php echo $pieId ?> = document.getElementById("piechart_<?php echo $pieId ?>").getContext("2d");
				var rCcanvas<?php echo $pieId ?> = new Chart(canvas<?php echo $pieId ?>).Doughnut(data_<?php echo $pieId ?>);
			}
		<?php 
	}
}
?>


