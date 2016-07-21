<?php // mobile check core ?>
<?php
class MobileCheck 
{
    private static $db;
    private static $siteId;
    
    function __construct($id) {
        $this->db = new SQlite3('speedyplus.db');
        $this->siteId = $id;
    }
    
    function getMobileCheck($monthId) 
    {
        $result = 'false';
        
        $sql = "select pass from mobilecheck where SiteId = " . $this->siteId . " and MonthId = " . $monthId . ";" ;
        
        $result = $this->db->querySingle($sql);
        if (empty($result)) {
            return 'unknown';
        }
        
        return $result;
    }
}