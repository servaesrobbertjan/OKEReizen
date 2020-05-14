<?php 

session_start();
require_once("pakket.php");

$pakketObj = new Pakket();
$PakketenLijst = $pakketObj->getAllePakketten();

?>
<?php
require_once("header.php");
?>

    <ul>
        <?php 
        
       
        
        ?>
    </ul>

</body>
<?php
require_once("footer.php");
?>