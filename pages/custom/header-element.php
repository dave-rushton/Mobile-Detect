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

$FwdUrl = $EleDao->getVariable($EleObj, 'fwdurl', false);
$text = $EleDao->getVariable($EleObj, 'text', false);
$small = $EleDao->getVariable($EleObj, 'small', false);
$img = $EleDao->getVariable($EleObj, 'imgurl', false);
$imgsiz = $EleDao->getVariable($EleObj, 'imgsiz', false);
$nomargin = $EleDao->getVariable($EleObj, 'nomargin', false);
$nopadding = $EleDao->getVariable($EleObj, 'nopadding', false);
$extramargin = $EleDao->getVariable($EleObj, 'extramargin', false);
$paralax = $EleDao->getVariable($EleObj, 'paralax', false);
$extrapadding = $EleDao->getVariable($EleObj, 'extrapadding', false);
$text = $EleDao->getVariable($EleObj, 'text', false);
$imgicon = $EleDao->getVariable($EleObj, 'imgicon', false);
$contrast = $EleDao->getVariable($EleObj, 'contrast', false);
$size = $EleDao->getVariable($EleObj, 'size', false);
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
if($paralax == 1){
    $paralax = "parallax background";
}
if($contrast == 1){
    $contrast = "contrast";
}

$classname = $nomargin." ".$nopadding." ".$extramargin." ".$extrapadding;

$TmpUpl = new UplDAO();

?>
<section class="<?php echo $classname; ?>">
    <div class="hero-section <?php echo $size." ".$paralax." ".$contrast;?>" style="background-image:url('<?php echo $img;?>');"  data-diff="100">
        <div class="cover">
            <div class="table">
                <div class="cell">
                    <div class="container">
                        <!--  alt="Handcrafted In England Since 1902"-->
                        <?php
                        echo $text;
                        ?>

                        <?php
                        if(!empty($imgicon))
                        echo  '<img src="'. $imgicon .'" >';
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>