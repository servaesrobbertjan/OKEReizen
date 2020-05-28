<?php 

session_start();
require_once("zoekobject.php");
require_once("pakket.php");

/* we kijken over de er een zoekopdracht door de klant is ingegeven */ 

unset($_SESSION["gekozenreis"]);

if (isset($_SESSION["zoekresultaat"])){
$pakketObj = new Pakket();

/* het zoekobject bestaat uit twee elementen nl, bestemmingsid en reistype. We halen dit uit de session en stoppen dit in de pakketfunctie om de 
gezocht reizen op te halen */

$zoekresultaat=unserialize($_SESSION["zoekresultaat"]);

$bestemming=$zoekresultaat->getBestemmingsId();

$reistype=$zoekresultaat->getReistype();

$pakkettenLijst = $pakketObj->getPakketByReisTypeAndBestemmingsId($reistype,$bestemming);


 } else {
  
    
    header("Location: index.php");
    exit;

 }


?>
<?php
require_once("header.php");
?>
<h2> Uw Zoekresultaat </h2>
<div class=pakketwrapper2>
        <?php 
        
/*We tonen de gezochte pakketten */
if (count($pakkettenLijst)>0){

        foreach ($pakkettenLijst as $pakket) {
            echo "<div class=pakket2><p><a href=\"pakketdetail.php?id=" . $pakket->getReisId() . "\">" . $pakket->getStad()
            . " (" . $pakket->getLand() . ")</a></p><i>" . $pakket->getReistype() . "</i><br><br><text> € " . $pakket->getPrijs()  . " per persoon / per overnachting" . "</text><br></a>
            </div>";

        }
    } else {

        echo "<p>Er werden geen pakketten gevonden die aan uw zoekopdracht voldoen.</p>";
    }


        
        ?>
        </div>
  

<br>

    <p><text><a href="index.php">Terug naar de startpagina</a></text></p>
  

</body>
<?php
require_once("footer.php");
?>