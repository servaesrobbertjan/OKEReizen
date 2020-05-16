<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>header</title>
</head>

<body>
    <a href="index.php">Home</a> -
    <a href="publicpage.php">Welkom</a> -
    <a href="review.php">Reviews</a> -
    <?php if (!isset($_SESSION["gebruiker"])) { ?>
        <a href="login.php">Login</a> -
        <a href="registreer.php">Registreren</a> -
    <?php } else { ?>
        <a href="logout.php">Logout</a> -
        <a href="privatepage.php">Privé-pagina</a>
    <?php 
    } 
    ?>
    <td><a href="csv.pdf" target="_blank"></a></td>