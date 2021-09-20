<?php 

require_once('../../config/config.php');
require_once('../../admin/patchworks.php');
require_once("../../admin/website/classes/pageelements.cls.php" );

require_once("../../admin/gallery/classes/uploads.cls.php");

require_once("../../admin/system/classes/subcategories.cls.php");
require_once("../../admin/system/classes/categories.cls.php");

require_once("../../admin/products/classes/products.cls.php");

$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

$SeoUrl = (isset($_GET['seourl'])) ? $_GET['seourl'] : NULL;

$FwdUrl = $EleDao->getVariable($EleObj, 'fwdurl' );

if (isset($FwdUrl) && !is_null($FwdUrl)) $SeoUrl = $FwdUrl;

$PrdCat = $EleDao->getVariable($EleObj, 'prdcat' );

$PerPag = (isset($_GET['perpag']) && is_numeric($_GET['perpag'])) ? $_GET['perpag'] : 12;
$OffSet = (isset($_GET['offset']) && is_numeric($_GET['offset'])) ? $_GET['offset'] : NULL;
$Pag_No = (isset($_GET['pag_no']) && is_numeric($_GET['pag_no'])) ? $_GET['pag_no'] : 1;

$TblNam = 'product-category';

$TmpSub = new SubDAO();
$subCategories = $TmpSub->select(NULL, $PrdCat, NULL, 0, true);

$NumCol = 3;
$NumCol = $EleDao->getVariable($EleObj, 'numcol' );
if (!is_numeric($NumCol)) $NumCol = 3;

$ColWid = 12 / $NumCol;

$UplDao = new UplDAO();

if (isset($subCategories->sub_id)) {

    $TmpPrd = new PrdDAO();
    $products = $TmpPrd->searchProductsByCategory($subCategories->sub_id, NULL, NULL, "RAND()");

} else {
    $products = NULL;
}

?>




<div class="section nomargin">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">

                <h2 class="header"><strong>PRODUCT</strong> SHOWCASE</h2>

            </div>
        </div>

        <div class="row">

            <?php

            $tableLength = count($products);
            if ($tableLength > 4) $tableLength = 4;

            for ($i=0;$i<$tableLength;++$i) {

            if ($products[$i]['prtseo'] != '') {
                $url = $SeoUrl.'/producttype/'.$products[$i]['prt_id'].'/'.$products[$i]['prtseo'];
            } else {
                $url = $SeoUrl.'/product/'.$products[$i]['prd_id'].'/'.$products[$i]['seourl'];
            }

            $uploads = $UplDao->select(NULL, 'PRODUCT', $products[$i]['prd_id'], NULL, false);

            if (count($uploads) == 0) {
                $uploads = $UplDao->select(NULL, 'PRDTYPE', $products[$i]['prt_id'], NULL, false);
            }


            if (
                isset($uploads[0]) &&
                file_exists($patchworks->docRoot.'uploads/images/products/'.$uploads[0]['filnam']) &&
                !is_dir($patchworks->docRoot.'uploads/images/products/'.$uploads[0]['filnam']))
            {
                $image = 'uploads/images/products/'.$uploads[0]['filnam'];
            } else {
                $image = 'http://placehold.it/614x414';
            }

            ?>

            <div class="col-sm-3">

                <div class="producttile">

                    <div class="image" style="background-image: url('<?php echo $image; ?>')">

                    </div>
                    <h3 class="productname">
                        <?php echo $products[$i]['prtnam']; ?><br>

                        <span><?php echo (isset($products[$i]['prtobj'])) ? $patchworks->getJSONVariable($products[$i]['prtobj'], 'subnam', false) : ''; ?></span>
                    </h3>
                    <a href="<?php echo $url; ?>" class="button productlink">VIEW PRODUCT <i class="fa fa-chevron-right"></i></a>

                </div>

            </div>

            <?php } ?>


        </div>
        <div class="row">
            <div class="col-sm-12">
                <hr>
            </div>
        </div>

    </div>
</div>