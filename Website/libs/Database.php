<?php

class Database extends PDO
{
    
    public function __construct($DB_HOST, $DB_NAME, $DB_USER, $DB_PASS)
    {
        parent::__construct('mysql:host='.$DB_HOST.';dbname='.$DB_NAME, $DB_USER, $DB_PASS);
        
        //parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTIONS);
    }
    
    public function query($sql) {
        $sth = $this->prepare($sql);
        foreach ($array as $key => $value) {
            $sth->bindValue("$key", $value);
        }
        
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }
    
}