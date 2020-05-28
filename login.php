<?php
session_start();
require_once("klanten.php");

$error = "";

if (isset($_POST["btnLogin"])) {
    $email = "";
    $wachtwoord = "";

    if (empty($_POST["txtEmail"])) {
        $error .= "Er moet een e-mailadres ingevuld zijn <br>";
    } else {
        $email = $_POST["txtEmail"];
    }

    if (empty($_POST["txtWachtwoord"])) {
        $error .= "Het wachtwoord moet ingevuld zijn<br>";
    } else {
        $wachtwoord = $_POST["txtWachtwoord"];
    }
    //login
    if ($error == "") {
        try {
            $gebruiker = new klanten(null, null, null, null, null, $email, $wachtwoord);
            $gebruiker = $gebruiker->login();
            $_SESSION["gebruiker"] = serialize($gebruiker);
        } catch (GebruikerBestaatNietException $e) {
            $error .= "Er is geen gebruiker met dit e-mailadres geregistreerd.<br>";
        } catch (WachtwoordIncorrectException $e) {
            $error .= "Het ingegeven wachtwoord is niet correct.<br>";
        }
        if (isset($_SESSION["gekozenreis"])){
            header("Location: reisboeken.php");
            exit;
        }
    }
}

require_once("header.php");

?>
<h1>Inloggen</h1>
<h2>Geef hier je e-mailadres en paswoord in.</h2>
<br>
<div >
<?php

if ($error == "" && isset($_SESSION["gebruiker"])) {
    echo "<p><text>U bent succesvol ingelogd!</text></p>";

    
} else if ($error != "") {
    echo "<span style=\"color:red;\">" . $error . "</span>";
}
if (!isset($_SESSION["gebruiker"])) {
?>

    <form action="<?php echo htmlentities($_SERVER["PHP_SELF"]); ?>" method="POST">
      <p>  E-mailadres: <input type="email" name="txtEmail"></p> <br>
      <p>  Wachtwoord: <input type="password" name="txtWachtwoord"> </p> <br>
        <p> <input type="submit" value="Inloggen" name="btnLogin"> </p>
    </form>

<?php
}
require_once("footer.php");
?>