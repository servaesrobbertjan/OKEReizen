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
            . " " . $pakket->getLand() . " ". $pakket->getReistype() . " " . $pakket->getOmscrhijving()
            . " " . $pakket->getHotel() . " ". $pakket->getPrijs() . " ". "</a></li>";
        }
        
        ?>
    </ul>

</body>
<?php
require_once("footer.php");
?>