<?php
session_start();
require_once("header.php");
require_once("boeking.php");
require_once("pakket.php");
require_once("exceptions.php");


$error = "";
$gekozenpakket = "";
$aantalpersonen = "";
$heendatum = "";
$aantaldagen = "";

/*Datums bepalen */

$today = new DateTime(null, new DateTimeZone('Europe/Brussels'));
$vandaag = $today->format("Y-m-d");

$tomorrow = $today->modify('+1 day');
$morgen = $tomorrow->format("Y-m-d");

$limit = $tomorrow->modify('+730 day');
$limiet = $limit->format("Y-m-d");


/* Indien niet ingelogd, eerst laten inloggen */

if (!isset($_SESSION["gebruiker"])) {
    header("Location: login.php");
    exit;
}


$pakket = new Pakket();


/* Id van het pakket ophalen uit session */

if (isset($_SESSION["gekozenreis"])) {
    $allepakketten = $pakket->getAllePakketten();
    $gekozenpakket = $pakket->getPakketById($_SESSION["gekozenreis"]);
} else {

    /* indien id niet gekend, terug naar zoekpagina of index */

    if (isset($_SESSION["zoekresultaat"])) {
        header("Location: zoekresultaat.php");
        exit;
    } else {
        if (!isset($_SESSION["zoekresultaat"])) {
            header("Location: index.php");
            exit;
        }
    }
}


/* controle op invoervelden */

if (isset($_POST["submitKnop"])) {

    if ($_POST["startreis"] < $morgen) {

        $error .= 'De vertrekdatum kan niet in het verleden liggen. ';
    }

    if ($_POST["startreis"] > $limiet) {

        $error .= 'Deze datum is nog niet beschikbaar om te boeken. ';
    }

    if (empty($_POST["personen"])) {

        $error .= 'U moeten een aantal personen opgeven';
    }

    if ($_POST["personen"] < 1 || $_POST["personen"] > 10) {

        $error .= 'Het minimum aantal personen is 1. Het maximum aantal personen is 10. ';
    }

    if (empty($_POST["dagen"])) {

        $error .= 'U moet een aantal dagen opgeven';
    }
}


/* aanmaken en opvullen van boekingsobject en wegschrijven naar de database */


if (isset($_POST["submitKnop"])) {


    if ($error == "" && isset($_SESSION["gebruiker"])){ 

        $gebruiker = unserialize($_SESSION["gebruiker"]);
        $klantnummer = $gebruiker->getId();
        $reisnummer = $gekozenpakket->getReisId();
        $omschrijving = $gekozenpakket->getOmschrijving();
        $reistype = $gekozenpakket->getReistype();
        $boekingsdatum = $vandaag;
        $heendatum = $_POST["startreis"];
        $aantaldagen = $_POST["dagen"];
        $aantalpersonen = $_POST["personen"];
        $stad = $gekozenpakket->getStad();
        $land = $gekozenpakket->getLand();
        $hotelnaam = $gekozenpakket->hotelid->getHotelnaam();
        $prijs = $gekozenpakket->getPrijs();


        $boekingObj = new Boeking(null, $reisnummer, $omschrijving, $reistype, $boekingsdatum, $heendatum, $aantaldagen, $aantalpersonen, $stad, $land, $hotelnaam, $prijs, $klantnummer);

        $boeking = $boekingObj->addBoeking();
$boekingsid = $boeking->getBoekingsid();
 
        $_SESSION["boekingsid"] = $boekingsid;


        if (!empty($_SESSION["boekingsid"]) && $error == ""){

      header("Location: boekingsinfo.php");
      exit;

        }
    }
    
}




?>


<h2> Uw gekozen pakket </h2>
<div class="plain">
<div class=pakket>

    

<!--Kijken of er een gekozenpakket is-->
<?php


if ($gekozenpakket) { ?>

    <form action="<?php echo htmlentities($_SERVER["PHP_SELF"]); ?>" method="POST">

        <!--Gekozen pakket weergeven-->

        <?php echo "<b>" . $gekozenpakket->getStad() . " (" . $gekozenpakket->getLand() . "</b>)<br><br>Luchthaven: " . $gekozenpakket->getLuchthaven() . "<br>";
                            echo "Hotel: " . $gekozenpakket->hotelid->getHotelNaam() . "<br><br>";
                            echo $gekozenpakket->getOmschrijving() . "<br><br>";
                            echo "<text>€ " . $gekozenpakket->getPrijs() . " per persoon/nacht.</text>";
                            echo "<b><span style=\"color:red\">" . $error . "</span><br>"; ?>        <br>

        </div>
        <br>

        <h2> Vervolledig de informatie </h2>
   <p>
        <!--invoer velden-->
        Vertrekdatum:<input type="date" id="start" name="startreis" value="<?php echo $morgen ?>" min=" <?php echo $morgen ?> " max="<?php echo  $limiet ?>"><br>

        Aantal personen: <input type="number" id="aantalpersonen" name="personen" value="2" min="1" max="10"><br>


        Aantal overnachtingen: <input type="number" id="aantaldagen" name="dagen" value="3" min="1" max="31"><br><br>


        <input type="submit" value="Boek uw reis" name="submitKnop">

</p>

    </form>
    
<?php } else {

    echo "<p>Er werd geen pakket geselecteerd.</p>";
} ?>
<br>

</div>

<p><text><a href="zoekresultaat.php">Terug naar zoekresultaten.</a></text></p>



<?php
require_once("footer.php");
?>