<?php

require_once('../../config/config.php');
require_once('../../admin/patchworks.php');
require_once("../../admin/website/classes/pageelements.cls.php");
require_once("../../admin/gallery/classes/gallery.cls.php");
require_once("../../admin/gallery/classes/uploads.cls.php");
$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

$SeoUrl = (isset($_GET['seourl'])) ? $_GET['seourl'] : NULL;

$Col1Txt = $EleDao->getVariable($EleObj, 'col1txt', false);
$Gal_ID = $EleDao->getVariable($EleObj, 'gal_id', false);
$text = $EleDao->getVariable($EleObj, 'text', false);
$nopadding = $EleDao->getVariable($EleObj, 'nopadding', false);
$nomargin = $EleDao->getVariable($EleObj, 'nomargin', false);
$extrapadding = $EleDao->getVariable($EleObj, 'extrapadding', false);
$extramargin = $EleDao->getVariable($EleObj, 'extramargin', false);
$image = $EleDao->getVariable($EleObj, 'imgurl', false);
$imgsiz = $EleDao->getVariable($EleObj, 'imgsiz', false);
$full = $EleDao->getVariable($EleObj, 'full', false);
$paralax = $EleDao->getVariable($EleObj, 'paralax', false);
$height = $EleDao->getVariable($EleObj, 'height', false);
if($height>0 & $height <120){

}else{
    $height = 50;
}
$image = str_replace("images/", "images/" . $imgsiz . "/", $image);

$class = $nopadding . " " . $nomargin . " " . $extrapadding . " " . $extramargin ;

$sizes = (explode("-",$imgsiz));
$imgheight = $sizes[1];
$imgwidth = $sizes[0];
?>
<?php
if($paralax == 1){
    ?>
    <div  data-diff="100"  data-img-width="<?php echo $imgwidth?>" data-img-height="<?php echo $imgheight?>"  class="just-image  parallax background <?php echo $full;?>" style="height:<?php echo $height ?>vw; background-image: url('<?php echo $image; ?>')">
    </div>
    <?php
}else{
    ?>
    <div class="just-image <?php echo $full;?>">
        <img src="<?php echo $image; ?>" alt="">
    </div>
    <?php
}
?>


