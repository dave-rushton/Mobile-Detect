<?php
$UplDao = new UplDAO();
$TmpPrd = new PrdDAO();
$TmpRel = new RelDAO();
$TmpUpl = new UplDAO();

$SrtOrd = ( isset($_GET['srtord']) )
    ? $_GET['srtord']
    : 'p.unipri DESC';

$StrSeo = ( isset($_GET['strseo']) )
    ? $_GET['strseo']
    : NULL;

$PerPag = ( isset($_GET['perpag']) && is_numeric($_GET['perpag']) )
    ? $_GET['perpag']
    : 12;

$Pag_No = ( isset($_GET['pag_no']) && is_numeric($_GET['pag_no']) )
    ? $_GET['pag_no']
    : 1;

$OffSet = $PerPag * ($Pag_No - 1);
$title = "";

if ( $Str_ID != 0 ) {
    $structureRec = $TmpStr->select($Str_ID, NULL, NULL, TRUE);

    $displayType = $patchworks->getJSONVariable($structureRec->strobj, 'dsptyp', TRUE);
    if ( is_null($displayType) ) {
        $displayType = 0;
    }

    $strdsc = json_decode($structureRec->strobj)[0];
    $description = ( $strdsc->name === 'strdsc' )
        ? $strdsc->value
        : $patchworks->getJSONVariable($structureRec->strobj, 'strdsc', TRUE);

    $includeSlider = $patchworks->getJSONVariable($structureRec->strobj, 'incsld', TRUE);
    if ( is_null($includeSlider) ) {
        $includeSlider = 0;
    }

    $title = $structureRec->strnam;
}

$eCommProp = $TmpEco->select(TRUE);
$inStk = ( $eCommProp->outstk == 0 )
    ? TRUE
    : NULL;

$TmpAtr = new AtrDAO();
$related = $TmpRel->structureProducts(NULL, 'PRODUCT', NULL, 'STRUCTURE', $Str_ID, FALSE, FALSE, $OffSet, $PerPag, $inStk);
$maxRec = $TmpRel->structureProducts(NULL, 'PRODUCT', NULL, 'STRUCTURE', $Str_ID, FALSE, FALSE, NULL, NULL, $inStk);
$MaxRec = count($maxRec);

$productPage = 'productlist';
$structureRecs = $TmpStr->selectLevel($Str_ID, NULL, NULL, FALSE);
$prt_ids = [];





for ( $i = 0; $i < count($structureRecs); $i++ ) {

    $uploads = $TmpUpl->select(NULL, 'STRUCTURE', $structureRecs[$i]['str_id'], NULL, FALSE);
    $class = 'noimg';
    $fileName = 'pages/img/cover-image-700-700.jpg';

    if (
        isset($uploads)
        && isset($uploads[0])
        && file_exists($patchworks->docRoot . 'uploads/images/products/700-700/' . $uploads[0]['filnam'])
    ) {
        $fileName = $patchworks->webRoot . 'uploads/images/products/700-700/' . $uploads[0]['filnam'];
        $class = '';
    }

    ?>
    <li class="prod">
        <a href="<?php echo $_GET['seourl'] . '/category/' . $structureRecs[$i]['str_id'] . '/' . $structureRecs[$i]['seourl']; ?>">
            <div class="product">
                <div class="graphic-wrapper">
                    <div class="cover">
                        <div class="table">
                            <div class="cell">
                                <img src="<?php echo $fileName; ?>" alt=""/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="title-wrapper">
                    <?php echo $structureRecs[$i]['strnam']; ?>
                </div>
            </div>
            <span class="content"></span>
        </a>
    </li>
    <?php
}

if ( count($structureRecs) == 0 ) {
    $editAttrGroup = $structureRec->tbl_id;
    $editReferenceTable = 'PRODUCTGROUP';
    $editReferenceID = '';
    include('product_search_form.php');

    $tableLength = count($related);
    if ( $tableLength > 0 ) {
        echo '<div class="row">';
        for ( $i = 0; $i < $tableLength; ++$i ) {
            if ( ! in_array($related[$i]['prt_id'], $prt_ids) ) {
                $prt_ids[] = $related[$i]['prt_id'];

                $uploads = $UplDao->select(null, 'PRDTYPE', $related[$i]['prt_id'], null, false);

                $className = '';
                if (
                    isset($uploads[0])
                    && file_exists($patchworks->docRoot . 'uploads/images/products/700-700/' . $uploads[0]['filnam'])
                ) {
                    $fileName = $patchworks->webRoot . 'uploads/images/products/700-700/' . $uploads[0]['filnam'];
                    $altName = $uploads[0]['uplttl'];
                } else if (
                    isset($uploads[0])
                    && file_exists($patchworks->docRoot . 'uploads/images/products/700/' . $uploads[0]['filnam'])
                    && ! is_dir($patchworks->docRoot . 'uploads/images/products/700/' . $uploads[0]['filnam'])
                ) {
                    $fileName = $patchworks->webRoot . 'uploads/images/products/700/' . $uploads[0]['filnam'];
                    $altName = $uploads[0]['uplttl'];
                }
                else {
                    $productImages = $TmpPrt->getProductImage($related[$i]['prt_id']);


                    if (
                        isset($productImages[0])
                        && file_exists($patchworks->docRoot . 'uploads/images/700-700/' . $uploads[0]['filnam'])
                        && ! is_dir($patchworks->docRoot . 'uploads/images/700-700/' . $uploads[0]['filnam'])
                    ) {
                        $fileName = 'uploads/images/700-700/' . $uploads[0]['filnam'];
                        $altName = $uploads[0]['uplttl'];
                    } else if (
                        file_exists($patchworks->docRoot . 'uploads/images/' . $uploads[0]['filnam'])
                        && ! is_dir($patchworks->docRoot . 'uploads/images/' . $uploads[0]['filnam'])
                    ) {
                        $fileName = 'uploads/images/' . $uploads[0]['filnam'];
                        $altName = $uploads[0]['uplttl'];
                    }
                    else {
                        $fileName = 'pages/img/cover-image-700-700.jpg';
                        $altName = 'No Image';
                        $className = 'noimg';
                    }
                }
//                else {
//                    $productImages = $TmpPrt->getProductImage($related[$i]['prt_id']);
//
//                    if (
//                        isset($productImages[0])
//                        && file_exists($patchworks->docRoot . 'uploads/images/products/700-700/' . $productImages[0]['filnam'])
//                        && ! is_dir($patchworks->docRoot . 'uploads/images/products/700-700/' . $productImages[0]['filnam'])
//                    ) {
//                        $fileName = 'uploads/images/products/700-700/' . $productImages[0]['filnam'];
//                        $altName = $productImages[0]['uplttl'];
//                    } else if (
//                        file_exists($patchworks->docRoot . 'uploads/images/products/' . $productImages[0]['filnam'])
//                        && ! is_dir($patchworks->docRoot . 'uploads/images/products/' . $productImages[0]['filnam'])
//                    ) {
//                        $fileName = 'uploads/images/products/' . $productImages[0]['filnam'];
//                        $altName = $productImages[0]['uplttl'];
//                    }
//                    else {
//                        $fileName = 'pages/img/cover-image-700-700.jpg';
//                        $altName = 'No Image';
//                        $className = 'noimg';
//                    }
//                }

                $url = $_GET['seourl'] . '/category/' . $structureRec->str_id . '/' . $structureRec->seourl . '/' . $productPage . '/' . $related[$i]['reftbl_id'] . '/' . $related[$i]['seourl'];
                //
                // IMAGE TAGS
                //
                $customValue = (!empty($related[$i]['prtobj']))
                    ? $patchworks->getJSONVariable($related[$i]['prtobj'], 'imagetags', false)
                    : '';

                $pcTagArray = explode(",", $customValue);
                ?>
                <!-- FLAGGGGG-->


                <div class="col-xs-6 col-sm-4 col-md-3">
                        <div class="productLink">
                            <a href="<?php echo $url; ?>" class="" itemscope
                               itemtype="http://schema.org/Product">
                                <link itemprop="url" href="<?php echo $url; ?>">
                                <meta itemprop="image" content="<?php echo $fileName;?>">
                                <meta itemprop="description" content="">

                                <span class="imagewrapper">
                                <img src="<?php echo $fileName;?>" alt="">
                            </span>
                                <span class="content">
                                <h2 itemprop="name"><?php echo $related[$i]['prtnam']; ?></h2>
                                  <span class="cta">View Product</span>
                            </span>
                            </a>
                            <a href="<?php echo $url; ?>" class="likeproduct "></a>
                            <div itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
                                <meta itemprop="price" content="<?php echo $related[$i]['buypri']; ?>">
                                <meta itemprop="priceCurrency" content="GBP">
                                <link itemprop="availability" href="http://schema.org/InStock">
                            </div>
                        </div>
                        <?php

                    ?>
                </div>





                <?php
            }
        }
        echo '</div>';
    }
    else if ( $displayType == 0 ) {
        ?>
        <div class="neg centre">
            <h2 class="h3">Sorry there are no products in this category</h2>
        </div>
        <?php
    }
    ?>
    <?php
}


?>
