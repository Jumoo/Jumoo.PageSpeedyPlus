<?php 
class Speedy
{
	private static $siteId = 0;
	private static $db ;

	function __construct($id) {
		$this->db = new SQlite3('speedyplus.db');
		$this->siteId = $id;
	}
	
	function getSiteName()
	{
		return $this->db->querySingle('SELECT Name FROM SITES WHERE ID = ' . $this->siteId . ';');
	}
	
	function getSiteUrl()
	{
		return $this->db->querySingle('SELECT Url FROM SITES WHERE ID = ' . $this->siteId . ';');
	}
	
	function getMonthName($monthId)
	{
		return $this->db->querySingle('SELECT Name From Months where ID = ' . $monthId . ';');
	}
	
	function getMonths()
	{
		$monthlist_sql = 'SELECT DISTINCT(Month) as Month From SpeedyResults_View where SiteId = :id order by Month DESC';
		$months = $this->getList($monthlist_sql, $this->siteId, 'Month');
	
		return $months ; 
	}
	
	function getProcessedMonths()
	{
		$monthlist = array();
		
		$months_sql = 'SELECT * FROM Months WHERE Processed = 1;';
		$statement = $this->db->prepare($months_sql);
		$rows = $statement->execute();
		while( $row = $rows->fetchArray())
		{
			$monthlist[] = $row ;
		}
		return $monthlist;
	}
	
	function getList($sql, $id, $itemName)
	{
		$list = array(); 
	
		$statement = $this->db->prepare($sql);
		$statement->bindValue(':id', $id, SQLITE3_INTEGER);
		$rows = $statement->execute();
		while ( $row = $rows->fetchArray())
		{
			$list[] = $row[$itemName] ; 
		}
		$statement->close();
		
		return $list ;
	}
	
	function getResults($platform, $month)
	{
		$speedyresult = array() ;
	
		$result_sql = 'SELECT * FROM SPEEDYRESULTS_VIEW WHERE SiteId = :id and Month = :month and Platform = :platform ;';
	
		$statement = $this->db->prepare($result_sql);
		$statement->bindValue(':id', $this->siteId, SQLITE3_INTEGER);
		$statement->bindValue(':month', $month, SQLITE3_TEXT);
		$statement->bindValue(':platform', $platform, SQLITE3_TEXT);
		
		$results = $statement->execute();
		
		while( $row = $results->fetchArray()) {
			$speedyresult[] = $row ; 
		}
		
		$statement->close();
		return $speedyresult; 
		
	}
	
	function getTable($platform, $month)
	{
		$speedytable = array() ;

		$tablesql = 'SELECT * FROM SPEEDYRESULTS_VIEW WHERE monthId = :month and platform = :platform ORDER BY Score DESC;';
		$statement = $this->db->prepare($tablesql);
		$statement->bindValue(':month', $month, SQLITE3_TEXT);
		$statement->bindValue(':platform', $platform, SQLITE3_TEXT);
		
		$results = $statement->execute();
		
		while( $row = $results->fetchArray()) {
			$speedytable[] = $row ; 
		}
		
		$statement->close();
		return $speedytable; 

	}

}
?>