<?php // wapplecore ?>
<?php
class Wapple
{
	private static $siteId = 0;
	private static $db ;


	function __construct($id) {
		$this->db = new SQlite3('speedyplus.db');
		$this->siteId = $id;	
	}
	
	function getFeatures($month)	
	{
		$features = array();
	
		$sql = "SELECT * FROM FEATURES INNER JOIN MONTHS on Months.Id = Features.monthId WHERE SiteId = :id AND Months.Name = :Month;";
		
		$statement = $this->db->prepare($sql);
		$statement->bindValue(':id', $this->siteId, SQLITE3_INTEGER);
		$statement->bindValue(':Month', $month, SQLITE3_TEXT);		
		$rows = $statement->execute();
		
		while( $row = $rows->fetchArray())
		{
			$features[] = $row;
		}
		
		$statement->close();
		
		return $features; 
		
	}
	
	function getSites($feature, $monthId)	
	{
		$sites = array();
	
		$sql = 'SELECT * FROM SITES INNER JOIN Features ON Features.SiteId = Sites.ID WHERE Application = :feature and MonthID = :month';

		$statement = $this->db->prepare($sql);
		$statement->bindValue(':feature', $feature, SQLITE3_TEXT);
		$statement->bindValue(':month', $monthId, SQLITE3_INTEGER);
		$rows = $statement->execute();
		
		while ( $row = $rows->fetchArray())
		{
			$sites[] = $row;
		}		
		$statement->close();
		return $sites;
	}
	
	function listApps($month, $category)
	{
		$features = array();
		
		$sql = 'select Speedy.MonthId as MonthId, Platform, Category, Application, Count(*) as SiteCount, Sum(Score)/Count(*) as AveScore, Max(Score) as TopScore, Min(Score) as LowScore from Speedy '
			. 'INNER JOIN Speedy_Result on Speedy_Result.SpeedyID = Speedy.Id '
			. 'INNER JOIN Features on Features.SiteId = Speedy.SiteId '
			. 'WHERE Speedy.MonthId = :month and Features.MonthId = :month and Category = :cat and Score > 0 and Platform = "mobile"  '
			. 'GROUP BY Application ORDER BY SiteCount DESC; ';
		
		$statement = $this->db->prepare($sql);
		$statement->bindValue(':month', $month, SQLITE3_INTEGER);
		$statement->bindValue(':cat', $category, SQLITE3_TEXT);
		$rows = $statement->execute();
		
		
		while( $row = $rows->fetchArray())
		{
			$features[] = $row;
		}
		$statement->close();
	

		return $features;	
	}
	
	function getCategories($month)
	{
		$categories = array();
		$sql = 'SELECT distinct(Category) FROM FEATURES WHERE MonthId = :month ORDER BY Category;';
		
		$statement = $this->db->prepare($sql);
		$statement->bindValue(':month', $month, SQLITE3_INTEGER);		
		$rows = $statement->execute();
		
		while( $row = $rows->fetchArray())
		{
			$categories[] = $row;
		}
		$statement->close();
		return $categories;	
	}
	
	function GetApp($siteId, $monthId, $category)
	{
		$sql = 'SELECT Application from Features where SiteId = ' . $siteId . ' and MonthId = ' . $monthId . ' and Category = "' . $category . '";';	
		$result = $this->db->querySingle($sql);
		if (empty($result)) {
			return 'unknown';
		}
		return $result;
	}

}

function ShowWapple($w, $month)
{
	$features = $w->getFeatures($month);
	if ( count($features) > 0 ) {
		
		?>
			<div class="wapple">
				<h3 class="page-header">Detected technologies <small>via Wappalizer</small></h3>
					<ul>
		<?php
		foreach($features as $feature) 
		{
		?>
				<li><strong>
					<a href="feature.php?feature=<?php echo $feature['Application']; ?>">
					<?php echo $feature['Application']; ?></a> : </strong><?php echo $feature['Category']; ?></li>
		<?php
		}
		?>
				</ul>
			</div>
		
		<?php
	}
}

?>