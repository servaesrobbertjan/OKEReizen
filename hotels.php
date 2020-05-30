<?php

require_once("exceptions.php");
require_once("dbconfig.php");
require_once("klanten.php");

class Hotels
{

    private $hotelId;
    private $hotelNaam;
    private $hotelTelefoon;
    private $hotelEmail;
   
    

    public function __construct($cid = null, $chotelNaam=null, $chotelTelefoon = null, $chotelEmail = null)
    {
        $this->hotelId = $cid;
        $this->hotelNaam = $chotelNaam;
        $this->hotelTelefoon = $chotelTelefoon;
        $this->hotelEmail = $chotelEmail;
        
    }

    public function getId()
    {
        return $this->hotelId;
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
    //alle hotels weergeven 
    public function getAllHotel()
    {
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USER, DBConfig::$DB_PASSWORD);
        $resultSet = $dbh->query("SELECT hotelId, hotelNaam, hotelTelefoon, hotelMail FROM hotel ORDER BY hotelId asc");

        $hotelLijst = array();

        foreach ($resultSet as $hotel) {
            $hotelObj = new Hotels($hotel["hotelId"], $hotel["hotelNaam"], $hotel["hotelTelefoon"], $hotel["hotelMail"]);
            array_push($hotelLijst,$hotelObj);
        }

        $dbh = null;
        return $hotelLijst;
    }

    public function getHotelByID($hotelId)
    {
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USER, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare("SELECT hotelNaam, hotelTelefoon, hotelMail FROM hotel WHERE hotelId = :hoteIid");
        $stmt->bindValue(":hotelId", $hotelId);
        $stmt->execute();
        $resultSet = $stmt->fetch(PDO::FETCH_ASSOC);
        $hotelObj = new Hotels($hotelId, $resultSet["hotelNaam"], $resultSet["hotelTelefoon"],$resultSet["hotelMail"]);
        $dbh = null;
        return $hotelObj;
    }
    // geval van een hotel toe te voegen dat al bestaat
    public function hotelBestaatAl()
    {
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USER, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare("SELECT * FROM hotel WHERE hotelNaam = :hotelNaam");
        $stmt->bindValue(":hotelNaam", $this->hotelNaam);
        $stmt->execute();
        $rowCount = $stmt->rowCount();
        $dbh = null;
        return $rowCount;
    }
   
   // al een functie geschreven om eventueel een hotel toe te voegen maar nog niet mogelijk op de site
    public function hotelToevoegen()
    {
        $rowCount = $this->hotelBestaatAl();
        if ($rowCount > 0) {
            throw new HotelBestaatAlException();
        }
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USER, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare("INSERT INTO hotel (hotelNaam, hotelTelefoon, hotelMail) VALUES (:hotelNaam, :hotelTelefoon,:hotelEmail)");
        $stmt->bindValue(":hotelNaam", $this->hotelNaam);
        $stmt->bindValue(":hotelTelefoon", $this->hotelTelefoon);
        $stmt->bindValue(":hotelEmail", $this->hotelEmail);
        $stmt->execute();
        $laatsteNieuweId = $dbh->lastInsertId();
        $dbh = null;
        $this->id = $laatsteNieuweId;
        return $this;
    }

    //al een functie geschreven om een hotel te verwijderen maar nog niet mogelijk op site 
    public function deleteHotelByID($hotelId) {
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USER, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare("DELETE FROM hotel WHERE hotelId = :id");
        $stmt->bindValue(":id", $hotelId);
        $stmt->execute();
        $dbh = null;
    }
    //al een functie geschreven om een hotel te up te daten maar nog niet mogelijk op site
    public function updateHotel()
    {
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USER, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare("UPDATE hotel SET hotelNaam = :hotelNaam, hotelTelefoon = :hotelTelefoon, hotelMail = :hotelEmail WHERE hotelId = :hotelId");
        $stmt->bindValue(":hotelId", $this->hotelId);
        $stmt->bindValue(":hotelNaam", $this->hotelNaam);
        $stmt->bindValue(":hotelTelefoon", $this->hotelTelefoon);
        $stmt->bindValue(":hotelEmail", $this->hotelEmail);
        $stmt->execute();
        $dbh = null;
    }
    //csv bestand uploaden 
    public function CSVImport($bestand)
    {
        if (empty($bestand)) {
            throw new GeenCSVOpgeladen();
        }

        $file = fopen($bestand, "r");

        $header = true;

        while (!feof($file)) {
            $line_of_text = fgetcsv($file);

            if ($header) {
                $header = false;
            }else {
                $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USER, DBConfig::$DB_PASSWORD);
                $stmt = $dbh->prepare("INSERT INTO hotel (hotelNaam, hotelTelefoon, hotelMail) VALUES (:hotelNaam, :hotelTelefoon, :hotelMail)");
                $stmt->bindValue(":hotelNaam", $line_of_text[0]);
                $stmt->bindValue(":hotelTelefoon", $line_of_text[1]);
                $stmt->bindValue(":hotelMail", $line_of_text[2]);
                $stmt->execute();
                
                $dbh = null;
            }
        }

        fclose($file);
    }
}