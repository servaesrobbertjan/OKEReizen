<?php
session_start();
require_once("pakket.php");
require_once("reviewClass.php");



/* 
Als er geen variable gekend is dan sturen we de gebruiker terug naar de index die ook als zoekpagina fungeert
Indien wel gekend gaan het pakket ophalen aan de hand van de ID
*/

if (empty($_GET["id"]) && empty($_SESSION["gekozenreis"])) {

    header("Location: index.php");
    exit;
} else {



    $pakketObj = new Pakket();

    if (!empty($_GET["id"])) {
        $_SESSION["gekozenreis"] = $_GET["id"];
        $pakket = $pakketObj->getPakketById($_SESSION["gekozenreis"]);
    } else {
        if (!empty($_SESSION["gekozenreis"])) {
            $pakket = $pakketObj->getPakketById($_SESSION["gekozenreis"]);
        } else {
            header("Location: index.php");
            exit;
        }
    }
}


if (isset($_SESSION["gebruiker"])) {

    $gebruiker = unserialize($_SESSION["gebruiker"]);
    $gebruiker = $gebruiker->getId();
}
/*Indien de klant de geselecteerde reis wil boeken, klikt hij op "boeken" en wordt hij naar de boekingspagina gestuurd */

if (isset($_POST["submitKnop"])) {

    if (!empty($_POST["gekozenreis"])) {
        $_SESSION["gekozenreis"] = $_POST["gekozenreis"];
        header("Location: reisboeken.php");
        exit;
    }
}



require_once("header.php");
?>
<?php if ($pakket) { ?>
    <h2>Pakketinformatie </h2>
    <div class="pakketwrapper">
        <div class="pakket2">
            <?php



            echo "<h2>" . $pakket->getLand() . " (" . $pakket->getStad() . ")</h2><br>";
            echo "<p>Hotel: " . $pakket->hotelid->getHotelNaam() . "</p><br>";

            echo "<p style=\"text-align:center\">Wat kan u verwachten? " . $pakket->getOmschrijving() . "</p><br>";

            echo "<p> Wat zal het u kosten? </p>" . "<text>€ " . $pakket->getPrijs() . " per persoon</text><br>";


            /*knop "boeken" */
            ?>

            <form action="<?php echo htmlentities($_SERVER["PHP_SELF"]); ?>" method="POST">
                <input type="hidden" value="<?php echo $_GET["id"] ?>" name="gekozenreis"><br>
                <p style="text-align:center"><input type="submit" value="Boek Nu" name="submitKnop"></p>
            </form><br><br><p>U moet geregistreerd zijn om te kunnen boeken.
            <text><a href="registreer.php">Registreer je hier<a></text></p>

        </div>

    <?php } else {

    echo "<br><br> U heeft geen reis geselecteerd.<br>";
}
if (!empty($gebruiker)) {

// controle enkel geauthoriseerde users kunnen het pakket aanpassen

    if ($gebruiker >= 0 && $gebruiker <= 4) {

        echo "<br><br> <p>  <text><a href=\"deleteEnUpdatePakket.php?id=" . $pakket->getReisId() . "\"> << Pakket wijzigen </a></text></p>";
    }
}
    ?>



    </div>
<br>
<?php
    if (isset ($_SESSION["zoekresultaat"])) { ?>
    
       <p><text><a href="zoekresultaat.php" style="font-size:15px">Terug naar het zoekresultaat</a></text></p>
      
    
    <?php }
    else { ?> 

    <p><text><a href="index.php" style="font-size:15px">Terug naar de startpagina</a></text></p>
      
 
    <?php } ?>

    <?php
    require_once("footer.php");
    ?>