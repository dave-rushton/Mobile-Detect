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

$class = $nopadding." ".$nomargin." ".$extrapadding." ".$extramargin;

$TmpGal = new GalDAO();
$galleries = $TmpGal->select(NULL, 'WEBGALLERY', NULL, NULL, false);

if (is_numeric($Gal_ID)) {
    $galleryRec = $TmpGal->select($Gal_ID, NULL, NULL, NULL, true);
    $_GET['galseo'] = $galleryRec->seourl;
}

$UplDao = new UplDAO();

?>
<div class="section <?php echo $class;?>">
    <div class="gallery-icons">
        <div class="container  ">
            <?php
            echo $text;
            ?>
        </div>
        <ul>
            <?php
            $UplObj = $UplDao->selectByGalSeo($_GET['galseo'], false, 'WEBGALLERY');
            $tableLength = count($UplObj);
            for ($i = 0; $i < $tableLength; ++$i) {
                ?>
                <li>
                    <img src="<?php echo 'uploads/images/' . $UplObj[$i]['filnam']; ?>" alt="<?php echo 'uploads/images/' . $UplObj[$i]['uplttl']; ?>">
                </li>
                <?php
            }
            ?>
        </ul>
    </div>
</div>