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
$galleries = $TmpGal->select(NULL, 'DOWNLOAD', NULL, NULL, false);

$UplDao = new UplDAO();

if (isset($_GET['galseo'])) {

    $uploads = $UplDao->selectByGalSeo($_GET['galseo'], false, 'DOWNLOAD');
    if (count($uploads) > 0) {

        ?>

        <div class="section">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">

                        <p><a href="<?php echo $SeoUrl; ?>">Back to libraries</a> </p>

                        <h2><?php echo $uploads[0]['galnam']; ?> Files</h2>
                    </div>
                </div>
                <div class="row">

                    <?php
                    $tableLength = count($uploads);
                    for ($i = 0; $i < $tableLength; ++$i) {
                        ?>

                        <div class="col-sm-3">
                            <a href="<?php echo $patchworks->webRoot; ?>uploads/files/<?php echo $uploads[$i]['filnam']; ?>"
                               target="_blank" class="downloadlink">
                            <span class="box">
                                <span class="textwrap">
                                    <span class="textmain">
                                        <strong><?php echo $uploads[$i]['uplttl']; ?></strong><br><br>
                                        <i><?php echo $uploads[$i]['upldsc']; ?></i>
                                    </span>
                                </span>
                            </span>
                            </a>
                        </div>

                    <?php } ?>

                </div>
            </div>
        </div>


    <?php } ?>

<?php } else { ?>

    <div class="section">
        <div class="container">


            <div class="row">
                <div class="col-sm-12">
                    <h2>Download Libraries</h2>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="image-gal-container">

                        <?php
                        $tableLength = count($galleries);
                        for ($i = 0; $i < $tableLength; ++$i) {

                            if (isset($_GET['galseo']) && $_GET['galseo'] == $galleries[$i]['seourl']) continue;

                            $colWid = ($i == 0 && !isset($_GET['galseo'])) ? '6' : '6';

                            ?>

                            <h3>
                                <a href="<?php echo $SeoUrl . '/gallery/' . $galleries[$i]['seourl']; ?>" class="readmore"><?php echo $galleries[$i]['galnam']; ?></a>
                            </h3>

                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


<?php } ?>