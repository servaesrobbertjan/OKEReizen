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

    if ($error == "") {
    }
}


/* aanmaken en opvullen van boekingsobject en wegschrijven naar de database */


if (isset($_POST["submitKnop"])) {


    if ($error == "" && isset($_SESSION["gebruiker"])) {


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

/* Er is een probleem  met de functie addBoeking er wordt enkel naar de tabel boekingen geschreven terwijl hij ook naar klantenreizen moet schrijven. Hier door zijn
er ook problemen met de queries die op deze tabel gebaseerd zijn*/

        $_SESSION["boekingsid"] = serialize($boeking->getBoekingsid());


        //header("Location: boekingsinfo.php");
        //exit;


    } else {

        header("Location: pakketdetails.php");
        exit;
    }
}




?>


<h2> Vervolledig de boekingsinformatie </h2>
<!--Kijken of er een gekozenpakket is-->
<?php if ($gekozenpakket) { ?>

    <form action="<?php echo htmlentities($_SERVER["PHP_SELF"]); ?>" method="POST">

        <!--Gekozen pakket weergeven-->

        Uw gekozen pakket: <?php echo $gekozenpakket->getStad() . " (" . $gekozenpakket->getLand() . ")<br>Luchthaven: " . $gekozenpakket->getLuchthaven() . "<br>";
                            echo "Hotel: " . $gekozenpakket->hotelid->getHotelNaam() . "<br>";
                            echo $gekozenpakket->getOmschrijving() . "<br>";
                            echo "€ " . $gekozenpakket->getPrijs() . " per persoon/nacht.<br>";
                            echo "<span style=\"color:red\">" . $error . "</span>"; ?>
        <br>


        <!--invoer velden-->
        Vertrekdatum:<input type="date" id="start" name="startreis" value="<?php echo $morgen ?>" min=" <?php echo $morgen ?> " max="<?php echo  $limiet ?>"><br>

        Aantal personen: <input type="number" id="aantalpersonen" name="personen" value="2" min="1" max="10"><br>


        Aantal overnachtingen: <input type="number" id="aantaldagen" name="dagen" value="3" min="1" max="31"><br>


        <input type="submit" value="Boek uw reis" name="submitKnop">

    </form>

<?php } else {

    echo "Er werd geen pakket geselecteerd.";
} ?>
<br>

<a href="zoekresultaat.php">Terug naar zoekresultaten.</a>



<?php
require_once("footer.php");
?>