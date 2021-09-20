<?php

$UplDao = new UplDAO();
$TmpPrd = new PrdDAO();
$TmpPrt = new PrtDAO();
$TmpRel = new RelDAO();

$productType = $TmpPrt->select($_GET['prt_id'], $_GET['prtseo'], NULL, NULL, NULL, NULL, NULL, NULL, true);
$products = $TmpPrd->select(NULL, NULL, $productType->prt_id, NULL, NULL, NULL, 'p.srtord', false, NULL, NULL, 0);

$uploads = $UplDao->select(NULL, 'PRDTYPE', $productType->prt_id, NULL, false);

$downloads = $UplDao->select(NULL, 'PRTFILE', $productType->prt_id, NULL, false);

//$uploads = $TmpPrt->getProductImage($productType->prt_id);

$relatedProducts = $TmpRel->relatedProducts(NULL, 'PRDTYPE', $productType->prt_id, 'PRDTYPE', NULL, false, "RAND()");

$TmpAtv = new AtvDAO();

?>


<div class="row">
    <div class="col-md-12">
        <div class="productItem">

            <div class="row">
                <div class="col-sm-6">

                    <div id="productGallery">

                        <?php if (count($uploads) > 0) { ?>
                            <div class="row" id="productPopupParent" style="margin-bottom: 30px;">
                                <div class="col-md-12">
                                    <a href="uploads/images/products/<?php echo $uploads[0]['filnam']; ?>"
                                       id="productPopupLink"
                                       class="image-link">
                                        lll
                                        <img src="uploads/images/products/<?php echo $uploads[0]['filnam']; ?>" alt=""
                                             id="heroImage" data-arr_id="0"/>
                                    </a>
                                </div>

                            </div>
                        <?php } ?>

                        <?php
                        $tableLength = count($uploads);
                        if ($tableLength > 1) {
                            ?>
                            <div class="row" style="margin: 0 0 30px 0;" id="productPopupParent">

                                <?php
                                for ($i = 0; $i < $tableLength; ++$i) {
                                    ?>

                                    <div class="col-xs-2">

                                        <a class="image-link"
                                           href="uploads/images/products/<?php echo $uploads[$i]['filnam']; ?>"
                                           data-arr_id="<?php echo $i; ?>"><img
                                                    src="uploads/images/products/169-130/<?php echo $uploads[$i]['filnam']; ?>"
                                                    alt=""/></a>

                                    </div>

                                <?php } ?>

                            </div>

                        <?php } ?>

                    </div>

                </div>
                <div class="col-sm-6">

                    <div id="productDetails">

                        <h1><?php echo $productType->prtnam; ?></h1>

                        <h4><?php echo ($productType->unipri > 0) ? $displayCurrency . $productType->unipri : 'POA'; ?></h4>



                        <div class="tabswrapper">

                            <ul class="tabselect">
                                <li class="active">
                                    <a href="#tab1">Product Info</a>
                                </li>

                                <?php
                                if (strlen($productTypeRec->prtspc)) {
                                    ?>

                                    <li>
                                        <a href="#tab2">Care</a>
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


                        <div class="socialshare">

                            <?php
                            $url = '';

                            if (isset($_GET['str_id']) && is_numeric($_GET['str_id'])) {
                                $url .= $patchworks->webRoot . $_GET['seourl'] . '/category/' . $_GET['str_id'] . '/' . $_GET['strseo'] . '/producttype/' . $_GET['prt_id'] . '/' . $_GET['prtseo'];;
                            } else {
                                $url .= $patchworks->webRoot . $_GET['seourl'] . '/producttype/' . $_GET['prt_id'] . '/' . $_GET['prtseo'];;
                            }

                            ?>


                            <p>Share this item</p>
                            <ul>
                                <li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url; ?>"
                                       target="_blank"> <i class="fa fa-facebook"></i> </a></li>
                                <li><a href="https://twitter.com/home?status=<?php echo $url; ?>" target="_blank"> <i
                                                class="fa fa-twitter"></i> </a></li>
                                <li><a href="https://plus.google.com/share?url=<?php echo $url; ?>" target="_blank"> <i
                                                class="fa fa-google-plus"></i> </a></li>
                                <li><a href="https://pinterest.com/pin/create/button/?url=&media=<?php echo $url; ?>"
                                       target="_blank"> <i class="fa fa-pinterest"></i> </a></li>
                            </ul>
                        </div>


                    </div>

                    <form action="pages/shoppingcart/shoppingcart_control.php" method="post" id="productForm"
                          class="form-vertical">

                        <input type="hidden" name="action" value="add">

                        <div class="radioList">

                            <div class="row">
                                <div class="col-xs-6"><p class="title"></p></div>
                                <div class="col-xs-2"><p class="title">Price</p></div>
                                <div class="col-xs-2"><p class="title">In-Stock</p></div>
                                <div class="col-xs-2"></div>
                            </div>

                            <?php
                            $tableLength = count($products);

                            $checked = false;
                            $check = true;
                            $inStock = false;

                            for ($i = 0; $i < $tableLength; ++$i) {

                                //if ($products[$i]['in_stk'] == 0) continue;

                                $products[$i]['unipri'] = $products[$i]['unipri'];

                                if (!$checked) {
                                    if ($products[$i]['in_stk'] > 0) {
                                        $checked = true;
                                        $check = true;
                                        $inStock = true;
                                    } else {
                                        $check = false;
                                    }
                                } else {
                                    $check = false;
                                }

                                ?>

                                <div class="row radioItem">
                                    <div class="col-xs-6">
                                        <label>
                                            <input type="radio" name="prd_id"
                                                   value="<?php echo $products[$i]['prd_id']; ?>" <?php echo ($products[$i]['in_stk'] <= 0) ? ' disabled ' : ''; ?><?php if ($products[$i]['in_stk'] > 0 && $check) echo 'checked'; ?>
                                                   data-unipri="<?php echo $products[$i]['unipri']; ?>" required>

                                            <span class="productname styled"><?php echo $products[$i]['prdnam']; ?></span>

                                            <?php
                                            $attrLabelRec = $TmpAtv->selectValueSet($products[$i]['atr_id'], 'PRODUCTGROUP', $products[$i]['prd_id'], 'products', 'prd_id', NULL);

                                            $FldNum = array();
                                            $FldTyp = array();
                                            $FldLbl = array();
                                            $FldLst = array();
                                            $FldReq = array();
                                            $FldVal = array();
                                            $FldDsc = array();

                                            $attrTableLength = count($attrLabelRec);
                                            for ($a = 0; $a < $attrTableLength; ++$a) {


                                                $FldNum[$a] = $attrLabelRec[$a]['atl_id'];
                                                $FldTyp[$a] = $attrLabelRec[$a]['atltyp'];
                                                $FldLbl[$a] = $attrLabelRec[$a]['atllbl'];
                                                $FldLst[$a] = $attrLabelRec[$a]['atllst'];
                                                $FldDsc[$a] = $attrLabelRec[$a]['atldsc'];
                                                $FldReq[$a] = ($attrLabelRec[$a]['atlreq'] == 1) ? true : false;
                                                $FldVal[$a] = ($attrLabelRec[$a]['atvval']) ? $attrLabelRec[$a]['atvval'] : '';

                                            }

                                            for ($a = 0; $a < count($FldNum); $a++) {

                                                if (empty($FldVal[$a])) continue;

                                                echo '<br><em>' . $FldLbl[$a] . '</em> ' . $FldVal[$a];

                                            }
                                            ?>

                                        </label>


                                    </div>
                                    <div class="col-xs-2">
                                        <div class="price"><?php echo ($products[$i]['unipri'] > 0) ? $displayCurrency . $products[$i]['unipri'] : 'POA'; ?></div>
                                    </div>
                                    <div class="col-xs-2"><?php echo ($products[$i]['in_stk'] > 0) ? '<i class="fa fa-check"></i>' : '<i class="fa fa-remove"></i>'; ?></div>
                                    <div class="col-xs-2"></div>
                                </div>

                                <?php
                            }
                            ?>

                        </div>


                        <?php
                        if ($inStock) {
                            ?>

                            <div class="control-group form-group">
                                <div class="controls">
                                    <label>Quantity:</label>
                                    <input type="number" class="form-control" name="qty" value="1" max="" min="1"
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



            <?php if ( count($relatedProducts) > 0 ) { ?>

                <div class="row" style="margin-top: 30px;">
                    <!--                        <div class="col-xs-12">-->

                    <div class="col-md-12">
                        <hr>
                        <h3>Related Products</h3>
                    </div>

                    <?php
                    for ($i=0;$i<count($relatedProducts);$i++) {

                        $productupload = $UplDao->select(NULL, 'PRODUCT', $relatedProducts[$i]['ref_id'], NULL, false);

                        ?>

                        <div class="col-md-3">
                            <div class="relatedItem">

                                <a href="<?php echo $_GET['seourl']; ?>/producttype/<?php echo $relatedProducts[$i]['ref_id']; ?>/<?php echo $relatedProducts[$i]['seourl']; ?>">

                                    <?php
                                    if (
                                        isset($productupload[0]) &&
                                        file_exists($patchworks->docRoot . 'uploads/images/products/' . $productupload[0]['filnam']) &&
                                        !is_dir($patchworks->docRoot . 'uploads/images/products/' . $productupload[0]['filnam'])
                                    ) {
                                        echo '<img src="uploads/images/products/' . $productupload[0]['filnam'] . '" alt="'.$productupload[0]['alttxt'].'" class="img-responsive" />';
                                    } else {
                                        echo '<img class="img-responsive" src="http://placehold.it/614x414&text=Awaiting Image">';
                                    }
                                    ?>

                                </a>

                                <h5><?php echo $relatedProducts[$i]['prtnam']; ?></h5>
                                <p><a href="<?php echo $_GET['seourl']; ?>/producttype/<?php echo $relatedProducts[$i]['ref_id']; ?>/<?php echo $relatedProducts[$i]['seourl']; ?>">Read More</a> </p>


                            </div>
                        </div>


                        <?php
                    }
                    ?>


                    <!--                        </div>-->
                </div>
            <?php } ?>


        </div>
    </div>
</div>