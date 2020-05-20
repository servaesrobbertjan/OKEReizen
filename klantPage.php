<?php 

session_start();
require_once("klanten.php");
require_once("boeking.php");


if (!isset($_SESSION["gebruiker"])) {
    // Er is niemand ingelogd
    header("Location: index.php");
    exit;
}

$gebruiker = unserialize($_SESSION["gebruiker"]);
//$klantNummer = 1;
$klantNummer = $gebruiker->getId();
$klantnaam = $gebruiker->getNaam();
// Start van de header html
require_once("header.php");
// Einde van de header html
?>

<h1>OKEReizen Klanten-pagina</h1>
<h2>Welkom <?php 
//echo $klantnaam; ?></h2>

<body>

        <H2>Jouw toekomstige OKEReizen</H2>

        <?php

        $klantBoekingen = new boeking();
        $klantBoekingen = $klantBoekingen->toonToekomstigeReizen($klantNummer);

        foreach ($klantBoekingen as $reis) { ?>

            <ul>
                <fieldset>
                    <?php
                    echo  "<b>" . "BoekingId: " . "</b>" . $reis->getreisId() ."<br>" .
                        "<b>" . "boekingsDatum: " . "</b>" . $reis->getboekingsDatum() . "<b>" . "<br>".
                        "<b>" . "land: " . "</b>" . $reis->getland() . "<b>" . "<br>".
                        "<b>" . "stad: " . "</b>" . $reis->getstad() . "<b>" . "<br>" . 
                        "<b>" . "hotel: " . "</b>" . $reis->gethotelnaam() . "<b>" . "<br>" .
                        "<b>" . "aantal personen: " . "</b>" . $reis->getaantalPersonen() . "<b>" . "<br>" .
                        "<b>" . "prijs: " . "</b>" . $reis->getprijs() . "<b>" . "<br>" 
                    ?>
            </ul>
            <?php

            }       
            ?>

        <H2>Jouw vorige OKEReizen</H2>

        <?php

        $klantBoekingen = new boeking();
        $klantBoekingen = $klantBoekingen->toonVorigeReizen($klantNummer);

        foreach ($klantBoekingen as $reis) { ?>

            <ul>
                <fieldset>
                    <?php
                    echo  "<b>" . "BoekingId: " . "</b>" . $reis->getreisId() ."<br>" .
                        "<b>" . "boekingsDatum: " . "</b>" . $reis->getboekingsDatum() . "<b>" . "<br>".
                        "<b>" . "land: " . "</b>" . $reis->getland() . "<b>" . "<br>".
                        "<b>" . "stad: " . "</b>" . $reis->getstad() . "<b>" . "<br>" . 
                        "<b>" . "hotel: " . "</b>" . $reis->gethotelnaam() . "<b>" . "<br>" .
                        "<b>" . "aantal personen: " . "</b>" . $reis->getaantalPersonen() . "<b>" . "<br>" .
                        "<b>" . "prijs: " . "</b>" . $reis->getprijs() . "<b>" . "<br>" 
                    ?>
            </ul>

        <?php

        }
// start van de footer html
require_once("footer.php");
?>