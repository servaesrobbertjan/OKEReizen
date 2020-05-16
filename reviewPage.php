<?php

session_start();
require_once("ReviewClass.php");

$review = "";
$error = "";
$score = "";
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

     /*
    // controleer of klantnummer is gevuld

   if (empty($_POST["txtKlantnummer"])) {
        $error .= "je klantnummer mag niet leeg zijn";
    } else {
        $klantNummer = $_POST["txtKlantnummer"];
    }

    // controleer of reisnummer is gevuld

    if (empty($_POST["txtReisnummer"])) {
        $error .= "je klantnummer mag niet leeg zijn";
    } else {
        $reisNummer = $_POST["txtReisnummer"];
    }
    */
}

//indien review en score ingevuld en verzonden en de user is ingelogd dan voeg bericht toe aan DB

if ($error == "") {
    try {
        $bericht = new review(null, null, $review, $score, null, null);
        $bericht->setReview($review);
        $bericht = $bericht->reviewToevoegen();
    } catch (reviewTeLang $e) {
        $error .= "--uw review is te lang, maximum karakters is 250--";
    }
}

require_once("header.php");

// als formulier verstuurd en error leeg toon bericht anders toon error

if (isset($_POST["knopOK"]) && $error == "") {
    echo "<br><span style=\"color:blue;>\"><b>" . " !!! dank voor uw review !!!" . "</b></span>";
} else if (isset($_POST["knopOK"]) && $error !== "") {
    echo "<br><span style=\"color:red;>\"><b>" . $error . "</b></span>";
}

// als gebruiker is ingelogd toon formulier

if (isset($_SESSION["gebruiker"])) {

?>

    </body>

    <H2>Vul hier je review in</H2>

    <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">

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

            </div>
        </fieldset>
    </form>

    <body>

    <?php
}
// als gebruiker niet is ingelogd toon reviews
    ?>

    <body>

        <H2>Reviews</H2>

        <?php

        $beoordeling = new review();
        $beoordeling = $beoordeling->toonReviews();

        foreach ($beoordeling as $bericht) { ?>

            <ul>
                <fieldset>
                    <?php
                    echo "<b>" . "Naam: " . "</b>" . $bericht->getNaam() . "<br>" .
                        "<b>" . "Stad: " . "</b>" . $bericht->getStad() . "<b>" . "<br>" .
                        "<b>" . "Land: " . "</b>" . $bericht->getLand() . "<b>" . "<br>" .
                        "<b>" . "Score: " . "</b>" . $bericht->getScore() . "<b>" . "<br>" . "<br>" .
                        "<b>" . "Review: " . "</b>" . "<br>" . $bericht->getReview() . "<b>" . "<br>" . "<br>" .
                        $bericht->getDatum() . "<b>" . "<br>";
                    ?>
            </ul>

        <?php
        }

        require_once("footer.php");

        ?>