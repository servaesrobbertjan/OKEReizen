<?php

require_once("klanten.php");
?>

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
            <a href="hoteldetail.php"> Onze Hotels </a>|
            <a href="reviewPage.php"> Reviews </a>|
            <?php if (!isset($_SESSION["gebruiker"])) {
                ?>
                <a href="login.php"> Login </a>|
                <a href="registreer.php"> Registreren </a>
            <?php } else { 
                if (isset($_SESSION["gebruiker"])) {

                    $gebruiker = unserialize($_SESSION["gebruiker"]);
                    $naam = $gebruiker->getNaam();
                }
                ?>
                <a href="logout.php"> Logout </a>|
                <a href="klantPage.php"> Mijn OKERreizen </a>
                <?php if ($naam=="Admin") { ?>
            <td><a href="CSV.php" target="_blank">| Administrator</a></td>
            <?php
            }
        }
            ?>
           
        </div>
    </div>
    <div class=wrapper>
