<?php

require_once("../config/config.php");
require_once("../admin/patchworks.php");
require_once("../admin/system/classes/places.cls.php");
require_once("../admin/ecommerce/classes/order.cls.php");
require_once("../admin/ecommerce/classes/orderline.cls.php");

$PwdTok = (isset($_SESSION['loginToken'])) ? $_SESSION['loginToken'] : '';
$PlaDao = new PlaDAO();
$loggedIn = $PlaDao->loggedIn($PwdTok);

if (!$loggedIn) {
    header('location: login');
    exit();
}

$TmpOrd = new OrdDAO();
$orders = $TmpOrd->select(NULL, 'CUS', $loggedIn->pla_id, NULL, false);

$TmpOln = new OlnDAO();

?>


<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-3">

                <div class="box">

                    <?php include('account.menu.php'); ?>

                </div>

            </div>
            <div class="col-sm-9">

                <h2 class="heading">Your Orders</h2>

                <hr>

                <div class="box">

                    <div class="orderlist">

                        <ul>

                            <?php

                            $tableLength = count($orders);
                            for ($i = 0; $i < $tableLength; ++$i) {
                                ?>


                                <li>
                                    <h3>
                                        <?php echo str_pad($orders[$i]['ord_id'], 8, "0", STR_PAD_LEFT); ?>
                                    </h3>


                                    <h5><?php echo date("jS M Y", strtotime($orders[$i]['invdat'])); ?></h5>

                                    <div class="orderdetail">

                                        <?php
                                        $orderLines = $TmpOln->select($orders[$i]['ord_id'], NULL, false);

                                        $totalOrderPrice = 0;

                                        for ($l = 0; $l < count($orderLines); $l++) {

                                            $linePrice = $orderLines[$l]['unipri'] * $orderLines[$l]['numuni'];
                                            $totalOrderPrice += $linePrice;

                                            // parse products
                                            ?>

                                            <div class="row orderLineRow">
                                                <div class="col-sm-10">
                                                    <h4><?php echo $orderLines[$l]['olndsc'] ?><?php echo ($orderLines[$l]['numuni'] > 1) ? ' x ' . number_format($orderLines[$l]['numuni'], 0) : ''; ?></h4>
                                                </div>
                                                <div class="col-sm-2 text-right">

                                                    <?php echo '&pound;' . number_format($linePrice, 2); ?>

                                                </div>
                                            </div>

                                            <?php

                                            $products = json_decode($orderLines[$l]['olndsc'], true);
                                            if (isset($products['products']) && is_array($products['products'])) {
                                                $products = $products['products'];
                                            }

                                            if (is_array($products)) {

                                                for ($p = 0; $p < count($products); $p++) {

                                                    ?>

                                                    <div class="row" style="margin-bottom: 10px;">
                                                        <div
                                                            class="col-sm-10"> <?php echo $products[$p]['prdnam']; ?><?php //var_dump($products[$p]); ?></div>
                                                        <!--                                        <div class="col-sm-2 text-right"> </div>-->
                                                        <!--                                        <div class="col-sm-2 text-right">9.99</div>-->
                                                        <!--                                        <div class="col-sm-2 text-right">9.99</div>-->
                                                    </div>

                                                    <?php

                                                }
                                            }

                                        }
                                        ?>

                                        <hr>

                                        <div class="row">
                                            <div class="col-sm-8">&nbsp;</div>
                                            <div class="col-sm-2 text-right"><strong>Total</strong></div>
                                            <div
                                                class="col-sm-2 text-right"><strong><?php echo '&pound;' . number_format($totalOrderPrice, 2); ?></strong></div>
                                        </div>


                                    </div>

                                </li>

                                <?php
                            }
                            ?>

                        </ul>

                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

<script>

    $(function () {

        $('.orderlist').find('a.showorder').click(function (e) {

            e.preventDefault();
            $(this).find('i').toggleClass('fa-chevron-down').toggleClass('fa-chevron-up');
            $(this).next().slideToggle(400);

        });

    })

</script>