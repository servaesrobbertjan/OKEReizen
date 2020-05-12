<?php

require_once("dbconfig.php");

class Pakket {


/* 
 
 * We maken een Object "Pakket", met alle relevante info van het reispakket. Hierin zit: het reisid, de omschrijving van het pakket, de bestemming, reistype, hotel en prijs

*/ 
private $reisid;
    private $omschrijving;
    private $bestemmingid;
    private $reistype;
    private $stad;
    private $land;
    private $luchthaven;
    private $hotelnaam;
    private $prijs;


    public function __construct($reisid = null, $omschrijving = null, $reistype = null, $stad = null, $land = null, $luchthaven = null, $hotelnaam = null, $prijs=null)
    {
        $this->reisid = $reisid;
        $this->omschrijving = $omschrijving;
        $this->reistype = $reistype;
        $this->stad = $stad;
        $this->land = $land;
        $this->luchthaven = $luchthaven;
        $this->hotelnaam = $hotelnaam;
        $this->prijs = $prijs;

    }

    
/* Getters en Setters */

public function getReisId()
{
    return $this->reisid;
}

    public function getOmschrijving()
    {
        return $this->omschrijving;
    }

   
    public function setOmschrijving($omschrijving)
    {
        $this->omschrijving = $omschrijving;

       
    }

  
    public function getBestemmingid()
    {
        return $this->bestemmingid;
    }

  
    public function setBestemmingid($bestemmingid)
    {
        $this->bestemmingid = $bestemmingid;

    }

    public function getReistype()
    {
        return $this->reistype;
    }
 
    public function setReistype($reistype)
    {
        $this->reistype = $reistype;

      
    }

 
    public function getStad()
    {
        return $this->stad;
    }

 
    public function setStad($stad)
    {
        $this->stad = $stad;

        return $this;
    }


    public function getLand()
    {
        return $this->land;
    }

  
    public function setLand($land)
    {
        $this->land = $land;

    
    }

    
    public function getHotelnaam()
    {
        return $this->hotelnaam;
    }

    
    public function setHotelnaam($hotelnaam)
    {
        $this->hotelnaam = $hotelnaam;

    
    }


    public function getLuchthaven()
    {
        return $this->luchthaven;
    }



   
    public function getPrijs()
    {
        return $this->prijs;
    }

   
    public function setPrijs($prijs)
    {
        $this->prijs = $prijs;

    }

public function getPakketByReisid($id){

}


public function getAllePakketten()

{


    $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USER, DBConfig::$DB_PASSWORD);
    $resultSet = $dbh->query("SELECT reisiNummer bestemmingsId, reisomschrijving, reis, hotel FROM reizen ORDER BY berichtid desc");

    $berichtenLijst = array();

    foreach ($resultSet as $bericht) {
        $berichtObj = new Bericht($bericht["berichtid"], $bericht["naam"], $bericht["bericht"], $bericht["datum"]);
        array_push($berichtenLijst, $berichtObj);
    }

    $dbh = null;
    return $berichtenLijst;

}

public function getPakketByReisTypeAndBestemming($type,$bestemming)

{

}



}