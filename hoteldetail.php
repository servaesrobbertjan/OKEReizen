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
    <?php

    foreach ($hotels as $hotel) {
        echo "<span style=\"font-weight:bold;\">Naam: </span>" . $hotel->getHotelNaam() . "<br>";
        echo $hotel->getHotelTelefoon() . "<br>"; echo $hotel->getHotelEmail()."<br><hr>";
    }


require_once("footer.php");
?>
