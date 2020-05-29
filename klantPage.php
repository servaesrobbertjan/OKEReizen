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
$reisNummer="";
$gebruiker = unserialize($_SESSION["gebruiker"]);
$klantNummer = $gebruiker->getId();
$klantnaam = $gebruiker->getNaam();

if(isset($_POST["reisNummer"])){
    $_SESSION["reisNummer"] = $_POST["reisNummer"];
    header("Location: reviewPage.php");
    exit;
    

}

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
                                        
                    echo   "<b>" . "Reisnummer: " . "</b>" . $reis->getreisId() ."<br>".
                     "<b>" . "BoekingId: " . "</b>" . $reis->getBoekingsid() ."<br>" .
                        "<b>" . "BoekingsDatum: " . "</b>" . $reis->getboekingsDatum(). "<br>".
                        "<b>" . "HeenDatum: " . "</b>" . $reis->getHeendatum() . "<br>".
                        "<b>" . "Land: " . "</b>" . $reis->getland() . "<br>".
                        "<b>" . "Stad: " . "</b>" . $reis->getstad() . "<br>" . 
                        "<b>" . "Hotel: " . "</b>" . $reis->gethotelnaam() . "<br>" .
                        "<b>". "Aantal personen: " . "</b>" . $reis->getaantalPersonen()  . "<br>" .
                        "<b>" . "Totale prijs betaald: € " . "</b>" . $reis->totaalPrijs() . "<br>";
                
                    

                    ?>
                    <br>
                    <form action="<?php echo htmlentities($_SERVER["PHP_SELF"]); ?>" method="post">
                    <input type="hidden" name="reisNummer" value="<?php echo $reis->getreisId() ?>">     
                    <input type="submit" value="Review">
                    </fieldset>
            </ul>
        </div>

        

        <?php

        }
// start van de footer html
require_once("footer.php");
?>