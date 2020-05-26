<?php 

require_once("pakket.php");

$pakketObj = new Pakket();
$pakketLijst = $pakketObj->getAllePakketten();

?>
<?php
require_once("header.php");
?>

<h2> Al onze pakketten </h2>
    <ul>
        <?php 
        
        foreach ($pakketLijst as $pakket) {
            echo "<li><a href=\"pakketdetail.php?id=" . $pakket->getReisId() . "\">" . $pakket->getStad() 
            . " " . $pakket->getLand() . "<br>". $pakket->getReistype() . " <br> €". $pakket->getPrijs()  . " per persoon/per overnachting". "<br><br></a>
            </li>";



        }
        
        ?>
    </ul>

    <a href="index.php">Terug naar de startpagina</a>

</body>
<?php
require_once("footer.php");
?>