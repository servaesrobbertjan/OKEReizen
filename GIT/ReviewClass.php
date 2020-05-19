<?php
require_once("DBconfig.php");
require_once("Exception.php");

class review
{

    private $id;
    private $naam;
    private $review;
    private $score;
    private $datum;
    private $stad;
    private $land;
    private $klantnummer;
    private $reisnummer;

    public function __construct($cid = null, $cnaam = null, $creview = null, $cscore = null, $cdatum = null, $cstad = null, $cland = null, $klantnummer = null, $reisnummer = null)
    {
        $this->id = $cid;
        $this->naam = $cnaam;
        $this->review = $creview;
        $this->score = $cscore;
        $this->datum = $cdatum;
        $this->stad = $cstad;
        $this->land = $cland;
        $this->klantnummer = $klantnummer;
        $this->reisnummer = $reisnummer;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNaam()
    {
        return $this->naam;
    }

    public function getReview()
    {
        return $this->review;
    }

    public function getScore()
    {
        return $this->score;
    }

    public function getDatum()
    {
        return $this->datum;
    }

    public function getStad()
    {
        return $this->stad;
    }

    public function getLand()
    {
        return $this->land;
    }

    public function getklantnummer()
    {
        return $this->klantnummer;
    }

    public function getreisnummer()
    {
        return $this->reisnummer;
    }

    public function setReview($review)
    {
        if (strlen($review) > 250) {
            throw new reviewTeLang();
        }
    }

    public function reviewToevoegen()
    {
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBconfig::$DB_USER, DBconfig::$DB_PASSWORD);
        $stmt = $dbh->prepare("INSERT INTO reviews (reviewBericht,reviewScore) VALUES (:review,:score)");
        $stmt->bindValue(":review", $this->review);
        $stmt->bindValue(":score", $this->score);
        $stmt->execute();
        $laatsteNewId = $dbh->lastInsertId();
        $dbh = null;

        if($laatsteNewId != NULL){
        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBconfig::$DB_USER, DBconfig::$DB_PASSWORD);
        $stmt = $dbh->prepare("INSERT INTO klantenreviews (klantNummer, reisNummer) VALUES (:klantnummer, :reisNummer)");
        $stmt->bindValue(":klantnummer", $this->klantnummer);
        $stmt->bindValue(":reisnummer", $this->reisnummer);
        $stmt->execute();
        $dbh = null;}

        $this->id = $laatsteNewId;
        return $this;
    }

    public function toonReviews()
    {

        $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBconfig::$DB_USER, DBconfig::$DB_PASSWORD);
        $resultset = $dbh->query("SELECT DISTINCT klanten.klantnaam, bestemmingen.stad, bestemmingen.land, reviews.reviewBericht, reviews.reviewScore, reviews.reviewDatum 
        FROM reviews left JOIN klantenreviews on reviews.reviewId = klantenreviews.reviewId join klanten on klanten.klantNummer 
        join reizen on reizen.reisNummer join bestemmingen on bestemmingen.bestemmingsId ORDER BY reviews.reviewDatum DESC");
        $reviewlijst = array();
        foreach ($resultset as $bericht) {
            $reviewobj = new review(null, $bericht["klantnaam"], $bericht["reviewBericht"], $bericht["reviewScore"], $bericht["reviewDatum"], $bericht["stad"], $bericht["land"]);
            array_push($reviewlijst, $reviewobj);
        }
        $dbh = null;
        return $reviewlijst;
    }
}
