<?php

require_once("pakket.php");
require_once("hotels.php");
require_once("exceptions.php");
require_once("dbconfig.php");
require_once("klanten.php");

class Boeking
{

    /* 
         
         * We maken een Object "Boeking", met alle relevante info van de boeking. Hierin zit: het object van het Pakket, het object van de User, object van de medereizigers, het aantal personen,  het aantal dagen,
         * object van het hotel, luchthaven, vertrekdatum, boekingsid
        
        */
    private $boekingsid;
    private $klantid;
    private $reisid;
    private $luchthaven;
    private $hotel;
    private $aantalpersonen;
    private $aantaldagen;
    private $vertrekdatum;



    public function __construct($boekingsid = null, $klantid=null, $reisid = null, $luchthaven = null, $hotel = null, $aantalpersonen = null, $aantaldagen = null, $vertrekdatum = null)

    {
        $this->boekingsid = $boekingsid;
        $this->klantid = $klantid; 
        $this->reisid = $reisid;
        $this->luchthaven = $luchthaven;
        $this->hotel = $hotel;
        $this->aantalpersonen = $aantalpersonen;
        $this->aantaldagen = $aantaldagen;
        $this->vertrekdatum = $vertrekdatum;
    }


    /*object in object, pakket.php + hotel.php */


    public function getBoekingsid()
    {
        return $this->boekingsid;
    }


    public function getReisid()
    {
        return $this->reisid;
    }


    public function setReisid($reisid)
    {
        $this->reisid = $reisid;
    }



    public function getLuchthaven()
    {
        return $this->luchthaven;
    }


    public function setLuchthaven($luchthaven)
    {
        $this->luchthaven = $luchthaven;

        return $this;
    }



    public function getAantaldagen()
    {
        return $this->aantaldagen;
    }


    public function setAantaldagen($aantaldagen)
    {
        $this->aantaldagen = $aantaldagen;
    }


    public function getVertrekdatum()
    {
        return $this->vertrekdatum;
    }


    public function setVertrekdatum($vertrekdatum)
    {
        $this->vertrekdatum = $vertrekdatum;
    }


    public function getAantalpersonen()
    {
        return $this->aantalpersonen;
    }


    public function setAantalpersonen($aantalpersonen)
    {
        $this->aantalpersonen = $aantalpersonen;
    }

    public function getHotel()
    {
        return $this->hotel;
    }


    public function setHotel($hotel)
    {
        $this->hotel = $hotel;
    }


    public function getKlantid()
    {
            return $this->klantid;
    }

    
    public function setKlantid($klantid)
    {
            $this->klantid = $klantid;
         
    }


    public function GetBoekingById($id)
    {
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USER, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare("select from boekingen INNER JOIN reizen ON INNER JOIN hotels ON bieren.SoortNr = soorten.SoortNr WHERE BierNr = :id");
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        $resultSet = $stmt->fetch(PDO::FETCH_ASSOC);
        $hotelObj = new Hotels($resultSet[]);
        $pakketObj = new Pakket(($resultSet[]));
        $boekingsObj = new Boeking($id, $resultSet[], $pakketObj, $hotelObj);

        $dbh = null;
        return $boekingsObj;
    }


    public function addBoeking($datum, $heendatum, $aantaldagen, $aantalpersonen)
    {
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USER, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare("INSERT INTO boekingen (boekingsDatum, heenDatum, aantalDagen, aantalPersonen) VALUES (:datum, :heendatum, :aantaldagen, :aantalpersonen)");
        $stmt->bindValue(":datum", $datum);
        $stmt->bindValue(":heendatum", $heendatum);
        $stmt->bindValue(":aantaldagen", $aantaldagen);
        $stmt->bindValue(":aantalpersonen", $aantalpersonen);
        $stmt->execute();
        $dbh = null;
    }

       
   
}
