<?php
session_start();
require_once("boeking.php");
require_once("klanten.php");




/*if (empty($_SESSION["boekingsid"])) {

  header("Location: pakketdetail.php");
  exit;
} 

else {
*/
    $boekingObj = new Boeking();

    $boeking = $boekingObj->getBoekingbyId($_SESSION["boekingsid"]);

    echo "<br>";

/*
}
*/



require_once("header.php");
?>


<?php


if ($boeking) {

    ?>   

    <h1>Bedankt voor uw boeking! <h1> 
    <h2>We contacteren u zo spoedig mogelijk. Hieronder kan u de details van uw boeking terugvinden: </h2>

    <?php


echo "Uw klantengegevens: ". $boeking->klantNummer->getNaam() . "<br>".  $boeking->klantNummer->getEmail() . "<br><br>";

echo "Uw boekingsnummer: ". $boeking->getBoekingsid()  . "<br>";

echo "Uw reisgegevens:" 


. $boeking->getStad() . " (" . $boeking->getLand() . ")<br>"

. "Uw hotel" . $boeking->hotelnaam->getHotelNaam() . "<br><br>". 

"Heendatum: ".  $boeking->getHeendatum() . "<br>". 

"Aantal dagen: " . $boeking->getaantalDagen() . "<br>". 

"Aantal Personen: " . $boeking->getaantalPersonen() . "<br>";


echo "<h2>Totale Prijs:". $boeking->totaalPrijs()  . "</h2><br>";

}
else {
    echo "Oei. Er liep iets fout. <br>";
}


?>

<a href="klantPage.php">Ga naar uw klantenpagina om deze boeking te beheren <a><br>
<a href="index.php">Naar de startpagina <a>



<?php
require_once("footer.php");
?>