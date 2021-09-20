<?php

$totalAmount = 0;

$TmpUpl = new UplDAO();

$relatedIDs = '';

$TmpRel = new RelDAO();
$UplDao = new UplDAO();
$TmpPrt = new PrtDAO();

$dispCurr = '&pound;';
$currConv = 1;

$totalItems = 0;
$basketPrice = 0;

?>
<div class="section">
    <div class="container">
        <?php
        /*
           <div class="breadcrumb">
            <ul>
                <li>
                    <a href="<?php echo $patchworks->productsURL ?>">
                        Shop
                    </a>
                </li>
            </ul>
        </div>
         * */
        ?>

        <div class="row">
            <div class="col-sm-12">

                <h2 class="styled">Shopping Cart</h2>
                <hr>
            </div>
        </div>


        <div class="row">
            <div class="col-sm-9">

                <div id="shoppingCart">

                    <?php if (1 == 2 && isset($shoppingcart->shoppingCart['disttl'])) { ?>
                        <p class="alert alert-info"><?php echo $shoppingcart->shoppingCart['disttl'] . ' ' . $shoppingcart->shoppingCart['distxt']; ?></p>
                    <?php } ?>

                    <?php
                    if (isset($shoppingcart->shoppingCart['items']) && is_array($shoppingcart->shoppingCart['items'])) {

                        for ($i = 0; $i < count($shoppingcart->shoppingCart['items']); $i++) {
                            if($shoppingcart->shoppingCart['items'][$i]['qty'] < 5){
                                $shoppingcart->shoppingCart['items'][$i]['qty'] = 5;
                            }
                            $totalItems += $shoppingcart->shoppingCart['items'][$i]['qty'];

                            $productRec = $TmpPrd->select($shoppingcart->shoppingCart['items'][$i]['prd_id'], NULL, NULL, NULL, NULL, NULL, NULL, true, NULL, NULL);

                            $relatedIDs .= (empty($relatedIDs)) ? $productRec->prt_id : ',' . $productRec->prt_id;

                            $UniPri = $shoppingcart->priceProduct($shoppingcart->shoppingCart['items'][$i]['prd_id'], $shoppingcart->shoppingCart['items'][$i]['qty'], NULL);

                            $lineAmount = number_format($UniPri * $shoppingcart->shoppingCart['items'][$i]['qty'], 2, '.', '');

                            $basketPrice += number_format($lineAmount, 2, '.', '');
                            $totalAmount += number_format($lineAmount, 2, '.', '');

                            ?>


                            <div class="row shoppingCartLine">
                                <div class="col-sm-12">
                                    <div class="inner">
                                        <div class="row">
                                            <div class="col-md-2">

                                                <div class="shoppingLineImg">
                                                    <?php

                                                    $uploads = $UplDao->select(NULL, 'PRDTYPE', $productRec->prt_id, NULL, false);

                                                    $className = '';
                                                    if (
                                                        isset($uploads[0]) &&
                                                        file_exists($patchworks->docRoot . 'uploads/images/products/169-130/' . $uploads[0]['filnam']) &&
                                                        !is_dir($patchworks->docRoot . 'uploads/images/products/169-130/' . $uploads[0]['filnam'])
                                                    ) {
                                                        $fileName = 'uploads/images/products/169-130/' . $uploads[0]['filnam'];
                                                        $altName = $uploads[0]['uplttl'];
                                                    }  else if (
                                                        isset($uploads[0]) &&
                                                        file_exists($patchworks->docRoot . 'uploads/images/169-130/' . $uploads[0]['filnam']) &&
                                                        !is_dir($patchworks->docRoot . 'uploads/images/169-130/' . $uploads[0]['filnam'])
                                                    ) {
                                                        $fileName = 'uploads/images/169-130/' . $uploads[0]['filnam'];
                                                        $altName = $uploads[0]['uplttl'];
                                                    } else {

                                                        $productImages = $TmpPrt->getProductVariantImage( $productRec->prd_id );


                                                        if (
                                                            isset($productImages[0]) &&
                                                            file_exists($patchworks->docRoot . 'uploads/images/products/169-130/' . $productImages[0]['filnam']) &&
                                                            !is_dir($patchworks->docRoot . 'uploads/images/products/169-130/' . $productImages[0]['filnam'])
                                                        ) {
                                                            $fileName = 'uploads/images/products/169-130/' . $productImages[0]['filnam'];
                                                            $altName = $productImages[0]['uplttl'];
                                                        } else {
                                                            $fileName = 'pages/img/noimg.png';
                                                            $altName = 'No Image';
                                                            $className = 'noimg';
                                                        }

                                                    }

                                                    ?>

                                                    <a href="<?php echo $patchworks->productsURL; ?>/producttype/<?php echo $productRec->prt_id; ?>/<?php echo $productRec->prtseo; ?>?view=products">

                                                        <img src="<?php echo $fileName; ?>" alt="<?php echo $productRec->prdnam; ?>">

                                                    </a>

                                                </div>

                                            </div>
                                            <div class="col-md-6">

                                                <strong><?php echo $productRec->prtnam; ?></strong><br>
                                                <i>
                                                    <?php
                                                    $varient = str_replace($productRec->prtnam, '', $productRec->prdnam);
                                                    $varient = str_replace("1/4","&frac14;",$varient);
                                                    $varient = str_replace("1/2","&frac12;",$varient);
                                                    $varient = str_replace("2/4","&frac12;",$varient);
                                                    $varient = str_replace("3/4","&frac34;",$varient);
                                                    $varient = str_replace("1/3","&frac13;",$varient);
                                                    $varient = str_replace("2/3","&frac23;",$varient);
                                                    $varient = str_replace("1/8","&frac18;",$varient);
                                                    $varient = str_replace("3/8","&frac38;",$varient);
                                                    $varient = str_replace("5/8","&frac58;",$varient);
                                                    $varient = str_replace("7/8","&frac78;",$varient);
                                                    echo $varient;
                                                    ?>
                                                </i><br>
                                                <small><?php echo $dispCurr.$UniPri; ?></small>

                                            </div>
                                            <div class="col-md-2 text-center">


                                                <form class="form-inline quantityForm"
                                                      action="shoppingcart/updateqty/<?php echo $shoppingcart->shoppingCart['items'][$i]['prd_id']; ?>"
                                                      method="post" style="margin-bottom: 30px;">
                                                    <input type="hidden" name="prd_id"
                                                           value="<?php echo $shoppingcart->shoppingCart['items'][$i]['prd_id']; ?>">
                                                    <input type="hidden" name="seourl" value="<?php echo $_GET['seourl']; ?>">



                                                    <div class="qtybuttons">

                                                        <a href="shoppingcart/updateqty/<?php echo $shoppingcart->shoppingCart['items'][$i]['prd_id']; ?>?qty=<?php echo $shoppingcart->shoppingCart['items'][$i]['qty']-1; ?>" class="lessqty">-</a>

                                                        <?php

                                                        ?>
                                                        <input type="number" min="5" name="qty" value="<?php echo $shoppingcart->shoppingCart['items'][$i]['qty']; ?>">

                                                        <a href="shoppingcart/updateqty/<?php echo $shoppingcart->shoppingCart['items'][$i]['prd_id']; ?>?qty=<?php echo $shoppingcart->shoppingCart['items'][$i]['qty']+1; ?>"class="addqty">+</a>

                                                        <a href="shoppingcart/updateqty/<?php echo $shoppingcart->shoppingCart['items'][$i]['prd_id']; ?>?qty=0" class="removelink"><small class="removeBtn">remove</small></a>


                                                    </div>

                                                </form>


                                                <!--                                    <a href="shoppingcart/remove/-->
                                                <?php //echo $shoppingcart->shoppingCart['items'][$i]['prd_id']; ?><!--"-->
                                                <!--                                       class="btn btn-xs btnremove pull-left"><i class="fa fa-minus"></i></a>-->
                                                <!--                                    --><?php //echo $shoppingcart->shoppingCart['items'][$i]['qty']; ?>
                                                <!--                                    <a href="shoppingcart/add/-->
                                                <?php //echo $shoppingcart->shoppingCart['items'][$i]['prd_id']; ?><!--"-->
                                                <!--                                       class="btn btn-xs btnadd pull-right"><i class="fa fa-plus"></i></a>-->
                                            </div>
                                            <div
                                                    class="col-md-2 text-right">
                                    <span class="cartprice">
                                        <?php
                                        echo ($lineAmount > 0) ? $dispCurr . number_format($lineAmount * $currConv, 2, '.', '') : 'POA';
                                        ?>
                                    </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php } ?>

                    <?php } ?>



                    <h4>We Accept</h4>
                    <p>
                        <img src="pages/img/we-accept.jpg" alt="we accept payments">
                    </p>

                </div>


            </div>

            <div class="col-sm-3">

                <div class="totalsbox">

                    <ul>
                        <li><span>Qty:</span> <?php echo $totalItems; ?></li>
                        <li><span>Sub Total:</span> <?php echo $dispCurr.number_format($basketPrice,2); ?></li>
                    </ul>

                </div>

            </div>

        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="checkoutBtn text-right">
                    <a href="<?php echo $patchworks->webRoot.$patchworks->productsURL; ?>" class="btn btn-warning"><i class="fa fa-angle-left"></i>
                        Continue Shopping </a>

                    <?php

                    if ($totalItems > 0) {

                        if (isset($shoppingcart->shoppingCart['customer']['cussna'])) {
                            ?>

                            <a href="checkout/options" class="btn btn-primary">Checkout <i
                                    class="fa fa-angle-right"></i> </a>

                            <?php
                        } else {
                            ?>

                            <a href="checkout/details" class="btn btn-primary">Checkout <i
                                    class="fa fa-angle-right"></i> </a>

                            <?php
                        }

                    }

                    ?>

                </div>

            </div>
        </div>

    </div>
</div>
