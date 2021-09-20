<?php

require_once('../../config/config.php');
require_once('../../admin/patchworks.php');
require_once("../../admin/website/classes/pageelements.cls.php");
require_once("../../admin/attributes/classes/attrgroups.cls.php");
require_once("../../admin/attributes/classes/attrlabels.cls.php");
require_once("../../admin/attributes/classes/attrvalues.cls.php");
require_once("../../admin/products/classes/product_types.cls.php");
require_once("../../admin/products/classes/products.cls.php");
require_once("../../admin/products/classes/structure.cls.php");
require_once("../../admin/gallery/classes/uploads.cls.php");
require_once("../../admin/system/classes/related.cls.php");

$displayCurrency = '&pound;';

$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

$PerPag = (isset($_GET['perpag']) && is_numeric($_GET['perpag'])) ? $_GET['perpag'] : 24;
$OffSet = (isset($_GET['offset']) && is_numeric($_GET['offset'])) ? $_GET['offset'] : NULL;
$Pag_No = (isset($_GET['pag_no']) && is_numeric($_GET['pag_no'])) ? $_GET['pag_no'] : 1;

if (!isset($OffSet) || !is_numeric($OffSet)) {
    $OffSet = ($Pag_No - 1) * $PerPag;
}

$SeoUrl = (isset($_GET['seourl'])) ? $_GET['seourl'] : NULL;
$FwdUrl = $EleDao->getVariable($EleObj, 'fwdurl');

if (!is_null($FwdUrl) && !empty($FwdUrl)) $SeoUrl = $FwdUrl;

//$Pag_No = (isset($_GET['pag_no']) && is_numeric($_GET['pag_no'])) ? $_GET['pag_no'] : 1;
//$PerPag = $EleDao->getVariable($EleObj, 'perpag');
//if (empty($PerPag) || is_null($PerPag)) $PerPag = 6;

$PrtSeo = (isset($_GET['prtseo'])) ? $_GET['prtseo'] : NULL;
$PrdSeo = (isset($_GET['prdseo'])) ? $_GET['prdseo'] : NULL;
$PrdCat = (isset($_GET['prdcat'])) ? $_GET['prdcat'] : NULL;

$SrtOrd = (isset($_GET['srtord'])) ? $_GET['srtord'] : 'p.unipri DESC';

$DspTyp = 'ProductCategory';

$NumCol = 4;
//$NumCol = $EleDao->getVariable($EleObj, 'numcol');
//if (!is_numeric($NumCol)) $NumCol = 3;

$ColWid = 12 / $NumCol;

$Str_ID = (isset($_GET['str_id']) && is_numeric($_GET['str_id'])) ? $_GET['str_id'] : 0;
$StrSeo = (isset($_GET['strseo'])) ? $_GET['strseo'] : NULL;

$UplDao = new UplDAO();
$TmpRel = new RelDAO();
$related = $TmpRel->structureProducts(NULL, 'PRODUCT', NULL, 'STRUCTURE', $Str_ID, false, false, $OffSet, $PerPag);
$maxRec  = $TmpRel->structureProducts(NULL, 'PRODUCT', NULL, 'STRUCTURE', $Str_ID, false, false, NULL, NULL);
$MaxRec  = count($maxRec);

$TmpStr = new StrDAO();
$TmpPrd = new PrdDAO();
$TmpPrt = new PrtDAO();

if (!is_null($PrtSeo)) {

    $DspTyp = 'ProductType';

    $productType = $TmpPrt->select(NULL, $_GET['prtseo'], NULL, NULL, NULL, NULL, NULL, NULL, true);

    $products = $TmpPrd->select(NULL, NULL, $productType->prt_id, NULL, NULL, NULL, 'p.srtord', false, NULL, NULL, 0);

    $UplDao = new UplDAO();
    $uploads = $TmpPrt->getProductImage($productType->prt_id);

}

$structureRec = $TmpStr->select($Str_ID, NULL, NULL, true);

?>

<div class="section nopadding">
    <div class="container">

        <?php

        if ($DspTyp == 'ProductCategory') {

            ?>

            <div class="row">
                <div class="col-sm-12">
                    <?php
                    $TmpStr->getBreadcrumb($Str_ID, $_GET['seourl']);
                    ?>
                </div>
            </div>

            <div class="row">

                <?php
                if ($Str_ID > 0) {
                ?>

                <div class="col-sm-2">

                    <h4>More Categories</h4>

                    <div id="subcategories">
                        <?php
                        if (empty($TmpStr->buildStructure2($Str_ID, $_GET['seourl']))) {
                            echo $TmpStr->buildStructure2($structureRec->par_id, $_GET['seourl']);
                        } else {
                            echo $TmpStr->buildStructure2($Str_ID, $_GET['seourl']);
                        }
                        ?>
                    </div>

                </div>

                <?php } ?>


                <div class="col-sm-<?php echo ($Str_ID > 0) ? '10' : '12'; ?>">


<!--                    <div class="shoplevel">-->
<!---->
<!--                        --><?php
//                        $TmpStr->buildStructure(0, $_GET['seourl'], NULL);
//                        ?>
<!---->
<!--                    </div>-->


                    <?php

                    if ($Str_ID == 0) {

                        $NumCol = 3;
                        $ColWid = 4;


                        $homeProducts = $TmpPrt->selectHomePage(NULL, NULL, false);

                        $tableLength = count($homeProducts);

                        for ($i = 0; $i < $tableLength; ++$i) {

                            $uploads = $TmpPrt->getProductImage($homeProducts[$i]['prt_id']);

                            ?>

                            <?php if (($i % $NumCol) == 0 && $i > 0) echo '</div>'; ?>
                            <?php if (($i % $NumCol) == 0) echo '<div class="row shopCategoryList">'; ?>

                            <div class="col-md-<?php echo $ColWid; ?> col-xs-12">
                                <a href="<?php echo $SeoUrl; ?>/producttype/<?php echo $homeProducts[$i]['seourl'] ?>" class="shopCategoryLink">

                                    <?php

                                    //echo $patchworks->docRoot . 'uploads/images/' . $uploads[0]['filnam'];

                                    if (
                                        isset($uploads[0]) &&
                                        file_exists($patchworks->docRoot . 'uploads/images/products/169-130/' . $uploads[0]['filnam']) &&
                                        !is_dir($patchworks->docRoot . 'uploads/images/products/169-130/' . $uploads[0]['filnam'])
                                    ) {
                                        echo '<img src="uploads/images/products/169-130/' . $uploads[0]['filnam'] . '" class="productImage" />';
                                    } else {
                                        echo '<div class="noimage" data-imgurl="'.$uploads[0]['filnam'].'"></div>';
                                    }
                                    ?>
                                    <h3><span><?php echo $homeProducts[$i]['prtnam']; ?></span></h3>
                                    <span
                                        class="price"><?php echo $displayCurrency . $homeProducts[$i]['unipri']; ?></span>
                                </a>
                            </div>

                            <?php

                        }

                        if ($tableLength > 0) echo '</div>';

                    }

                    ?>


                    <?php

                    $tableLength = count($related);
                    for ($i = 0; $i < $tableLength; ++$i) {

                        //$uploads = $UplDao->select(NULL, 'PRDTYPE', $related[$i]['prt_id'], NULL, false);

                        $uploads = $TmpPrt->getProductImage($related[$i]['prt_id']);

                        ?>
                        <?php if (($i % $NumCol) == 0 && $i > 0) echo '</div>'; ?>
                        <?php if (($i % $NumCol) == 0) echo '<div class="row shopCategoryList">'; ?>

                        <div class="col-md-<?php echo $ColWid; ?> col-xs-12">
                            <a href="<?php echo $SeoUrl; ?>/category/<?php echo $structureRec->str_id; ?>/<?php echo $structureRec->seourl; ?>/producttype/<?php echo $related[$i]['prt_id'] ?>/<?php echo $related[$i]['seourl'] ?>" class="shopCategoryLink">


                                <?php
                                if (
                                    isset($uploads[0]) &&
                                    file_exists($patchworks->docRoot . 'uploads/images/products/169-130/' . $uploads[0]['filnam']) &&
                                    !is_dir($patchworks->docRoot . 'uploads/images/products/169-130/' . $uploads[0]['filnam'])
                                ) {
                                    echo '<img src="uploads/images/products/169-130/' . $uploads[0]['filnam'] . '" class="productImage" />';
                                } else {
                                    echo '<div class="noimage" data-imgurl="'.$uploads[0]['filnam'].'"></div>';
                                }
                                ?>
                                <h3><span><?php echo $related[$i]['prtnam']; ?></span></h3>
                                <span class="price">
                                    from <span><?php echo $displayCurrency . $related[$i]['unipri']; ?></span><br>
<!--                                    <span class="shipping">Free Shipping</span>-->
                                </span>
                            </a>
                        </div>

                        <?php
                    }

                    if ($tableLength > 0) echo '</div>';

                    ?>

                    <?php

                    $useQS = false;
                    $qryString = $_SERVER['QUERY_STRING'];
                    $strPos = strpos($qryString, 'atvtblnam');
                    if ($strPos > 0) {
                        $useQS = true;
                        $qryString = substr($qryString, $strPos, (strlen($qryString) - $strPos));
                    }

                    if (count($related) > 0) {
                        ?>
                        <nav>
                            <ul class="pagination">

                                <?php
                                $useQS = false;
                                $qryString = $_SERVER['QUERY_STRING'];
                                $strPos = strpos($qryString, 'atvtblnam');
                                if ($strPos > 0) {
                                    $useQS = true;
                                    $qryString = substr($qryString, $strPos, (strlen($qryString) - $strPos));
                                }
                                ?>

                                <li class="disabled"><a href="#">&laquo;</a></li>

                                <?php

                                if ((is_numeric($MaxRec) && $MaxRec > 0) && (is_numeric($PerPag) && $PerPag > 0)) {
                                    $PageCount = ceil($MaxRec / $PerPag);
                                } else {
                                    $PageCount = 0;
                                }

                                for ($p = 0; $p < $PageCount; $p++) {

                                    $url = $SeoUrl . '/category/' . $Str_ID.'/'.$StrSeo . '?'. 'pag_no=' . ($p + 1);

//                                    if ($useQS) {
//                                        $url = $SeoUrl . '/productgroup/' . $AtrSeo . '?'. $qryString.'&pag_no=' . ($p + 1);
//                                    } else {
//                                        $url = $SeoUrl . '/productgroup/' . $AtrSeo . '?pag_no=' . ($p + 1);
//                                    }
                                    ?>

                                    <li <?php if ($Pag_No == ($p + 1)) echo 'class="active"'; ?>>

                                        <a href="<?php echo $url; ?>"><?php echo $p + 1; ?>

                                            <span class="sr-only">(current)</span>

                                        </a>

                                    </li>

                                <?php } ?>

                                <li class="disabled"><a href="#">&raquo;</a></li>


                            </ul>
                        </nav>
                        <?php
                    } else if ($Str_ID > 0) {
                        ?>

                        <h2>Sorry there are no products to match your search</h2>

                        <?php
                    }
                    ?>


                </div>
            </div>

            <?php

        }

        ?>

        <?php if ($DspTyp == 'ProductType') { ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="productItem">

                        <div class="breadCrumb">


                            <?php
                            //$structureParent = $TmpRel->select(NULL, 'PRODUCT', $productType->prt_id, 'STRUCTURE', NULL, true, ' srtord DESC ');
                            //$TmpStr->getBreadCrumb($structureParent->ref_id);
                            ?>

                        </div>


                        <div class="row">
                            <div class="col-md-12">
                                <h1><?php echo $productType->prtnam; ?> </h1>
                                <h3 class="productPrice">&pound;<?php echo $productType->unipri; ?></h3>
                                <hr/>

                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-9">
                                <div id="productGallery">
                                    <ul>

                                        <?php
                                        $tableLength = count($uploads);
                                        for ($i = 0; $i < $tableLength; ++$i) {

                                            echo '<li>';

                                            if (
                                                file_exists($patchworks->docRoot . 'uploads/images/products/169-130/' . $uploads[$i]['filnam']) &&
                                                !is_dir($patchworks->docRoot . 'uploads/images/products/169-130/' . $uploads[$i]['filnam'])
                                            ) {

                                                echo '<a href="uploads/images/products/' . $uploads[$i]['filnam'] . '" data-arr_id="'.$i.'" data-prd_id="' . $uploads[$i]['tbl_id'] . '" class="image-link productThumb" style="background-image: url(uploads/images/products/169-130/' . $uploads[$i]['filnam'] . ');">';
                                                echo '<img src="uploads/images/products/169-130/' . $uploads[$i]['filnam'] . '" alt="" /></a>';
                                                echo '<span>'.$uploads[$i]['prdnam'].'</span><br>';

                                            } else {

                                                echo '<a href="uploads/images/products/' . $uploads[$i]['filnam'] . '" data-arr_id="'.$i.'" data-prd_id="' . $uploads[$i]['tbl_id'] . '" class="image-link productThumb">';
                                                echo '<div class="noimage" data-imgurl="'.$uploads[$i]['filnam'].'"></div></a>';
                                                echo '<span>'.$uploads[$i]['filnam'].'</span>';

                                            }

                                            echo '<div class="mobilebtns">';
                                            echo '<a href="shoppingcart/add/'.$uploads[$i]['tbl_id'].'" class="addtocart">add to cart</a>';
                                            echo '<a href="uploads/images/products/' . $uploads[$i]['filnam'] . '"  data-arr_id="'.$i.'" data-prd_id="' . $uploads[$i]['tbl_id'].'" class="viewproduct">view</a>';
                                            echo '</div>';

                                            echo '</li>';

                                        }
                                        ?>
                                    </ul>
                                </div>

                            </div>
                            <div class="col-md-3 hidden-sm hidden-xs">

                                <?php if (count($uploads) > 0) { ?>

                                    <a href="uploads/images/products/<?php echo $uploads[0]['filnam']; ?>" id="productPopupLink" class="image-link" target="_blank" data-arr_id="0">
                                    <img src="uploads/images/products/<?php echo $uploads[0]['filnam']; ?>" id="heroImage"/>
                                    </a>
                                    <p class="redtext">click to view larger image</p>

                                <?php } else { ?>



                                <?php } ?>

                                <div class="productDescription">
                                    <h3>Product Description</h3>
                                    <?php echo $productType->prtdsc; ?>
                                </div>

                                <hr>
                                <h4>Add To Basket</h4>

                                <form action="pages/shoppingcart/shoppingcart_control.php" method="post" id="productForm"
                                      class="form-vertical">

                                    <input type="hidden" name="action" value="add">

                                    <div class="control-group form-group">
                                        <div class="controls">
                                            <label>Select Product:</label>
                                            <select name="prd_id" class="form-control">

                                                <?php
                                                $tableLength = count($products);
                                                for ($i = 0; $i < $tableLength; ++$i) {

                                                    $products[$i]['unipri'] = $products[$i]['unipri'];

                                                    ?>

                                                    <option value="<?php echo $products[$i]['prd_id']; ?>">
                                                        <?php echo $products[$i]['prdnam']; ?>

                                                        <?php
                                                        if ($products[$i]['unipri'] != $productType->unipri) echo ' (&pound;'.$products[$i]['unipri'].')';
                                                        ?>

                                                    </option>

                                                    <?php
                                                }
                                                ?>
                                            </select>

                                        </div>
                                    </div>

                                    <div class="control-group form-group">
                                        <div class="controls">
                                            <label>Quantity:</label>
                                            <input type="text" class="form-control" name="qty" value="1">

                                        </div>
                                    </div>

                                    <div class="form-actions">
                                        <button type="submit">Add To Cart</button>
                                    </div>

                                </form>

                            </div>


                        </div>


                        <!---->


                    </div>


                </div>
            </div>

        <?php } ?>

        <!-- END PRODUCT TYPE -->


    </div>
</div>

<link rel="stylesheet" type="text/css" href="pages/css/cloud-zoom.css">
<script src="pages/js/cloud-zoom.js"></script>

<link rel="stylesheet" href="pages/css/magnific-popup.css" />
<script src="pages/js/jquery.magnific-popup.min.js"></script>

<script>

    $(function () {

        $('.image-link:not(#productPopupLink)').click(function(e){

            e.preventDefault();

            var prdId = $(this).data('prd_id');
            $('[name="prd_id"]', $('#productForm')).val(prdId);

            $('#heroImage').attr("src", $(this).attr('href')).data('arr_id', $(this).data('arr_id'));
            $('#productPopupLink').attr("href", $(this).find('img').attr('href') );

        });

        var itemArray = [];

        $('.image-link:not(#productPopupLink)').each(function(){
            itemArray.push( {src: $(this).attr('href')} );
        });

        $('#productPopupLink').magnificPopup({
            items: itemArray,
            type:'image',
            gallery: {
                enabled: true
            },
            callbacks: {
                open: function () {

                    startAt = $('#heroImage').data('arr_id');

                    $.magnificPopup.instance.goTo(startAt);
                }
            }
        });


        $('.viewproduct').click(function(e){

            $('#heroImage').attr("src", $(this).attr('href')).data('arr_id', $(this).data('arr_id'));
            $('#productPopupLink').attr("href", $(this).find('img').attr('href') );

        })

        $('.viewproduct').magnificPopup({
            items: itemArray,
            type:'image',
            gallery: {
                enabled: true
            },
            callbacks: {
                open: function () {

                    startAt = $('#heroImage').data('arr_id');

                    $.magnificPopup.instance.goTo(startAt);
                }
            }
        });

        $('.image-link:not(#productPopupLink)').first().click();

    })

</script>