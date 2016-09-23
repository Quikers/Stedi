<?php

class Database extends PDO
{
    
    public function __construct($DB_HOST, $DB_NAME, $DB_USER, $DB_PASS)
    {
        parent::__construct('mysql:host='.$DB_HOST.';dbname='.$DB_NAME, $DB_USER, $DB_PASS);
    }
    
    public function Query($sql, $format = true, $returnLastInsertedID = false, $returnRowCount = false) {
        $sth = $this->prepare($sql);
        $sth->execute();
        
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($result) == 1 && $format == true) { $result = $result[0]; }
        
        if ($returnLastInsertedID) {
            $result["result"] = $result;
            $result["lastInsertedID"] = $this->lastInsertId();
        }
        
        if ($returnRowCount) { $result["rowCount"] = $sth->rowCount(); }
        
        return $result;
    }
    
}