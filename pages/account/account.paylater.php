<?php


require_once("../admin/custom/classes/baskets.cls.php");
require_once("../admin/products/classes/products.cls.php");
require_once("../admin/ecommerce/classes/order.cls.php");
require_once("../admin/ecommerce/classes/orderline.cls.php");
require_once("../admin/ecommerce/classes/delivery.cls.php");
require_once("../admin/products/classes/discounts.cls.php");
require_once("../admin/ecommerce/classes/vat.cls.php");


function withoutVAT($amount = 0, $vatrate=20)
{
    $vatCalc = ((100 + $vatrate) / 100);
    return number_format(($amount / $vatCalc), 2);
}

function calcVAT($amount = 0, $vatrate=20)
{
    $vatCalc = ((100 + $vatrate) / 100);
    return number_format(round($amount - ($amount / $vatCalc), 2, PHP_ROUND_HALF_DOWN), 2);
}

function addVAT($amount = 0, $vatrate=20)
{
    $amount = $amount + (($amount / 100) * $vatrate);
    return number_format($amount, 2);
}


function repricebasket($shoppingCart = NULL) {

    $TmpBsk = new BskDAO();
    $TmpPrd = new PrdDAO();
    $TmpBpg = new BpgDAO();
    $TmpBpr = new BprDAO();

    $basketPrice = 0;

    if (!is_null($shoppingCart) && is_array($shoppingCart)) {

        $TmpBsk = new BskDAO();

        for ($b = 0; $b < count($shoppingCart['items']); $b++) {

            // find basket record
            $basketRec = $TmpBsk->select($shoppingCart['items'][$b]['bsk_id'], NULL, NULL, NULL, true);

            // set base price
            $basketPrice = $basketPrice + $basketRec->unipri;

            // loop basket products
            for ($p = 0; $p < count($shoppingCart['items'][$b]['products']); $p++) {

                $basketProductRec = $TmpBpr->select($shoppingCart['items'][$b]['products'][$p]['bpr_id'], NULL, NULL, true);

                // find product
                $productRec = $TmpPrd->select($basketProductRec->prd_id, NULL, NULL, NULL, NULL, NULL, NULL, true, NULL, NULL );

                // add extra price
                $basketPrice = $basketPrice + $basketProductRec->extpri;

            }

            // multiply by quantity
            $basketPrice = $basketPrice * $shoppingCart['items'][$b]['qty'];

        }

        // voucher?

        // discount?

    }

    return $basketPrice;

}

$TmpPla = new PlaDAO();
$customers = $TmpPla->select(NULL, 'CUS', NULL, NULL);

$TmpPrd = new PrdDAO();
$products = $TmpPrd->select(NULL, NULL, NULL, NULL, false);

$TmpDel = new DelDAO();
$delivery = $TmpDel->select(NULL, NULL, NULL, NULL, false);

$Ord_ID = (isset($_GET['order']) && is_numeric($_GET['order'])) ? $_GET['order'] : NULL;

$TmpOrd = new OrdDAO();
$TmpOln = new OlnDAO();
$TmpDis = new DisDAO();

if (is_numeric($Ord_ID)) {
    $order = $TmpOrd->select($Ord_ID, NULL, NULL, NULL, true);
    $orderlines = $TmpOln->select($Ord_ID, NULL, false);
    $discountRec = $TmpDis->selectByCode($order->discod, true);
}

$TmpVat = new VatDAO();
$vatRecs = $TmpVat->select();

$shoppingCart = json_decode( $order->ordobj, true );

//var_dump($shoppingCart);

$TmpBsk = new BskDAO();

?>




<div class="section">

    <div class="container">

        <div class="row">

            <div class="col-sm-12">


                <div class="pagewrapper">


                    <h2 class=" styled heading">SHOPPING CART SUMMARY</h2>


                    <div class="box margin">


                        <?php
                        $deliveryWeight = 0;
                        $basketTotal = 0;

                        if (isset($shoppingCart['items']) && is_array($shoppingCart['items'])) {

                            for ($i = 0; $i < count($shoppingCart['items']); $i++) {

                                $basketTotal += $shoppingCart['items'][$i]['unipri'] * $shoppingCart['items'][$i]['qty'];

                                // find basket
                                $basketRec = $TmpBsk->select($shoppingCart['items'][$i]['bsk_id'], NULL, NULL, NULL, true);

                                $basketLimits = json_decode($basketRec->bsktxt, true);
                                $deliveryWeight = $deliveryWeight + ($basketRec->weight * $shoppingCart['items'][$i]['qty']);

                                ?>



                                <div class="basketitemwrapper">


                                    <div class="row">

                                        <div class="col-sm-3 text-center">


                                            <?php



                                            if (

                                                isset($basketRec->bskimg) && !empty($basketRec->bskimg) &&

                                                file_exists($patchworks->docRoot . 'uploads/images/basket/' . $basketRec->bskimg) &&

                                                !is_dir($patchworks->docRoot . 'uploads/images/basket/' . $basketRec->bskimg)
                                            ) {

                                                echo '<img src="' . $patchworks->webRoot . 'uploads/images/basket/' . $basketRec->bskimg . '" />';

                                            } else {


                                            }



                                            ?>


                                        </div>

                                        <div class="col-sm-9">


                                            <h1><?php echo $basketRec->bskttl; ?></h1>

                                            <h4>&pound;<?php echo repricebasket($shoppingCart, $basketRec->bsk_id); ?></h4>

                                            <?php

                                            // display products

                                            if (isset($shoppingCart['items'][$i]['products']) && is_array($shoppingCart['items'][$i]['products'])) {

                                                echo '<ul>';

                                                for ($p = 0; $p < count($shoppingCart['items'][$i]['products']); $p++) {

                                                    //echo '<li>' . $shoppingCart['items'][$i]['products'][$p]['prdnam'] . '</li>';

                                                    if ($shoppingCart['items'][$i]['products'][$p]['bprqty'] > 1) {
                                                        echo '<li>'.number_format($shoppingCart['items'][$i]['products'][$p]['bprqty'],0).' x '.$shoppingCart['items'][$i]['products'][$p]['prdnam'] . '</li>';
                                                    } else {
                                                        echo '<li>'.$shoppingCart['items'][$i]['products'][$p]['prdnam'] . '</li>';
                                                    }


                                                }

                                                echo '</ul>';
                                            }
                                            ?>



                                            <?php

                                            // display extras

                                            if (isset($shoppingCart['items'][$i]['extras']) && is_array($shoppingCart['items'][$i]['extras'])) {

                                                echo '<ul>';

                                                for ($p = 0; $p < count($shoppingCart['items'][$i]['extras']); $p++) {


                                                    $basketExtra = $TmpBsk->selectSingleProduct($shoppingCart['items'][$i]['extras'][$p]['bpr_id']);


                                                    echo '<li>' . $basketExtra->exttxt . ' : ' . $basketExtra->prdnam . ' (' . $basketExtra->extpri . ')</li>';

                                                }

                                                echo '</ul>';

                                            }

                                            ?>



                                            <?php

                                            // display extras

                                            if (isset($shoppingCart['items'][$i]['bextras']) && is_array($shoppingCart['items'][$i]['bextras'])) {

                                                echo '<ul>';

                                                for ($p = 0; $p < count($shoppingCart['items'][$i]['bextras']); $p++) {


                                                    $basketExtra = $TmpBsk->selectBasketExtra($shoppingCart['items'][$i]['bextras'][$p]['bex_id']);


                                                    echo '<li>' . $basketExtra->bexttl . ' (' . $basketExtra->bexpri . ')</li>';

                                                }

                                                echo '</ul>';

                                            }

                                            ?>

                                            <?php

                                            if (isset($shoppingCart['items'][$i]['forminfo']) && is_array($shoppingCart['items'][$i]['forminfo'])) {

                                                // display custom options

                                                echo '<ul>';

                                                for ($p = 0; $p < count($shoppingCart['items'][$i]['forminfo']['fldnum']); $p++) {

                                                    echo '<li>' . $shoppingCart['items'][$i]['forminfo']['lbl'][$p] . ' <strong>' . $shoppingCart['items'][$i]['forminfo']['fld'][$p] . '</strong></li>';

                                                }

                                                echo '</ul>';

                                            }

                                            ?>


                                        </div>


                                    </div>


                                </div>

                                <?php

                            }


                        }

                        ?>


                    </div>


                    <?php
                    if (isset($shoppingCart['customer']) && is_array($shoppingCart['customer'])) {
                        ?>

                        <div class="row">
                            <div class="col-sm-6">
                                <h2 class="heading">Billing Details</h2>
                                <div class="box margin">
                                    <p>
                                        <strong><?php echo $shoppingCart['customer']['cusfna'] . ' ' . $shoppingCart['customer']['cussna']; ?></strong><br>
                                        <?php echo $shoppingCart['customer']['payadr1']; ?><br>
                                        <?php echo $shoppingCart['customer']['payadr2']; ?><br>
                                        <?php echo $shoppingCart['customer']['payadr3']; ?><br>
                                        <?php echo $shoppingCart['customer']['payadr4']; ?><br>
                                        <?php echo $shoppingCart['customer']['paypstcod']; ?>
                                    </p>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <h2 class="heading">Delivery Details</h2>
                                <div class="box margin">
                                    <p>
                                        <strong><?php echo $shoppingCart['customer']['cusfna'] . ' ' . $shoppingCart['customer']['cussna']; ?></strong><br>
                                        <?php echo $shoppingCart['customer']['adr1']; ?><br>
                                        <?php echo $shoppingCart['customer']['adr2']; ?><br>
                                        <?php echo $shoppingCart['customer']['adr3']; ?><br>
                                        <?php echo $shoppingCart['customer']['adr4']; ?><br>
                                        <?php echo $shoppingCart['customer']['pstcod']; ?>
                                    </p>

                                    <p>Delivery Date From: <?php echo date("jS M Y", strtotime($shoppingCart['deliverydate'])); ?></p>

                                    <?php

                                    if (!isset($shoppingCart['customer']['coucod'])) $shoppingCart['customer']['coucod'] = '';

                                    //
                                    // CHECK DELIVERY EXTRA FOR POSTCODE
                                    //

                                    $pstcodArr = explode(" ", $shoppingCart['customer']['pstcod']);

                                    $TmpDel = new DelDAO();

                                    $delivery = $TmpDel->select(NULL, 'PC-' . $pstcodArr[0], $deliveryWeight, false);

                                    if (count($delivery) > 0) {

                                        //echo count($delivery).'~~~';

                                    } else {
                                        $delivery = $TmpDel->select(NULL, $shoppingCart['customer']['coucod'], $deliveryWeight, false);
                                    }

                                    if (count($delivery) > 0) {

                                        //
                                        // check change of country
                                        //

                                        $findDelivery = false;

                                        if (isset($shoppingCart['del_id'])) {
                                            for ($i = 0; $i < count($delivery); ++$i) {
                                                if ($shoppingCart['del_id'] == $delivery[$i]['del_id']) $findDelivery = true;
                                            }
                                        }

                                        if (!isset($shoppingCart['del_id']) || empty($shoppingCart['del_id']) || !$findDelivery ) {

                                            $shoppingCart['del_id'] = $delivery[0]['del_id'];
                                            $shoppingCart['delnam'] = $delivery[0]['delnam'];
                                            $shoppingCart['delpri'] = $delivery[0]['delpri'];
                                            $shoppingCart['delcod'] = $delivery[0]['delcod'];

                                            $_SESSION['cart'] = json_encode($shoppingCart);

                                        }

                                        ?>

                                        <div class="row">
                                            <div class="col-md-5"><label>Delivery Options</label></div>
                                            <div class="col-md-7">

                                                <form id="deliveryForm" class="form-vertical"
                                                      action="pages/shoppingcart/shoppingcart_control.php">
                                                    <input type="hidden" name="action" value="delivery">
                                                    <input type="hidden" name="rtnurl" value="<?php echo $_GET['seourl']; ?>">

                                                    <div class="control-group form-group" style="margin-bottom: 0;">
                                                        <div class="controls">

                                                            <select name="del_id" class="form-control">
                                                                <?php
                                                                $tableLength = count($delivery);
                                                                for ($i = 0; $i < $tableLength; ++$i) {
                                                                    ?>
                                                                    <option
                                                                        value="<?php echo $delivery[$i]['del_id']; ?>" <?php if (isset($shoppingCart['del_id']) && $shoppingCart['del_id'] == $delivery[$i]['del_id']) echo 'selected'; ?>><?php echo $delivery[$i]['delnam'] . ' (' . number_format($delivery[$i]['delpri'],2) . ')'; ?></option>
                                                                <?php } ?>
                                                            </select>

                                                        </div>
                                                    </div>

                                                    <script>
                                                        $(function () {
                                                            $('[name="del_id"]', $('#deliveryForm')).change(function () {

                                                                $('#deliveryForm').submit();

                                                            });
                                                        });
                                                    </script>

                                                </form>

                                            </div>

                                        </div>

                                        <hr>
                                        <?php
                                    } else {
                                        ?>

                                        <div class="alert alert-danger" style="margin-top: 20px;">

                                            <p>We have not found a delivery record, please call us to discuss the
                                                delivery of your
                                                products</p>

                                        </div>

                                        <?php
                                    }
                                    ?>


                                </div>


                            </div>

                        </div>

                    <?php } ?>


                    <div class="box total">

                        <h3 style="margin: 0;">DELIVERY
                            <small>(&pound;)</small>
                            <span id="basketPrice"><?php echo $shoppingCart['delpri']; ?></span></h3>

                    </div>

                    <div class="box total">

                        <?php
                        $totalPrice = repricebasket($shoppingCart) + $shoppingCart['delpri'];
                        ?>

                        <h3 style="margin: 0;">TOTAL
                            <small>(&pound;)</small>
                            <span id="basketPrice"><?php echo number_format( $totalPrice , 2); ?></span></h3>

                    </div>


                    <div class="box text-right">

                        <?php
                        if (
                            isset($shoppingCart['customer']) &&
                            is_array($shoppingCart['customer']) &&
                            isset($shoppingCart['items']) &&
                            is_array($shoppingCart['items'])
                        ) {

                            ?>



                            <form action="https://test.secure-server-hosting.com/secutran/secuitems.php" method="post">
                                <input type="hidden" name="filename" value="sh209683/payment.html"/>
                                <input type="hidden" name="transactionamount" value="<?php echo $totalPrice; ?>"/>
                                <input type="hidden" name="transactioncurrency" value="GBP"/>
                                <input type="hidden" name="shreference" value="SH209683"/>
                                <input type="hidden" name="checkcode" value="580601"/>

                                <input type="hidden" name="products_price" value="<?php echo withoutVAT($totalPrice); ?>"/>
                                <input type="hidden" name="shippingcharge" value="<?php echo $shoppingCart['delpri']; ?>"/>
                                <input type="hidden" name="transactiontax" value="<?php echo calcVAT($totalPrice, $basketRec->vatrat); ?>"/>

                                <input type="hidden" name="orderref" value="<?php echo $Ord_ID; ?>"/>

                                <?php
                                $productString = '';
                                if (isset($shoppingCart['items']) && is_array($shoppingCart['items'])) {
                                    for ($b = 0; $b < count($shoppingCart['items']); $b++) {
                                        $productString .= '['.$shoppingCart['items'][$b]['bsk_id'].'|'.$shoppingCart['items'][$b]['bsk_id'].'|'.$shoppingCart['items'][$b]['bskttl'].'|'.$shoppingCart['items'][$b]['unipri'].'|'.$shoppingCart['items'][$b]['qty'].'|'.number_format($shoppingCart['items'][$b]['unipri'] * $shoppingCart['items'][$b]['qty'],2).']';
                                    }
                                }
                                ?>

                                <input type="hidden" name="secuitems" value="<?php echo $productString; ?>"/>

                                <input type="hidden" name="cardholdersname" value="<?php echo $shoppingCart['customer']['cusfna'] . ' ' . $shoppingCart['customer']['cussna']; ?>">
                                <input type="hidden" name="cardholdersemail" value="<?php echo $shoppingCart['customer']['cusema']; ?>">

                                <input type="hidden" name="cardholderaddr1" value="<?php echo $shoppingCart['customer']['payadr1']; ?>">
                                <input type="hidden" name="cardholderaddr2" value="<?php echo $shoppingCart['customer']['payadr2']; ?>">
                                <input type="hidden" name="cardholdercity" value="<?php echo $shoppingCart['customer']['payadr3']; ?>">
                                <input type="hidden" name="cardholderstate" value="<?php echo $shoppingCart['customer']['payadr4']; ?>">
                                <input type="hidden" name="cardholderpostcode" value="<?php echo $shoppingCart['customer']['paypstcod']; ?>">

                                <input type="hidden" name="cardholdertelephonenumber" value="<?php echo $shoppingCart['customer']['custel']; ?>">

                                <input type="hidden" name="deliveryname" value="<?php echo $shoppingCart['customer']['cusfna'] . ' ' . $shoppingCart['customer']['cussna']; ?>">
                                <input type="hidden" name="deliveryAddr1" value="<?php echo $shoppingCart['customer']['adr1']; ?>">
                                <input type="hidden" name="deliveryCity" value="<?php echo $shoppingCart['customer']['adr2']; ?>">
                                <input type="hidden" name="ship_city" value="<?php echo $shoppingCart['customer']['adr3']; ?>">
                                <input type="hidden" name="deliveryState" value="<?php echo $shoppingCart['customer']['adr4']; ?>">
                                <input type="hidden" name="deliveryPostcode" value="<?php echo $shoppingCart['customer']['pstcod']; ?>">

                                <input type="submit" value="Proceed to Payment" class="updatebtn"/>

                            </form>


                        <?php } ?>

                    </div>


                </div>


            </div>

        </div>

    </div>

</div>

<div class="section">

    <div class="container">

        <div class="row">

            <div class="col-sm-12">


                <div class="steps">


                    <ul>

                        <li><a><strong>STEP 1</strong> CUSTOMISATION</a></li>

                        <li><a><strong>STEP 2</strong> PERSONALISE</a></li>

                        <li><a class="active"><strong>STEP 3</strong> SUMMARY</a></li>

                        <li><a><strong>STEP 4</strong> CHECKOUT</a></li>

                    </ul>


                </div>


            </div>

        </div>

    </div>

</div>