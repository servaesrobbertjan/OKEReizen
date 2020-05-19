<?php
session_start();
require_once("header.php");
require_once("boeking.php");



$today = new DateTime(null, new DateTimeZone('Europe/Brussels'));
$vandaag = $today->format("Y-m-d");

$tomorrow = $today->modify('+1 day');
$morgen = $tomorrow->format("Y-m-d");

$limit = $tomorrow->modify('+730 day');
$limiet = $limit->format("Y-m-d");


$pakket = new Pakket();

/*if (isset($_SESSION["boeking"])){ */
    $gekozenreis = $pakket->getPakketById(1);

    /*
}

else {

        if (isset($_SESSION["zoekresultaat"])){
            header("Location: zoekresultaat.php");
            exit;
        }else{
  
    header("Location: index.php");
    exit;
        }
}
*/
?>



<form action="<?php echo htmlentities($_SERVER["PHP_SELF"]); ?>" method="POST">

Uw gekozen pakket: <?php var_dump($gekozenreis) ?>




Aantal personen <input type="number" id="aantaldagen" name="dagen" value="2" min="1" max="10">

Bestemming: <select name="bestemming">
    <?php
    foreach ($pakketLijst as $pakket) {
        echo "<option value=\"" . $pakket->getBestemmingsId() . "\">" . $pakket->getStad() . " (" . $pakket->getLand() . ")</option>";
    }
    ?>
</select><br>



Vertrekdatum:<input type="date" id="start" name="startreis" value="<?php echo $vandaag ?>" min=" <?php echo $vandaag ?> " max="<?php echo  $limiet ?>">
<br>

Aantal dagen <input type="number" id="aantaldagen" name="dagen" value="3" min="1" max="31">



<!--eindreis mag niet kleiner of gelijk zijn dan startreis én het veld moet ingevuld zijn, 
voor de rest gaan we hier niets mee doen bij de zoekresultaten omdat onze reizen geen specifieke start en einddata hebben.
Bij de boekingspagina worden de data opnieuw gevraagd en gaan we ze pas in het object opslaan -->


<input type="submit" value="Boek uw reis" name="submitKnop">
<br>

<a href="zoekresultaat.php">Terug naar zoekresultaten.</a>

</form>

<?php 
require_once("footer.php");
?>