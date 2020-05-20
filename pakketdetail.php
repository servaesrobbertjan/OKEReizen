<?php
session_start();
require_once("pakket.php");

if (empty($_GET["id"]) && empty($_POST["boeking"])) {

    header("Location: index.php");
    exit;
} else {

    $pakketObj = new Pakket();

    if (!empty($_GET["id"]) && empty($_SESSION["boeking"])) {

        $pakket = $pakketObj->getPakketById($_GET["id"]);
    } else {
        $pakket = $pakketObj->getPakketById($_SESSION["boeking"]);
    }



}



if (isset($_POST["submitKnop"])){

if (!empty($_POST["boeking"])) {
    $_SESSION["boeking"] = $_POST["boeking"];
    header("Location: reisboeken.php");
    exit;

}
}


require_once("header.php");
?>

<h2>Pakketinformatie </h2>
<?php


echo $pakket->getStad() . "<br>";
echo $pakket->hotelid->getHotelNaam() . "<br>";
echo $pakket->getOmschrijving() . "<br>";
echo $pakket->getPrijs() . "<br>";


/*knop "boeken" */
?>

<form action="<?php echo htmlentities($_SERVER["PHP_SELF"]); ?>" method="POST">
    <input type="hidden" value="<?php echo $_GET["id"] ?>" name="boeking">
    <input type="submit" value="Boek Nu" name="submitKnop">
</form>



<?php
require_once("footer.php");
?>