<?php

require_once("dbconfig.php");
require_once("hotels.php");

class Pakket {

/* 
 * We maken een Object "Pakket", met alle relevante info van het reispakket. Hierin zit: het reisid, de omschrijving van het pakket, de bestemming, reistype, hotel en pri
*/ 
    private $reisid;
    private $omschrijving;
    private $reistype;
    private $bestemmingsid;
    private $stad;
    private $land;
    public $hotelid;
    private $prijs;


    public function __construct($reisid = null, $omschrijving = null, $reistype = null, $bestemmingsid = null, $stad = null, $land = null, $hotelid = null, $prijs=null)
    {
        $this->reisid = $reisid;
        $this->omschrijving = $omschrijving;
        $this->reistype = $reistype;
        $this->bestemmingsid = $bestemmingsid;
        $this->stad = $stad;
        $this->land = $land;
        $this->hotelid = $hotelid;
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

  
    public function getBestemmingsid()
    {
        return $this->bestemmingsid;
    }

  
    public function setBestemmingsid($bestemmingsid)
    {
        $this->bestemmingsid = $bestemmingsid;

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

    
    public function getHotelid()
    {
        return $this->hotelid;
    }

    
    public function setHotelid($hotelid)
    {
        $this->hotelid = $hotelid;

    
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
        $stmt = $dbh->prepare("SELECT reisNummer, reizen.bestemmingsId, reisType, stad, land, hotel.hotelId, hotelNaam, reisOmschrijving, prijs FROM reizen
        INNER JOIN bestemmingen on bestemmingen.bestemmingsId = reizen.bestemmingsid
        INNER JOIN reistypes on reistypes.reisTypeId = reizen.reisTypeId
        INNER JOIN hotel on hotel.HotelId = reizen.HotelId
        WHERE reisNummer = :id");
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        $resultSet = $stmt->fetch(PDO::FETCH_ASSOC);
        $hotelObj = new Hotels($resultSet["hotelId"],$resultSet["hotelNaam"], null, null);
        $pakketObj = new Pakket($id, $resultSet["reisOmschrijving"], $resultSet["reisType"], $resultSet["bestemmingsId"], $resultSet["stad"], $resultSet["land"], $hotelObj, $resultSet["prijs"]);
       

        $dbh = null;
        return $pakketObj;
    }


public function getAllePakketten()

{
    $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USER, DBConfig::$DB_PASSWORD);
    $resultSet = $dbh->query("SELECT reisNummer, reizen.bestemmingsId, reisType, stad, land, reizen.hotelId, hotelNaam, reisOmschrijving, prijs FROM reizen 
    INNER JOIN bestemmingen on bestemmingen.bestemmingsId = reizen.bestemmingsId
    INNER JOIN reistypes on reistypes.reisTypeId = reizen.reisTypeId
    INNER JOIN hotel on hotel.hotelId = reizen.hotelId");
 
    $pakkettenLijst = array();

    foreach ($resultSet as $pakket) {
       
        $hotelObj = new Hotels($pakket["hotelId"], $pakket["hotelNaam"], null, null);
       
        $pakketObj = new Pakket($pakket["reisNummer"], $pakket["reisOmschrijving"], $pakket["reisType"], $pakket["bestemmingsId"], $pakket["stad"], $pakket["land"], $hotelObj, $pakket["prijs"]);
     
        array_push($pakkettenLijst, $pakketObj);
    }

    $dbh = null;
    return $pakkettenLijst;
    
}


public function getPakketByReisTypeAndBestemmingsId($reistype,$bestemmingsid)

//* functie die de klant toelaat om op reistype & bestemming te zoeken */
{

        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USER, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare("SELECT reisNummer, reizen.bestemmingsId, reisType, stad, land, reizen.hotelId, hotelNaam, reisOmschrijving, prijs from reizen 
        INNER JOIN reistypes on reistypes.reisTypeId = reizen.reisTypeId
        INNER JOIN bestemmingen on bestemmingen.bestemmingsId = reizen.bestemmingsId
        INNER JOIN hotel on hotel.hotelId = reizen.hotelId
        WHERE reizen.bestemmingsId =:bestemmingsid AND reisType = :reistype");
        $stmt->bindValue(":bestemmingsid", $bestemmingsid);
        $stmt->bindValue(":reistype", $reistype);
        $stmt->execute();
        $resultSet = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pakkettenLijst = array();

        foreach ($resultSet as $pakket) {
            $hotelObj = new Hotels($pakket["hotelId"],$pakket["hotelNaam"], null, null,);
            $pakketObj = new Pakket($pakket["reisNummer"], $pakket["reisOmschrijving"], $pakket["reisType"], $pakket["bestemmingsId"], $pakket["stad"],$pakket["land"], $hotelObj, $pakket["prijs"]);
            array_push($pakkettenLijst, $pakketObj);
        }

        $dbh = null;
        return $pakkettenLijst;
    }




public function getAlleReisTypes() {
 
//* Ophalen van alle reistypes in de databank */

$dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USER, DBConfig::$DB_PASSWORD);
$resultSet = $dbh->query("SELECT DISTINCT reisType FROM reizen
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
        $stmt = $dbh->prepare("SELECT reizen.reisNummer, bestemmingen.bestemmingsId, reisType, stad, land, reizen.hotelId, reisOmschrijving, prijs from 
        reizen
        INNER JOIN bestemmingen on bestemmingen.bestemmingsId = reizen.bestemmingsId
        INNER JOIN hotel on hotel.hotelId = reizen.hotelId
        INNER JOIN reisTypes on reisTypes.reisTypeId = reizen.reisTypeId
        LEFT JOIN klantenreviews on klantenreviews.reisNummer = klantenreviews.reisNummer
        LEFT JOIN reviews on reviews.reviewId = klantenreviews.reviewId
        WHERE reisType =:reistype 
        order by reviewScore desc
        LIMIT 0, 2;"
        );
 
        $stmt->bindValue(":reistype", $reistype);
        $stmt->execute();
        $resultSet = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pakkettenLijst = array();

        foreach ($resultSet as $pakket) {
            $hotelObj = new Hotels($resultSet["hotelId"],$resultSet["hotelNaam"], 0, 0, );
            $pakketObj = new Pakket($pakket["reisNummer"], $pakket["reisOmschrijving"], $pakket["reisType"], $pakket["bestemmingsId"], $pakket["stad"],$pakket["land"], $hotelObj, $pakket["prijs"]);
            array_push($pakkettenLijst, $pakketObj);
        }

        $dbh = null;
        return $pakkettenLijst;
    }
    public function deletePakketByID($id) {
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USER, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare("DELETE FROM boekingen  WHERE boekingsId = :id");
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        $dbh = null;
    }
    public function updatePakket()
    {
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USER, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare("UPDATE reis SET  luchthaven = :luchthaven, bestemmingsId = :bestemmingsid ,  hotelId = :hotelid, reisTypeId = :reistypeid, reisOmschrijving = :reisomschrijving, prijs = :prijs WHERE reisNummer = :reisid");

        
        $stmt->bindValue(":luchthaven", $this->luchthaven);
        $stmt->bindValue(":bestemmingsid", $this->bestemmingsid);
        $stmt->bindValue(":hotelId", $this->hotelid);
        $stmt->bindValue(":reistypeid", $this->reistype);
        $stmt->bindValue(":reisomschrijving", $this->omschrijving);
        $stmt->bindValue(":reisid", $this->reisid);
        $stmt->bindValue(":prijs", $this->prijs);
        $stmt->execute();
        $dbh = null;
    }

}