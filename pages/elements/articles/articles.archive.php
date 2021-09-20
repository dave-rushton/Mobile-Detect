<?php

require_once("../../../config/config.php");
require_once("../../../admin/patchworks.php");
require_once("../../../admin/website/classes/pageelements.cls.php");
require_once("../../../admin/website/classes/articles.cls.php");

$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

$ArtSeo = (isset($_GET['artseo'])) ? $_GET['artseo'] : NULL;
$SeoUrl = (isset($_GET['seourl'])) ? $_GET['seourl'] : NULL;

$ClsNam = $EleDao->getVariable($EleObj, 'clsnam' );

$TmpArt = new ArtDAO();
?>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">

                <div class="<?php echo $ClsNam; ?>">
                <?php $TmpArt->getArticlesArchive( $SeoUrl ); ?>
                </div>

            </div>
        </div>
    </div>
</div>