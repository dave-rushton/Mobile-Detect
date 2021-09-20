<?php

require_once("../../config/config.php");
require_once("../../admin/patchworks.php");
require_once("../../admin/website/classes/pageelements.cls.php");

$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

$eleVarArr = json_decode($EleObj->elevar, true);

$ImgUrl = NULL;
$ImgUrl = $EleDao->getVariable($EleObj, 'imgurl' );
$ImgSiz = $EleDao->getVariable($EleObj, 'imgsiz' );
$Speed = $EleDao->getVariable($EleObj, 'speed' );
$Offset = $EleDao->getVariable($EleObj, 'offset' );
$ImgTtl = $EleDao->getVariable($EleObj, 'imgttl' );

if (empty($Speed)) $Speed = 1;
if (empty($Offset)) $Offset = 0;

$Height = explode("-", $ImgSiz);
$Height = $Height[1];

if (!empty($ImgUrl)) {

?>

    <div class="parallaximage" style="height: <?php echo $Height; ?>px; background-image: url(<?php echo $ImgUrl; ?>);" data-speed="<?php echo $Speed; ?>" data-type="background" data-offsetY="<?php echo $Offset; ?>">

        <div class="textblock">
            <?php echo $ImgTtl; ?>
        </div>

    </div>


<?php
}
?>
