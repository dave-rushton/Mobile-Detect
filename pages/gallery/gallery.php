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

$TmpGal = new GalDAO();
$galleries = $TmpGal->select(NULL, 'WEBGALLERY', NULL, NULL, false);

$UplDao = new UplDAO();

if (isset($_GET['galseo'])) {

    $UplObj = $UplDao->selectByGalSeo($_GET['galseo'], false, 'WEBGALLERY');
    if (count($UplObj) > 0) {

?>

        <h2><?php echo $UplObj[0]['galnam']; ?> Gallery</h2>

        <div class="row" id="galleryPopupParent">
        <?php
        $tableLength = count($UplObj);
        for ($i=0;$i<$tableLength;++$i) {
            ?>
            <div class="col-md-6">

                <a class="image-link" href="<?php echo 'uploads/images/'.$UplObj[$i]['filnam']; ?>">

                <img src="<?php echo 'uploads/images/750-400/'.$UplObj[$i]['filnam']; ?>" alt="<?php echo $UplObj[$i]['uplttl']; ?>" style="margin-bottom: 30px;">

                </a>
            </div>
        <?php } ?>
        </div>

<?php } echo '<h2>Our Other Galleries</h2>'; } else { ?>


<?php } ?>

<div class="row custom">
    <div class="image-gal-container">

        <?php
        $tableLength = count($galleries);
        for ($i=0;$i<$tableLength;++$i) {

            if (isset($_GET['galseo']) && $_GET['galseo'] == $galleries[$i]['seourl']) continue;

            $UplObj = $UplDao->select(NULL, 'WEBGALLERY', $galleries[$i]['gal_id'], NULL, false);

            $colWid = ($i == 0 && !isset($_GET['galseo'])) ? '6' : '6';

        ?>

        <div class="col-xs-<?php echo $colWid; ?>">
            <a href="<?php echo $patchworks->webRoot.$SeoUrl.'/gallery/'.$galleries[$i]['seourl']; ?>">
                <div class="image-gal">

                    <?php $imgSize = ($i == 0 && !isset($_GET['galseo'])) ? '750-400' : '750-400'; ?>

                    <img src="<?php echo 'uploads/images/'.$imgSize.'/'.$UplObj[0]['filnam']; ?>" alt="<?php echo $UplObj[0]['uplttl']; ?>">

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
