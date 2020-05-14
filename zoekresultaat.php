<?php 

session_start();

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