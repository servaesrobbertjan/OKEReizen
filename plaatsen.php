<?php

require_once("exceptions.php");
require_once("dbconfig.php");

class Plaatsen {

    private $plaatsId;
    private $gemeente;
    private $postcode;
 

    public function __construct($plaatsId = null, $gemeente = null, $postcode=null)
    {
        $this->plaatsId = $plaatsId;
        $this->gemeente = $gemeente;
        $this->postcode = $postcode;
       
        
    }


    public function getPlaatsId()
    {
        return $this->plaatsId;
    }


    public function setPlaatsId($plaatsId)
    {
        $this->plaatsId = $plaatsId;

        return $this;
    }


    public function getGemeente()
    {
        return $this->gemeente;
    }

 
    public function setGemeente($gemeente)
    {
        $this->gemeente = $gemeente;

   
    }

  
    public function getPostcode()
    {
        return $this->postcode;
    }


    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;

      
    }

    public Function  getAlleGemeente()
    {
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBconfig::$DB_USER, DBconfig::$DB_PASSWORD);
        $resultset = $dbh->query("SELECT plaatsId, gemeente, postcode FROM plaatsen");
        $plaatslijst = array();
        foreach ($resultset as $plaats) {
            $plaatsobj = new plaatsen($plaats["plaatsId"], $plaats["gemeente"], $plaats["postcode"]);
            array_push($plaatslijst, $plaatsobj);
        }
        $dbh = null;
        return $plaatslijst;
    }

    
}