<?php 

require_once("pakket.php");

$pakketObj = new Pakket();
$pakketLijst = $pakketObj->getAllePakketten();

?>
<?php
require_once("header.php");
?>


    <ul>
        <?php 
        
        foreach ($pakketLijst as $pakket) {
            echo "<li><a href=\"pakketdetail.php?id=" . $pakket->getPakketId() . "\">" . $pakket->getStad() 
            . " " . $pakket->getLand() . "<br>". $pakket->getReistype() . "<br>" . $pakket->getOmschrijving()
            . "<br>" . $pakket->hotelid->getHotelNaam() . " <br><br> €". $pakket->getPrijs()  . " per persoon/per overnachting". "</a>
            </li>";



        }
        
        ?>
    </ul>

    <a href="index.php">Terug naar de startpagina</a>

</body>
<?php
require_once("footer.php");
?>