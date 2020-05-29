<?php

session_start();

require_once("klanten.php");
require_once("plaatsen.php");

// datum en leeftijdslimiet bepalen

$today = new DateTime(null, new DateTimeZone('Europe/Brussels'));
$minimumage = $today->modify('-6576 day');
$minleeftijd = $minimumage->format("Y-m-d");

$error = "";

//vallidatie formulier

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
<h2>Vul je gegevens in</h1>
<br>
<?php
if ($error == "" && isset($_SESSION["gebruiker"])) {
    echo "U bent succesvol geregistreerd.";
} else if ($error !== "") {
    echo "<span style=\"color:red;\">" . $error . "</span>";
}
if (!isset($_SESSION["gebruiker"])) {
?>

    <form action="<?php echo htmlentities($_SERVER["PHP_SELF"]); ?>" method="POST">
    <p>    Naam en Voornaam: <input type="text" name="txtNaam" maxlength="64"> </p><br>
    <p> Straat en Huisnummer: <input type="text" name="txtAdres" maxlength="128"></p> <br>
    <p> Postcode en Gemeente: <select name="txtPlaats">
            <?php
            $plaatsen = new plaatsen();
            $plaatsen = $plaatsen->getAlleGemeente();
            foreach ($plaatsen as $plaats) {
            ?>
                <option value="<?php echo $plaats->getPlaatsId()?>"><?php echo $plaats->getPostcode() . " " . $plaats->getGemeente() ?></option><br>
            <?php   
            }
            ?>
            </select></p><br>
      <p>  Geboortedatum: <input type="date" name="txtGeboorteDatum" value="1980-01-01" min="1900-01-01" max="<?php echo  $minleeftijd ?>"> </p><br>
       <p> E-mailadres: <input type="email" name="txtEmail" maxlength="64"></p> <br>
       <p>   Wachtwoord: <input type="password" name="txtWachtwoord"></p> <br>
       <p>  Herhaal wachtwoord: <input type="password" name="txtWachtwoordHerhaal"> </p> <br>
       <p>  <input type="submit" value="Registreren" name="btnRegistreer"> </p>

    </form>

<?php
}
require_once("footer.php");
?>