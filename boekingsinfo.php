<?php
session_start();
require_once("boeking.php");
require_once("klanten.php");
require_once("hotels.php");


//controle of boekingsid in de session zit

if (empty($_SESSION["boekingsid"])) {

  header("Location: pakketdetail.php");
  exit;
} 

else {

    $boekingObj = new Boeking();

    $boeking = $boekingObj->getBoekingbyId($_SESSION["boekingsid"]);

    echo "<br>";


}




require_once("header.php");
?>


<?php


if ($boeking) {

    ?>   

    <h1>Bedankt voor uw boeking! <h1> 
    <h2>We contacteren u zo spoedig mogelijk. Hieronder kan u de details van uw boeking terugvinden: </h2>

    

<div class="plain">



            <ul>
                <fieldset>
                    <?php
                    
                    echo  
                    "<b>" . "Uw klantengegevens: " . "</b>" . $boeking->klantNummer->getNaam() . " (".$boeking->klantNummer->getEmail().")<br>". 
                    "<b>" . "Reisnummer: " . "</b>" . $boeking->getreisId() ."<br>".
                     "<b>" . "BoekingId: " . "</b>" . $boeking->getBoekingsid() ."<br>" .
                        "<b>" . "BoekingsDatum: " . "</b>" . $boeking->getboekingsDatum(). "<br>".
                        "<b>" . "HeenDatum: " . "</b>" . $boeking->getHeendatum() . "<br>".
                        "<b>" . "Land: " . "</b>" . $boeking->getland() . "<br>".
                        "<b>" . "Stad: " . "</b>" . $boeking->getstad() . "<br>" . 
                        "<b>" . "Hotel: " . "</b>" . $boeking->hotelnaam->getHotelNaam() . "<br>" .
                        "<b>". "Aantal personen: " . "</b>" . $boeking->getaantalPersonen()  . "<br>" .
                        "<text><b>" . "Totale prijs te betalen: € " . "</b>" . $boeking->totaalPrijs() . "</text>" . "<br>";
                    ?>
                      </fieldset>
            </ul>

        </div>


<?php
}
else {
    echo "<p>Oei. Er liep iets fout.</p> <br>";
}


?>

<p><text><a href="klantPage.php">Ga naar uw klantenpagina om deze boeking te beheren <a></text></p><br>
<p><text><a href="index.php">Naar de startpagina <a></text></p>



<?php
require_once("footer.php");
?>