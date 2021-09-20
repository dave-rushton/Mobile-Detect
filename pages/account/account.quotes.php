<?php

require_once("../config/config.php");
require_once("../admin/patchworks.php");
require_once("../admin/system/classes/places.cls.php");
require_once("../admin/system/classes/tempobject.cls.php");

$PwdTok = (isset($_SESSION['loginToken'])) ? $_SESSION['loginToken'] : '';
$PlaDao = new PlaDAO();
$loggedIn = $PlaDao->loggedIn($PwdTok);

if (!$loggedIn) {
    header('location: login');
    exit();
}

$TmpDao = new TmpDAO();
$tempObjects = $TmpDao->select(NULL, 'QUOTE', $loggedIn->pla_id, false);

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

                <h2 class="heading">Your Quotes</h2>

                <div class="box">

                <ul class="orderlist">

                <?php

                $tableLength = count($tempObjects);
                for ($i=0;$i<$tableLength;++$i) {

                    ?>

                    <li>
                        <h3><a class="orderlink" href="baskets/retrievequote?tmp_id=<?php echo $tempObjects[$i]['tmp_id']; ?>"><?php echo str_pad($tempObjects[$i]['tmp_id'], 8, "0", STR_PAD_LEFT); ?></a></h3>
                        <span><?php echo date("jS M Y", strtotime($tempObjects[$i]['credat'])); ?></span>

                        <a href="#" class="removequote btn btn-mini"><i class="fa fa-trash"></i></a>
<!--                        <pre> --><?php //if (isset($tempObjects)) print_r(json_decode($tempObjects[$i]['tmpobj'])); ?><!--</pre>-->

                        <div class="orderdetail">

                            <?php

                            $object = json_decode($tempObjects[$i]['tmpobj']);

                            $totalPrice = 0;

                            for ($b=0;$b<count($object->items); $b++) {

                                ?>

                                <h4 style="margin: 20px 0 30px;"><?php echo $object->items[$b]->bskttl; ?></h4>

                                <?php


                                for ($p=0;$p<count($object->items[$b]->products); $p++) {

                                    //print_r($object->items[$b]);

                                    ?>

                                    <div class="row" style="margin-bottom: 10px;">
                                        <div class="col-sm-6"><?php echo $object->items[$b]->products[$p]->prdnam; ?></div>
                                        <div class="col-sm-2 text-right"><?php echo $object->items[$b]->qty; ?></div>
                                        <div class="col-sm-2 text-right"><?php echo $object->items[$b]->products[$p]->unipri; ?></div>
                                        <div class="col-sm-2 text-right"><?php echo number_format($object->items[$b]->qty * $object->items[$b]->products[$p]->unipri,2); ?></div>
                                    </div>

                                    <?php

                                }

                                $totalPrice += ($object->items[$b]->qty * $object->items[$b]->unipri);

                                ?>


                                <?php
                            }
                            ?>

                            <div class="row">
                                <div class="col-sm-8">&nbsp;</div>
                                <div class="col-sm-2 text-right"><strong>Total</strong></div>
                                <div class="col-sm-2 text-right"><?php echo $totalPrice; ?></div>
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

<script>

    $(function(){

        $('.orderlist').find('a.orderlink').click(function(e){
            if (!confirm('Retrieve this quote and recreate basket?')) {
                return false;
            }
        });

        $('.orderlist').find('a.removequote').click(function(e){
            if (!confirm('Delete this quote?')) {
                return false;
            }
        });

    })

</script>