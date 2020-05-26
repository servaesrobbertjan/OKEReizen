<?php
session_start();
require_once("header.php");
require_once("boeking.php");
require_once("pakket.php");



$error="";
$gekozenpakket="";
$aantalpersonen="";
$heendatum="";
$aantaldagen="";


$today = new DateTime(null, new DateTimeZone('Europe/Brussels'));
$vandaag = $today->format("Y-m-d");

$tomorrow = $today->modify('+1 day');
$morgen = $tomorrow->format("Y-m-d");

$limit = $tomorrow->modify('+730 day');
$limiet = $limit->format("Y-m-d");



if (!isset($_SESSION["gebruiker"])){
    header("Location: login.php");
    exit;
    }


$pakket = new Pakket();

if (isset($_SESSION["gekozenreis"])){ 
$allepakketten = $pakket->getAllePakketten();
    $gekozenpakket = $pakket->getPakketById($_SESSION["gekozenreis"]);


    
}

else {

        if (isset($_SESSION["zoekresultaat"])){
            header("Location: zoekresultaat.php");
            exit;
        }else{
            if (!isset($_SESSION["zoekresultaat"])){
    header("Location: index.php");
    exit;
            }
        }
        
}


if (isset($_POST["submitKnop"])) {

if ($_POST["startreis"] < $morgen) {

    $error .= 'De vertrekdatum kan niet in het verleden liggen. ';
} 

if ($_POST["startreis"] > $limiet) {

    $error .= 'Deze datum is nog niet beschikbaar om te boeken. ';
} 

if (empty($_POST["personen"])){

    $error .= 'U moeten een aantal personen opgeven';
} 

if ($_POST["personen"] < 1 || $_POST["personen"] > 10){

    $error .= 'Het minimum aantal personen is 1. Het maximum aantal personen is 10. ';
} 

if (empty($_POST["dagen"])){

    $error .= 'U moet een aantal dagen opgeven';
} 

if ($error=""){


}



}





if(isset($_POST["submitKnop"]))
{

    
    if ($error == "" && isset($_SESSION["gebruiker"])) {
       
        try {
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
    

            $boekingObj = new Boeking(0, $reisnummer, $omschrijving, $reistype, $boekingsdatum, $heendatum, $aantaldagen, $aantalpersonen, $stad,$land, $hotelnaam, $prijs,$klantnummer);
    
            $boeking = $boekingObj->addBoeking();
            


             }

             catch (Boekingmislukt $e) {
                $error .= "Uw boeking is mislukt. Gelieve ons te contacteren";
            }
            $_SESSION["boekingsid"] = $boeking->getBoekingsid();
            header("Location: boekingsinfo.php");
            exit;

        }
    }




?>

<h2> Vervolledig de boekingsinformatie </h2>

<form action="<?php echo htmlentities($_SERVER["PHP_SELF"]); ?>" method="POST">

Uw gekozen pakket: <?php echo $gekozenpakket->getStad() . " (". $gekozenpakket->getLand(). ")<br>";
echo "Hotel: " . $gekozenpakket->hotelid->getHotelNaam() . "<br>";
echo $gekozenpakket->getOmschrijving() . "<br>";
echo "€ ".$gekozenpakket->getPrijs() . " per persoon/nacht.<br>"; ?>

<br>


Vertrekdatum:<input type="date" id="start" name="startreis" value="<?php echo $morgen ?>" min=" <?php echo $morgen ?> " max="<?php echo  $limiet ?>"><br>
U vertrekt vanuit de volgende luchthaven: <?php echo " ".$gekozenpakket->getLuchthaven() ?><br>

Aantal personen: <input type="number" id="aantalpersonen" name="personen" value="2" min="1" max="10"><br>


Aantal overnachtingen: <input type="number" id="aantaldagen" name="dagen" value="3" min="1" max="31"><br>


<input type="submit" value="Boek uw reis" name="submitKnop">

</form>
<br>

<a href="zoekresultaat.php">Terug naar zoekresultaten.</a>



<?php 
require_once("footer.php");
?>