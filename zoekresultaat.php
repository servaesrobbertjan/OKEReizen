<?php 

session_start();
require_once("zoekobject.php");
require_once("pakket.php");

/* we kijken over de er een zoekopdracht door de klant is ingegeven */ 

if (isset($_SESSION["zoekresultaat"])){
$pakketObj = new Pakket();

/* het zoekobject bestaat uit twee elementen nl, bestemmingsid en reistype. We halen dit uit de session en stoppen dit in de pakketfunctie om de 
gezocht reizen op te halen */

$zoekresultaat=unserialize($_SESSION["zoekresultaat"]);

$bestemming=$zoekresultaat->getBestemmingsId();

$reistype=$zoekresultaat->getReistype();

$pakkettenLijst = $pakketObj->getPakketByReisTypeAndBestemmingsId($reistype,$bestemming);


 } else {
  
    $pakketObj = new Pakket();
    $pakkettenLijst = $pakketObj->getPakketById(1);
    
  
    /* header("Location: index.php");
    exit;
*/
 }


?>
<?php
require_once("header.php");
?>

    <ul>
        <?php 
        
/*We tonen de gezochte pakketten */
if (count($pakkettenLijst)>0){

        foreach ($pakkettenLijst as $pakket) {
            echo "<li><a href=\"pakketdetail.php?id=" . $pakket->getReisId() . "\">" . $pakket->getStad() 
            . " (" . $pakket->getLand() . ")<br>". $pakket->getReistype() . " <br><br> €". $pakket->getPrijs() . " per persoon per overnachting". "</a>
            </li>";

        }
    } else {

        echo "Er werden geen pakketten gevonden die aan uw zoekopdracht voldoen.";
    }


        
        ?>
    </ul>


<a href="index.php">Terug naar zoeken</a>

</body>
<?php
require_once("footer.php");
?>