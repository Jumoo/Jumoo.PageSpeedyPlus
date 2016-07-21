<?php include 'monthnames.php' ; ?>
<?php 
class Speedy
{
	private static $siteId = 0;
	private static $db ;

	function __construct($id) {
		$this->db = new SQlite3('speedyplus.db');
		$this->siteId = $id;
	}
	
	function getByGSS($gss)
	{
		$this->siteId = $this->db->querySingle('SELECT Id from Sites where GSS = "' . $gss . '";');
		return $this->siteId; 
	}
    
	function getSiteShortName()
	{
		return $this->getSiteShortNameById($this->siteId);
	}
	
	function getSiteName()
	{
		return $this->getSiteNameById($this->siteId);
	}
	
	function getSiteNameById($id)
	{
		$name =  $this->db->querySingle('SELECT DisplayName FROM SITES WHERE ID = ' . $id . ';');
		if ($name == null)
		{
			return getSiteShortNameById($id);
		}
		return $name;
	}
	
	function getSiteShortNameById($id)
	{
		return $this->db->querySingle('SELECT Name FROM SITES WHERE ID = ' . $id . ';');
	}
	
	function getSiteCode()
	{
		return $this->db->querySingle('SELECT GSS FROM SITES WHERE ID = ' . $this->siteId . ';');
	}
	
	function getSiteId() {
		return $this->siteId; 
	}
	
	function getSiteUrl()
	{
		return $this->db->querySingle('SELECT Url FROM SITES WHERE ID = ' . $this->siteId . ';');
	}
	
	function getMonthName($monthId)
	{
		return $GLOBALS['monthNames'][$monthId];
		// return $this->db->querySingle('SELECT Name From Months where ID = ' . $monthId . ';');
	}

	function getMonths()
	{
		$monthlist_sql = 'SELECT DISTINCT(Month) as Month From SpeedyResults_View where SiteId = :id order by MonthId DESC';
		$months = $this->getList($monthlist_sql, $this->siteId, 'Month');
	
		return $months ; 
	}
	
	function getScores($id, $type)
	{
		$scores = array();
		
		$scoreSql = 'select score from SpeedyResults_View where siteID = :id and platform = :type order by MonthId';
		$statement = $this->db->prepare($scoreSql);
		$statement->bindValue(':id', $id, SQLITE3_INTEGER);
		$statement->bindValue(':type', $type);
		$rows = $statement->execute();
		while( $row = $rows->fetchArray())
		{
			$scores[] = $row ;
		}
		return $scores;
	}
	
	function getTotalSizes($id)
	{
		$scores = array();
		
		$scoreSql = 'select * from SpeedyResults_View where siteID = :id and platform = "desktop" order by MonthId';
		$statement = $this->db->prepare($scoreSql);
		$statement->bindValue(':id', $id, SQLITE3_INTEGER);
		$rows = $statement->execute();
		while( $row = $rows->fetchArray())
		{
			$scores[] = $row ;
		}
		return $scores;
	}
	
	function getProcessedMonths()
	{
		$monthlist = array();
		
		$months_sql = 'SELECT * FROM Months WHERE Processed = 1 order by id;';
		$statement = $this->db->prepare($months_sql);
		$rows = $statement->execute();
		while( $row = $rows->fetchArray())
		{
			$monthlist[] = $row ;
		}
		return $monthlist;
	}
	
	function getMonthsWithNewSites()
	{
		$monthlist = array();
		
		$months_sql = 'select distinct(Months.Id), Months.Name from Months inner join NewSites on Months.Id = NewSites.NewMonthID order by Months.Id;';
		$statement = $this->db->prepare($months_sql);
		$rows = $statement->execute();
		while( $row = $rows->fetchArray())
		{
			$monthlist[] = $row ;
		}
		return $monthlist;
		
	}
	
	function getMonthsWithScores()
	{
		$monthlist = array(); 
		$months_sql = 'select Months.* from Months inner join SPEEDY on Months.Id = Speedy.MonthId where speedy.SiteId = :id order by Months.Id';
		$statement = $this->db->prepare($months_sql);
		$statement->bindValue(':id', $this->siteId);
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
	
	function getScore($platform, $monthId)
	{
		$sql = 'SELECT score from SPEEDYRESULTS_VIEW where SiteId = ' . $this->siteId . ' and MonthId = ' . $monthId . ' and Platform = "' . $platform . '";';	
		return $this->db->querySingle($sql);
	}
		
	function getResults($platform, $month)
	{
		$speedyresult = array() ;
	
		$result_sql = 'SELECT * FROM SPEEDYRESULTS_VIEW WHERE SiteId = :id and Month = :month and Platform = :platform order by MonthId;';
	
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
	
	function getNewSites($month)
	{
		$newSites = array();
		
		$newSiteSql = 'SELECT * FROM NewSites_View WHERE newMonthId = :month;' ;
		$statement = $this->db->prepare($newSiteSql);
		$statement->bindValue(':month', $month, SQLITE3_INTEGER);
		
		$results = $statement->execute();
		
		while( $row = $results->fetchArray()) {
			$newSites[] = $row ;
		}
		$statement->close();
		return $newSites;
	}
	
	function getLatestSites()
	{
		$newSites = array();
		
		$latestSql = 'SELECT * FROM NewSites_View ORDER BY newMonthId DESC;';
		
		$statement = $this->db->prepare($latestSql);
		
		$results = $statement->execute();
		
		while( $row = $results->fetchArray()) {
			$newSites[] = $row ;
		}
		$statement->close();
		return $newSites;
	}
	
	function getSiteUpdates()
	{
		$updates = array();
		
		$newSiteSql = 'SELECT * FROM NewSites_View WHERE id = :siteId ORDER BY newMonthId DESC;' ;
		$statement = $this->db->prepare($newSiteSql);
		$statement->bindValue(':siteId', $this->siteId, SQLITE3_INTEGER);
		
		$results = $statement->execute();
		
		while( $row = $results->fetchArray()) {
			$updates[] = $row ;
		}
		$statement->close();
		return $updates;
	}
}
?>