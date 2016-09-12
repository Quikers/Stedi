<?php

class Database extends PDO
{
    
    public function __construct($DB_HOST, $DB_NAME, $DB_USER, $DB_PASS)
    {
        parent::__construct('mysql:host='.$DB_HOST.';dbname='.$DB_NAME, $DB_USER, $DB_PASS);
        
        //parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTIONS);
    }
    
    public function query($sql, $format = true) {
        $sth = $this->prepare($sql);
        $sth->execute();
        
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($result) == 1 && $format == true) { $result = $result[0]; }
        return $result;
    }
    
}