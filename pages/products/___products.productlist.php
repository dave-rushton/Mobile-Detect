<?php

$UplDao = new UplDAO();
$TmpPrd = new PrdDAO();
$TmpPrt = new PrtDAO();

$productType = $TmpPrt->select(NULL, $_REQUEST['prtseo'], NULL, NULL, NULL, NULL, NULL, NULL, true);
//$products = $TmpPrd->select(NULL, NULL, $productType->prt_id, NULL, NULL, NULL, 'p.srtord', false, NULL, NULL, 0);

//$uploads = $UplDao->select(NULL, 'PRDTYPE', $productType->prt_id, NULL, false);
//$uploads = $TmpPrt->getProductImage($productType->prt_id);

$SrtOrd = (isset($_GET['srtord'])) ? $_GET['srtord'] : 'p.unipri DESC';

$prdIDs = NULL;
if (isset($_REQUEST['formaction']) && $_REQUEST['formaction'] == 'SEARCH') {

    $TmpAtv = new AtvDAO();

    //
    // process attribute group values
    //

    $FldArrStr = '';
    $AtrArr = (isset($_REQUEST['fldnum'])) ? $_REQUEST['fldnum'] : NULL;
    for ($fldnum = 0; $fldnum < count($AtrArr); $fldnum++) {
        $FldArrStr .= ($FldArrStr == '') ? $AtrArr[$fldnum] : ',' . $AtrArr[$fldnum];
    }

    $FldValStr = '';
    $AtrVal = (isset($_REQUEST['fld'])) ? $_REQUEST['fld'] : NULL;
    $requestedMatches = 0;
    for ($fldnum = 0; $fldnum < count($AtrVal); $fldnum++) {
        $FldValStr .= ($FldValStr == '') ? $AtrVal[$fldnum] : ',' . $AtrVal[$fldnum];
        if ($AtrVal[$fldnum] != '') $requestedMatches++;
    }

    $Atr_ID = (isset($_REQUEST['atr_id'])) ? $_REQUEST['atr_id'] : NULL;
    $TblNam = (isset($_REQUEST['atvtblnam'])) ? $_REQUEST['atvtblnam'] : NULL;
    $Tbl_ID = (isset($_REQUEST['atvtbl_id'])) ? $_REQUEST['atvtbl_id'] : NULL;

    $attributeSearch = $TmpAtv->searchAttributeValues($Atr_ID, $AtrArr, $AtrVal, $TblNam, $Tbl_ID);

    if (count($attributeSearch)) {

        $prdIDs = '';

        foreach ($attributeSearch as $row) {

            if (isset($row['atv_eq']) && $row['atv_eq'] == $requestedMatches) {
                $prdIDs .= ($prdIDs == '') ? $row['Ref_ID'] : "," . $row['Ref_ID'];
            } else if ($requestedMatches == 0) {
                $prdIDs .= ($prdIDs == '') ? $row['Ref_ID'] : "," . $row['Ref_ID'];
            }
        }

    } else {

        if (count($AtrArr) == 0) {

        }

    }

}

if (!empty($prdIDs) && $requestedMatches > 0) {
    $products = $TmpPrd->selectByIDs($productType->prt_id, $prdIDs, NULL, NULL, NULL, $SrtOrd);
} else {
    $products = $TmpPrd->select(NULL, NULL, $productType->prt_id, NULL, NULL, NULL, 'p.srtord', false, NULL, NULL, 0);
}

?>

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

                <div class="col-sm-6">
                    <div class="productthumblist">
                        <?php
                        $tableLength = count($products);
                        for ($i = 0;
                             $i < $tableLength;
                             ++$i) {

//                                $uploads = $TmpPrd->getProductImage($products[$i]['prd_id']);
                            $uploads = $UplDao->select(NULL,'PRDTYPE',$products[$i]['prd_id']);
//                                $uploads = $TmpPrd->getProductImage($products[$i]['prd_id']);

                            $linkImage = 'pages/img/noimg.png';
                            $thumbImage = 'pages/img/noimg.png';

                            if (
                                isset($uploads[0]) &&
//                                    file_exists($patchworks->docRoot . 'uploads/images/products/169-130/' . $uploads[0]['filnam']) &&
                                file_exists($patchworks->docRoot . 'uploads/images/169-130/' . $uploads[0]['filnam']) &&
                                !is_dir($patchworks->docRoot . 'uploads/images/169-130/' . $uploads[0]['filnam'])
                            ) {
                                $thumbImage = 'uploads/images/169-130/'.$uploads[0]['filnam'];
                                $linkImage = 'uploads/images/'.$uploads[0]['filnam'];
                            } else {
                                $thumbImage = 'pages/img/noimg.png';
                                $linkImage = 'pages/img/noimg.png';
                            }
                            ?>
                            <a class="image-link"
                               href="<?php echo $linkImage; ?>"
                               data-arr_id="<?php echo $i; ?>"
                               data-prd_id="<?php echo $products[$i]['prd_id']; ?>"
                               data-prdnam="<?php echo htmlspecialchars($products[$i]['prdnam'], ENT_QUOTES); ?>"
                               data-unipri="<?php echo $products[$i]['unipri']; ?>"
                                >

                                <span class="image-link-img" style="background-image: url('<?php echo $thumbImage; ?>');">

                                </span>

                            </a>
                            <?php
                        }
                        ?>
                    </div>


                    <div id="productImageView">

                        <div class="row" id="productPopupParent" style="margin-bottom: 30px;">
                            <div class="col-md-12">
                                <a href="uploads/images/products/<?php echo $uploads[0]['filnam']; ?>"
                                   id="productPopupLink"
                                   class="image-link">
                                    <img src="uploads/images/products/<?php echo $uploads[0]['filnam']; ?>" alt=""
                                         id="heroImage" data-arr_id="0"/>
                                </a>
                            </div>

                        </div>

                    </div>



                </div>

                <?php

                if(1==2){

                }
                ?>
                <div class="col-xs-12 col-sm-6">
                    <form action="pages/shoppingcart/shoppingcart_control.php" method="post" id="productForm" class="form-vertical">
                        <h2 id="productName" class="styled"></h2>
                        <hr>

                        <div class="select-wrapper">
                            <div class="row">
                                <div class="col-sm-3">
                                    <p style="text-align: right; line-height: 40px; margin: 0;">
                                        <strong>Quantity</strong>
                                    </p>
                                </div>
                                <div class="col-sm-9">
                                    <div class="form-group qtyinputwrapper hide">
                                        <div class="input-group">
                                            <input class="form-control qtyinput" placeholder="Quantity"
                                                   value="1"
                                                   type="tel"
                                                   name="qty" data-parsley-type="digits"
                                                   data-parsley-max="50000"
                                                   data-parsley-errors-container="#qtyErrorBlock">
                                        </div>
                                        <div id="qtyErrorBlock"></div>
                                    </div>

                                    <div class="form-group qtyselectwrapper">
                                        <div class="input-group">
                                            <select class="form-control qtyselect" style="height: 40px; width: 100%; display: block;" data-parsley-errors-container="#qtySelectErrorBlock">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10+</option>
                                            </select>
                                        </div>
                                        <div id="qtySelectErrorBlock"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="select-wrapper">
                            <div class="row">
                                <div class="col-sm-3">
                                    <p style="text-align: right; line-height: 40px; margin: 0;">
                                        <strong>Size</strong>
                                    </p>
                                </div>
                                <div class="col-sm-9">
                                    <div class="form-group qtyinputwrapper hide">
                                        <div class="input-group">
                                            <input class="form-control qtyinput" placeholder="Quantity"
                                                   value="1"
                                                   type="tel"
                                                   name="qty" data-parsley-type="digits"
                                                   data-parsley-max="50000"
                                                   data-parsley-errors-container="#qtyErrorBlock">
                                        </div>
                                        <div id="qtyErrorBlock"></div>
                                    </div>

                                    <div class="form-group qtyselectwrapper">
                                        <div class="input-group">
                                            <select class="form-control qtyselect" style="height: 40px; width: 100%; display: block;" data-parsley-errors-container="#qtySelectErrorBlock">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10+</option>
                                            </select>
                                        </div>
                                        <div id="qtySelectErrorBlock"></div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <h4 class="h2">Price - &pound;<span id="productPrice">N/A</span> (per pair)</h4>
                        <h2 class="h2">Product Description</h2>
                        <?php
                        echo $productType->prtdsc;
                        ?>
                        <hr>

                        <div class="tabswrapper">

                            <ul class="tabselect">
                                <li class="active">
                                    <a href="#tab1">Product Info</a>
                                </li>

                                <?php
                                if (strlen($productType->prtspc)) {
                                    ?>
                                    <li>
                                        <a href="#tab2">Care</a>
                                    </li>

                                    <?php
                                }
                                ?>

                                <?php
                                $RelDao = new RelDAO();
                                $relatedRecs = $RelDao->relatedProductTypes(NULL,'PRDTYPE',$productType->prt_id,'PRDTYPE',NULL,false);
                                $tableLength = count($relatedRecs);
                                if ($tableLength > 0) {
                                    ?>
                                    <li>
                                        <a href="#tab3">Related</a>
                                    </li>
                                    <?php
                                }

                                ?>


                                <?php

                                $UplDao = new UplDAO();
                                $downloads = $UplDao->select(NULL, 'PRTFILE', $productType->prt_id, NULL, false);

                                $tableLength = count($downloads);

                                if ($tableLength > 0) {
                                    ?>

                                    <li>
                                        <a href="#tab4">Downloads</a>
                                    </li>

                                    <?php
                                }

                                ?>


                            </ul>

                            <div class="tabs">

                                <div class="tab active" id="tab1">


                                    <?php
                                    $uploads = $UplDao->select(NULL, 'PRDTYPE', $productType->prt_id, NULL, false);

                                    $fileName = '';
                                    $className = '';
                                    if (
                                        isset($uploads[0]) &&
                                        file_exists($patchworks->docRoot . 'uploads/images/products/169-130/' . $uploads[0]['filnam']) &&
                                        !is_dir($patchworks->docRoot . 'uploads/images/products/169-130/' . $uploads[0]['filnam'])
                                    ) {
                                        $fileName = 'uploads/images/products/' . $uploads[0]['filnam'];
                                        $altName = $uploads[0]['uplttl'];
                                    }
                                    ?>

                                    <?php

                                    $customValue = (!empty($productType->prtobj)) ? $patchworks->getJSONVariable($productType->prtobj, 'imagedisplay', true) : '';

                                    $pcTagArray = explode(",", $customValue);

                                    if ( in_array('Description Image', $pcTagArray) ) {
                                        echo '<div class="row">';
                                        echo '<div class="col-sm-4"><img src="'.$fileName.'" /></div>';
                                        echo '<div class="col-sm-8">'.$productType->prtdsc.'</div>';
                                        echo '</div>';
                                    } else {
                                        echo $productType->prtdsc;
                                    }

                                    ?>

                                </div>
                                <div class="tab" id="tab2">
                                    <?php
                                    echo $productType->prtspc;
                                    ?>
                                </div>
                                <div class="tab" id="tab3">

                                    <ul>
                                        <?php
                                        for ($r=0;$r<$tableLength;$r++) {
                                            ?>

                                            <li><a href="<?php echo $_GET['seourl']; ?>/productlist/<?php echo $relatedRecs[$r]['ref_id']; ?>/<?php echo $relatedRecs[$r]['seourl']; ?>"><?php echo $relatedRecs[$r]['prtnam']; ?></a></li>

                                            <?php
                                        }
                                        ?>
                                    </ul>

                                </div>


                                <div class="tab" id="tab4">

                                    <div id="productDownloads">

                                        <ul>

                                            <?php
                                            for ($i=0;$i<count($downloads);$i++) {

                                                if (($i % 3) == 0) {
                                                    //if ($i > 0) echo '</div>';
                                                    //echo '<div class="row">';
                                                }

                                                ?>

                                                <li>
                                                    <a href="uploads/files/<?php echo $downloads[$i]['filnam']; ?>" target="_blank" class="pdflink">
                                                        <?php echo $downloads[$i]['uplttl']; ?>
                                                        <span>Download File</span>
                                                    </a>
                                                </li>


                                                <?php
                                            }
                                            ?>

                                        </ul>


                                    </div>

                                </div>

                            </div>

                        </div>



                        <div class="row">
                            <div class="col-sm-12">

                                <div class="row">
                                    <?php

                                    // Price Bands

                                    $priceBands = $TmpPrb->select(NULL, NULL, $productType->prt_id, NULL, date("Y-m-d"), NULL, NULL, false);

                                    if (count($priceBands)) {

                                        for ($b=0;$b<count($priceBands);$b++) {

                                            ?>

                                            <div class="col-sm-4">

                                                <a href="shoppingcart/add/<?php echo $priceBands[$b]['prd_id']; ?>?qty=<?php echo intval($priceBands[$b]['numuni']); ?>" class="pricebandlink">

                                                    Order <?php echo intval($priceBands[$b]['numuni']); ?> units
                                                    <br>
                                                    <strong><?php echo $displayCurrency.$priceBands[$b]['unipri']; ?></strong>

                                                </a>

                                            </div>

                                            <?php

                                        }

                                    }

                                    ?>

                                </div>
                            </div>
                        </div>






                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="prd_id" value="0">


                        <div class="row">
                            <div class="col-sm-5">
                                <button type="submit" style="width:100%; height: 40px;">Add To Cart</button>
                            </div>
                        </div>

                    </form>

                </div>


            </div>

        </div>

    </div>

</div>