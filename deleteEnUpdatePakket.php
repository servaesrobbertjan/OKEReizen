<?php
session_start();
require_once("pakket.php");
require_once("updatEnDeleteDet.php");


$error = "";

if (isset($_POST["btnDelete"])) {
    $pakketId = $_POST["boekingsId"];
    $pakketObj = new Pakket();
    $pakket = $pakketObj->getPakketByID($id);
    $pakketObj->deletePakketByID($id);
} else if (isset($_POST["btnUpdate"])) {
    $pakketId = $_POST["boekingsId"];

    if (!empty($_POST["txtOmschrijving"])) {
        $pakketOmschrijving = $_POST["txtOmschrijving"];
    } else {
        $error .= "De naam van de omschrijving mag niet leeg zijn.<br>";
    }

    if (!empty($_POST["txtPrijs"])) {
        $pakketPrijs = $_POST["txtPrijs"];

    } else {
        $error .= "De nieuwe prijs moet ingevuld zijn.<br>";
    }
   

    if ($error == "") {
        $pakketObj = new Pakket($pakketId, $pakketOmschrijving, $pakketPrijs);
        $pakketObj->updatePakket();
        $pakket = $pakketObj->getPakketByID($id);
    }
} else {
    $pakketObj = new Pakket();
    $pakket = $pakketObj->getPakketByID($id);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>update en delete pakketreizen</title>
</head>

<body>

    <?php
    if (isset($_POST["btnDelete"])) {

        echo "<h1>Pakket \"" . $pakket->getOmschrijving() . "\" verwijderd</h1>";

        echo "Klik <a href=\"index.php\">hier</a> om terug te gaan naar de overzichtspagina.";
    } else if (isset($_POST["btnUpdate"])) {
        echo "<h1>pakket \"" . $pakket->getOmschrijving() . "\" geüpdatet</h1>";
        echo "Nieuwe waarden:<br>";
        echo "<ul>";
        echo "<li>omschrijving: " . $pakket->getOmschrijving() . "</li>";
        echo "<li>Prijs: " . $pakket->getPrijs() . "</li>";
        echo "</ul>";
        echo "Klik <a href=\"deleteEnUpdateHotel.php\">hier</a> om terug te gaan naar de overzichtspagina.";
    } else {
    ?>

        <h1>pakket: <?php echo $pakket->getOmschrijving(); ?></h1>
        <form action="<?php echo htmlentities($_SERVER["PHP_SELF"]); ?>" method="POST">
            Omschrijving : <input type="text" name="txtOmschrijving" value="<?php echo $pakket->getOmschrijving(); ?>"><br>
            Prijs: <input type="number" name="txtPrijs" value="<?php echo $pakket->getPrijs(); ?>"><br>
            <input type="hidden" value="<?php echo $pakketId; ?>" name="boekingsId">
            
            <input type="submit" name="btnDelete" value="Pakket verwijderen">
            <input type="submit" name="btnUpdate" value="Pakket updaten">
            </form>

        <?php
    }
        ?>

</body>

</html>