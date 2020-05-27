<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" type="text/css" href="layout.css">
    <meta charset="UTF-8">
    <title>OKEReizen. Het beste reisbureau in West-Europa</title>
</head>

<body>
    <div class=menu>
        <div class=logo>
           <a href="index.php"> <img src="OKER.png"> </a>
        </div>
        <div class=menutext>
            <a href="index.php"> Home </a> |
            <a href="allepakketten.php"> Onze Pakketten </a>|
            <a href="reviewPage.php"> Reviews </a>|
            <?php if (!isset($_SESSION["gebruiker"])) { ?>
                <a href="login.php"> Login </a>|
                <a href="registreer.php"> Registreren </a>
            <?php } else { ?>
                <a href="logout.php"> Logout </a>|
                <a href="klantPage.php"> Mijn OKERreizen </a>
            <?php
            }
            ?>
            <td><a href="csv.pdf" target="_blank"></a></td>
        </div>
    </div>
    <div class=wrapper>