<?php 

session_start();
require_once("klanten.php");

if (!isset($_SESSION["gebruiker"])) {
    // Er is niemand ingelogd
    header("Location: index.php");
    exit;
}

$gebruiker = unserialize($_SESSION["gebruiker"], ["klant"]);

// Start van de header html
require_once("header.php");
// Einde van de header html
?>

<h1>OKEReizen Klanten-pagina</h1>
<h2>Welkom <?php echo $gebruiker->getEmail(); ?></h2>
<p>Deze pagina is enkel toegankelijk voor ingelogde gebruikers.</p>

<?php 
// start van de footer html
require_once("footer.php");
?>