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
	
	function getSites($feature)	
	{
		$sites = array();
	
		$sql = 'SELECT * FROM SITES INNER JOIN Features ON Features.SiteId = Sites.ID WHERE Application = :feature';
		$statement = $this->db->prepare($sql);
		$statement->bindValue(':feature', $feature, SQLITE3_TEXT);
		$rows = $statement->execute();
		
		while ( $row = $rows->fetchArray())
		{
			$sites[] = $row;
		}		
		$statement->close();
		return $sites;
	}
	
	function listFeatures()
	{
		$features = array();
		
		$sql = 'SELECT distinct(Application), Category FROM FEATURES ORDER BY Category;';
		
		$statement = $this->db->prepare($sql);
		$rows = $statement->execute();
		
		while( $row = $rows->fetchArray())
		{
			$features[] = $row;
		}
		$statement->close();
		return $features;	
	}

}

function ShowWapple($w, $month)
{
	$features = $w->getFeatures($month);
	if ( count($features) > 0 ) {
		
		?>
		<div class="col-md-6">
			<div class="result wapple">
				<h3>Wappalizer Results: (detected technologies)</h3>
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
		</div>
		
		<?php
	}
}

?>