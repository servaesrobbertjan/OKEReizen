<?php
require_once("pakket.php");
$pakketObj = new Pakket();
$pakket = $pakketObj->getPakketById($_GET["id"]);
require_once("header.php");
?>

<h2>Pakketinformatie </h2>
<?php


echo $pakket->getStad() . "<br>";
echo $pakket->getHotelNaam() . "<br>";
echo $pakket->getOmschrijving() . "<br>";
echo $pakket->getPrijs() . "<br>";

?>

<?php 
require_once("footer.php");
?>