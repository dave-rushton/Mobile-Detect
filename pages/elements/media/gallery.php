<?php

require_once('../../../config/config.php');
require_once('../../../admin/patchworks.php');
require_once("../../../admin/website/classes/pageelements.cls.php");
require_once("../../../admin/gallery/classes/gallery.cls.php");
require_once("../../../admin/gallery/classes/uploads.cls.php");

$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

$SeoUrl = (isset($_GET['seourl'])) ? $_GET['seourl'] : NULL;

$TmpGal = new GalDAO();
$galleries = $TmpGal->select(NULL, 'WEBGALLERY', NULL, NULL, false);

$Gal_ID = $EleDao->getVariable($EleObj, 'gal_id' );
$ImgSiz = $EleDao->getVariable($EleObj, 'imgsiz' );
$NumCol = $EleDao->getVariable($EleObj, 'numcol' );
$IncOth = $EleDao->getVariable($EleObj, 'incoth' );


if (is_numeric($Gal_ID)) {
    $galleryRec = $TmpGal->select($Gal_ID, NULL, NULL, NULL, true);

    $_GET['galseo'] = $galleryRec->seourl;

}

$UplDao = new UplDAO();
?>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-2">
                &nbsp;
            </div>
            <div class="col-sm-8">

                <?php
                if (isset($_GET['galseo'])) {

                    $UplObj = $UplDao->selectByGalSeo($_GET['galseo'], false, 'WEBGALLERY');
                    if (count($UplObj) > 0) {

                        ?>

                        <h2><?php echo $UplObj[0]['galnam']; ?></h2>

                        <div class="row" id="galleryPopupParent">
                            <?php
                            $tableLength = count($UplObj);
                            for ($i = 0; $i < $tableLength; ++$i) {
                                ?>
                                <div class="col-xs-6 col-sm-3">

                                    <a class="image-link"
                                       href="<?php echo 'uploads/images/' . $UplObj[$i]['filnam']; ?>" title="<?php echo $UplObj[$i]['uplttl']; ?>">

                                        <span style="background-image: url('<?php echo 'uploads/images/169-130/' . $UplObj[$i]['filnam']; ?>')"></span>



                                    </a>
                                </div>
                            <?php } ?>
                        </div>

                    <?php }

                } else { ?>


                <?php } ?>


                <?php
                if ($IncOth == 'on') {

                    echo '<h2>Our Other Galleries</h2>';
                    ?>

                    <div class="row custom">
                        <div class="image-gal-container">

                            <?php
                            $tableLength = count($galleries);
                            for ($i = 0; $i < $tableLength; ++$i) {

                                if (isset($_GET['galseo']) && $_GET['galseo'] == $galleries[$i]['seourl']) continue;

                                $UplObj = $UplDao->select(NULL, 'WEBGALLERY', $galleries[$i]['gal_id'], NULL, false);

                                $colWid = ($i == 0 && !isset($_GET['galseo'])) ? '6' : '6';

                                ?>

                                <div class="col-xs-<?php echo $colWid; ?>">
                                    <a href="<?php echo $patchworks->webRoot . $SeoUrl . '/gallery/' . $galleries[$i]['seourl']; ?>">
                                        <div class="image-gal">

                                            <?php $imgSize = '620-414'; ?>

                                            <img
                                                src="<?php echo 'uploads/images/' . $imgSize . '/' . $UplObj[0]['filnam']; ?>"
                                                alt="<?php echo $UplObj[0]['uplttl']; ?>">

                                            <div class="global-cloud type1a gallery-position">
                                                <div class="table">
                                                    <div class="cell">
                                                        <?php echo $galleries[$i]['galnam']; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                            <?php } ?>
                        </div>
                    </div>

                    <?php
                }
                ?>

            </div>
        </div>
    </div>
</div>