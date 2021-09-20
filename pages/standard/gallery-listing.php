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

$Gal_ID = $EleDao->getVariable($EleObj, 'gal_id', false);
$text = $EleDao->getVariable($EleObj, 'text', false);

//DO NOT ADD IN HERE
    $spacing = $EleDao->getVariable($EleObj, 'spacing', false);
    $nopadding = $EleDao->getVariable($EleObj, 'nopadding', false);
    $nomargin = $EleDao->getVariable($EleObj, 'nomargin', false);
    $extrapadding = $EleDao->getVariable($EleObj, 'extrapadding', false);
    $extramargin = $EleDao->getVariable($EleObj, 'extramargin', false);
    $theme = $EleDao->getVariable($EleObj, 'theme', false);
    $animation = $EleDao->getVariable($EleObj, 'animation', false);
    $indent = $EleDao->getVariable($EleObj, 'indent', false);
    $imgsiz = $EleDao->getVariable($EleObj, 'imgsiz', false);
    $negmargin = $EleDao->getVariable($EleObj, 'negmargin', false);
    $gal_id = $EleDao->getVariable($EleObj, 'gal_id', false);
    $class = $spacing." ".$nopadding." ".$nomargin." ".$extrapadding." ".$extramargin." ".$theme." ".$negmargin;
//DO NOT ADD IN HERE

$TmpGal = new GalDAO();
$galleries = $TmpGal->select(NULL, 'WEBGALLERY', NULL, NULL, false);

if (is_numeric($Gal_ID)) {
    $galleryRec = $TmpGal->select($Gal_ID, NULL, NULL, NULL, true);
    $_GET['galseo'] = $galleryRec->seourl;
}

$UplDao = new UplDAO();

?>
<div class="section <?php echo $class;?>">
    <?php
    if(!empty($indent))
        echo '<div class="' . $indent . '">'
    ?>
        <div class="gallery-icons">
            <ul>
                <?php
                $UplObj = $UplDao->select(NULL,'WEBGALLERY',$gal_id);
                $tableLength = count($UplObj);
                for ($i = 0; $i < $tableLength; ++$i) {
                    ?>
                    <li>
                        <?php
                        if(!empty($UplObj[$i]['urllnk'])){
                            ?>
                            <a href="<?php echo $UplObj[$i]['urllnk']; ?>">
                                <span class="img-wrapper">
                                        <img src="<?php echo 'uploads/images/'.$imgsiz."/" . $UplObj[$i]['filnam']; ?>" alt="<?php echo 'uploads/images/' . $UplObj[$i]['uplttl']; ?>">
                                </span>
                                <span class="desc-wrapper">
                                    <?php echo $UplObj[$i]['upldsc']; ?>
                                </span>
                            </a>
                            <?php
                        }else{
                            ?>
                            <span class="img-wrapper">
                                <img src="<?php echo 'uploads/images/'.$imgsiz."/" . $UplObj[$i]['filnam']; ?>" alt="<?php echo 'uploads/images/' . $UplObj[$i]['uplttl']; ?>">
                            </span>
                            <span class="desc-wrapper">
                                    <?php echo $UplObj[$i]['upldsc']; ?>
                                </span>

                            <?php
                        }
                        ?>

                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
    <?php
    if(!empty($indent))
        echo '</div>'
    ?>
</div>