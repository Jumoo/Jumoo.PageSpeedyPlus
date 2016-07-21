<select class="form-control input-lg" name="id">
    <option value="0">pick a council</option>
<?php
        $db = new SQlite3('speedyplus.db');

        $statement = $db->prepare('SELECT * FROM SITES WHERE ACTIVE = 1 and DisplayName != "" ORDER BY DisplayName COLLATE NOCASE;');
        $results = $statement->execute();
        while ($row = $results->fetchArray()) {
        ?> 
            <option value="<?php echo $row['Id'] ?>">
                <?php echo $row['DisplayName'] ?> 
            </option>
        <?php
        }
        
        $statement->close();
?>
</select>