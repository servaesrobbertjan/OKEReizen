<?php
session_start();
require_once("Hotels.php");

$error = "";

if (isset($_POST["CSVSubmit"])) {

    try {
        $CSVObj = new Hotels();
        $CSVObj->CSVImport($_FILES["CSVBestand"]["tmp_name"]);
    }catch(GeenCSVOpgeladen $e) {
        $error .= "Er is geen CSV bestand geslecteerd.";
    }
}

require_once("header.php");
?>

<h1>CSV Importeren</h1>

<?php

if ($error == "" && isset($_POST["CSVSubmit"])) {
    echo "U hebt het CSV bestand succesvol geimporteerd.";
} else if ($error != "") {
    echo"<span style=\"color:red;\">" . $error . "</span>";
}
if (!isset($_POST["CSVSubmit"])) {
?>

<form action="<?php echo htmlentities($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
    Selecteer het CSV bestand:<input type="file" name="CSVBestand"><br>
    <input type="submit" value="CSV bestand importeren" name="CSVSubmit">
</form>

<?php 
} 
require_once("footer.php");
?>