<?php

require_once("exceptions.php");
require_once("dbconfig.php");
require_once("klanten.php");

class Hotels
{

    private $id;
    private $hotelNaam;
    private $hotelTelefoon;
    private $hotelEmail;
   
    

    public function __construct($cid = null, $chotelNaam=null, $chotelTelefoon = null, $chotelEmail = null)
    {
        $this->id = $cid;
        $this->hotelNaam = $chotelNaam;
        $this->hotelTelefoon = $chotelTelefoon;
        $this->hotelEmail = $chotelEmail;
        
    }

    public function getId()
    {
        return $this->id;
    }

    public function getHotelNaam()
    {
        return $this->hotelNaam;
    }
    public function getHotelTelefoon()
    {
        return $this->hotelTelefoon;
    }
    public function getHotelEmail()
    {
        return $this->hotelEmail;
    }

    public function setHotelNaam($hotelNaam)
    {   
      
        $this->hotelNaam = $hotelNaam;
    
    }
    public function setHotelTelefoon($hotelTelefoon)
    {   
      
        $this->hotelTelefoon= $hotelTelefoon;
    
    }
    public function setHotelEmail($hotelEmail)
    {   
      
        $this->hotelEmail = $hotelEmail;
    
    }

    public function getAllHotel()
    {
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USER, DBConfig::$DB_PASSWORD);
        $resultSet = $dbh->query("SELECT id, hotelNaam, hotelTelefoon, hotelEmail FROM hotels ORDER BY ID asc");

        $hotelLijst = array();

        foreach ($resultSet as $hotel) {
            $hotelObj = new Bericht($hotel["id"], $hotel["hotelNaam"], $hotel["hotelTelefoon"], $hotel["hotelEmail"]);
            array_push($hotelLijst, $hotelObj);
        }

        $dbh = null;
        return $hotelLijst;
    }
   
    public function hotelBestaatAl()
    {
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USER, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare("SELECT * FROM hotels WHERE hotelNaam = :hotelNaam");
        $stmt->bindValue(":hotelNaam", $this->hotelNaam);
        $stmt->execute();
        $rowCount = $stmt->rowCount();
        $dbh = null;
        return $rowCount;
    }
   
   
    public function hotelToevoegen()
    {
        $rowCount = $this->hotelBestaatAl();
        if ($rowCount > 0) {
            throw new HotelBestaatAlException();
        }
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USER, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare("INSERT INTO hotels (hotelNaam, hotelTelefoon, hotelEmail) VALUES (:hotelNaam, :hotelTelefoon,:hotelEmail)");
        $stmt->bindValue(":klantNaam", $this->naam);
        $stmt->bindValue(":klantAdres", $this->adres);
        $stmt->bindValue(":plaatsId", $this->plaats);
        $stmt->bindValue(":geboortedatum", $this->geboortedatum);
        $stmt->bindValue(":emailAdres", $this->email);
        $stmt->bindValue(":wachtwoord", $this->wachtwoord);
        $stmt->execute();
        $laatsteNieuweId = $dbh->lastInsertId();
        $dbh = null;
        $this->id = $laatsteNieuweId;
        return $this;
    }
}