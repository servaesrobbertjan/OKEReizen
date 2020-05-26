<?php
session_start();
require_once("pakket.php");

/* 
Als er geen variable gekend is dan sturen we de gebruiker terug naar de index die ook als zoekpagina fungeert
Indien wel gekend gaan het pakket ophalen aan de hand van de ID
*/ 

if (empty($_GET["id"]) && empty($_SESSION["gekozenreis"])) {

    header("Location: index.php");
    exit;
} else {



    $pakketObj = new Pakket();

    if (!empty($_GET["id"])){

        $pakket = $pakketObj->getPakketById($_GET["id"]);
    
    } else {
        if(!empty($_SESSION["gekozenreis"])){
        $pakket = $pakketObj->getPakketById($_SESSION["gekozenreis"]);
        }
    }


}

/*Indien de klant de geselecteerde reis wil boeken, klikt hij op "boeken" en wordt hij naar de boekingspagina gestuurd */ 

if (isset($_POST["submitKnop"])){

if (!empty($_POST["gekozenreis"])) {
    $_SESSION["gekozenreis"] = $_POST["gekozenreis"];
    header("Location: reisboeken.php");
    exit;

}
}


require_once("header.php");
?>
<?php if ($pakket) {?>
<h2>Pakketinformatie </h2>
<?php


echo "Bestemming: " . $pakket->getStad() . "<br>";
echo "Hotel: " . $pakket->hotelid->getHotelNaam() . "<br>";
echo "Wat kan u verwachten? " . $pakket->getOmschrijving() . "<br>".
"U vertrekt vanuit volgende luchthaven: " . $pakket->getLuchthaven()."<br>"
;
echo "Wat zal het u kosten? " . "€ ". $pakket->getPrijs() . " per persoon<br>";


/*knop "boeken" */
?>

<form action="<?php echo htmlentities($_SERVER["PHP_SELF"]); ?>" method="POST">
    <input type="hidden" value="<?php echo $_GET["id"] ?>" name="gekozenreis">
    <input type="submit" value="Boek Nu" name="submitKnop"><br><br>U moet geregistreerd zijn om te kunnen boeken.
    <a href="registreer.php">Registreer je hier<a>
</form>

<?php } else

echo "<br><br> U heeft geen reis geselecteerd.<br><a href=\"zoekresultaat.php\"> Terug naar de zoekresultaten <a>"
?>

<?php
require_once("footer.php");
?>