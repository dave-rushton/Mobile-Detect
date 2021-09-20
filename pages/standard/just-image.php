<?php

require_once('../../config/config.php');
require_once('../../admin/patchworks.php');
require_once("../../admin/website/classes/pageelements.cls.php");

$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

$SeoUrl = (isset($_GET['seourl'])) ? $_GET['seourl'] : NULL;

$Col1Txt = $EleDao->getVariable($EleObj, 'col1txt', false);
$Col2Txt = $EleDao->getVariable($EleObj, 'col2txt', false);
$Col3Txt = $EleDao->getVariable($EleObj, 'col3txt', false);
$Col4Txt = $EleDao->getVariable($EleObj, 'col4txt', false);

$ClsNam = $EleDao->getVariable($EleObj, 'clsnam', false);
$image_style = $EleDao->getVariable($EleObj, 'image_style', false);
$image = $EleDao->getVariable($EleObj, 'imgurl', false);
$imgsiz = $EleDao->getVariable($EleObj, 'imgsiz', false);
$image = str_replace("images/","images/".$imgsiz."/",$image);


$spacing = $EleDao->getVariable($EleObj, 'spacing', false);
$nopadding = $EleDao->getVariable($EleObj, 'nopadding', false);
$nomargin = $EleDao->getVariable($EleObj, 'nomargin', false);
$extrapadding = $EleDao->getVariable($EleObj, 'extrapadding', false);
$extramargin = $EleDao->getVariable($EleObj, 'extramargin', false);
$theme = $EleDao->getVariable($EleObj, 'theme', false);
$animation = $EleDao->getVariable($EleObj, 'animation', false);
$indent = $EleDao->getVariable($EleObj, 'indent', false);
$negmargin = $EleDao->getVariable($EleObj, 'negmargin', false);
$class = $spacing." ".$nopadding." ".$nomargin." ".$extrapadding." ".$extramargin." ".$theme." ".$negmargin;
?>



<div class="section <?php echo $class; ?>">
    <div class="image-wrapper just-image <?php echo $image_style;?>">
        <img src="<?php echo $image;?>" alt="">
    </div>
</div>