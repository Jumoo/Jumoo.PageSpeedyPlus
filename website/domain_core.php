<?php // domain_core ?>
<?php

class Domains 
{

    function __construct($id) {
        $this->db = new SQlite3('speedyplus.db');
        $this->siteId = $id;
    }

    function getDomains() 
    {

        $domains = array();

        $sql = "SELECT * FROM Domains WHERE SiteId = :id;";

        $statement = $this->db->prepare($sql);
        $statement->bindValue(':id', $this->siteId, SQLITE3_INTEGER);
        $rows = $statement->execute();

        while ($row = $rows->fetchArray())
        {
            $domains[] = $row;
        }

        $statement->close();

        return $domains;
    }

    function getSiteFeatureCount()
    {
        
        $sql = "SELECT count(*) from Domain_Features where Application != 'error' and DomainId in (Select Id from Domains where SiteId =  " . $this->siteId . ");";
        return $this->db->querySingle($sql);
    }

    function getDomainFeatures($domainId)
    {
        $features = array();

        $sql = "SELECT Domain_Features.* FROM Domain_Features"
            . " INNER JOIN Domains on Domains.Id = Domain_Features.DomainId"
            . " WHERE Domains.Id = :id"
            . " GROUP BY Domain_Features.Application"
            . " ;";

        $statement = $this->db->prepare($sql);
        $statement->bindValue(':id', $domainId, SQLITE3_INTEGER);
        $rows = $statement->execute();

        while ($row = $rows->fetchArray())
        {
            $features[] = $row;
        }

        $statement->close();

        return $features;
    }

    function getFeatures() 
    {

        $features = array();

        $sql = "SELECT Domain_Features.* FROM Domain_Features"
            . " INNER JOIN Domains on Domains.Id = Domain_Features.DomainId"
            . " WHERE Domains.SiteId = :id"
            . " GROUP BY Domain_Features.Application"
            . " ;";

        $statement = $this->db->prepare($sql);
        $statement->bindValue(':id', $this->siteId, SQLITE3_INTEGER);
        $rows = $statement->execute();

        while ($row = $rows->fetchArray())
        {
            $features[] = $row;
        }

        $statement->close();

        return $features;
    }

    function getSites($feature)
    {
        $sites = array();

        $sql = "SELECT Distinct(Sites.Id), Sites.Name FROM Domain_Features "
            . " INNER JOIN Domains on Domains.Id = Domain_Features.DomainId"
            . " INNER JOIN Sites on Sites.Id = Domains.SiteId"
            . " WHERE Domain_Features.Application = :feature;";

        $statement = $this->db->prepare($sql);
        $statement->bindValue(':feature', $feature, SQLITE3_TEXT);
        $rows = $statement->execute();

        while ($row = $rows->fetchArray())
        {
            $sites[] = $row;
        }

        $statement->close();

        return $sites;
    }

    function getAllFeatures()
    {
        $features = array();
        $sql = "SELECT * FROM Domain_Features GROUP BY Application;";

        $statement = $this->db->prepare($sql);
        $statement->bindValue(':id', $this->siteId, SQLITE3_INTEGER);
        $rows = $statement->execute();

        while ($row = $rows->fetchArray())
        {
            $features[] = $row;
        }

        $statement->close();

        return $features;
    }

    function getAllDomains()
    {
        $domains = array();

        $sql = "SELECT * FROM Domains where Id not in (Select DomainId from Domain_Features)";

        $statement = $this->db->prepare($sql);
        $statement->bindValue(':id', $this->siteId, SQLITE3_INTEGER);
        $rows = $statement->execute();

        while ($row = $rows->fetchArray())
        {
            $domains[] = $row;
        }

        $statement->close();

        return $domains;
    }

    // CREATE TABLE SiteLinks(Id INTEGER PRIMARY KEY, SiteId INTEGER, Pages INT, Docs INT, Err INT);
    function getPages()
    {
        return $this->db->querySingle("SELECT Pages FROM SiteLinks WHERE SiteId = " . $this->siteId . ';');
    }
    function getDocs()
    {
        return $this->db->querySingle("SELECT Docs FROM SiteLinks WHERE SiteId = " . $this->siteId . ';');
    }
    function getQueue() {
        return $this->db->querySingle("SELECT Queued FROM SiteLinks WHERE SiteId = " . $this->siteId . ';');
    }
    function getDomainCount() {
        return $this->db->querySingle("SELECT count(*) FROM Domains WHERE SiteId = " . $this->siteId . ';');
    }
}

?>