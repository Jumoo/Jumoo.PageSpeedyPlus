<?php // access core ?>
<?php
class Checker
{
	private static $siteId = 0;
	private static $db ;


	function __construct($id) {
		$this->db = new SQlite3('speedyplus.db');
		$this->siteId = $id;	
	}
	
	function getAccess($month)	
	{
		$results = array();
	
		$sql = "SELECT * FROM CHECKER INNER JOIN MONTHS on Months.Id = CHECKER.monthId WHERE SiteId = :id AND Months.Name = :Month;";
		
		$statement = $this->db->prepare($sql);
		$statement->bindValue(':id', $this->siteId, SQLITE3_INTEGER);
		$statement->bindValue(':Month', $month, SQLITE3_TEXT);		
		$rows = $statement->execute();
		
		while( $row = $rows->fetchArray())
		{
			$results[] = $row;
		}
		
		$statement->close();
		
		return $results; 
		
	}
}

function ShowChecker($c, $month, $s)
{
	$checks = $c->getAccess($month);;
	
	if ( count($checks) > 0 ) {
		?>
		<div class="col-md-6">
			<div class="result wapple">
				<h3>Accessibility Check [Homepage: (WCAG2.0-A)]</h3>
					<ul>
		<?php
		foreach($checks as $check) 
		{
		?>	<p>
				<strong><?php echo $check['Status']; ?></strong><br/>
				Errors: <?php echo $check['Errors']; ?><br/>
				<?php 
					$raw_url = 'results/' . $check['MonthID'] . '/checker/' . $s->getSiteName() . '.xml' ;
				?>
				<a href="<?php echo $raw_url; ?>">Raw Results</a>
			</p>
					
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