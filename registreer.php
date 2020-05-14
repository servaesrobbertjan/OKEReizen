<?php

session_start();

require_once("klanten.php");

$error = "";

if (isset($_POST["btnRegistreer"])) {
    $email = "";
    $wachtwoord = "";
    $wachtwoordHerhaal = "";
    $naam="";
    $adres="";
    $plaats="";
    $geboortedatum="";

    if (empty($_POST["txtEmail"])) {
        $error .= "Het e-mailadres moet ingevuld worden<br>";
    } else {
        $email = $_POST["txtEmail"];
    }

    if (empty($_POST["txtWachtwoord"]) || empty($_POST["txtWachtwoordHerhaal"])) {
        $error .= "De wachtwoorden moeten ingevuld worden<br>";
    } else {
        $wachtwoord = $_POST["txtWachtwoord"];
        $wachtwoordHerhaal = $_POST["txtWachtwoordHerhaal"];
    }
    if (empty($_POST["txtNaam"])) {
        $error .= "De naam moet ingevuld worden<br>";
    } else {
        $naam = $_POST["txtNaam"];
    }
    if (empty($_POST["txtAdres"])) {
        $error .= "Het adres moet ingevuld worden<br>";
    } else {
        $adres = $_POST["txtAdres"];
    }
    if (empty($_POST["txtPlaats"])) {
        $error .= "De postcode en gemeente moeten ingevuld worden<br>";
    } else {
        $plaats = $_POST["txtPlaats"];
    }
    if (empty($_POST["txtGeboorteDatum"])) {
        $error .= "De geboortedatum moet ingevuld worden<br>";
    } else {
        $geboortedatum = $_POST["txtGeboorteDatum"];
    }

    if ($error == "") {
        // Alles is ingevuld, dus alles kan opgeslagen worden in de database
        try {
            $gebruiker = new Klanten();
            $gebruiker->setNaam($naam);
            $gebruiker->setAdres($adres);
            $gebruiker->setPlaats($plaats);
            $gebruiker->setGeboorteDatum($geboortedatum);
            $gebruiker->setEmail($email);
            $gebruiker->setWachtwoord($wachtwoord, $wachtwoordHerhaal);
            $gebruiker = $gebruiker->registreer();
            $_SESSION["gebruiker"] = serialize($gebruiker);
        } catch (OngeldigEmailadresException $e) {
            $error .= "Er moet een geldig e-mailadres ingevuld worden<br>";
        } catch (WachtwoordenKomenNietOvereenException $e) {
            $error .= "De wachtwoorden moeten overeenkomen<br>";
        } catch (GebruikerBestaatAlException $e) {
            $error .= "Er bestaat al een gebruiker met dit e-mailadres<br>";
        }
    }
}



require_once("header.php");

?>

<h1>Registreren</h1>

<?php
if ($error == "" && isset($_SESSION["gebruiker"])) {
    echo "U bent succesvol geregistreerd.";
} else if ($error !== "") {
    echo "<span style=\"color:red;\">" . $error . "</span>";
}
if (!isset($_SESSION["gebruiker"])) {
?>

    <form action="<?php echo htmlentities($_SERVER["PHP_SELF"]); ?>" method="POST">
        Naam en Voornaam: <input type="text" name="txtNaam"> <br>
        Straat en Huisnummer: <input type="text" name="txtAdres"> <br>
        Postcode en gemeente: <input type="text" name="txtPlaats"> <br>
        geboortedatum: <input type="text" name="txtGeboorteDatum"> <br>
        E-mailadres: <input type="email" name="txtEmail"> <br>
        Wachtwoord: <input type="password" name="txtWachtwoord"> <br>
        Herhaal wachtwoord: <input type="password" name="txtWachtwoordHerhaal"> <br>
        <input type="submit" value="Registreren" name="btnRegistreer">

    </form>

<?php
}

require_once("footer.php");
?>