<?php

$UplDao = new UplDAO();
$TmpPrd = new PrdDAO();
$TmpPrt = new PrtDAO();
$TmpRel = new RelDAO();
$TmpUpl = new UplDAO();

$SrtOrd = (isset($_GET['srtord'])) ? $_GET['srtord'] : 'p.unipri DESC';
$StrSeo = (isset($_GET['strseo'])) ? $_GET['strseo'] : NULL;

$PerPag = (isset($_GET['perpag']) && is_numeric($_GET['perpag'])) ? $_GET['perpag'] : 12;
$Pag_No = (isset($_GET['pag_no']) && is_numeric($_GET['pag_no'])) ? $_GET['pag_no'] : 1;

$OffSet = $PerPag * ($Pag_No - 1);




$structureRec = $TmpStr->select($Str_ID, NULL, NULL, true);

$displayType = $patchworks->getJSONVariable($structureRec->strobj, 'dsptyp', true);
if (is_null($displayType)) $displayType = 0;

$includeSlider = $patchworks->getJSONVariable($structureRec->strobj, 'incsld', true);
if (is_null($includeSlider)) $includeSlider = 0;

$eCommProp = $TmpEco->select(true);

// Default
$productPage = 'producttype';
if ($eCommProp->prddsp == 2) $productPage = 'productfull';
if ($eCommProp->prddsp == 3) $productPage = 'productlist';

$inStk = ($eCommProp->outstk == 0) ? true : NULL;


$TmpAtr = new AtrDAO();
$related = $TmpRel->structureProducts(NULL, 'PRODUCT', NULL, 'STRUCTURE', $Str_ID, false, false, $OffSet, $PerPag, $inStk, ($eCommProp->prddsp == 2) ? true : false );
$maxRec = $TmpRel->structureProducts(NULL, 'PRODUCT', NULL, 'STRUCTURE', $Str_ID, false, false, NULL, NULL, $inStk, ($eCommProp->prddsp == 2) ? true : false );
$MaxRec = count($maxRec);

?>


<?php
    if(isset($enable_bread_crumb)){
        if($enable_bread_crumb == true){
            ?>
            <div class="row">
                <div class="col-sm-12">
                    <div class="topdetails" style="position: relative;">
                        <?php
                        $TmpStr->getBreadcrumb($Str_ID, $_GET['seourl'], $Prt_ID);
                        ?>
                    </div>
                </div>
            </div>
            <?php
        }
    }

?>


<div class="row">

    <div class="col-md-12">

        <div class="row">

            <?php

            $structureRecs = $TmpStr->selectLevel($Str_ID, NULL, NULL, false);


            for ($i = 0; $i < count($structureRecs); $i++) {

                $uploads = $TmpUpl->select(NULL, 'STRUCTURE', $structureRecs[$i]['str_id'], NULL, false);

                $class = 'noimg';
                $fileName = 'pages/img/noimg.png';
                if (isset($uploads) && isset($uploads[0])) {
                    $fileName = $patchworks->webRoot . 'uploads/images/products/' . $uploads[0]['filnam'];
                    $class = '';
                }

                ?>










                <div class="col-xs-6 col-sm-3">
                    <a href="<?php echo $_GET['seourl'] . '/category/' . $structureRecs[$i]['str_id'] . '/' . $structureRecs[$i]['seourl']; ?>" class="shopCategoryLink">
                        <span class="imagewrapper">
                            <span class="image" style="background-image: url('<?php echo $fileName; ?>')">

                            </span>
                        </span>
                        <span class="content">
                            <h2><?php echo $structureRecs[$i]['strnam']; ?></h2>
                        </span>
                    </a>

                </div>
                <?php

            }

            ?>

        </div>
    </div>


    <?php
    if ( count($structureRecs) == 0 ) {
        ?>
        <div class="col-md-12">

            <?php
            $editAttrGroup = $structureRec->tbl_id;
            $editReferenceTable = 'PRODUCTGROUP';
            $editReferenceID = '';
            include('product_search_form.php');
            ?>

            <div>
                <div class="row">

                    <?php

                    $tableLength = count($related);

                    if ($tableLength > 0) {

                        for ($i = 0; $i < $tableLength; ++$i) {

                            $uploads = $UplDao->select(NULL, 'PRDTYPE', $related[$i]['prt_id'], NULL, false);




                            $className = '';
                            if (
                                isset($uploads[0]) &&
                                file_exists($patchworks->docRoot . 'uploads/images/products/169-130/' . $uploads[0]['filnam']) &&
                                !is_dir($patchworks->docRoot . 'uploads/images/products/169-130/' . $uploads[0]['filnam'])
                            ) {
                                $fileName = 'uploads/images/products/700-700/' . $uploads[0]['filnam'];
                                $altName = $uploads[0]['uplttl'];
                            } else {

                                $productImages = $TmpPrt->getProductImage($related[$i]['prt_id']);

                              //uploads[0] was $productImages[0];
                                if (
                                    isset($productImages[0]) &&
                                    file_exists($patchworks->docRoot . 'uploads/images/products/169-130/' . $uploads[0]['filnam']) &&
                                    !is_dir($patchworks->docRoot . 'uploads/images/products/169-130/' . $uploads[0]['filnam'])
                                ) {
                                    $fileName = 'uploads/images/products/700-700/' . $productImages[0]['filnam'];
                                    $altName = $productImages[0]['uplttl'];
                                }
                                else if (
                                    isset($productImages[0]) &&
                                    file_exists($patchworks->docRoot . 'uploads/images/169-130/' . $uploads[0]['filnam']) &&
                                    !is_dir($patchworks->docRoot . 'uploads/images/169-130/' . $uploads[0]['filnam'])
                                ) {

                                    $fileName = 'uploads/images/700-700/' . $uploads[0]['filnam'];
                                    $altName = $uploads[0]['uplttl'];
                                } else {
                                    $fileName = 'pages/img/noimg.png';
                                    $altName = 'No Image';
                                    $className = 'noimg';
                                }

                            }

                            $url = $_GET['seourl'] . '/category/' . $structureRec->str_id . '/' . $structureRec->seourl . '/' . $productPage . '/' . $related[$i]['reftbl_id'] . '/' . $related[$i]['seourl'];

                            if ($eCommProp->prddsp == 2) {
                                $url = $_GET['seourl'] . '/category/' . $structureRec->str_id . '/' . $structureRec->seourl . '/' . $productPage . '/' . $related[$i]['prd_id'] . '/' . $related[$i]['prdseo'];
                            } else {

                            }

                            //
                            // IMAGE TAGS
                            //
                            $customValue = (!empty($related[$i]['prtobj'])) ? $patchworks->getJSONVariable($related[$i]['prtobj'], 'imagetags', false) : '';
                            $pcTagArray = explode(",", $customValue);

                            ?>

                            <div class="col-xs-6 col-sm-4 col-md-3">

                                <a href="<?php echo $url; ?>" class="productLink"  itemscope itemtype="http://schema.org/Product">

                                    <link itemprop="url" href="<?php echo $url; ?>" />
                                    <meta itemprop="image" content="<?php echo $patchworks->webRoot.$fileName; ?>" />
                                    <meta itemprop="description" content="<?php echo strip_tags($related[$i]['prtdsc']); ?>" />

                                    <span class="imagewrapper">
                                       <img src="<?php echo $fileName; ?>" alt="">
                                    </span>
                                    <span class="content">
<!--                                        <h2 itemprop="name">--><?php //echo ($eCommProp->prddsp == 2) ? $related[$i]['prdnam'].' : '.$related[$i]['prtnam'] : $related[$i]['prtnam']; ?><!--</h2>-->
                                        <h2 itemprop="name"><?php echo ($eCommProp->prddsp == 2) ? $related[$i]['prtnam'] : $related[$i]['prtnam']; ?></h2>
<!--                                        <p>--><?php //echo $displayCurrency.number_format(($eCommProp->prddsp == 2 ? $related[$i]['prdpri'] : $related[$i]['unipri']),2,'.',''); ?><!--</p>-->


                                        <?php
                                        if ($eCommProp->prddsp == 2) {
                                            $likedProducts = array();
                                            $likedProducts = $TmpRel->select(NULL, 'CUS', (isset($loggedIn->pla_id) ? $loggedIn->pla_id : 0), 'PRODUCT', $related[$i]['prd_id'], false);
                                            ?>
                                            <a href="<?php echo $patchworks->webRoot . 'pages/products/product.like.php?prd_id=' . $related[$i]['prd_id']; ?>"
                                               class="likeproduct <?php echo (isset($likedProducts) && count($likedProducts) > 0) ? 'active' : ''; ?>"></a>
                                            <?php
                                        }
                                        ?>


                                        <div itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
                                            <meta itemprop="price" content="<?php echo number_format(($eCommProp->prddsp == 2 ? $related[$i]['prdpri'] : $related[$i]['unipri']),2,'.',''); ?>">
                                            <meta itemprop="priceCurrency" content="GBP">
                                            <link itemprop="availability" href="http://schema.org/InStock">
                                        </div>

                                    </span>
                                </a>

                            </div>

                            <?php
                        }

                    } else if ($displayType == 0) {

                        ?>

                        <div class="col-sm-12">

                            <h2>Sorry there are no products in this category</h2>

                        </div>


                        <?php

                    }
                    ?>

                </div>

            </div>

            <?php

            if ((is_numeric($MaxRec) && $MaxRec > 0) && (is_numeric($PerPag) && $PerPag > 0) && ($MaxRec > $PerPag)) {

                ?>

                <div class="row">
                    <div class="col-sm-12">

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

                                <?php
                                if ($Pag_No > 1) {
                                    $url = $_GET['seourl'] . '/category/' . $Str_ID . '/' . $StrSeo . '?' . 'pag_no=1';
                                    ?>
                                    <li><a href="<?php echo $url; ?>">&laquo;</a></li>
                                    <?php
                                }
                                ?>

                                <?php

                                if ((is_numeric($MaxRec) && $MaxRec > 0) && (is_numeric($PerPag) && $PerPag > 0)) {
                                    $PageCount = ceil($MaxRec / $PerPag);
                                } else {
                                    $PageCount = 0;
                                }

                                for ($p = 0; $p < $PageCount; $p++) {

                                    $url = $_GET['seourl'] . '/category/' . $Str_ID . '/' . $StrSeo . '?' . 'pag_no=' . ($p + 1);

                                    //                                    if ($useQS) {
                                    //                                        $url = $_GET['seourl'] . '/productgroup/' . $AtrSeo . '?'. $qryString.'&pag_no=' . ($p + 1);
                                    //                                    } else {
                                    //                                        $url = $_GET['seourl'] . '/productgroup/' . $AtrSeo . '?pag_no=' . ($p + 1);
                                    //                                    }
                                    ?>

                                    <li <?php if ($Pag_No == ($p + 1)) echo 'class="active"'; ?>>

                                        <a href="<?php echo $url; ?>"><?php echo $p + 1; ?>

                                            <span class="sr-only">(current)</span>

                                        </a>

                                    </li>

                                <?php } ?>

                                <?php
                                if ($Pag_No < $PageCount) {
                                    $url = $_GET['seourl'] . '/category/' . $Str_ID . '/' . $StrSeo . '?' . 'pag_no=' . $PageCount;
                                    ?>
                                    <li><a href="<?php echo $url; ?>">&raquo;</a></li>
                                <?php } ?>


                            </ul>
                        </nav>

                    </div>
                </div>

                <?php
            }
            ?>


        </div>
        <?php
    }
    ?>


</div>
