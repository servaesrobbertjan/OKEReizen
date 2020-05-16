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
            private $stad;
            private $land;
            private $hotelnaam;
            private $prijs;
        
        
            public function __construct($omschrijving = null, $reistype = null, $stad = null, $land = null, $hotelnaam = null, $prijs=null)
            {
                $this->omschrijving = $omschrijving;
                $this->reistype = $reistype;
               
                $this->stad = $stad;
                $this->land = $land;
                $this->hotelnaam = $hotelnaam;
                $this->prijs = $prijs;
        
            }

}