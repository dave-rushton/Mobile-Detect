<?php

require_once('../../config/config.php');
require_once('../../admin/patchworks.php');
require_once("../../admin/website/classes/pageelements.cls.php");

require_once("../../admin/gallery/classes/uploads.cls.php");

require_once("../../admin/system/classes/subcategories.cls.php");
require_once("../../admin/system/classes/categories.cls.php");

require_once("../../admin/products/classes/products.cls.php");
require_once("../../admin/products/classes/producttypes.cls.php");

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

$UplDao = new UplDAO();

if (isset($subCategories->sub_id)) {

    $TmpPrt = new PrtDAO();
    $TmpPrd = new PrdDAO();
    $products = $TmpPrd->searchProductsByCategory($subCategories->sub_id, NULL, NULL, "RAND()");

} else {
    $products = NULL;
}

?>


<?php
if ($DspTyp == '1') {
    ?>
    <div class="section">
        <div class="container">

            <div class="row">
                <div class="col-md-1">&nbsp;</div>
                <div class="col-md-10">

                    <h3><?php echo $subCategories->subnam; ?></h3>
                    <hr>

                    <div class="productSlider">
                        <div class="flexslider" data-type="multislide" data-numitm="4" data-itmwid="300"
                             data-sldsel="on">
                            <ul class="slides">

                                <?php
                                $tableLength = count($products);
                                for ($i = 0; $i < $tableLength; ++$i) {

                                    if ($products[$i]['prtseo'] != '') {
                                        $url = $SeoUrl . '/producttype/' . $products[$i]['prt_id'] . '/' . $products[$i]['prtseo'];
                                    } else {
                                        $url = $SeoUrl . '/product/' . $products[$i]['prd_id'] . '/' . $products[$i]['seourl'];
                                    }

                                    $uploads = $UplDao->select(NULL, 'PRODUCT', $products[$i]['prd_id'], NULL, false);

                                    if (count($uploads) == 0) {
                                        $uploads = $UplDao->select(NULL, 'PRDTYPE', $products[$i]['prt_id'], NULL, false);
                                    }

                                    ?>

                                    <li>


                                        <div class="productSlide">

                                            <?php
                                            $imagefile = 'pages/img/noimg.png';

                                            if (
                                                isset($uploads[0]) &&
                                                file_exists($patchworks->docRoot . 'uploads/images/products/540-440/' . $uploads[0]['filnam']) &&
                                                !is_dir($patchworks->docRoot . 'uploads/images/products/540-440/' . $uploads[0]['filnam'])
                                            ) {
                                                $imagefile = 'uploads/images/products/540-440/' . $uploads[0]['filnam'];
                                            } else {

                                            }

                                            ?>

                                            <div class="imagewrapper">
                                                <div class="imageholder"
                                                     style="background-image: url('<?php echo $imagefile; ?>');">

                                                </div>
                                            </div>

                                            <?php
                                            if (
                                                isset($uploads[0]) &&
                                                file_exists($patchworks->docRoot . 'uploads/images/products/540-440/' . $uploads[0]['filnam']) &&
                                                !is_dir($patchworks->docRoot . 'uploads/images/products/540-440/' . $uploads[0]['filnam'])
                                            ) {
                                                //echo '<img src="uploads/images/products/540-440/' . $uploads[0]['filnam'] . '" class="img-responsive productImage" />';
                                            } else {
                                                //echo '<img class="img-responsive productImage" src="http://placehold.it/614x414">';
                                            }
                                            ?>

                                            <h3>
                                                <?php echo $products[$i]['prtnam']; ?>
                                            </h3>
                                            <h4>&pound;<?php echo $products[$i]['unipri']; ?></h4>

                                            <div class="description"><?php echo $products[$i]['prtdsc']; ?></div>

                                            <div class="price"><a href="<?php echo $url; ?>">VIEW PRODUCT</a></div>

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

    <?php
} else {
?>

<div class="section">
    <div class="container">

        <div class="row">
            <div class="col-md-1">&nbsp;</div>
            <div class="col-md-10">

                <h3><?php echo $subCategories->subnam; ?></h3>
                <hr>

            </div>
            <div class="col-md-1">&nbsp;</div>
        </div>
        <div class="row">
            <div class="col-md-1">&nbsp;</div>
            <div class="col-md-10">

                <div class="productSlider">
                    <div class="flexslider">
                        <ul class="slides">

                            <?php
                            $tableLength = count($products);
                            for ($i = 0; $i < $tableLength; ++$i) {

                                if ($products[$i]['prtseo'] != '') {
                                    $url = $SeoUrl . '/producttype/' . $products[$i]['prt_id'] .'/' . $products[$i]['prtseo'];
                                } else {
                                    $url = $SeoUrl . '/product/' . $products[$i]['prd_id'] .'/'. $products[$i]['seourl'];
                                }

                                $uploads = $UplDao->select(NULL, 'PRODUCT', $products[$i]['prd_id'], NULL, false);

                                if (count($uploads) == 0) {
                                    $uploads = $UplDao->select(NULL, 'PRDTYPE', $products[$i]['prt_id'], NULL, false);
                                }

                                ?>

                                <li>

                                    <div class="productSlideSingle">

                                        <h3><?php echo $products[$i]['prdnam']; ?></h3>

                                        <p>
                                            <?php echo $products[$i]['prddsc']; ?>
                                        </p>

                                        <p class="links">
                                            <a href="<?php echo $url; ?>" class="readmore">View Product</a>
                                        </p>

                                        <div class="imagewrapper">

                                            <?php
                                            $imgUrl = 'pages/img/noimg.png';
                                            if (
                                                isset($uploads[0]) &&
                                                file_exists($patchworks->docRoot . 'uploads/images/products/540-440/' . $uploads[0]['filnam']) &&
                                                !is_dir($patchworks->docRoot . 'uploads/images/products/540-440/' . $uploads[0]['filnam'])
                                            ) {
                                                $imgUrl = 'uploads/images/products/540-440/' . $uploads[0]['filnam'];
                                            } else {

                                            }
                                            ?>

                                            <div class="image" style="background-image: url('<?php echo $imgUrl; ?>')">

                                            </div>

                                        </div>

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

<?php } ?>