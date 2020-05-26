<?php

require_once("exceptions.php");
require_once("dbconfig.php");

class Klanten
{

    private $id;
    private $naam;
    private $adres;
    private $plaats;
    private $geboortedatum;
    private $email;
    private $wachtwoord;
    

    public function __construct($cid = null, $cnaam=null, $cadres=null, $cplaats=null,$cgeboortedatum=null, $cemail = null, $cwachtwoord = null)
    {
        $this->id = $cid;
        $this->naam = $cnaam;
        $this->adres = $cadres;
        $this->plaats = $cplaats;
        $this->geboortedatum = $cgeboortedatum;
        $this->email = $cemail;
        $this->wachtwoord = $cwachtwoord;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNaam()
    {
        return $this->naam;
    }
    public function getAdres()
    {
        return $this->adres;
    }
    public function getPlaats()
    {
        return $this->plaats;
    }
    public function getGeboortedatum()
    {
        return $this->geboortedatum;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getWachtwoord()
    {
        return $this->wachtwoord;
    }
    public function setNaam($snaam)
    {

        $this->naam = $snaam;
    }
    public function setAdres($sadres)
    {

        $this->adres = $sadres;
    }

    public function setPlaats($splaats)
    {

        $this->plaats = $splaats;
    }
    public function setGeboorteDatum($sgeboortedatum)
    {
        $date = new DateTime(null, new DateTimeZone('Europe/Brussels'));
        $sgeboortedatum=$date->format("Y-m-d");
        $this->geboortedatum = $sgeboortedatum;
    }
    public function setEmail($semail)
    {
        if (!filter_var($semail, FILTER_VALIDATE_EMAIL)) {
            throw new OngeldigEmailadresException();
        }
        $this->email = $semail;
    }
   
    public function setWachtwoord($swachtwoord, $swachtwoordHerhaal)
    {
        if ($swachtwoord !== $swachtwoordHerhaal) {
            throw new WachtwoordenKomenNietOvereenException();
        }
        $this->wachtwoord = password_hash($swachtwoord, PASSWORD_DEFAULT);
    }

    public function emailAlInGebruik()
    {
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USER, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare("SELECT * FROM klanten WHERE emailAdres = :email");
        $stmt->bindValue(":email", $this->email);
        $stmt->execute();
        $rowCount = $stmt->rowCount();
        $dbh = null;
        return $rowCount;
    }

    public function registreer()
    {
        $rowCount = $this->emailAlInGebruik();
        if ($rowCount > 0) {
            throw new GebruikerBestaatAlException();
        }
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USER, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare("INSERT INTO klanten (klantNaam, klantAdres, plaatsId, geboortedatum, emailAdres, wachtwoord) VALUES (:klantNaam, :klantAdres, :plaatsId, :geboortedatum, :emailAdres, :wachtwoord)");
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
    //  login
    public function login()
    {
        $rowCount = $this->emailAlInGebruik();
        if ($rowCount == 0) {
            throw new GebruikerBestaatNietException();
        }

        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USER, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare("SELECT klantNummer,klantNaam,wachtwoord, emailAdres FROM klanten WHERE emailAdres = :emailAdres");
        $stmt->bindValue(":emailAdres", $this->email);
        $stmt->execute();
        $resultSet = $stmt->fetch(PDO::FETCH_ASSOC);

        $isWachtwoordCorrect = password_verify($this->wachtwoord, $resultSet["wachtwoord"]);

        if (!$isWachtwoordCorrect) {
            throw new WachtwoordIncorrectException();
        }
        $this->id = $resultSet["klantNummer"];
        $this->naam = $resultSet["klantNaam"];
        $this->email = $resultSet["emailAdres"];
        
        $dbh = null;
        var_dump($this);
        return $this;
    }

    public function email() {
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USER, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare("SELECT * FROM klanten WHERE emailAdres = :email");
        $stmt->bindValue(":email", $this->email);
        $stmt->execute();
        $resultSet = $stmt->fetch(PDO::FETCH_ASSOC);
        $dbh = null;
        return $resultSet;
    }
}
