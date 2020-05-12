<?php
session_start();
include_once("pakket.php");


$pakket= new Pakket();
$pakketLijst = $pakket->getAllePakketten();



$error ='';

/* Datums ophalen en converteren naar string */

$today = new DateTime(null, new DateTimeZone('Europe/Brussels'));
$vandaag=$today->format("Y-m-d");

$tomorrow = $today->modify('+1 day');
$morgen = $tomorrow->format("Y-m-d");

$limit= $tomorrow->modify('+730 day');
$limiet = $limit->format("Y-m-d");


/* Controle op minimum en maximum datums bepalen voor invoer */

if (isset($_POST["submitKnop"]))
{

if ($_POST["eindreis"] <= $_POST["startreis"]) {

    $error= 'Uw vertrekdatum kan niet vroeger zijn dan of gelijk zijn aan uw datum van terugkeer.';
}

if ($_POST["startreis"] < $vandaag || $_POST["eindreis"] < $morgen)  {

    $error= 'U kan niet in het verleden vertrekken of aankomen.';
}

if ($_POST["startreis"] > $limiet || $_POST["eindreis"] > $limiet)  {

    $error= 'Deze datum is nog niet beschikbaar om te boeken';
}

if (empty($_POST["eindreis"]) || empty($_POST["startreis"])) {

    $error= 'U moet een datum voor vertrek en terugkomst opgeven.';
}



}



?>



<?php
require_once("header.php");
?>

<h1>Welkom op onze Website <h1>

        <h2>Welke reis wil u maken?</h2>

<?php echo "<span style=\"color:red\">". $error ."</span>" ?>

        <form action="<?php echo htmlentities($_SERVER["PHP_SELF"]); ?>" method="POST">

            <!-- Van alle reispakketten de bestemming(stad) en reistypes in een selectielijst zetten -->

            Bestemming: <select name="bestemming">
                <?php
                foreach ($pakketLijst as $pakket) {
                    echo "<option value=\"" . $pakket->getStad() . "</option>";
                }
                ?>
            </select><br>

            Type reis: <select name="reistype">
                <?php
                foreach ($pakketLijst as $pakket) {
                    echo "<option value=\"" . $pakket->getReisType() . "</option>";
                }
                ?>
            </select>
            <br>


            Vertrekdatum:<input type="date" id="start" name="startreis" value="<?php echo $vandaag?>" min=" <?php echo $vandaag ?> " max="<?php echo  $limiet ?>">
            <br>

            Terugkeerdatum <input type="date" id="einde" name="eindreis" value="<?php   echo $morgen ?>" min=" <?php echo $morgen ?> " max="<?php  echo $limiet ?>">

            <!--eindreis mag niet kleiner of gelijk zijn dan startreis én het veld moet ingevuld zijn, 
voor de rest gaan we hier niets mee doen bij de zoekresultaten omdat onze reizen geen specifieke start en einddata hebben.
Bij de boekingspagina worden de data opnieuw gevraagd en gaan we ze pas in het object opslaan -->


            <input type="submit" value="OK" name="submitKnop">



        </form>


        <h2> Onze best beoordeelde reizen </h2>
        <div>
            <div>reis1</div>
            <div>reis2</div>
            <div>reis3</div>

            <!-- getPakketten where reviewscore is max order by desc and row_number < 4 -->


        </div>


        <div>

            <h2> Over ons </h2>

            Wij zijn een klein reisbureau dat reizen over heel Europa organiseert. <br>
            Locatie: Genk <br>
            Tel: xxx/xx xx xx<br>
            Mail:contact@okereizen.be
            <!-- Vaste info -->

        </div>


        <?php
        require_once("footer.php");
        ?>