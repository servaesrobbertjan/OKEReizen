<?php

require_once("dbconfig.php");

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
    private $hotelnaam;
    private $prijs;
    private $klantNummer;


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
        $stmt->bindValue(":aantaldagen", $this->aantaldagen);
        $stmt->bindValue(":aantalpersonen", $this->aantalpersonen);
        $stmt->execute();
        $laatsteNewId = $dbh->lastInsertId();
        $dbh = null;


        if($laatsteNewId != NULL){
            $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBconfig::$DB_USER, DBconfig::$DB_PASSWORD);
            $stmt = $dbh->prepare("INSERT INTO klantenreizen (klantNummer, reisNummer, boekingsId) VALUES (:klantnummer, :reisnummer, :boekingsid)");
            $stmt->bindValue(":klantnummer", $this->klantnummer);
            $stmt->bindValue(":reisnummer", $this->reisnummer);
            $stmt->bindValue(":boekingsid", $this->boekingsid);
            $stmt->execute();
            $dbh = null;}

        $this->id = $laatsteNewId;
        return $this;
    }

    public function getBoekingbyId($id)
    {
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBconfig::$DB_USER, DBconfig::$DB_PASSWORD);
        $stmt = $dbh->prepare("SELECT reizen.boekingsId,reizen.reisOmschrijving, reistypes.reisType, boekingen.boekingsDatum, boekingen.heenDatum, boekingen.aantalDagen,boekingen.aantalPersonen, bestemmingen.stad, bestemmingen.land, hotel.hotelNaam, reizen.prijs 
        FROM reizen INNER JOIN boekingen on reizen.boekingsId = boekingen.boekingsId INNER JOIN bestemmingen on bestemmingen.bestemmingsId = reizen.bestemmingsId INNER JOIN hotel on hotel.hotelId = reizen.hotelId 
        INNER JOIN klantenreizen on klantenreizen.reisNummer = reizen.reisNummer INNER JOIN reistypes on reistypes.reisTypeId = reizen.reisTypeId INNER JOIN klanten ON klanten.klantNummer = klantenreizen.klantNummer 
        WHERE boekingen.boekingsId = :id");
        $stmt->bindValue(":id", $id);
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
        $stmt = $dbh->prepare("SELECT reizen.reisNummer, reizen.boekingsId,reizen.reisOmschrijving, reistypes.reisType, boekingen.boekingsDatum, boekingen.heenDatum, boekingen.aantalDagen,boekingen.aantalPersonen, bestemmingen.stad, bestemmingen.land, hotel.hotelNaam, reizen.prijs 
        FROM reizen INNER JOIN boekingen on reizen.boekingsId = boekingen.boekingsId INNER JOIN bestemmingen on bestemmingen.bestemmingsId = reizen.bestemmingsId INNER JOIN hotel on hotel.hotelId = reizen.hotelId 
        INNER JOIN klantenreizen on klantenreizen.reisNummer = reizen.reisNummer INNER JOIN reistypes on reistypes.reisTypeId = reizen.reisTypeId INNER JOIN klanten ON klanten.klantNummer = klantenreizen.klantNummer 
        WHERE boekingen.boekingsDatum > CURRENT_DATE AND klanten.klantNummer = :klantNummer");
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
        $stmt = $dbh->prepare("SELECT reizen.reisNummer, reizen.boekingsId,reizen.reisOmschrijving, reistypes.reisType, boekingen.boekingsDatum, boekingen.heenDatum, boekingen.aantalDagen,boekingen.aantalPersonen, bestemmingen.stad, bestemmingen.land, hotel.hotelNaam, reizen.prijs 
        FROM reizen INNER JOIN boekingen on reizen.boekingsId = boekingen.boekingsId INNER JOIN bestemmingen on bestemmingen.bestemmingsId = reizen.bestemmingsId INNER JOIN hotel on hotel.hotelId = reizen.hotelId 
        INNER JOIN klantenreizen on klantenreizen.reisNummer = reizen.reisNummer INNER JOIN reistypes on reistypes.reisTypeId = reizen.reisTypeId INNER JOIN klanten ON klanten.klantNummer = klantenreizen.klantNummer 
        WHERE boekingen.boekingsDatum < CURRENT_DATE AND klanten.klantNummer = :klantNummer");
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
