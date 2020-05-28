<?php
session_start();
require_once("klanten.php");
require_once("pakket.php");
require_once("zoekobject.php");

/*object aanmaken en ophalen pakketten db*/

$naam = '';
$gebruiker = "";
$pakketObj = new Pakket();
$pakketLijst = $pakketObj->getAllePakketten();
$typesLijst = $pakketObj->getAlleReistypes();

$pakkettenzomer = $pakketObj->getPakketByReisTypeWithBestReviewScore("Zomer reizen");
$pakkettenwinter = $pakketObj->getPakketByReisTypeWithBestReviewScore("winter reizen");
$pakkettencitytrip = $pakketObj->getPakketByReisTypeWithBestReviewScore("city trips");

//* kijken of gebruiker ingelogd is 

if (isset($_SESSION["gebruiker"])) {

    $gebruiker = unserialize($_SESSION["gebruiker"]);
    $naam = $gebruiker->getNaam();
}

$error = '';

/* Datums ophalen en converteren naar string */

$today = new DateTime(null, new DateTimeZone('Europe/Brussels'));
$vandaag = $today->format("Y-m-d");

$tomorrow = $today->modify('+1 day');
$morgen = $tomorrow->format("Y-m-d");

$limit = $tomorrow->modify('+730 day');
$limiet = $limit->format("Y-m-d");


/* Controle op minimum en maximum datums bepalen voor invoer */

if (isset($_POST["submitKnop"])) {

    if ($_POST["eindreis"] <= $_POST["startreis"]) {

        $error .= 'Uw vertrekdatum kan niet vroeger zijn dan of gelijk zijn aan uw datum van terugkeer. ';
    }

    if ($_POST["startreis"] < $vandaag || $_POST["eindreis"] < $morgen) {

        $error .= 'U kan niet in het verleden vertrekken of aankomen. ';
    }

    if ($_POST["startreis"] > $limiet || $_POST["eindreis"] > $limiet) {

        $error .= 'Deze datum is nog niet beschikbaar om te boeken. ';
    }

    if (empty($_POST["eindreis"]) || empty($_POST["startreis"])) {

        $error .= 'U moet een datum voor vertrek en terugkomst opgeven. ';
    }


    if (empty($_POST["bestemming"])) {
        $error .= "Gelieve een bestemming in te geven. ";
    }
    if (empty($_POST["reistype"])) {
        $error .= "Gelieve een reistype in te geven. ";
    }


    if ($error == "") {

        /*zoekobject aanmaken en serializen + sturen gebruiker naar de resultatenpagina */

        $zoekobject = new Zoekobject($_POST["bestemming"], $_POST["reistype"]);
        $_SESSION["zoekresultaat"] = serialize($zoekobject);
        header("Location: zoekresultaat.php");
        exit;
    }
}



?>



<?php
require_once("header.php");
?>

<h1>Hallo<?php
            if ($naam != "") {
                echo " " . $naam;
            } ?>. Welkom op onze Website <h1>

<br>

        <h2>Welke reis wil u maken?</h2>

        <?php echo "<span style=\"color:red\">" . $error . "</span>" ?>

        <div class=zoekwrapper>

            <div class=zoeken>

                <div>
                    <form action="<?php echo htmlentities($_SERVER["PHP_SELF"]); ?>" method="POST">

                        <!-- Van alle reispakketten de bestemming(stad) en reistypes in een selectielijst zetten -->

                        Bestemming: <br><select name="bestemming">
                            <?php
                            foreach ($pakketLijst as $pakket) {
                                echo "<option value=\"" . $pakket->getBestemmingsId() . "\">" . $pakket->getStad() . " (" . $pakket->getLand() . ")</option>";
                            }
                            ?>
                        </select><br><br>

                        Vertrekdatum: <br><input type="date" id="start" name="startreis" value="<?php echo $vandaag ?>" min=" <?php echo $vandaag ?> " max="<?php echo  $limiet ?>">
                    <br><br>
 
                   
                </div>


                <div>
                Type reis: <br><select name="reistype">
                            <?php
                            foreach ($typesLijst as $reistype) {
                                echo "<option value=\"" . $reistype  . "\">" . $reistype . "</option>";
                            }
                            ?>
                        </select>
                        <br><br>
                 
                    Terugkeerdatum: <br><input type="date" id="einde" name="eindreis" value="<?php echo $morgen ?>" min=" <?php echo $morgen ?> " max="<?php echo $limiet ?>">
                    <br><br>
                   
                    <!--eindreis mag niet kleiner of gelijk zijn dan startreis én het veld moet ingevuld zijn, 
voor de rest gaan we hier niets mee doen bij de zoekresultaten omdat onze reizen geen specifieke start en einddata hebben.
Bij de boekingspagina worden de data opnieuw gevraagd en gaan we ze pas in het object opslaan -->
                </div>
            </div>

            <input type="submit" value="Zoek uw reis" name="submitKnop">

<br>

            <text><a href="allepakketten.php">Geef alle reispakketten weer.</a></text>

            </form>



        </div>
        <br>
        <br>
        <h2> Onze best beoordeelde Zomerreizen </h2>
        <div class=pakketwrapper>
            <?php foreach ($pakkettenzomer as $pakket) {

                echo "<div class=\"pakket\"><text><p><a href=\"pakketdetail.php?id=" . $pakket->getReisId() . "\">"
                    . $pakket->getStad()
                    . " (" . $pakket->getLand() . ")</a></p><br><p> " . $pakket->getOmschrijving()
                    . "<br><br>Hotel: " . $pakket->hotelid->getHotelNaam() . "<br><br><text>" . $pakket->getPrijs() . " euro</text><br> " . "</p></text></div><br><br>";
            }
            ?>
            <div class=pakket><img src="1.png"></div>
        </div>
        <br>
        <br>
        <h2> Onze best beoordeelde Winterreizen </h2>
        <div class=pakketwrapper>
            <?php foreach ($pakkettenwinter as $pakket) {

                echo "<div class=\"pakket\"><text><p><a href=\"pakketdetail.php?id=" . $pakket->getReisId() . "\">"
                    . $pakket->getStad()
                    . " (" . $pakket->getLand() . ")</a></p><br><p> " . $pakket->getOmschrijving()
                    . "<br><br>Hotel: " . $pakket->hotelid->getHotelNaam() . "<br><br><text>" . $pakket->getPrijs() . " euro</text><br>" . "</p></text></div><br><br>";
            } ?>
            <div class=pakket><img src="2.png"></div>
        </div>
        <br>
        <br>

        <h2> Onze best beoordeelde City Trips </h2>
        <div class=pakketwrapper>
            <?php foreach ($pakkettencitytrip as $pakket) {
                echo "<div class=\"pakket\"><text><p><a href=\"pakketdetail.php?id=" . $pakket->getReisId() . "\">"
                    . $pakket->getStad()
                    . " (" . $pakket->getLand() . ")</a></p><br><p> " . $pakket->getOmschrijving()
                    . "<br><br>Hotel: " . $pakket->hotelid->getHotelNaam() . "<br><br><text>" . $pakket->getPrijs() . " euro</text><br>" . "</p></text></div><br><br>";
            }



            ?>
            <div class=pakket><img src="3.png"></div>

        </div>



        <br>
        <br>

        </div>

        <div class=footer>
            <h2><text> Over ons </text></h2>
            <p>
                Wij zijn een klein reisbureau dat reizen over heel Europa organiseert. <br>
                Locatie: Genk <br>
                Tel: xxx/xx xx xx<br>
                Mail:contact@okereizen.be
                <!-- Vaste info -->
            </p>

            <br>

        </div>
        <?php
        require_once("footer.php");
        ?>