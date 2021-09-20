<?php

$UplDao = new UplDAO();
$TmpPrd = new PrdDAO();
$TmpPrt = new PrtDAO();
$TmpRel = new RelDAO();

$productRec = $TmpPrd->select($_GET['prd_id'], NULL, NULL, NULL, NULL, NULL, NULL, true, NULL, NULL, 0);
$productTypeRec = $TmpPrt->select($productRec->prt_id, NULL, NULL, NULL, NULL, NULL, NULL, NULL, true);
$uploads = $UplDao->select(NULL, 'PRODUCT', $productRec->prd_id, NULL, false);

$TmpAtv = new AtvDAO();
$attrLabelRec = $TmpAtv->selectValueSet($productRec->atr_id, 'PRODUCTGROUP', $productRec->prd_id, 'products', 'prd_id', NULL);

$FldNum = array();
$FldTyp = array();
$FldLbl = array();
$FldLst = array();
$FldReq = array();
$FldVal = array();
$FldDsc = array();

$i = 0;

$tableLength = count($attrLabelRec);
for ($i = 0; $i < $tableLength; ++$i) {

    $FldNum[$i] = $attrLabelRec[$i]['atl_id'];
    $FldTyp[$i] = $attrLabelRec[$i]['atltyp'];
    $FldLbl[$i] = $attrLabelRec[$i]['atllbl'];
    $FldLst[$i] = $attrLabelRec[$i]['atllst'];
    $FldDsc[$i] = $attrLabelRec[$i]['atldsc'];
    $FldReq[$i] = ($attrLabelRec[$i]['atlreq'] == 1) ? true : false;
    $FldVal[$i] = ($attrLabelRec[$i]['atvval']) ? $attrLabelRec[$i]['atvval'] : '';

}


?>

<div class="row">
    <div class="col-md-12">
        <div class="productItem">

            <div class="breadCrumb">


                <?php
                //$structureParent = $TmpRel->select(NULL, 'PRODUCT', $productRec->prt_id, 'STRUCTURE', NULL, true, ' srtord DESC ');
                //$TmpStr->getBreadCrumb($structureParent->ref_id);
                ?>

            </div>

            <div class="row">
                <div class="col-md-12">
                    <h1><?php echo $productRec->prtnam; ?> </h1>
                    <h2><?php echo $productRec->prdnam; ?> </h2>
                    <h3><?php echo $displayCurrency.$productRec->unipri; ?> </h3>

                    <?php
                    $likedProducts = $TmpRel->select(NULL,'CUS',(isset($loggedIn->pla_id) ? $loggedIn->pla_id : 0),'PRODUCT',$productRec->prd_id,false);
                    ?>
                    <a href="<?php echo $patchworks->webRoot.'pages/products/product.like.php?prd_id='.$productRec->prd_id; ?>"class="likeproduct <?php echo (isset($likedProducts) && count($likedProducts) > 0) ? 'active' : ''; ?>"></a>

                    <hr/>

                </div>
            </div>

            <div class="row">

                <div class="col-sm-5">

                    <?php if (count($uploads) > 0) { ?>
                        <div class="row" id="productPopupParent">
                            <div class="col-md-12">
                                <a href="uploads/images/products/<?php echo $uploads[0]['filnam']; ?>" id="productPopupLink"
                                   class="image-link">
                                    <img src="uploads/images/products/<?php echo $uploads[0]['filnam']; ?>" alt=""
                                         id="heroImage" style="width: 100%;" data-arr_id="0"/>
                                </a>
                            </div>

                        </div>
                    <?php } ?>

                    <div class="row" style="margin-top: 20px;" id="productPopupParent">

                        <?php
                        $tableLength = count($uploads);
                        for ($i = 0; $i < $tableLength; ++$i) {
                            ?>

                            <div class="col-xs-2">

                                <a class="image-link" href="uploads/images/products/<?php echo $uploads[$i]['filnam']; ?>"
                                   data-arr_id="<?php echo $i; ?>"

                                   data-prd_id="<?php echo $productRec->prd_id; ?>"
                                   data-prdnam="<?php echo htmlspecialchars($productRec->prdnam, ENT_QUOTES); ?>"
                                   data-unipri="<?php echo $productRec->unipri; ?>"

                                    ><img
                                        src="uploads/images/products/169-130/<?php echo $uploads[$i]['filnam']; ?>" alt=""
                                        style="width: 100%;"/></a>

                            </div>

                        <?php } ?>

                    </div>

                </div>

                <div class="col-sm-7">

                    <div id="productDetails">

                        <div class="tabswrapper">

                            <ul class="tabselect">
                                <li class="active">
                                    <a href="#tab1">Product Info</a>
                                </li>

                                <?php
                                if (strlen($productTypeRec->prtspc)) {
                                    ?>

                                    <li>
                                        <a href="#tab2">Product Specification</a>
                                    </li>


                                    <?php
                                }
                                ?>


                                <?php

                                $RelDao = new RelDAO();
                                $relatedRecs = $RelDao->relatedProductTypes(NULL,'PRDTYPE',$productTypeRec->prt_id,'PRDTYPE',NULL,false);

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
                                $downloads = $UplDao->select(NULL, 'PRTFILE', $productTypeRec->prt_id, NULL, false);

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
                                    $uploads = $UplDao->select(NULL, 'PRDTYPE', $productTypeRec->prt_id, NULL, false);

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

                                    $customValue = (!empty($productTypeRec->prtobj)) ? $patchworks->getJSONVariable($productTypeRec->prtobj, 'imagedisplay', true) : '';

                                    $pcTagArray = explode(",", $customValue);

                                    if ( in_array('Description Image', $pcTagArray) ) {
                                        echo '<div class="row">';
                                        echo '<div class="col-sm-4"><img src="'.$fileName.'" /></div>';
                                        echo '<div class="col-sm-8">'.$productTypeRec->prtdsc.'</div>';
                                        echo '</div>';
                                    } else {
                                        echo $productTypeRec->prtdsc;
                                    }

                                    ?>

                                </div>
                                <div class="tab" id="tab2">
                                    <?php
                                    echo $productTypeRec->prtspc;
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
                                                    <a href="pages/products/download.upload.php?upl_id=<?php echo $downloads[$i]['upl_id']; ?>" target="_blank" class="pdflink">
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


<!--                        <div class="socialshare">-->
<!---->
<!--                            --><?php
//                            $url = '';
//
//                            if (isset($_GET['str_id']) && is_numeric($_GET['str_id'])) {
//                                $url .= $patchworks->webRoot . $_GET['seourl'] . '/category/' . $_GET['str_id'] . '/' . $_GET['strseo'] . '/producttype/' . $_GET['prt_id'] . '/' . $_GET['prtseo'];;
//                            } else {
//                                $url .= $patchworks->webRoot . $_GET['seourl'] . '/producttype/' . $_GET['prt_id'] . '/' . $_GET['prtseo'];;
//                            }
//
//                            ?>
<!---->
<!---->
<!--                            <p>Share this item</p>-->
<!--                            <ul>-->
<!--                                <li><a href="https://www.facebook.com/sharer/sharer.php?u=--><?php //echo $url; ?><!--"-->
<!--                                       target="_blank"> <i class="fa fa-facebook"></i> </a></li>-->
<!--                                <li><a href="https://twitter.com/home?status=--><?php //echo $url; ?><!--" target="_blank"> <i-->
<!--                                            class="fa fa-twitter"></i> </a></li>-->
<!--                                <li><a href="https://plus.google.com/share?url=--><?php //echo $url; ?><!--" target="_blank"> <i-->
<!--                                            class="fa fa-google-plus"></i> </a></li>-->
<!--                                <li><a href="https://pinterest.com/pin/create/button/?url=&media=--><?php //echo $url; ?><!--"-->
<!--                                       target="_blank"> <i class="fa fa-pinterest"></i> </a></li>-->
<!--                            </ul>-->
<!--                        </div>-->
<!---->
<!---->
<!--                    </div>-->


                        <form action="pages/shoppingcart/shoppingcart_control.php" method="post" id="productForm"
                              class="form-vertical">

                            <input type="hidden" name="action" value="add">

                            <input type="hidden" name="prd_id" value="<?php echo $productRec->prd_id; ?>">

                            <?php

                            // STOCK UPDATE (YES/NO) !!!
                            $inStock = true;
                            $productRec->in_stk = 999;

                            if ($inStock) {
                                ?>

                                <div class="control-group form-group">
                                    <div class="controls">
                                        <label>Quantity:</label>
                                        <input type="number" class="form-control" name="qty" value="1" max="<?php echo $productRec->in_stk; ?>" min="1"
                                               data-parsley-max="">

                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit">Add To Cart</button>
                                </div>

                            <?php } else { ?>

                                <div class="row">
                                    <div class="col-sm-12">

                                        <h5>Sorry this product is currently out of stock...</h5>

                                    </div>
                                </div>

                            <?php } ?>

                        </form>


                </div>

            </div>

        </div>

    </div>

</div>