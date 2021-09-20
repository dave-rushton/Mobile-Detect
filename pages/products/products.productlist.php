<?php

$UplDao = new UplDAO();
$TmpPrd = new PrdDAO();
$TmpPrt = new PrtDAO();




$productType = $TmpPrt->select(NULL, $_REQUEST['prtseo'], NULL, NULL, NULL, NULL, NULL, NULL, true);

$_COOKIE["custom_product"] = $productType->prt_id;



$productTypeList = $TmpPrt->getvarientlist( $productType->prt_id);

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
<?php
$measurement = $patchworks->getJSONVariable($productType->prtobj,"measurement",false);
if(empty($measurement)){
    $measurement = "pair";
}

?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="productItem">

                <div class="breadCrumb">


                    <?php
                    //$structureParent = $TmpRel->select(NULL, 'PRODUCT', $productType->prt_id, 'STRUCTURE', NULL, true, ' srtord DESC ');
                    //$TmpStr->getBreadCrumb($structureParent->ref_id);
                    ?>

                </div>


                <?php
                /*
                  <div class="container">
                    <div class="breadcrumb type1">
                        <ul>
                            <li>
                                <a href="shop">
                                    Shop
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                 * */
                ?>

                <div class="row">

                    <div class="col-sm-6">
                        <div class="productthumblist">
                            <?php
                            $tableLength = count($products);
                            for ($i = 0;
                                 $i < $tableLength;
                                 ++$i) {

                                $uploads = $UplDao->select(null, 'PRDTYPE', $products[$i]['prt_id'], null, false);

                                if (
                                    isset($uploads[0]) &&
//                                    file_exists($patchworks->docRoot . 'uploads/images/products/169-130/' . $uploads[0]['filnam']) &&
                                    file_exists($patchworks->docRoot . 'uploads/images/products/' . $uploads[0]['filnam']) &&
                                    !is_dir($patchworks->docRoot . 'uploads/images/products/' . $uploads[0]['filnam'])
                                ) {
                                    $thumbImage = 'uploads/images/products/'.$uploads[0]['filnam'];
                                    $linkImage = 'uploads/images/products/'.$uploads[0]['filnam'];
                                }else if (
                                    isset($uploads[0]) &&
//                                    file_exists($patchworks->docRoot . 'uploads/images/products/169-130/' . $uploads[0]['filnam']) &&
                                    file_exists($patchworks->docRoot . 'uploads/images/' . $uploads[0]['filnam']) &&
                                    !is_dir($patchworks->docRoot . 'uploads/images/' . $uploads[0]['filnam'])
                                ) {
                                    $thumbImage = 'uploads/images/'.$uploads[0]['filnam'];
                                    $linkImage = 'uploads/images/'.$uploads[0]['filnam'];
                                } else {
                                    $thumbImage = 'pages/img/noimg.png';
                                    $linkImage = 'pages/img/noimg.png';
                                }


                                //  data-prdnam="<?php echo htmlspecialchars($products[$i]['prdnam'], ENT_QUOTES);
                                ?>
                                <a class="image-link"
                                   href="<?php echo $linkImage; ?>"
                                   data-arr_id="<?php echo $i; ?>"
                                   data-prd_id="<?php echo $products[$i]['prd_id']; ?>"
                                   data-prdnam="<?php echo htmlspecialchars($productType->prtnam, ENT_QUOTES); ?>"
                                "
                                data-unipri="<?php echo $products[$i]['unipri']; ?>"
                                >

                                <span class="image-link-img" style="background-image: url('<?php echo $thumbImage; ?>');">

                                </span>

                                </a>
                                <?php
                            }
                            ?>
                        </div>

                        <a href="uploads/images/<?php echo $uploads[0]['filnam']; ?>"

                           class="image-link">
                                    <span class="frame">
                                            <img  id="heroImage" src="uploads/images/<?php echo $uploads[0]['filnam']; ?>" alt=""  data-arr_id="0"/>
                                    </span>
                        </a>

                    </div>

                    <div class="col-xs-12 col-sm-6">
                        <form action="pages/shoppingcart/shoppingcart_control.php" method="post" id="productForm" class="form-vertical">


                            <h2 class="styled" id="productName"></h2>
                            <hr>
                            <div class="content-wrapper">

                                <div class="indent-20">
                                    <div class="inner">
                                        <div class="label">
                                            Size:
                                        </div>
                                        <?php
                                        echo "<select id='varients'>";
                                        foreach ($productTypeList as $list){
                                            $product_name = $list['prdnam'];
                                            $product_name = str_replace("1/4","&frac14;",$product_name);
                                            $product_name = str_replace("1/2","&frac12;",$product_name);
                                            $product_name = str_replace("2/4","&frac12;",$product_name);
                                            $product_name = str_replace("3/4","&frac34;",$product_name);
                                            $product_name = str_replace("1/3","&frac13;",$product_name);
                                            $product_name = str_replace("2/3","&frac23;",$product_name);
                                            $product_name = str_replace("1/8","&frac18;",$product_name);
                                            $product_name = str_replace("3/8","&frac38;",$product_name);
                                            $product_name = str_replace("5/8","&frac58;",$product_name);
                                            $product_name = str_replace("7/8","&frac78;",$product_name);
                                            echo "<option data-price='". $list['unipri'] ."' data-prd_id='". $list['prd_id'] ."' data-prdnam='". $product_name ."'>" . $product_name . "</option>";
                                        }
                                        echo "</select>";
                                        ?>
                                    </div>
                                </div>
                                <div class="indent-20">
                                    <div class="inner">
                                        <div class="label" style="margin-top: 0;">
                                            Quantity (<?php echo $measurement;?>s):
                                        </div>
                                        <div class="form-group qtyselectwrapper">
                                            <div class="input-group">
                                                <div class="qty-input">
                                                    <input name="qty" class="form-number" placeholder="" value=""  type="text" min="5" data-parsley-errors-container="#qtySelectErrorBlock"/>
                                                </div>
                                                <!--<div class="qty-select">
                                                    <select data-max="10"  class="qty-selector">
                                                        <option value="5">5</option>
                                                        <option value="6">6</option>
                                                        <option value="7">7</option>
                                                        <option value="8">8</option>
                                                        <option value="9">9</option>
                                                        <option value="10">10+</option>
                                                    </select>
                                                </div>-->

                                                <small><i>Minimum 5 <?php echo $measurement;?>s</i></small>
                                            </div>
                                            <div id="qtySelectErrorBlock"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <div class="content-wrapper type1">
                                <h4 class="h2">Price - &pound;<span id="productPrice">N/A</span> (per <?php echo $measurement;?>) Ex. VAT</h4>
                                <h2 class="h2 upper">Product Description</h2>
                                <hr>
                                <?php

                                echo $productType->prtdsc;
                                ?>
                            </div>

                            <?php
                            $priceBands = $TmpPrb->select(NULL, NULL, $productType->prt_id, NULL, date("Y-m-d"), NULL, NULL, false);

                            if (count($priceBands)) {
                                ?>

                                <div class="row">
                                    <div class="col-sm-12">

                                        <div class="row">
                                            <?php

                                            // Price Bands



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
                                            ?>

                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>






                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="prd_id" value="0">


                            <div class="row">
                                <div class="col-sm-12">
                                    <button type="submit">Add To Basket</button>
                                </div>
                            </div>
                        </form>

                    </div>


                </div>

            </div>

        </div>

    </div>

</div>
