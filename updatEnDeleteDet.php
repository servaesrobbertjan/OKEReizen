<?php
require_once("pakket.php");
$pakketObj = new Pakket();
$pakketLijst = $pakketObj->getAllePakketten();

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
        
        foreach ($pakketLijst as $pakket) {
            echo "<li><a href=\"index.php?id=" . $pakket->getReisId() . "\">" . $pakket->getHotelNaam()  . $hotel->getHotelEmail(). "</a></li>";
        }
        
        ?>
    </ul>
</body>
</html>