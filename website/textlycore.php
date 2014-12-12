<?php // textly core ?>
<?php 
class Textly
{
	private static $db ;
	private static $siteId = 0;
	
	function __construct($id) {
		$this->db = new SQlite3('speedyplus.db');
		$this->siteId = $id;
	}
	
	function getResults($monthId)
	{
		$results = array();

		$sql = "select * from textly where SiteId = :id and MonthId = :MonthId";
		
		$statement = $this->db->prepare($sql);
		$statement->bindValue(':MonthId', $monthId, SQLITE3_INTEGER);
		$statement->bindValue(':id', $this->siteId, SQLITE3_INTEGER);
		
		$rows = $statement->execute();
		
		while ( $row = $rows->fetchArray())
		{
			$results[] = $row;
		}
		
		$statement->close();
		
		return $results; 		
	}	
}
?>