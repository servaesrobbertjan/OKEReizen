<?php

session_start();
require_once("ReviewClass.php");
require_once("klanten.php");
require_once("pakket.php");

$review = "";
$error = "";
$score = "";
$reisNummer = "";
$naam = "";

// controleer of review is verstuurd en gebruiker is ingelogd

if (isset($_POST["knopOK"]) && isset($_SESSION["gebruiker"])) {

    // controleer of review is gevuld

    if (empty($_POST["txtReview"])) {
        $error .= "je review mag niet leeg zijn";
    } else {
        $review = $_POST["txtReview"];
    }

    // controleer of score is gevuld

    if (empty($_POST["txtScore"])) {
        $error .= "je score mag niet leeg zijn";
    } else {
        $score = $_POST["txtScore"];
    }

    // controleer of reisnummer is gevuld

    if (empty($_SESSION["reisNummer"])) {
        $error .= "je reisnummer mag niet leeg zijn";
    } else {
        $reisNummer = $_SESSION["reisNummer"];
    }
}

//indien review en score ingevuld en verzonden en de user is ingelogd dan voeg bericht toe aan DB

if ($error == "" && isset($_SESSION["gebruiker"])) {
    try {
        $gebruiker = unserialize($_SESSION["gebruiker"]);
        $klantNummer = $gebruiker->getId();
        $bericht = new review(null, null, $review, $score, null, null, null, $klantNummer, $reisNummer);
        $bericht->setReview($review);
        $bericht = $bericht->reviewToevoegen();
       
        if (isset($_POST["knopOK"])) {

            unset($_SESSION["reisNummer"]);
         }


    } catch (reviewTeLang $e) {
        $error .= "--uw review is te lang, maximum karakters is 250--";
    }

}

// als formulier verstuurd en error leeg toon bericht anders toon error





require_once("header.php");

if (isset($_POST["knopOK"]) && $error == "") {
    echo "<br><span style=\"color:blue;>\"><b><p>" . " !!! dank voor uw review !!!" . "</p></b></span>";
} else if (isset($_POST["knopOK"]) && $error !== "") {
    echo "<br><span style=\"color:red;>\"><b>" . $error . "</b></span>";
}

// als gebruiker is ingelogd toon formulier

if (isset($_SESSION["gebruiker"])) {

?>


    <?php if (isset($_SESSION["gebruiker"]) && isset($_SESSION["reisNummer"])) { 
        $pakketObj = new Pakket();
        $pakket = $pakketObj->getPakketById($_SESSION["reisNummer"]);
        ?>

        <H2>Vul hier je review in</H2>

        <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
            <p>Reisnummer:</p> <?php echo "<p><text>". $_SESSION["reisNummer"] . "</text></p>"; ?>
            <p>Bestemming:</p> <?php echo "<p><text>". $pakket->getStad() ." (". $pakket->getLand() . ")</text></p>"
                        ?> <br>
            <ul>
                <fieldset>

                    <legend>Review jouw reis </legend><br>
                    Score: <br> <input type="radio" name="txtScore" value="0">0
                    <input type="radio" name="txtScore" value="1">1
                    <input type="radio" name="txtScore" value="2">2
                    <input type="radio" name="txtScore" value="3">3
                    <input type="radio" name="txtScore" value="4">4
                    <input type="radio" name="txtScore" value="5">5 <br><br>
                    Vertel ons hier jouw ervaring: <br> <textarea rows="5" cols="50" name="txtReview"></textarea> <br><br>

                    <input type="submit" name="knopOK" value="verzenden">

                </fieldset>
            </ul>
        </form>

    <?php } ?>

 

    <?php

}
// als gebruiker niet is ingelogd toon reviews
    ?>


        <H2>Reviews</H2>

        <?php

        $beoordeling = new review();
        $beoordeling = $beoordeling->toonReviews();



        foreach ($beoordeling as $bericht) { ?>
            <div class="plain">
                <ul>
                    <fieldset>
                        <?php
                        echo "<b>" . "Naam: " . "</b>" . $bericht->getNaam() . "<br>" .
                            "<b>" . "Stad: " . "</b>" . $bericht->getStad()  . "<br>" .
                            "<b>" . "Land: " . "</b>" . $bericht->getLand() . "<br>" .
                            "<b>" . "Score: " . "</b>" . $bericht->getScore() . "<br>" . "<br>" .
                            "<b>" . "Review: " . "</b>" . "<br>" . $bericht->getReview() . "<br>" . "<br><div style=\"text-align:right\">" .
                            $bericht->getDatum() . "<div><br>";
                        ?>
                    </fieldset>
                </ul>
                <div>




                <?php
            }
                ?>
                <p><text><a href="index.php">Terug naar de startpagina</a></text></p>
                <?php

                require_once("footer.php");

                ?>