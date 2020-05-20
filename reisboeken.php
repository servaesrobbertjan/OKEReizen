<?php
session_start();
require_once("header.php");
require_once("boeking.php");
require_once("pakket.php");


$gekozenreis="";

$today = new DateTime(null, new DateTimeZone('Europe/Brussels'));
$vandaag = $today->format("Y-m-d");

$tomorrow = $today->modify('+1 day');
$morgen = $tomorrow->format("Y-m-d");

$limit = $tomorrow->modify('+730 day');
$limiet = $limit->format("Y-m-d");


$pakket = new Pakket();


/*if (isset($_SESSION["boeking"])){ */
$allepakketten = $pakket->getAllePakketten();
    $gekozenpakket = $pakket->getPakketById(1);


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

Uw gekozen pakket: <?php echo $gekozenpakket->getStad() . "<br>";
echo $gekozenpakket->hotelid->getHotelNaam() . "<br>";
echo $gekozenpakket->getOmschrijving() . "<br>";
echo $gekozenpakket->getPrijs() . " per persoon/nacht.<br>"; ?>

<br>

Aantal personen <input type="number" id="aantaldagen" name="dagen" value="2" min="1" max="10">

 

Vertrekdatum:<input type="date" id="start" name="startreis" value="<?php echo $morgen ?>" min=" <?php echo $morgen ?> " max="<?php echo  $limiet ?>">
<br>

Aantal dagen <input type="number" id="aantaldagen" name="dagen" value="3" min="1" max="31"><br>


<input type="submit" value="Boek uw reis" name="submitKnop">
<br>

<a href="zoekresultaat.php">Terug naar zoekresultaten.</a>

</form>

<?php 
require_once("footer.php");
?>