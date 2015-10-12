<?
	$name = $_GET["council"];
  $stmt = $db->prepare('SELECT id from Sites where Name = :name');
  $stmt->bindValue(':name', 1, SQLITE3_INTEGER);
?>
