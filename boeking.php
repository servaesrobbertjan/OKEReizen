<?php

require_once("dbconfig.php");
require_once("klanten.php");
require_once("hotels.php");

class Boeking
{

    /* 
         
         * We maken een Object "Boeking", met alle relevante info van de boeking. Hierin zit: het object van het Pakket, het object van de User, object van de medereizigers, het aantal personen,  het aantal dagen,
         * object van het hotel, luchthaven, vertrekdatum, boekingsid
        
        */

    private $boekingsid;
    private $reisid;
    private $omschrijving;
    private $reistype;
    private $boekingsdatum;
    private $heendatum;
    private $aantalDagen;
    private $aantalPersonen;
    private $stad;
    private $land;
    public $hotelnaam;
    private $prijs;
    public $klantNummer;
    
    public function __construct($boekingsid = null, $reisid = null, $omschrijving = null, $reistype = null, $boekingsdatum = null, $heendatum = null, $aantalDagen = null, $aantalPersonen = null, $stad = null, $land = null, $hotelnaam = null, $prijs = null, $klantNummer = null)
    {
        $this->boekingsid = $boekingsid;
        $this->reisid = $reisid;
        $this->omschrijving = $omschrijving;
        $this->reistype = $reistype;
        $this->boekingsdatum = $boekingsdatum;
        $this->heendatum = $heendatum;
        $this->aantalDagen = $aantalDagen;
        $this->aantalPersonen = $aantalPersonen;
        $this->stad = $stad;
        $this->land = $land;
        $this->hotelnaam = $hotelnaam;
        $this->prijs = $prijs;
        $this->klantNummer = $klantNummer;
     }


   
     public function getBoekingsid()
        {
        return $this->boekingsid;
        }


    public function getreisId()
    {
        return $this->reisid;
    }

    public function getomschrijving()
    {
        return $this->omschrijving;
    }

    public function getreisType()
    {
        return $this->reistype;
    }

    public function getboekingsDatum()
    {
        return $this->boekingsdatum;
    }

    public function getHeendatum()
    {
        return $this->heendatum;

    }

    public function setHeendatum($heendatum)
    {
        $this->heendatum = $heendatum;

    }

    public function getaantalDagen()
    {
        return $this->aantalDagen;
    }

    public function getaantalPersonen()
    {
        return $this->aantalPersonen;
    }

    public function getstad()
    {
        return $this->stad;
    }

    public function getland()
    {
        return $this->land;
    }

    public function gethotelnaam()
    {
        return $this->hotelnaam;
    }

    public function getprijs()
    {
        return $this->prijs;
    }

    public function getklantNummer()
    {
        return $this->klantNummer;
    }

        public function totaalPrijs()
    {
        $totaalPrijs = $this->aantalDagen * $this->aantalPersonen * $this->prijs;
        return $totaalPrijs;
    }


    public function addBoeking()
    {
     
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBconfig::$DB_USER, DBconfig::$DB_PASSWORD);
        $stmt = $dbh->prepare("INSERT INTO boekingen (boekingsDatum, heenDatum, aantalDagen, aantalPersonen) VALUES (:boekingsdatum,:heendatum,:aantaldagen,:aantalpersonen)");
        $stmt->bindValue(":boekingsdatum", $this->boekingsdatum);
        $stmt->bindValue(":heendatum", $this->heendatum);
        $stmt->bindValue(":aantaldagen", $this->aantalDagen);
        $stmt->bindValue(":aantalpersonen", $this->aantalPersonen);
        $stmt->execute();
        $laatsteNewId = $dbh->lastInsertId();
        $dbh = null;
        $this->boekingsid = $laatsteNewId;  


        if($laatsteNewId != null){
            $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBconfig::$DB_USER, DBconfig::$DB_PASSWORD);
            $stmt = $dbh->prepare("INSERT INTO klantenreizen (klantNummer, reisNummer, boekingsId) VALUES (:klantnummer, :reisnummer, :boekingsid)");
            $stmt->bindValue(":klantnummer", $this->klantNummer);
            $stmt->bindValue(":reisnummer", $this->reisid);
            $stmt->bindValue(":boekingsid", $this->boekingsid);
            $stmt->execute();
            $dbh = null;
        }

        return $this;
    }

    public function getBoekingbyId($id)
    {
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBconfig::$DB_USER, DBconfig::$DB_PASSWORD);
        $stmt = $dbh->prepare("SELECT klanten.klantNummer, klantNaam, emailAdres, boekingen.boekingsId, boekingsDatum, 
        heenDatum, aantalDagen, aantalPersonen, reizen.reisNummer, reizen.bestemmingsId, reisType, stad, land, 
        reizen.hotelId, hotelNaam, reisOmschrijving, prijs FROM boekingen 
        LEFT JOIN klantenreizen on klantenreizen.boekingsId = boekingen.boekingsId
        INNER JOIN reizen on reizen.reisNummer = klantenreizen.reisNummer
        INNER JOIN klanten on klanten.klantNummer = klantenreizen.klantNummer 
        INNER JOIN bestemmingen on bestemmingen.bestemmingsId = reizen.bestemmingsId
        INNER JOIN reistypes on reistypes.reisTypeId = reizen.reisTypeId
        INNER JOIN hotel on hotel.hotelId = reizen.hotelId
        WHERE boekingen.boekingsId = :id");
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        $resultSet = $stmt->fetch(PDO::FETCH_ASSOC);
  
    
            $hotelObj = new Hotels($resultSet["hotelId"], $resultSet["hotelNaam"], null, null);
            $klantObj = new Klanten ($resultSet["klantNummer"], $resultSet["klantNaam"],null,null,null,$resultSet["emailAdres"],null); 

            $boekingobj = new Boeking(
                $resultSet["boekingsId"],
                $resultSet["reisNummer"],
                $resultSet["reisOmschrijving"],
                $resultSet["reisType"],
                $resultSet["boekingsDatum"],
                $resultSet["heenDatum"],
                $resultSet["aantalDagen"],
                $resultSet["aantalPersonen"],
                $resultSet["stad"],
                $resultSet["land"], 
                $hotelObj, 
                $resultSet["prijs"], 
                $klantObj
            );
     
        $dbh = null;
        return $boekingobj;
    }


    

    public function getAlleBoekingen()
    {
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USER, DBConfig::$DB_PASSWORD);
        $resultSet = $dbh->query("SELECT klanten.klantNummer, klantNaam, emailAdres, boekingen.boekingsId, boekingsDatum, 
        heenDatum, aantalDagen, aantalPersonen, reizen.reisNummer, reizen.bestemmingsId, reisType, stad, land, 
        reizen.hotelId, hotelNaam, reisOmschrijving, prijs FROM boekingen 
        LEFT JOIN klantenreizen on klantenreizen.boekingsId = boekingen.boekingsId
        INNER JOIN reizen on reizen.reisNummer = klantenreizen.reisNummer
        INNER JOIN klanten on klanten.klantNummer = klantenreizen.klantNummer 
        INNER JOIN bestemmingen on bestemmingen.bestemmingsId = reizen.bestemmingsId
        INNER JOIN reistypes on reistypes.reisTypeId = reizen.reisTypeId
        INNER JOIN hotel on hotel.hotelId = reizen.hotelId");
     
     if($resultSet){
        $pakkettenLijst = array();
    
        foreach ($resultSet as $pakket) {
           
            $hotelObj = new Hotels($pakket["hotelId"], $pakket["hotelNaam"], null, null);
            $klantObj = new Klanten ($pakket["klantNummer"], $pakket["klantNaam"],null,null,null,$pakket["emailAdres"],null); 

            $boekingObj= new Boeking (
                $pakket["boekingsId"],
                $pakket["reisNummer"],
                $pakket["reisOmschrijving"],
                $pakket["boekingsDatum"],
                $pakket["heenDatum"],
                $pakket["aantalDagen"],
                $pakket["aantalPersonen"],
                $pakket["stad"],
                $pakket["land"], 
                $hotelObj, 
                $pakket["prijs"], 
                $klantObj
            );
            array_push($pakkettenLijst, $boekingObj);
        }
     }
        $dbh = null;
        return $pakkettenLijst;
        
 

    }

    public function toonToekomstigeReizen($klantNummer)
    {
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBconfig::$DB_USER, DBconfig::$DB_PASSWORD);
        $stmt = $dbh->prepare("SELECT reizen.reisNummer, boekingen.boekingsId, reizen.reisOmschrijving, reistypes.reisType, boekingen.boekingsDatum, boekingen.heenDatum, boekingen.aantalDagen,boekingen.aantalPersonen, bestemmingen.stad, bestemmingen.land, hotel.hotelNaam, reizen.prijs 
        FROM boekingen 
        LEFT JOIN klantenreizen on klantenreizen.boekingsId = boekingen.boekingsId 
        INNER JOIN reizen on reizen.reisNummer = klantenreizen.reisNummer 
        INNER JOIN bestemmingen on bestemmingen.bestemmingsId = reizen.bestemmingsId 
        INNER JOIN hotel on hotel.hotelId = reizen.hotelId 
        
        INNER JOIN reistypes on reistypes.reisTypeId = reizen.reisTypeId 
        INNER JOIN klanten ON klanten.klantNummer = klantenreizen.klantNummer 
        WHERE boekingen.heenDatum >= CURRENT_DATE AND klanten.klantNummer = :klantNummer
        order by boekingen.heenDatum asc");
        $stmt->bindValue(":klantNummer", $klantNummer);
        $stmt->execute();
        $resultset = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $boekinglijst = array();
        foreach ($resultset as $boeking) {

            $boekingobj = new Boeking(
                $boeking["boekingsId"],
                $boeking["reisNummer"],
                $boeking["reisOmschrijving"],
                $boeking["reisType"],
                $boeking["boekingsDatum"],
                $boeking["heenDatum"],
                $boeking["aantalDagen"],
                $boeking["aantalPersonen"],
                $boeking["stad"],
                $boeking["land"],
                $boeking["hotelNaam"],
                $boeking["prijs"]
            );
            array_push($boekinglijst, $boekingobj);
        }
        $dbh = null;
        return $boekinglijst;
    }

    public function toonVorigeReizen($klantNummer)
    {
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBconfig::$DB_USER, DBconfig::$DB_PASSWORD);
        $stmt = $dbh->prepare("SELECT reizen.reisNummer, boekingen.boekingsId, reizen.reisOmschrijving, reistypes.reisType, boekingen.boekingsDatum, boekingen.heenDatum, boekingen.aantalDagen,boekingen.aantalPersonen, bestemmingen.stad, bestemmingen.land, hotel.hotelNaam, reizen.prijs 
        FROM boekingen 
        LEFT JOIN klantenreizen on klantenreizen.boekingsId = boekingen.boekingsId 
        LEFT JOIN reizen on reizen.reisNummer = klantenreizen.reisNummer 
        INNER JOIN bestemmingen on bestemmingen.bestemmingsId = reizen.bestemmingsId 
        INNER JOIN hotel on hotel.hotelId = reizen.hotelId 
        
        INNER JOIN reistypes on reistypes.reisTypeId = reizen.reisTypeId 
        INNER JOIN klanten ON klanten.klantNummer = klantenreizen.klantNummer 
        WHERE boekingen.heenDatum < CURRENT_DATE AND klanten.klantNummer = :klantNummer
        order by boekingen.heenDatum desc");
        $stmt->bindValue(":klantNummer", $klantNummer);
        $stmt->execute();
        $resultset = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $boekinglijst = array();
        foreach ($resultset as $boeking) {

            $boekingobj = new Boeking(
                $boeking["boekingsId"],
                $boeking["reisNummer"],
                $boeking["reisOmschrijving"],
                $boeking["reisType"],
                $boeking["boekingsDatum"],
                $boeking["heenDatum"],
                $boeking["aantalDagen"],
                $boeking["aantalPersonen"],
                $boeking["stad"],
                $boeking["land"],
                $boeking["hotelNaam"],
                $boeking["prijs"]
            );
            array_push($boekinglijst, $boekingobj);
        }
        $dbh = null;
        return $boekinglijst;
    }




        


      
}
