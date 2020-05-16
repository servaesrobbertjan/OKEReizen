<?php
session_start();
require_once("hotels.php");
require_once("updatEnDeleteDet.php");


$error = "";

if (isset($_POST["btnDelete"])) {
    $hotelId = $_POST["hotelId"];
    $hotelObj = new Hotels();
    $hotel = $hotelObj->getHotelByID($hotelId);
    $hotelObj->deleteHotelByID($hotelId);
} else if (isset($_POST["btnUpdate"])) {
    $hotelId = $_POST["hotelId"];

    if (!empty($_POST["txtHotelNaam"])) {
        $hotelNaam = $_POST["txtHotelNaam"];
    } else {
        $error .= "De naam van het hotel mag niet leeg zijn.<br>";
    }

    if (!empty($_POST["txtHotelTelefoon"])) {
        $hotelTelefoon = $_POST["txtHotelTelefoon"];

    } else {
        $error .= "De telefoonnummer moet ingevuld zijn.<br>";
    }
   
    if (!empty($_POST["txtHotelEmail"])) {
        $hotelEmail = $_POST["txtHotelEmail"];

    } else {
        $error .= "Het E-mailadres moet ingevuld zijn.<br>";
    }

    if ($error == "") {
        $hotelObj = new Hotels($hotelId, $hotelNaam, $hotelTelefoon, $hotelEmail);
        $hotelObj->updateHotel();
        $hotel = $hotelObj->getHotelByID($hotelId);
    }
} else {
    $hotelObj = new Hotels();
    $hotel = $hotelObj->getHotelByID($hotelId);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>update en delete hotels</title>
</head>

<body>

    <?php
    if (isset($_POST["btnDelete"])) {

        echo "<h1>Hotel \"" . $hotel->getHotelNaam() . "\" verwijderd</h1>";

        echo "Klik <a href=\"oefening5.php\">hier</a> om terug te gaan naar de overzichtspagina.";
    } else if (isset($_POST["btnUpdate"])) {
        echo "<h1>Hotel \"" . $hotel->getHotelNaam() . "\" geüpdatet</h1>";
        echo "Nieuwe waarden:<br>";
        echo "<ul>";
        echo "<li>Naam: " . $hotel->getHotelNaam() . "</li>";
        echo "<li>Telefoonnummer: " . $hotel->getHotelTelefoon() . "</li>";
        echo "<li>E-mail: " . $hotel->getHotelEmail() . "</li>";
        echo "</ul>";
        echo "Klik <a href=\"deleteEnUpdateHotel.php\">hier</a> om terug te gaan naar de overzichtspagina.";
    } else {
    ?>

        <h1>Hotel: <?php echo $hotel->getHotelNaam(); ?></h1>
        <form action="<?php echo htmlentities($_SERVER["PHP_SELF"]); ?>" method="POST">
            Naam: <input type="text" name="txtHotelNaam" value="<?php echo $hotel->getHotelNaam(); ?>"><br>
            telefoonnummer: <input type="text" name="txtHotelTelefoon" value="<?php echo $hotel->getHotelTelefoon(); ?>"><br>
            E-mail adres: <input type="text" name="txtHotelEmail" value="<?php echo $hotel->getHotelEmail(); ?>"><br>
            <input type="hidden" value="<?php echo $hotelId; ?>" name="hotelId">
            
            <input type="submit" name="btnDelete" value="Hotel verwijderen">
            <input type="submit" name="btnUpdate" value="Hotel updaten">
            </form>

        <?php
    }
        ?>

</body>

</html>