<?php 

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
        
        
            public function __construct($reisid = null, $omschrijving = null, $reistype = null, $boekingsdatum=null, $aantalDagen = null, $aantalPersonen = null, $stad = null, $land = null, $hotelnaam = null, $prijs=null)
            {
                $this->$reisid = $reisid;
                $this->omschrijving = $omschrijving;
                $this->reistype = $reistype;
                $this->boekingsdatum = $boekingsdatum;
                $this->aantalDagen = $aantalDagen;
                $this->aantalPersonen = $aantalPersonen;
                $this->stad = $stad;
                $this->land = $land;
                $this->hotelnaam = $hotelnaam;
                $this->prijs = $prijs;
        
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

            public function totaalPrijs()
            {
                $totaalPrijs = $this->aantalDagen * $this->aantalPersonen * $this->prijs;
                return $totaalPrijs;
            }

            public function toonReizen($klantNummer)
            {
            $dbh = new PDO(DBconfig::$DB_CONNSTRING, DBconfig::$DB_USER, DBconfig::$DB_PASSWORD);
            $resultset = $dbh->query("SELECT DISTINCT reizen.reisNummer, reizen.reisOmschrijving, reizen.prijs, boekingen.boekingsDatum, boekingen.aantalDagen, boekingen.aantalPersonen, bestemmingen.stad, bestemmingen.land
            FROM reizen LEFT JOIN boekingen on reizen.boekingsId = boekingen.boekingsId left JOIN bestemmingen on bestemmingen.bestemmingsId
            left join hotel on hotel.hotelId left join klantenreizen on klantenreizen.reisNummer left join klanten on klanten.klantNummer
            where boekingen.boekingsDatum < CURRENT_DATE");
            $reizenlijst = array();
            foreach ($resultset as $reis) {
            $reisobj = new review($reis["reisnummer"], $reis["reisOmschrijving"], $reis["prijs"], $reis["boekingsdatum"], $reis["aantalDagen"], $reis["aantalPersonen"], $reis["stad"], $reis["land"]);
            array_push($reizenlijst, $reisobj);
            }
            $dbh = null;
            return $reizenlijst;
            }
}