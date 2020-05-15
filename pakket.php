<?php

require_once("dbconfig.php");

class Pakket {


/* 
 
 * We maken een Object "Pakket", met alle relevante info van het reispakket. Hierin zit: het reisid, de omschrijving van het pakket, de bestemming, reistype, hotel en prijs

*/ 
private $reisid;
    private $omschrijving;

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

    public function getPakketById($id)
    {
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USER, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare("SELECT reisNummer, bestemmingsId, reisType, stad, land, hotelNaam, reisOmschrijving, prijs FROM bestemmingen
        INNER JOIN reizen on reizen.bestemmingsId = bestemmingen.bestemmingsid
        INNER JOIN reisTypes on reitypes.reisTypeId = reizen.reisTypeId
        WHERE reisNummer = :id");
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        $resultSet = $stmt->fetch(PDO::FETCH_ASSOC);

        $pakketObj = new Pakket($id, $resultSet["reisOmschrijving"], $resultSet["reisType"], $resultSet["stad"],$resultSet["land"], 0,  $resultSet["hotelNaam"], $resultSet["prijs"]);

        $dbh = null;
        return $pakketObj;
    }


public function getAllePakketten()

{
    $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USER, DBConfig::$DB_PASSWORD);
    $resultSet = $dbh->query("SELECT reisNummer, reisType, stad, land, hotelNaam, reisOmschrijving, prijs FROM bestemmingen
    INNER JOIN reizen on reizen.bestemmingsId = bestemmingen.bestemmingsId
    INNER JOIN reisTypes on reistypes.reisTypeId = reizen.reisTypeId
    INNER JOIN hotel on hotel.hotelId = reizen.hotelId
    order by stad asc
    ");

    $pakkettenLijst = array();

    foreach ($resultSet as $pakket) {
        $pakketObj = new Pakket($pakket["reisNummer"], $pakket["reisOmschrijving"], $pakket["reisType"], $pakket["stad"], $pakket["land"], 0, $pakket["hotelNaam"], $resultSet["prijs"]);
        array_push($pakkettenLijst, $pakketObj);
    }

    $dbh = null;
    return $pakkettenLijst;
    
}


public function getPakketByReisTypeAndBestemming($reistype,$bestemming)

//* functie die de klant toelaat om op reistype & bestemming te zoeken */
{

        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USER, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare("SELECT reisNummer, bestemmingdId, reisType, stad, land hotelNaam, reisOmschrijving, prijs from reizen 
        INNER JOIN reisTypes on reisTypes.reisTypeId = reizen.reisTypeId
        INNER JOIN hotel on hotel.hotelId = reizen.hotelId
        WHERE bestemming =:bestemming AND reisType = :reistype ");
        $stmt->bindValue(":bestemming", $bestemming);
        $stmt->bindValue(":reistype", $reistype);
        $stmt->execute();
        $resultSet = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pakkettenLijst = array();

        foreach ($resultSet as $pakket) {
            $pakketObj = new Pakket($pakket["reisNummer"], $pakket["reisOmschrijving"], $pakket["reisType"], $pakket["stad"],$pakket["land"], 0,  $pakket["hotelNaam"], $pakket["prijs"]);
            array_push($pakkettenLijst, $pakketObj);
        }

        $dbh = null;
        return $pakkettenLijst;
    }




public function getAlleReisTypes() {
 
//* Ophalen van alle reistypes in de databank */

$dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USER, DBConfig::$DB_PASSWORD);
$resultSet = $dbh->query("SELECT reisType FROM reizen
     INNER JOIN reistypes on reistypes.reisTypeId = reizen.reisTypeId");

$reisTypeLijst = array();

foreach ($resultSet as $type) {
    $reisTypeObj = new Pakket($type["reisType"]);
    array_push($reistypeLijst, $reisTypeObj);
}

$dbh = null;
return $reisTypeLijst;

}


public function getPakketByReisTypeWithBestReviewScore($reistype)

//* Per reistype worden de drie beste pakketten getoond */

{

        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USER, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare("SELECT reizen.reisNummer, bestemmingen.bestemmingsId, reisType, stad, hotelNaam, reisOmschrijving, prijs from 
        reizen
        INNER JOIN bestemmingen on bestemmingen.bestemmingsId = reizen.bestemmingsId
        INNER JOIN hotel on hotel.hotelId = reizen.hotelId
        INNER JOIN klantenreviews on klantenreviews.reisNummer = klantenreviews.reisNummer
        INNER JOIN reviews on reviews.reviewId = klantenreviews.reviewId
        WHERE reisType =:reistype 
        order by reviewScore desc
        fetch first 3 rows only"
        );
 
        $stmt->bindValue(":reistype", $reistype);
        $stmt->execute();
        $resultSet = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pakkettenLijst = array();

        foreach ($resultSet as $pakket) {
            $pakketObj = new Pakket($pakket["reisNummer"], $pakket["reisOmschrijving"], $pakket["reisType"], $pakket["stad"],$pakket["land"], 0,  $pakket["hotelNaam"], $pakket["prijs"]);
            array_push($pakkettenLijst, $pakketObj);
        }

        $dbh = null;
        return $pakkettenLijst;
    }


}