<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>OKEReizen. Het beste reisbureau in West-Europa</title>
</head>

<body>
    <a href="index.php">Home</a> -
    <a href="allepakketten.php">Onze Pakketten</a> -
<<<<<<< HEAD
    <a href="reviewpage.php">Reviews</a> -
    <?php if (!isset($_SESSION["gebruiker"])) { ?>
        <a href="login.php">Login</a> -
        <a href="registreer.php">Registreren</a>
    <?php } else { ?>
        <a href="logout.php">Logout</a> -
        <a href="klantPage.php">Privé-pagina</a>
=======
    <a href="reviewPage.php">Reviews</a> -
    <?php if (!isset($_SESSION["gebruiker"])) { ?>
        <a href="login.php">Login</a> -
        <a href="registreer.php">Registreren</a> -
        <?php } else { ?>
        <a href="logout.php">Logout</a> -
        <a href="klantPage.php">Mijn OKERreizen</a> -
>>>>>>> efd71088240ab1d2bfe9375971d0eeae07fb9592
    <?php 
    } 
    ?>
    <td><a href="csv.pdf" target="_blank"></a></td>