<?php
session_start();
require_once("pakket.php");


/* controle dat zeker enkel de admin of de andere drie beheerders op deze pagina kunnen */

if (isset($_SESSION["gebruiker"])) {

    $gebruiker = unserialize($_SESSION["gebruiker"]);
    $gebruiker = $gebruiker->getId();

    if ($gebruiker < 0 || $gebruiker > 4) {

        header("Location: pakketdetail.php");
        exit;
    }
} else {

    header("Location: pakketdetail.php");
    exit;
}


$pakket = "";
$error = "";
$id = "";

/* id ophalen via GET */

if (isset($_GET["id"])) {
    $id = $_GET["id"];
} else {

    if (isset($_POST["id"])) {
        $id = $_POST["id"];
    } else {

        header("Location: allepakketten.php");
        exit;
    }
}

$pakketObj = new Pakket();
$pakket = $pakketObj->getPakketById($id);
/* Deleteknop: Pakket ophalen via id en controles invoer */

if (isset($_POST["btnDelete"]) && !empty($id)) {

    $pakketObj->deletePakketByID($id);


} else if (isset($_POST["btnUpdate"])) {


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
        $pakketObj = new Pakket($id, $pakketOmschrijving, null, null, null, null, null, $pakketPrijs, null);
        $pakketObj->updatePakket();
        $pakket = $pakketObj->getPakketById($id);
    }
} else {
  
   
   
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <h1>Update en delete pakketreizen</h1>
</head>

<body>

    <?php
    if (isset($_POST["btnDelete"])) {

        echo "<p><b>Pakket<b> \"" . $pakket->getOmschrijving() . "\" verwijderd</p>";
        echo "<p><b>Prijs<b> \"" . $pakket->getPrijs() . "\" <b>verwijderd</b></p>";


        echo "Klik <a href=\"allepakketten.php\">hier</a> om terug te gaan naar de overzichtspagina.";
    } else if (isset($_POST["btnUpdate"])) {
        echo "<p><b>Pakket</b> \"" . $pakket->getOmschrijving() . "\" <b>geüpdatet</b></p>";
        echo "<p><b>Prijs</b> \"" . $pakket->getPrijs() . "\" <b>geüpdatet</b></p>";
        echo "Nieuwe waarden:<br>";
        echo "<ul>";
        echo "<li><b>Omschrijving:</b> " . $pakket->getOmschrijving() . "</li>";
        echo "<li><b>Prijs:</b> " . $pakket->getPrijs() . "</li>";
        echo "</ul>";
        echo "Klik <a href=\"allepakketten.php\">hier</a> om terug te gaan naar de overzichtspagina.";
    } else {
    ?>

        <h2>pakket: <?php echo $pakket->getReisId() . " " . $pakket->getStad() . " (" . $pakket->getLand() . ")"; ?></h2>
        <form action="<?php echo htmlentities($_SERVER["PHP_SELF"]); ?>" method="POST">
            Omschrijving : <br><textarea name="txtOmschrijving" rows="5"><?php echo $pakket->getOmschrijving(); ?></textarea><br><br>
            Prijs: <input type="number" name="txtPrijs" value="<?php echo $pakket->getPrijs(); ?>"><br>
            <input type="hidden" value="<?php echo $id;?>" name="id">
<br>
            <input type="submit" name="btnDelete" value="Pakket verwijderen">
            <input type="submit" name="btnUpdate" value="Pakket updaten">
        </form>

      <a href="pakketdetail.php"> Terug naar de site <a>

    <?php
    }
    ?>

</body>

</html>