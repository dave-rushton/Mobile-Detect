<?php

require_once('../../config/config.php');
require_once('../../admin/patchworks.php');
require_once("../../admin/website/classes/pageelements.cls.php");

require_once("../../admin/gallery/classes/uploads.cls.php");

require_once("../../admin/system/classes/subcategories.cls.php");
require_once("../../admin/system/classes/categories.cls.php");

require_once("../../admin/products/classes/products.cls.php");

$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

$SeoUrl = (isset($_GET['seourl'])) ? $_GET['seourl'] : NULL;

$FwdUrl = $EleDao->getVariable($EleObj, 'fwdurl');

if (isset($FwdUrl) && !is_null($FwdUrl)) $SeoUrl = $FwdUrl;

$PrdCat = $EleDao->getVariable($EleObj, 'prdcat');

$TblNam = 'product-category';

$TmpSub = new SubDAO();
$subCategories = $TmpSub->select(NULL, $PrdCat, NULL, 0, true);

$UplDao = new UplDAO();

if (isset($subCategories->sub_id)) {

    $TmpPrd = new PrdDAO();
    $products = $TmpPrd->searchProductsByCategory($subCategories->sub_id, NULL, NULL, "RAND()");

} else {
    $products = NULL;
}


?>


<div class="section flexsection">
    <div class="container">

        <div class="row">
            <div class="col-md-12">

                <h3 class="text-center"> <?php echo $subCategories->subnam; ?> </h3>

                <div class="productSlider">
                    <div class="flexslider" data-type="multislide">
                        <ul class="slides">

                            <?php
                            $tableLength = count($products);
                            for ($i = 0; $i < $tableLength; ++$i) {

                                if ($products[$i]['prtseo'] != '') {
                                    $url = $SeoUrl . '/producttype/' . $products[$i]['prt_id'] .'/' . $products[$i]['prtseo'];
                                } else {
                                    $url = $SeoUrl . '/product/' . $products[$i]['prd_id'] .'/' . $products[$i]['seourl'];
                                }

                                $uploads = $UplDao->select(NULL, 'PRODUCT', $products[$i]['prd_id'], NULL, false);

                                if (count($uploads) == 0) {
                                    $uploads = $UplDao->select(NULL, 'PRDTYPE', $products[$i]['prt_id'], NULL, false);
                                }

                                ?>

                                <li>

                                    <div class="productSlide">

                                        <a href="<?php echo $url; ?>" class="productlink">
                                            <?php
                                            if (
                                                isset($uploads[0]) &&
                                                file_exists($patchworks->docRoot . 'uploads/images/products/200-200/' . $uploads[0]['filnam']) &&
                                                !is_dir($patchworks->docRoot . 'uploads/images/products/200-200/' . $uploads[0]['filnam'])
                                            ) {
                                                echo '<img src="uploads/images/products/200-200/' . $uploads[0]['filnam'] . '" class="img-responsive productImage" />';
                                            } else {
                                                echo '<img class="img-responsive productImage" src="http://placehold.it/200x200">';
                                            }
                                            ?>
                                        </a>

                                        <h3><a href="<?php echo $url; ?>" class="productLink"
                                               target="_blank"><?php echo $products[$i]['prdnam']; ?></a><br/>
                                            <span>&pound;<?php echo $products[$i]['unipri']; ?></span></h3>

                                    </div>

                                </li>

                            <?php } ?>

                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>