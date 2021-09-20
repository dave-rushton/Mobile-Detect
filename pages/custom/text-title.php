<?php

require_once('../../config/config.php');
require_once('../../admin/patchworks.php');
require_once("../../admin/website/classes/pageelements.cls.php");
require_once("../../admin/website/classes/articles.cls.php");
require_once("../../admin/gallery/classes/uploads.cls.php");

$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

$SeoUrl = (isset($_GET['seourl'])) ? $_GET['seourl'] : NULL;

$text = $EleDao->getVariable($EleObj, 'text', false);
$title = $EleDao->getVariable($EleObj, 'title', false);
$nomargin = $EleDao->getVariable($EleObj, 'nomargin', false);
$nopadding = $EleDao->getVariable($EleObj, 'nopadding', false);
$extramargin = $EleDao->getVariable($EleObj, 'extramargin', false);
$extrapadding = $EleDao->getVariable($EleObj, 'extrapadding', false);
$multiplier = $EleDao->getVariable($EleObj, 'multiplier', false);
$vspacing = $EleDao->getVariable($EleObj, 'vspacing', false);
$adjusted = $EleDao->getVariable($EleObj, 'adjusted', false);

$TmpArt = new ArtDAO();
$articles = $TmpArt->select(NULL, NULL, 2, 1, false);

$img = str_replace("uploads/images/","uploads/images/".$imgsiz."/",$img);
$graphic = str_replace("uploads/images/","uploads/images/".$imgsiz."/",$img);

if($nomargin == 1){
    $nomargin = "nomargin";
}
if($nopadding == 1){
    $nopadding = "nopadding";
}
if($extramargin == 1){
    $extramargin = "extramargin";
}
if($extrapadding == 1){
    $extrapadding = "extrapadding";
}

$classname = $multiplier." ".$nomargin." ".$nopadding." ".$extramargin." ".$extrapadding;

$TmpUpl = new UplDAO();

?>
<section class="<?php echo $classname ; ?>">
    <div class="container">
        <div class="text-title <?php echo $vspacing." ".$adjusted;?>">
            <div class="title">
                <?php echo $title; ?>
            </div>
            <div class="text">
                <?php echo $text; ?>
            </div>
        </div>
    </div>
</section>