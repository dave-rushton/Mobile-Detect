<?php

require_once("../../config/config.php");
require_once("../../admin/patchworks.php");
require_once("../../admin/website/classes/pageelements.cls.php");
require_once("../../admin/products/classes/structure.cls.php");
require_once("../../admin/gallery/classes/uploads.cls.php");
require_once("../../admin/system/classes/related.cls.php");
require_once("../../admin/products/classes/products.cls.php");
require_once("../../admin/products/classes/product_types.cls.php");
require_once("../../admin/ecommerce/classes/ecommprop.cls.php");

$EleDao = new PelDAO();
$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

$TmpEco = new EcoDAO();
$eCommProp = $TmpEco->select(true);

$TmpStr = new StrDAO();
$TmpUpl = new UplDAO();

$IncCat = $EleDao->getVariable($EleObj, 'inccat');
$IncPrd = $EleDao->getVariable($EleObj, 'incprd');
$StrSeo = $EleDao->getVariable($EleObj, 'str_id');
$FwdUrl = $EleDao->getVariable($EleObj, 'fwdurl');
$structureRec = $TmpStr->selectBySeo($StrSeo, NULL, NULL, true);

if (!isset($structureRec->str_id)) die();

$Str_ID = $structureRec->str_id;

$displayCurrency = '&pound;';

?>

<div class="section nopadding nomargin">

    <div class="container">

        <?php
        if ($IncCat == 'on') {
            ?>

            <div class="row">
                <div class="col-sm-12">

                    <div class="row">

                        <div class="homePageCategories">
                            <?php

                            $homePage = $TmpStr->selectLevel($Str_ID, NULL, NULL, false);

                            for ($i = 0; $i < count($homePage); $i++) {

                                $uploads = $TmpUpl->select(NULL, 'STRUCTURE', $homePage[$i]['str_id'], NULL, false);

                                $className = 'noimg';
                                $fileName = 'pages/img/noimg.png';
                                if (isset($uploads) && isset($uploads[0])) {
                                    $fileName = $patchworks->webRoot . 'uploads/images/products/' . $uploads[0]['filnam'];
                                    $className = '';
                                }

                                $description = $patchworks->getJSONVariable($homePage[$i]['strobj'], 'strdsc', false);

                                ?>
                                <div class="col-sm-4">

                                    <div class="<?php echo $className; ?>">

                                        <div class="productLink <?php echo $className; ?>">

                                            <a href="<?php echo $FwdUrl . '/category/' . $homePage[$i]['str_id'] . '/' . $homePage[$i]['seourl']; ?>"
                                               class="productimage">
                                                <div class="imageholder"
                                                     style="background-image: url('<?php echo $fileName; ?>');">

                                                </div>
                                            </a>

                                            <h2>
                                                <span>
                                                <?php echo $homePage[$i]['strnam']; ?>
                                                </span>

                                            </h2>

                                        </div>

                                    </div>


                                </div>
                                <?php

                            }

                            ?>

                        </div>
                    </div>

                </div>


            </div>

            <?php
        }
        ?>

        <div class="row">

        <?php


        if ($IncPrd == 'on') {

            $TmpUpl = new UplDAO();
            $TmpRel = new RelDAO();
            $TmpPrd = new PrdDAO();
            $TmpPrt = new PrtDAO();
            $related = $TmpRel->structureProducts(NULL, 'PRODUCT', NULL, 'STRUCTURE', $Str_ID, false, false, NULL, NULL, NULL);
            $tableLength = count($related);

            for ($i = 0; $i < $tableLength; ++$i) {

                $products = $TmpPrd->select(NULL, NULL, $related[$i]['prt_id'], NULL, NULL, NULL, 'p.srtord', false, NULL, NULL, 0);
                $uploads = $TmpPrt->getProductTypeImage($related[$i]['prt_id']);

                $productPage = ($related[$i]['atr_id'] == 0) ? 'producttype' : 'productlist';

                if (
                    isset($uploads[0]) &&
                    file_exists($patchworks->docRoot . 'uploads/images/products/169-130/' . $uploads[0]['filnam']) &&
                    !is_dir($patchworks->docRoot . 'uploads/images/products/169-130/' . $uploads[0]['filnam'])
                ) {
                    $fileName = 'uploads/images/products/500-700/' . $uploads[0]['filnam'];
                } else {
                    $fileName = 'pages/img/noimg.png';
                }


                //
                // Logo
                //
                if (
                    isset($uploads[1]) &&
                    file_exists($patchworks->docRoot . 'uploads/images/products/169-130/' . $uploads[0]['filnam']) &&
                    !is_dir($patchworks->docRoot . 'uploads/images/products/169-130/' . $uploads[0]['filnam'])
                ) {
                    $fileName2 = 'uploads/images/products/' . $uploads[1]['filnam'];
                } else {
                    $fileName2 = 'pages/img/noimg.png';
                }

                ?>

                        <div class="col-xs-6 col-sm-3">

                            <a href="<?php echo $FwdUrl . '/producttype/' . $related[$i]['prt_id'] . '/' . $related[$i]['seourl'] ?>" class="homeproduct">
                                <div class="imagewrapper">
                                    <div class="image" style="background-image: url('<?php echo $fileName; ?>')">

                                    </div>

                                    <div class="productlogo" style="background-image: url('<?php echo $fileName2; ?>')">

                                    </div>

                                </div>
                                <h2>
                                        <strong class="greentext"><?php echo $related[$i]['prtnam']; ?></strong>
                                </h2>
                            </a>


                            <form action="<?php echo $FwdUrl . '/producttype/' . $related[$i]['prt_id'] . '/' . $related[$i]['seourl'] ?>" method="post" id="quickProductForm" class="form-vertical">
                                <input type="hidden" name="action" value="add">
                                <div class="control-group form-group" style="display: none;">
                                    <div class="controls">
                                        <label>Quantity:</label>
                                        <input type="number" class="form-control" name="qty" value="1" max="" min="1" data-parsley-max="">
                                    </div>
                                </div>
                                <div class="form-actions" style="text-align: center;">
                                    <button type="submit">Buy Now</button>
                                </div>

                            </form>

                        </div>

                <?php
            }
        }
        ?>

        </div>

    </div>

</div>