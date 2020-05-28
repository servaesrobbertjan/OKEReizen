<?php 

session_start();
require_once("klanten.php");
require_once("boeking.php");

// Er is niemand ingelogd
if (!isset($_SESSION["gebruiker"])) {
    header("Location: index.php");
    exit;
}

// er is een gebruiker ingelogd

$gebruiker = unserialize($_SESSION["gebruiker"]);
$klantNummer = $gebruiker->getId();
$klantnaam = $gebruiker->getNaam();



// Start van de header html
require_once("header.php");

?>

<h1>OKEReizen Klanten-pagina</h1>
<h2><text>Welkom <?php 
echo $klantnaam; ?></text>
</h2>
<body>

        <H2>Jouw toekomstige OKEReizen</H2>
        
        <?php
        // haal alle toekomstige reizen van de ingelogde gebruiker op en toon deze

        $klantBoekingen = new boeking();
        $klantBoekingen = $klantBoekingen->toonToekomstigeReizen($klantNummer);

        foreach ($klantBoekingen as $reis) { ?>
<div class="plain">
            <ul>
                <fieldset>
                    <?php
                    
                    echo   "<b>" . "Reisnummer: " . "</b>" . $reis->getreisId() ."<br>".
                     "<b>" . "BoekingId: " . "</b>" . $reis->getBoekingsid() ."<br>" .
                        "<b>" . "BoekingsDatum: " . "</b>" . $reis->getboekingsDatum(). "<br>".
                        "<b>" . "HeenDatum: " . "</b>" . $reis->getHeendatum() . "<br>".
                        "<b>" . "Land: " . "</b>" . $reis->getland() . "<br>".
                        "<b>" . "Stad: " . "</b>" . $reis->getstad() . "<br>" . 
                        "<b>" . "Hotel: " . "</b>" . $reis->gethotelnaam() . "<br>" .
                        "<b>". "Aantal personen: " . "</b>" . $reis->getaantalPersonen()  . "<br>" .
                        "<text><b>" . "Totale prijs te betalen: € " . "</b>" . $reis->totaalPrijs() . "</text>" . "<br>";
                    ?>
                      </fieldset>
            </ul>

        </div>
            <?php

            }       
            ?>

        <H2>Jouw vorige OKEReizen</H2>

        <?php
        // haal alle vorige reizen van de ingelogde gebruiker op en toon deze

        $klantBoekingen = new boeking();
        $klantBoekingen = $klantBoekingen->toonVorigeReizen($klantNummer);

        foreach ($klantBoekingen as $reis) { ?>

<div class="plain">
            <ul>
                <fieldset>
                    <?php
<<<<<<< HEAD
                    echo   "<b>" . "Reisnummer: " . $reis->getreisId()."</b>"."<br>".
                     "<b>" . "BoekingId: " .  $reis->getBoekingsid() ."</b>" ."<br>" .
                        "<b>" . "boekingsDatum: " . $reis->getboekingsDatum() . "<b>" . "<br>".
                        "<b>" . "heenDatum: " . "</b>" . $reis->getHeendatum() . "<b>" . "<br>".
                        "<b>" . "land: " . "</b>" . $reis->getland() . "<b>" . "<br>".
                        "<b>" . "stad: " . "</b>" . $reis->getstad() . "<b>" . "<br>" . 
                        "<b>" . "hotel: " . "</b>" . $reis->gethotelnaam() . "<b>" . "<br>" .
                        "<b>" . "aantal personen: " . "</b>" . $reis->getaantalPersonen() . "<b>" . "<br>" .
                        "<b>" . "prijs: " . "</b>" . $reis->getprijs() . "<b>" . "<br>";

=======
                    
                    echo   "<b>" . "Reisnummer: " . "</b>" . $reis->getreisId() ."<br>".
                     "<b>" . "BoekingId: " . "</b>" . $reis->getBoekingsid() ."<br>" .
                        "<b>" . "BoekingsDatum: " . "</b>" . $reis->getboekingsDatum(). "<br>".
                        "<b>" . "HeenDatum: " . "</b>" . $reis->getHeendatum() . "<br>".
                        "<b>" . "Land: " . "</b>" . $reis->getland() . "<br>".
                        "<b>" . "Stad: " . "</b>" . $reis->getstad() . "<br>" . 
                        "<b>" . "Hotel: " . "</b>" . $reis->gethotelnaam() . "<br>" .
                        "<b>". "Aantal personen: " . "</b>" . $reis->getaantalPersonen()  . "<br>" .
                        "<b>" . "Totale prijs betaald: € " . "</b>" . $reis->totaalPrijs() . "<br>";
>>>>>>> 3af15577f3495748cb4919a1a3f08cbc3d00864d
                        $reisNummer = $reis->getreisId();
                        
                        if(isset($_POST["ReviewOK"])&& !empty($reisNummer)){
                            $_SESSION["reisNummer"] = $reisNummer;
                            
                        }
var_dump($_SESSION["reisNummer"]);
var_dump($reisNummer);
                    ?>
                    <br>
                    <form action="reviewPage.php" method="post">
                    <input type="submit" name="ReviewOk" value="Review">
                    </fieldset>
            </ul>
        </div>

        

        <?php

        }
// start van de footer html
require_once("footer.php");
?>