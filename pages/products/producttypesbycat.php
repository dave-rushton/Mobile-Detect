<?php

require_once('../../config/config.php');
require_once('../../admin/patchworks.php');
require_once("../../admin/website/classes/pageelements.cls.php");

require_once("../../admin/gallery/classes/uploads.cls.php");

require_once("../../admin/system/classes/subcategories.cls.php");
require_once("../../admin/system/classes/categories.cls.php");

require_once("../../admin/products/classes/products.cls.php");
require_once("../../admin/products/classes/product_types.cls.php");

$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

$SeoUrl = (isset($_GET['seourl'])) ? $_GET['seourl'] : NULL;

$FwdUrl = $EleDao->getVariable($EleObj, 'fwdurl');

if (isset($FwdUrl) && !is_null($FwdUrl)) $SeoUrl = $FwdUrl;

$PrdCat = $EleDao->getVariable($EleObj, 'prdcat');
$DspTyp = $EleDao->getVariable($EleObj, 'dsptyp');
if (is_null($DspTyp)) $DspTyp = '1';

$PerPag = (isset($_GET['perpag']) && is_numeric($_GET['perpag'])) ? $_GET['perpag'] : 12;
$OffSet = (isset($_GET['offset']) && is_numeric($_GET['offset'])) ? $_GET['offset'] : NULL;
$Pag_No = (isset($_GET['pag_no']) && is_numeric($_GET['pag_no'])) ? $_GET['pag_no'] : 1;

$TblNam = 'product-category';

$TmpSub = new SubDAO();
$subCategories = $TmpSub->select(NULL, $PrdCat, NULL, 0, true);

$NumCol = 3;
$NumCol = $EleDao->getVariable($EleObj, 'numcol');
if (!is_numeric($NumCol)) $NumCol = 3;

$ColWid = 12 / $NumCol;

$TmpPrt = new PrtDAO();
$UplDao = new UplDAO();

if (isset($subCategories->sub_id)) {

    $TmpPrd = new PrdDAO();
    $products = $TmpPrd->searchProductsByCategory($subCategories->sub_id, NULL, NULL, "RAND()");

} else {
    $products = NULL;
}

?>


<?php
if (isset($subCategories->subnam)) {
    ?>

    <div class="section">
        <div class="container">

            <div class="row">
                <div class="col-md-12">

                    <h3><?php echo $subCategories->subnam; ?></h3>
                    <hr>

                </div>
            </div>
            <div class="row">


                        <?php
                        $tableLength = count($products);
                        for ($i = 0; $i < $tableLength; ++$i) {

                            if (isset($products[$i]['prtseo']) && $products[$i]['prtseo'] != '') {
                                $url = $SeoUrl . '/productlist/' . $products[$i]['prt_id'] . '/' . $products[$i]['prtseo'];
                            } else {
                                $url = $SeoUrl . '/productlist/' . $products[$i]['prt_id'] . '/' . $products[$i]['seourl'];
                            }

                            $uploads = $UplDao->select(NULL, 'PRDTYPE', $products[$i]['prt_id'], NULL, false);

                            $className = '';
                            if (
                                isset($uploads[0]) &&
                                file_exists($patchworks->docRoot . 'uploads/images/products/169-130/' . $uploads[0]['filnam']) &&
                                !is_dir($patchworks->docRoot . 'uploads/images/products/169-130/' . $uploads[0]['filnam'])
                            ) {
                                $fileName = 'uploads/images/products/169-130/' . $uploads[0]['filnam'];
                                $altName = $uploads[0]['uplttl'];
                            } else {

                                $productImages = $TmpPrt->getProductImage($products[$i]['prt_id']);

                                if (
                                    isset($productImages[0]) &&
                                    file_exists($patchworks->docRoot . 'uploads/images/products/169-130/' . $productImages[0]['filnam']) &&
                                    !is_dir($patchworks->docRoot . 'uploads/images/products/169-130/' . $productImages[0]['filnam'])
                                ) {
                                    $fileName = 'uploads/images/products/169-130/' . $productImages[0]['filnam'];
                                    $altName = $productImages[0]['uplttl'];
                                } else {
                                    $fileName = 'pages/img/noimg.png';
                                    $altName = 'No Image';
                                    $className = 'noimg';
                                }

                            }

                            //
                            // IMAGE TAGS
                            //
                            $customValue = (!empty($products[$i]['prtobj'])) ? $patchworks->getJSONVariable($products[$i]['prtobj'], 'imagetags', false) : '';
                            $pcTagArray = explode(",", $customValue);

                            ?>

                            <div class="col-xs-6 col-sm-4 col-md-3">


                                <a href="<?php echo $url; ?>" class="shopCategoryLink">
                                    <span class="imagewrapper">
                                        <span class="image" style="background-image: url('<?php echo $fileName; ?>')">

                                            <span class="imgtags">
                                            <?php
                                            for ($t=0;$t<count($pcTagArray);$t++) {

                                                if (!empty($pcTagArray[$t]))echo '<span class="imgtag">'.$pcTagArray[$t].'</span>';
                                            }
                                            ?>
                                            </span>

                                        </span>
                                    </span>
                                    <span class="content">
                                        <h2><?php echo $products[$i]['prtnam']; ?></h2>
                                        <p>from &pound;<?php echo $products[$i]['unipri']; ?></p>
                                    </span>
                                </a>

                            </div>

                        <?php } ?>

                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php
}
?>