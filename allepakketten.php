<?php 

require_once("pakket.php");

$pakketObj = new Pakket();
$PakkettenLijst = $pakketObj->getAllePakketten();

?>
<?php
require_once("header.php");
?>


    <ul>
        <?php 
        
        foreach ($pakkettenLijst as $pakket) {
            echo "<li><a href=\"pakketdetail.php?id=" . $pakket->getPakketId() . "\">" . $pakket->getStad() 
            . " " . $pakket->getLand() . "<br>". $pakket->getReistype() . "<br>" . $pakket->getOmscrhijving()
            . "<br>" . $pakket->getHotel() . " <br><br> €". $pakket->getPrijs() * 7 . " per persoon/per week". "</a>
            </li>";



        }
        
        ?>
    </ul>

    <a href="index.php">Terug naar de startpagina</a>

</body>
<?php
require_once("footer.php");
?>