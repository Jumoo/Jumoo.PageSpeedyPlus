<?php // review-core ?>
<?php

class Reviews 
{
    function __construct($id) {
        $this->db = new SQlite3('speedyplus.db');
        $this->id = $id;
    }

    function getReview() {
            $sql = "SELECT Url FROM REVIEWS WHERE SiteId = " . $this->id . ";";
            return $this->db->querySingle($sql);
   }
};

?>