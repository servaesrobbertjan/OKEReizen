<?php
session_start();
require_once("pakket.php");

$pakketObj = new Pakket();
$pakketLijst = $pakketObj->getAllePakketten();


//wissen zoekopdracht uit sessie
unset($_SESSION["zoekresultaat"]);


?>
<?php
require_once("header.php");
?>

<h2> Al onze pakketten </h2>
<div class=pakketwrapper2>

    <?php

    foreach ($pakketLijst as $pakket) {
        echo "<div class=pakket2><p><a href=\"pakketdetail.php?id=" . $pakket->getReisId() . "\">" . $pakket->getStad()
            . " (" . $pakket->getLand() . ")</a></p><i>" . $pakket->getReistype() . "</i><br><br><text> € " . $pakket->getPrijs()  . " per persoon / per overnachting" . "</text><br></a>
            </div>";
    }
echo "<br>"
    ?>

  
 
</div>
<br>

        <p><text><a href="index.php">Terug naar de startpagina</a></text></p>
      
  
<br>   
</body>
<?php
require_once("footer.php");
?>