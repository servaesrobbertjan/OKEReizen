<?php 

session_start();
require_once("pakket.php");


if (isset($_SESSION["zoekresultaat"])){
$pakketObj = new Pakket();
$zoekresultaat->unserialize($_SESSION["zoekresultaat"]);
$bestemming=$zoekresultaat->getBestemmingsId();
$reistype=$zoekresultaat->getReistype();
$PakketenLijst = $pakketObj->getPakketByReisTypeAndBestemmingsId($reistype,$bestemming);


 } else {
  
    header("Location: index.php");
    exit;

 }


?>
<?php
require_once("header.php");
?>

    <ul>
        <?php 
        
        foreach ($pakkettenLijst as $pakket) {
            echo "<li><a href=\"pakketdetail.php?id=" . $pakket->getPakketId() . "\">" . $pakket->getStad() 
            . " " . $pakket->getLand() . "<br>". $pakket->getReistype() . "<br>" . $pakket->getOmscrhijving()
            . "<br>" . $pakket->getHotel() . " <br><br> €". $pakket->getPrijs() . " per persoon/per nacht". "</a>
            </li>";

        }
        
        ?>
    </ul>


<a href="index.php">Terug naar zoeken</a>

</body>
<?php
require_once("footer.php");
?>