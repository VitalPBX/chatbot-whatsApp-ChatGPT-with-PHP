<?php
class PDO_DB{
    private $_c;

    public function __construct(){
        $this->_initDBConnection();
    }

    private function _initDBConnection(){    
       $host = config::DB_HOST;
       $dbName = config::DB_NAME;
    
       try {
            $c = new PDO("mysql:host=$host;port=3306;dbname=$dbName;charset=utf8mb4", config::DB_USERNAME, config::DB_PASSWORD);
            $c->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $this->_c = $c;
       }catch(PDOException $e){
            throw new RuntimeException($e->getMessage());
       }
    }

    public function fetchRow($query){
        $c = $this->_c;

        $stmt = $c->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function fetchAll($query){
        $c = $this->_c;

        $stmt = $c->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}