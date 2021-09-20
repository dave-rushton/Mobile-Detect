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

$TmpArt = new ArtDAO();
$articles = $TmpArt->select(NULL, NULL, 2, 1, false);

$TmpUpl = new UplDAO();

?>
<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">

                <div class="pageheading">

                    <h3>Our stories</h3>
                    <h2>LATEST NEWS</h2>

                </div>

            </div>
        </div>

        <div class="row">

            <?php
            $tableLength = count($articles);
            for ($i=0;$i<$tableLength;++$i) {

                $uploads = $TmpUpl->select(NULL, 'ARTICLE', $articles[$i]['art_id'], NULL, false);

                $imgUrl = 'pages/img/noimg.png';

                if (
                    file_exists($patchworks->docRoot . 'uploads/images/' . $uploads[0]['filnam']) &&
                    !is_dir($patchworks->docRoot . 'uploads/images/' . $uploads[0]['filnam'])
                ) {
                    $imgUrl = $patchworks->webRoot.'uploads/images/'.$uploads[0]['filnam'];
                }

            ?>

            <div class="col-sm-6">
                <div class="homenewsitem">

                    <a href="<?php echo $patchworks->webRoot.$FwdUrl.'/article/'.$articles[$i]['seourl']; ?>" class="newsimage" style="background-image: url('<?php echo $imgUrl; ?>');">

                        <div class="date">
                            <?php echo date("d", strtotime($articles[$i]['artdat'])) ?>
                            <span><?php echo date("M", strtotime($articles[$i]['artdat'])) ?></span>
                        </div>

                    </a>

                    <h3 class="newstitle">
                        <?php echo $articles[$i]['artttl'] ?>
                    </h3>
                    <p class="newsdescription">
                        <?php echo nl2br($articles[$i]['artdsc']); ?>
                    </p>

                </div>
            </div>

            <?php } ?>


        </div>

    </div>
</div>
