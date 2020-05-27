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
<h2>Welkom <?php 
echo $klantnaam; ?></h2>

<body>

        <H2>Jouw toekomstige OKEReizen</H2>
        
        <?php
        // haal alle toekomstige reizen van de ingelogde gebruiker op en toon deze

        $klantBoekingen = new boeking();
        $klantBoekingen = $klantBoekingen->toonToekomstigeReizen($klantNummer);

        foreach ($klantBoekingen as $reis) { ?>

            <ul>
                <fieldset>
                    <?php
                    
                    echo   "<b>" . "Reisnummer: " . "</b>" . $reis->getreisId() . "<b>"."<br>".
                     "<b>" . "BoekingId: " . "</b>" . $reis->getBoekingsid() ."<br>" .
                        "<b>" . "boekingsDatum: " . "</b>" . $reis->getboekingsDatum() . "<b>" . "<br>".
                        "<b>" . "heenDatum: " . "</b>" . $reis->getHeendatum() . "<b>" . "<br>".
                        "<b>" . "land: " . "</b>" . $reis->getland() . "<b>" . "<br>".
                        "<b>" . "stad: " . "</b>" . $reis->getstad() . "<b>" . "<br>" . 
                        "<b>" . "hotel: " . "</b>" . $reis->gethotelnaam() . "<b>" . "<br>" .
                        "<b>" . "aantal personen: " . "</b>" . $reis->getaantalPersonen() . "<b>" . "<br>" .
                        "<b>" . "prijs: " . "</b>" . $reis->getprijs() . "<b>" . "<br>";
                    ?>
            </ul>
            <?php

            }       
            ?>

        <H2>Jouw vorige OKEReizen</H2>

        <?php
        // haal alle vorige reizen van de ingelogde gebruiker op en toon deze

        $klantBoekingen = new boeking();
        $klantBoekingen = $klantBoekingen->toonVorigeReizen($klantNummer);

        foreach ($klantBoekingen as $reis) { ?>

            <ul>
                <fieldset>
                    <?php
                    echo   "<b>" . "Reisnummer: " . "</b>" . $reis->getreisId() . "<b>"."<br>".
                     "<b>" . "BoekingId: " . "</b>" . $reis->getBoekingsid() ."<br>" .
                        "<b>" . "boekingsDatum: " . "</b>" . $reis->getboekingsDatum() . "<b>" . "<br>".
                        "<b>" . "heenDatum: " . "</b>" . $reis->getHeendatum() . "<b>" . "<br>".
                        "<b>" . "land: " . "</b>" . $reis->getland() . "<b>" . "<br>".
                        "<b>" . "stad: " . "</b>" . $reis->getstad() . "<b>" . "<br>" . 
                        "<b>" . "hotel: " . "</b>" . $reis->gethotelnaam() . "<b>" . "<br>" .
                        "<b>" . "aantal personen: " . "</b>" . $reis->getaantalPersonen() . "<b>" . "<br>" .
                        "<b>" . "prijs: " . "</b>" . $reis->getprijs() . "<b>" . "<br>";

                        $reisNummer = $reis->getreisId();
                        $land = $reis->getland();
                        $stad = $reis->getstad();
                        $hotel = $reis->gethotelnaam();

                        if(isset($_POST["ReviewOK"])&& !empty($reisNummer)){
                            $_SESSION["reisNummer"] = $reisNummer;
                            
                        }

                    ?>
                    <br>
                    <form action="/Eindproject_php/reviewPage.php" method="post">
                    <input type="submit" name="ReviewOk" value="Review">
            </ul>

        <?php

        }
// start van de footer html
require_once("footer.php");
?>