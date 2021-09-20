<?php

require_once("../../../config/config.php");
require_once("../../../admin/patchworks.php");
require_once("../../../admin/website/classes/pageelements.cls.php");

$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

$ImgUrl = NULL;
$ImgUrl = $EleDao->getVariable($EleObj, 'imgurl', false );

$SecTxt = $EleDao->getVariable($EleObj, 'sectxt', false );

$SecSiz = $EleDao->getVariable($EleObj, 'secsiz', false );
$SecDif = $EleDao->getVariable($EleObj, 'secdif', false );

$ClsNam = $EleDao->getVariable($EleObj, 'clsnam', false );

if (!empty($ImgUrl)) {

    list($width, $height, $type, $attr) = getimagesize($patchworks->docRoot.$ImgUrl);

?>

<div class="background parallax <?php echo $SecSiz; ?>" style="background-image:url(<?php echo $ImgUrl; ?>);" data-img-width="<?php echo $width; ?>" data-img-height="<?php echo $height; ?>" data-diff="<?php echo $SecDif; ?>">
    <div class="content-a">
        <div class="content-b">
            <?php echo (!empty($ClsNam)) ? '<div class="'.$ClsNam.'">' : ''; ?>
            <?php echo $SecTxt; ?>
            <?php echo (!empty($ClsNam)) ? '</div>' : ''; ?>
        </div>
    </div>
</div>

<?php
}
?>