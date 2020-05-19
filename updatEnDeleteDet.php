<?php
require_once("hotels.php");
$hotelObj = new Hotels();
$hotelLijst = $hotelObj->getAllHotel();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>update en delete</title>
</head>
<body>
    <ul>
        <?php 
        
        foreach ($hotelLijst as $hotel) {
            echo "<li><a href=\"deleteEnUpdateHotel.php?id=" . $hotel->getId() . "\">" . $hotel->getHotelNaam()  . $hotel->getHotelEmail(). "</a></li>";
        }
        
        ?>
    </ul>
</body>
</html>