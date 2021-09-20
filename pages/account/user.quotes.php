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
$objects = $TmpDao->select(NULL, 'QUOTE', $loggedIn->pla_id, false);

?>

<div class="section">
    <div class="container contentbox">
        <div class="row">
            <div class="col-sm-2">&nbsp;</div>
            <div class="col-sm-8">

                <h1 class="heading">My Quotes</h1>


                <table class="table table-bordered table-striped table-highlight" id="employeeTable">
                    <thead>
                    <tr>
                        <th width="150">Date</th>
                        <th>Object</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $tableLength = count($objects);
                    for ($i=0;$i<$tableLength;++$i) {

                        $object = json_decode($objects[$i]['tmpobj']);


                        $totalPrice = 0;
                        for ($b=0;$b<count($object->items); $b++) {
                            $totalPrice += ($object->items[$b]->qty * $object->items[$b]->unipri);
                        }

                        ?>
                        <tr>
                            <td><small><?php echo date("jS M Y H:i", strtotime($objects[$i]['credat'])); ?></small></td>
                            <td style="text-align: right;"><a href="tempobjects/object-edit.php?tmp_id=<?php echo $objects[$i]['tmp_id'] ?>" class="showObject">&pound;<?php echo number_format($totalPrice,2); ?></a></td>
                        </tr>
                        <tr style="">
                            <td colspan="2">
                                <div class="objectdetail">

                                    <?php

                                    $totalPrice = 0;

                                    for ($b=0;$b<count($object->items); $b++) {

                                        $totalPrice += ($object->items[$b]->qty * $object->items[$b]->unipri);

                                        ?>

                                        <div class="row">
                                            <div class="col-sm-6"><?php echo $object->items[$b]->prdnam; ?></div>
                                            <div class="col-sm-2 text-right"><?php echo $object->items[$b]->qty; ?></div>
                                            <div class="col-sm-2 text-right">&pound;<?php echo $object->items[$b]->unipri; ?></div>
                                            <div class="col-sm-2 text-right">&pound;<?php echo number_format($object->items[$b]->qty * $object->items[$b]->unipri,2); ?></div>
                                        </div>

                                        <?php
                                    }
                                    ?>

                                    <hr>

                                    <div class="row">
                                        <div class="col-sm-9">&nbsp;</div>
                                        <div class="col-sm-1 text-right"><strong>Total</strong></div>
                                        <div class="col-sm-2 text-right">&pound;<?php echo number_format($totalPrice,2); ?></div>
                                    </div>


                                    <?php
                                    if (isset($object->customer)) {
                                        ?>

                                        <h5><?php echo $object->customer->cusfna.' '.$object->customer->cussna; ?></h5>
                                        <p>
                                            <?php echo $object->customer->adr1; ?><br>
                                            <?php echo $object->customer->adr2; ?><br>
                                            <?php echo $object->customer->adr3; ?><br>
                                            <?php echo $object->customer->adr4; ?><br>
                                            <?php echo $object->customer->pstcod; ?><br>
                                            <?php echo $object->customer->coucod; ?><br>
                                        </p>
                                        <h5>Contact</h5>
                                        <p>
                                            Tel: <?php echo $object->customer->custel; ?><br>
                                            Mob: <?php echo $object->customer->cusmob; ?><br>
                                            Email: <?php echo $object->customer->cusema; ?>
                                        </p>

                                        <?php
                                    }
                                    ?>

                                    <a href="tempobjects/convert_object_to_order.php?tmp_id=<?php echo $objects[$i]['tmp_id']; ?>" class="btn btn-primary">Convert to order</a>



                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>