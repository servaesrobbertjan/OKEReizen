<?php
require_once("pakket.php");
$pakketObj = new Pakket();
$pakketenLijst = $pakketObj->getAllePakketten();

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
        
        foreach ($pakketenLijst as $pakket) {
            echo "<li><a href=\"index.php?id=" . $pakket->getPrijs() . "\">" . $pakket->getOmschrijving(). "</a></li>";
        }
        
        ?>
    </ul>
</body>
</html>