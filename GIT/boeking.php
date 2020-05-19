<?php

require_once("DBConfig.php");

class Boeking
{

    /* 
         
         * We maken een Object "Boeking", met alle relevante info van de boeking. Hierin zit: het object van het Pakket, het object van de User, object van de medereizigers, het aantal personen,  het aantal dagen,
         * object van het hotel, luchthaven, vertrekdatum, boekingsid
        
        */

    private $reisid;
    private $omschrijving;
    private $reistype;
    private $boekingsdatum;
    private $aantalDagen;
    private $aantalPersonen;
    private $stad;
    private $land;
    private $hotelnaam;
    private $prijs;
    private $klantNummer;


    public function __construct($reisid = null, $omschrijving = null, $reistype = null, $boekingsdatum = null, $aantalDagen = null, $aantalPersonen = null, $stad = null, $land = null, $hotelnaam = null, $prijs = null, $klantNummer = null)
    {
        $this->reisid = $reisid;
        $this->omschrijving = $omschrijving;
        $this->reistype = $reistype;
        $this->boekingsdatum = $boekingsdatum;
        $this->aantalDagen = $aantalDagen;
        $this->aantalPersonen = $aantalPersonen;
        $this->stad = $stad;
        $this->land = $land;
        $this->hotelnaam = $hotelnaam;
        $this->prijs = $prijs;
        $this->klantNummer = $klantNummer;
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

    public function toonToekomstigeReizen($klantNummer)
    {
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBconfig::$DB_USER, DBconfig::$DB_PASSWORD);
        $stmt = $dbh->prepare("SELECT reizen.boekingsId,reizen.reisOmschrijving, reistypes.reisType, boekingen.boekingsDatum, boekingen.aantalDagen,boekingen.aantalPersonen, bestemmingen.stad, bestemmingen.land, hotel.hotelNaam, reizen.prijs 
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
                $boeking["reisOmschrijving"],
                $boeking["reisType"],
                $boeking["boekingsDatum"],
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
        $stmt = $dbh->prepare("SELECT reizen.boekingsId,reizen.reisOmschrijving, reistypes.reisType, boekingen.boekingsDatum, boekingen.aantalDagen,boekingen.aantalPersonen, bestemmingen.stad, bestemmingen.land, hotel.hotelNaam, reizen.prijs 
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
                $boeking["reisOmschrijving"],
                $boeking["reisType"],
                $boeking["boekingsDatum"],
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
