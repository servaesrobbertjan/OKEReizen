<?php

session_start();
require_once("hotels.php");
require_once("klanten.php");

$hotelObj = new Hotels();
$hotels = $hotelObj->getAllHotel();


?>
<!DOCTYPE html>
<html lang="en">
<?php
require_once("header.php");
?>
<head>
    <meta charset="UTF-8">
    <title>hotels</title>
</head>

<body>
    <h1>Top hotels</h1>
    <h2>kies hier je hotel</h2>
    <div class = "plain">
    <?php

    foreach ($hotels as $hotel) {
        echo "<text \">Naam:"  . $hotel->getHotelNaam() . "<br>";
        echo $hotel->getHotelTelefoon() . "<br>"; echo $hotel->getHotelEmail()."</text><br><hr>";
    }
?> </div>
<?php
    require_once("footer.php");
?>
