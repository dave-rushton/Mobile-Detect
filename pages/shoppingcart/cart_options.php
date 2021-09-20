<?php

require $patchworks->docRoot . 'admin/ecommerce/classes/vat.cls.php';

function basketVAT($items = [], $vatObj = null)
{
    $TmpPrd = new PrdDAO();

    if ( empty($items) ) {
        return 0.00;
    }

    if ( empty($vatObj) ) {
        return 0.00;
    }

    $basketVat = 0.00;

    $vatRate = $vatObj->vatrat;
//    echo 'VAT Rate: ' . $vatObj->vatrat . '<br/>';

    foreach ($items as $item) {
        $productRec = $TmpPrd->select(
            $item['prd_id'],
            null,
            null,
            null,
            null,
            null,
            null,
            true
        );

//        echo '-----------<br/>';
//        echo 'Item ID: ' . $item['prt_id'] . ' (' . $item['prd_id'] . ')<br/>';
//        echo 'No. Items: ' . $item['qty'] . '<br/>';
//        echo 'Item Price: &pound;' . $productRec->unipri . '<br/>';

        $itemVat = (floatval($productRec->unipri) / 100) * $vatRate;

//        echo 'Item VAT: &pound;' . $itemVat . '<br/>';
//        echo 'Price inc. VAT: &pound;' . ($productRec->unipri + $itemVat) . '<br/>';

        $basketVat += $itemVat * intval($item['qty']);
//        echo 'Total VAT: &pound;' . $basketVat . '<br/>';
    }

    return number_format($basketVat, 2);
}

$TmpPrd = new PrdDAO();
$TmpUpl = new UplDAO();
$TmpDel = new DelDAO();
$TmpPrt = new PrtDAO();
$TmpVat = new VatDAO();
$TmpEco = new EcoDAO();

$currConv = 1;
$discountAmount = 0;
$dispCurr = '&pound;';
$ecoProp = $TmpEco->select(true);
$totalAmount = 0;
$totalItems = 0;
$totalWeight = 0;

$vatObj = $TmpVat->select(null, null, null, true, true);
$vat = basketVAT($shoppingcart->shoppingCart['items'], $vatObj);

?>
<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h2 class="styled">Shopping Cart</h2>
                <hr/>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-9">
                <div class="shoppingCart">
                    <div id="shoppingCart">
                    <?php
                        if (
                            isset($shoppingcart->shoppingCart['items'])
                            && is_array($shoppingcart->shoppingCart['items'])
                        ) {
                            for ($i = 0; $i < count($shoppingcart->shoppingCart['items']); $i++) {
                                $totalItems += $shoppingcart->shoppingCart['items'][$i]['qty'];

                                //
                                // FIND PRODUCT FOR PRICE
                                //
                                $productRec = $TmpPrd->select(
                                    $shoppingcart->shoppingCart['items'][$i]['prd_id'],
                                    null,
                                    null,
                                    null,
                                    null,
                                    null,
                                    null,
                                    true
                                );

                                $totalWeight += ($productRec->weight * $shoppingCart['items'][$i]['qty']);

                                $UniPri = $shoppingcart->priceProduct(
                                    $shoppingcart->shoppingCart['items'][$i]['prd_id'],
                                    $shoppingcart->shoppingCart['items'][$i]['qty'],
                                    null
                                );

                                $lineAmount = number_format(
                                    $UniPri * $shoppingcart->shoppingCart['items'][$i]['qty'],
                                    2,
                                    '.',
                                    ''
                                );

                                if (isset($shoppingcart->shoppingCart['items'][$i]['discod'])) {
                                    if ($shoppingcart->shoppingCart['items'][$i]['pctamt'] == 'A') {
                                        $lineAmount -= ($shoppingcart->shoppingCart['items'][$i]['disamt'] * $shoppingcart->shoppingCart['items'][$i]['qty']);
                                    } else {
                                        $lineAmount -= ($lineAmount / 100) * $shoppingcart->shoppingCart['items'][$i]['disamt'];
                                    }
                                }

                                $totalAmount += $lineAmount;
                            ?>
                            <div class="row shoppingCartLine">
                                <div class="col-md-2">
                                    <div class="shoppingLineImg">
                                    <?php
                                        $uploads = $TmpUpl->select(null, 'PRDTYPE', $productRec->prt_id, null, false);

                                        $className = '';
                                        if (
                                            isset($uploads[0])
                                            && file_exists(
                                                $patchworks->docRoot . 'uploads/images/products/169-130/' . $uploads[0]['filnam']
                                            )
                                            && !is_dir(
                                                $patchworks->docRoot . 'uploads/images/products/169-130/' . $uploads[0]['filnam']
                                            )
                                        ) {
                                            $fileName = 'uploads/images/products/169-130/' . $uploads[0]['filnam'];
                                            $altName = $uploads[0]['uplttl'];
                                        } else {
                                            if (
                                                isset($uploads[0])
                                                && file_exists(
                                                    $patchworks->docRoot . 'uploads/images/169-130/' . $uploads[0]['filnam']
                                                )
                                                && !is_dir(
                                                    $patchworks->docRoot . 'uploads/images/169-130/' . $uploads[0]['filnam']
                                                )
                                            ) {
                                                $fileName = 'uploads/images/169-130/' . $uploads[0]['filnam'];
                                                $altName = $uploads[0]['uplttl'];
                                            } else {
                                                $productImages = $TmpPrt->getProductVariantImage($productRec->prd_id);

                                                if (
                                                    isset($productImages[0]) &&
                                                    file_exists(
                                                        $patchworks->docRoot . 'uploads/images/products/169-130/' . $productImages[0]['filnam']
                                                    ) &&
                                                    !is_dir(
                                                        $patchworks->docRoot . 'uploads/images/products/169-130/' . $productImages[0]['filnam']
                                                    )
                                                ) {
                                                    $fileName = 'uploads/images/products/169-130/' . $productImages[0]['filnam'];
                                                    $altName = $productImages[0]['uplttl'];
                                                } else {
                                                    $fileName = 'pages/img/noimg.png';
                                                    $altName = 'No Image';
                                                    $className = 'noimg';
                                                }
                                            }
                                        }
                                    ?>
                                        <a href="<?= $patchworks->productsURL . '/producttype/' . $productRec->prt_id . '/' . $productRec->prtseo . '?view=products'; ?>">
                                            <img src="<?= $fileName; ?>"
                                                 alt="<?= $productRec->prdnam; ?>">
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <strong><?= $productRec->prtnam; ?></strong><br>
                                    <i>
                                    <?php
                                        $varient = str_replace($productRec->prtnam, '', $productRec->prdnam);
                                        $varient = str_replace("1/4", "&frac14;", $varient);
                                        $varient = str_replace("1/2", "&frac12;", $varient);
                                        $varient = str_replace("2/4", "&frac12;", $varient);
                                        $varient = str_replace("3/4", "&frac34;", $varient);
                                        $varient = str_replace("1/3", "&frac13;", $varient);
                                        $varient = str_replace("2/3", "&frac23;", $varient);
                                        $varient = str_replace("1/8", "&frac18;", $varient);
                                        $varient = str_replace("3/8", "&frac38;", $varient);
                                        $varient = str_replace("5/8", "&frac58;", $varient);
                                        $varient = str_replace("7/8", "&frac78;", $varient);

                                        echo $varient;
                                    ?>
                                    </i>
                                    <?php
                                        if (isset($shoppingcart->shoppingCart['items'][$i]['disnam'])) {
                                            echo '<br>
                                                <small>
                                                    <em>' . $shoppingcart->shoppingCart['items'][$i]['discod']
                                                    . ' ' . $shoppingcart->shoppingCart['items'][$i]['disnam']
                                                    . '</em>
                                                </small>';
                                        }
                                    ?>
                                    <p><small><?= '&pound;' . $productRec->unipri; ?></small></p>
                                </div>
                                <div class="col-md-2 text-center">
                                    Qty:
                                    <?= $shoppingcart->shoppingCart['items'][$i]['qty']; ?>
                                </div>
                                <div class="col-md-2 text-right">
                                    <span class="cartprice">
                                    <?= $dispCurr . number_format($lineAmount * $currConv, 2, '.', ''); ?>
                                    </span>
                                </div>
                            </div>
                            <?php
                            }

                            $basketPrice = $totalAmount;

                            if (isset($shoppingcart->shoppingCart['discount']['discod'])) {
                                $discountAmount = 0;

                                if ($shoppingcart->shoppingCart['discount']['pctamt'] == 'A') {
                                    $discountAmount = $shoppingcart->shoppingCart['discount']['disamt'];
                                    $totalAmount = $totalAmount - $shoppingcart->shoppingCart['discount']['disamt'];
                                } else {
                                    $discountAmount = (($totalAmount / 100) * $shoppingcart->shoppingCart['discount']['disamt']);
                                    $totalAmount = $totalAmount - (($totalAmount / 100) * $shoppingcart->shoppingCart['discount']['disamt']);
                                }

                                if ($totalAmount < 0) {
                                    $totalAmount = 0;
                                }
                            }

                            if (!isset($shoppingcart->shoppingCart['customer']['coucod'])) {
                                $shoppingcart->shoppingCart['customer']['coucod'] = '';
                            }

                            //
                            // CHECK DELIVERY EXTRA FOR POSTCODE
                            //
                            $pstcodArr = explode(" ", $shoppingcart->shoppingCart['customer']['pstcod']);
                            $TmpDel = new DelDAO();
                            $delivery = $TmpDel->select(null, 'PC-' . $pstcodArr[0], $totalAmount, 0, false);

                            if (count($delivery) === 0) {
                                $delivery = $TmpDel->select(
                                    null,
                                    $shoppingcart->shoppingCart['customer']['coucod'],
                                    $totalAmount,
                                    0,
                                    false,
                                    'PRICE'
                                );

                                $delweight = $TmpDel->select(
                                    null,
                                    $shoppingcart->shoppingCart['customer']['coucod'],
                                    $totalWeight,
                                    0,
                                    false,
                                    'WEIGHT'
                                );

                                $delivery = array_merge($delivery, $delweight);

                                if ( count($delivery) === 0 ) {
                                    echo '<div class="alert alert-danger" style="margin-top: 20px;">
                                        <p>We have not found a delivery record, please call us to discuss the
                                            delivery of your
                                            products</p>
                                    </div>';
                                } else {
                                    if (!isset($shoppingcart->shoppingCart['delivery']['del_id'])) {
                                        $shoppingcart->setDelivery($delivery[0]['del_id']);
                                    } else {
                                        $delSame = false;

                                        for ($i = 0; $i < count($delivery); ++$i) {
                                            if ($shoppingcart->shoppingCart['delivery']['del_id'] == $delivery[$i]['del_id']) {
                                                $delSame = true;
                                                break;
                                            }
                                        }

                                        if ($delSame == false) {
                                            $shoppingcart->setDelivery($delivery[0]['del_id']);
                                        }
                                    }

                                    if (isset($shoppingcart->shoppingCart['delivery']['del_id'])) {
                                        $totalAmount += $shoppingcart->shoppingCart['delivery']['delpri'];
                                    }
                                }
                            }
                        }
                    ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="totalsbox">
                            <h3>Billing</h3>
                            <strong><?= $shoppingcart->shoppingCart['customer']['paycus']; ?></strong><br>
                            <strong><?= $shoppingcart->shoppingCart['customer']['custtl']
                                . ' '
                                . $shoppingcart->shoppingCart['customer']['cusfna']
                                . ' '
                                . $shoppingcart->shoppingCart['customer']['cussna'];
                            ?></strong><br>
                            <?= $shoppingcart->shoppingCart['customer']['payadr1']; ?><br>
                            <?= $shoppingcart->shoppingCart['customer']['payadr2']; ?><br>
                            <?= $shoppingcart->shoppingCart['customer']['payadr3']; ?><br>
                            <?= $shoppingcart->shoppingCart['customer']['payadr4']; ?><br>
                            <?= $shoppingcart->shoppingCart['customer']['paypstcod']; ?>
                            <a href="checkout/details" class="editlink">edit</a>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="totalsbox">
                            <h3>Delivery</h3>
                            <strong><?= $shoppingcart->shoppingCart['customer']['cusnam']; ?></strong><br>
                            <strong><?= $shoppingcart->shoppingCart['customer']['ordfao']; ?></strong><br>
                            <?= $shoppingcart->shoppingCart['customer']['adr1']; ?><br>
                            <?= $shoppingcart->shoppingCart['customer']['adr2']; ?><br>
                            <?= $shoppingcart->shoppingCart['customer']['adr3']; ?><br>
                            <?= $shoppingcart->shoppingCart['customer']['adr4']; ?><br>
                            <?= $shoppingcart->shoppingCart['customer']['pstcod']; ?>
                            <a href="checkout/details" class="editlink">edit</a>
                        </div>
                    </div>
                </div>
                <div class="row" style="display: none;">
                    <div class="col-sm-12" style="font-size: 0.8em;">
                        <p>
                            <strong>Delivery Instructions: </strong>
                            <?= (isset($shoppingcart->shoppingCart['deliveryinstructions']))
                                ? $shoppingcart->shoppingCart['deliveryinstructions']
                                : '';
                            ?>
                            <br>
                            <strong>Special Instructions: </strong>
                            <?= (isset($shoppingcart->shoppingCart['specialinstructions']))
                                ? $shoppingcart->shoppingCart['specialinstructions']
                                : '';
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="totalsbox">
                <?php $multiBuyAmount = $shoppingcart->checkMultibuy(); ?>
                    <ul>
                        <li><span>Qty:</span> <?= $totalItems; ?></li>
                        <li><span>Sub Total:</span> <?= $dispCurr . number_format($basketPrice, 2); ?></li>
                    </ul>
                </div>
                <?php
                    if (count($delivery) > 0) {
                ?>
                    <form id="deliveryForm" class="form-vertical" action="pages/shoppingcart/shoppingcart_control.php"
                          style="margin-bottom: 30px;">
                        <h3>Shipping</h3>
                        <input type="hidden" name="action" value="delivery">
                        <input type="hidden" name="rtnurl" value="<?= $_GET['seourl']; ?>">
                        <div class="control-group form-group" style="margin-bottom: 0;">
                            <div class="controls">
                            <?php
                                $tableLength = count($delivery);
                                for ($i = 0; $i < $tableLength; ++$i) {
                            ?>
                                <label class="deliveryoption <?php
                                    if ($delivery[$i]['delpri'] == 0) {
                                        echo 'freedelivery';
                                    }
                                ?>">
                                    <input type="radio" name="del_id"
                                           value="<?= $delivery[$i]['del_id']; ?>" <?php
                                                if (
                                                    isset($shoppingcart->shoppingCart['delivery']['del_id'])
                                                    && $shoppingcart->shoppingCart['delivery']['del_id'] == $delivery[$i]['del_id']
                                                ) {
                                                    echo 'checked';
                                                }
                                            ?>
                                    >
                                    <?= $delivery[$i]['delnam'] . ' &pound;' . number_format(
                                            $delivery[$i]['delpri'] * $currConv,
                                            2
                                        );
                                    ?>
                                </label>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                        <script>
                            jQuery(function($) {
                                $('[name="del_id"]', $('#deliveryForm')).change(function() {
                                    $('#deliveryForm').submit();
                                });
                            });
                        </script>
                    </form>
                    <?php
                                } else {
                                    if (!$TmpDel->inEurope(
                                            $shoppingcart->shoppingCart['customer']['coucod']
                                        )
                                        && !empty($patchworks->getJSONVariable($ecoProp->ecoobj, 'noneutext', false))
                                    ) {
                    ?>
                    <div class="alert alert-danger">
                        <p><strong>Information:</strong> <?= $patchworks->getJSONVariable($ecoProp->ecoobj, 'noneutext', false); ?></p>
                    </div>
                    <?php
                                    }

                                    if ($TmpDel->inEurope(
                                            $shoppingcart->shoppingCart['customer']['coucod']
                                        )
                                        && !empty($patchworks->getJSONVariable($ecoProp->ecoobj, 'eutext', false))) {
                    ?>
                    <div class="alert alert-danger">
                        <p>
                            <strong>Information:</strong>
                            <?= $patchworks->getJSONVariable(
                                    $ecoProp->ecoobj,
                                    'eutext',
                                    false
                                );
                            ?>
                        </p>
                    </div>
                    <?php
                                    }

                                    if ($TmpDel->inEurope($shoppingcart->shoppingCart['customer']['coucod'])) {
                    ?>
                    <div class="alert alert-danger">
                        <p>
                            <strong>Warning:</strong> We have not found a delivery record, please call us to discuss
                            the delivery of your products.
                        </p>
                    </div>
                    <?php
                                    }
                                }

                    $deliveryVAT = ($shoppingcart->shoppingCart['delivery']['delpri'] / 100) * $vatObj->vatrat;
                    $totalVat = $vat + $deliveryVAT;

                    ?>
                    <div class="totalsbox">
                    <?php $multiBuyAmount = $shoppingcart->checkMultibuy(); ?>
                        <ul>
                            <li><span>Qty:</span> <?= $totalItems; ?></li>
                            <li><span>Sub Total:</span> <?= $dispCurr . number_format($basketPrice, 2); ?></li>
                            <?php
                                if ($discountAmount > 0) {
                            ?>
                            <li><span>Discount:</span> <?= $dispCurr . number_format($discountAmount, 2); ?></li>
                            <?php
                                }

                                if ($multiBuyAmount > 0) {
                                    $totalAmount = $totalAmount - $multiBuyAmount;
                            ?>
                            <li><span>Offers:</span> <?= '-' . $dispCurr . number_format($multiBuyAmount, 2); ?></li>
                            <?php
                                }

                                if (isset($shoppingcart->shoppingCart['delivery']['delpri'])) {
                            ?>
                            <li>
                                <span>Delivery:</span> <?= $dispCurr . $shoppingcart->shoppingCart['delivery']['delpri']; ?>
                            </li>
                            <?php
                            }
                            ?>
                            <li><span>VAT:</span> <?= $dispCurr . number_format($totalVat, 2); ?></li>
                            <li>
                                <span>Total:</span> <?= $dispCurr . number_format(
                                    ($totalAmount + $totalVat) * $currConv,
                                    2,
                                    '.',
                                    ''
                                );
                                ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <?php
                if ($totalItems >= 5) {
            ?>
            <div class="row">
                <div class="col-md-12">
                    <h3>Payment Method</h3>
                    <hr>
                    <div class="row">
                    <?php
                        if ($eCommProp->sp_sta != 'NA') {
                    ?>
                        <div class="col-sm-6">
                            <h5>Pay by Credit/Debit Card</h5>
                            <hr>
                            <p>
                                <img src="pages/img/payment-logos.jpg" alt="we accept payments">
                            </p>
                            <div id="paybycreditcard">
                                <form action="payment/sagepay/sagepaycheckout.php"
                                      style="margin-top: 30px; margin-bottom: 30px;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p><label><input type="checkbox" required> I agree to the <a
                                                            href="terms-and-conditions" target="_blank">terms and
                                                        conditions</a></label>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary">Confirm
                                                &amp; Make
                                                Payment <i class="fa fa-angle-right"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <?php
                        }

                        if ($eCommProp->wp_sta != 'NA') {
                            ?>
                            <div class="col-sm-6">
                                <h5>Pay by Credit/Debit Card</h5>
                                <hr>
                                <p>
                                    <img src="pages/img/payment-logos.jpg" alt="we accept payments">
                                </p>
                                <div id="paybycreditcard">
                                    <form action="payment/worldpay/worldpaycheckout.php"
                                          style="margin-top: 30px; margin-bottom: 30px;">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p><label><input type="checkbox" required> I agree to the <a
                                                                href="terms-and-conditions" target="_blank">terms and
                                                            conditions</a></label>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary">Confirm
                                                    &amp; Make
                                                    Payment <i class="fa fa-angle-right"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <?php
                        }

                        if ($eCommProp->pp_sta != 'NA') {
                            ?>
                            <div class="col-sm-6">
                                <div id="paybypaypal">
                                    <p>
                                        <img src="pages/img/paypal-logo.jpg" alt="we accept payments">
                                    </p>
                                    <?php
                                    $client_id = ($eCommProp->pp_sta === 'TEST')
                                        ? $patchworks->PAYPAL_TEST_CLIENT_KEY
                                        : $patchworks->PAYPAL_LIVE_CLIENT_KEY;
                                    ?>
                                    <script src="https://www.paypal.com/sdk/js?client-id=<?= $client_id; ?>&currency=GBP"></script>
                                    <script>paypal.Buttons({
                                            locale: 'en_GB',
                                            createOrder: function(data, actions) {
                                                return actions.order.create({
                                                    purchase_units: [
                                                        {
                                                            amount: {
                                                                value: "<?= number_format(
                                                                    ($totalAmount + $totalVat) * $currConv,
                                                                    2
                                                                ); ?>",
                                                                currency_code: "GBP",
                                                                /*breakdown: {
                                                                    item_total: "<?= number_format($basketPrice, 2); ?>",
                                                                    tax_total: "<?= $vat; ?>"
                                                                }*/
                                                            }//,
                                                            //items: <?php
                                                                /*$itemObjArray = [];

                                                                foreach ($shoppingcart->shoppingCart['items'] as $item) {
                                                                    $itemObj = new stdClass();
                                                                    $itemObj->name = $item['prtnam'];
                                                                    $itemObj->unit_amount = new stdClass();
                                                                    $itemObj->unit_amount->currency_code = 'GBP';
                                                                    $itemObj->unit_amount->value = $item['unipri'];
                                                                    $itemObj->tax = new stdClass();
                                                                    $itemObj->tax->currency_code = 'GBP';
                                                                    $itemObj->tax->value = number_format(
                                                                        (floatval($item['unipri']) / 100) * floatval($vatObj->vatrat),
                                                                        2
                                                                    );
                                                                    $itemObj->quantity = $item['qty'];

                                                                    $itemObjArray[] = $itemObj;
                                                                }

                                                                echo json_encode($itemObjArray)*/
                                                            ?>/*,
                                                            /*shipping: {
                                                                currency_code: "GBP",
                                                                value: "<?= $shoppingcart->shoppingCart['delivery']['delpri']; ?>"
                                                            }*/
                                                        }
                                                    ]
                                                });
                                            },
                                            onApprove: function(data, actions) {
                                                return actions.order.capture().then(function() {
                                                    $.ajax('/payment/paypal/paypal-checkout.php', {
                                                        method: 'post',
                                                        data: {
                                                            orderID: data.orderID,
                                                        },
                                                    }).then(function() {
                                                        window.location.href = 'checkout/complete';
                                                    });
                                                });
                                            },
                                        }).render('#paybypaypal');</script>
                                </div>
                            </div>
                            <?php
                        }

                        if ($eCommProp->colect == 1) {
                            ?>
                            <div class="col-sm-6">
                                <div id="payinstore">
                                    <h5>Collect &amp; Pay In Store</h5>
                                    <hr>
                                    <form class="collect" action="pages/shoppingcart/shoppingcart_control.php"
                                          method="post" id="collect_form"
                                          style="margin-top: 30px; margin-bottom: 30px;">
                                        <input type="hidden" name="action" value="collectorder">
                                        <input type="hidden" name="rtnurl" value="<?php echo $_GET['seourl']; ?>">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p><label><input type="checkbox" required> I agree to the <a
                                                                href="terms-and-conditions" target="_blank">terms and
                                                            conditions</a></label>
                                                </p>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Collect and Pay InStore <i
                                                    class="fa fa-angle-right"></i></button>
                                    </form>
                                </div>
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
    </div>
</div>
