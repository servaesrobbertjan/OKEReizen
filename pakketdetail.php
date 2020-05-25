<?php
session_start();
require_once("pakket.php");

/* 
Als er geen variable gekend is dan sturen we de gebruiker terug naar de index die ook als zoekpagina fungeert
Indien wel gekend gaan het pakket ophalen aan de hand van de ID
*/ 

if (empty($_GET["id"]) && empty($_POST["gekozenreis"])) {

    header("Location: index.php");
    exit;
} else {



    $pakketObj = new Pakket();

    if (!empty($_GET["id"]) && empty($_SESSION["gekozenreis"])) {

        $pakket = $pakketObj->getPakketById($_GET["id"]);
    
    } else {
        $pakket = $pakketObj->getPakketById($_SESSION["gekozenreis"]);
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

<h2>Pakketinformatie </h2>
<?php


if ($pakket) {
echo $pakket->getStad() . "<br>";
echo $pakket->hotelid->getHotelNaam() . "<br>";
echo $pakket->getOmschrijving() . "<br>";
echo $pakket->getPrijs() . "<br>";
}

/*knop "boeken" */
?>

<form action="<?php echo htmlentities($_SERVER["PHP_SELF"]); ?>" method="POST">
    <input type="hidden" value="<?php echo $_GET["id"] ?>" name="gekozenreis">
    <input type="submit" value="Boek Nu" name="submitKnop">
</form>



<?php
require_once("footer.php");
?>