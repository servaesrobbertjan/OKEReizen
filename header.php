<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>header</title>
</head>

<body>
    <a href="index.php">Home</a> -
    <a href="allepakketten.php">Onze Pakketten</a> -
    <a href="review.php">Reviews</a> -
    <?php if (!isset($_SESSION["gebruiker"])) { ?>
        <a href="login.php">Login</a> -
<<<<<<< HEAD
        <a href="registreer.php">Registreren</a> -
=======
        <a href="registreer.php">Registreren </a>
>>>>>>> 5a3a953147583a023c9d6c13c4cbf9e51c7f137b
    <?php } else { ?>
        <a href="logout.php">Logout</a> -
        <a href="privatepage.php">Privé-pagina</a>
    <?php 
    } 
    ?>
    <td><a href="csv.pdf" target="_blank"></a></td>