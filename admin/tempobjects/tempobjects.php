<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("../system/classes/tempobject.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpObj = new TmpDAO();
$objects = $TmpObj->select(NULL, NULL, NULL, false);

?>
<!doctype html>
<html>
<head>
    <title>Objects</title>
    <?php include('../webparts/headdata.php'); ?>
    <!-- dataTables -->
    <script src="js/plugins/datatable/jquery.dataTables.min.js"></script>
    <script src="js/plugins/datatable/TableTools.min.js"></script>
    <script src="js/plugins/datatable/ColReorder.min.js"></script>
    <script src="js/plugins/datatable/ColVis.min.js"></script>
    <script src="js/plugins/datatable/jquery.dataTables.columnFilter.js"></script>

    <style>

        .objectdetail {
        }

    </style>

    <script>

        $(function(){

            $('.showObject').click(function(e){
                e.preventDefault();
                $(this).parent().parent().next().slideToggle(5);
            })

        })

    </script>

</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-orange">
<div class="container-fluid" id="content">
    <?php include('../webparts/website-left.php'); ?>
    <div id="main">
        <div class="container-fluid">
            <div class="page-header">
                <div class="pull-left">
                    <h1>Objects</h1>
                </div>
                <div class="pull-right">
                    <?php include('../webparts/index-info.php'); ?>
                </div>
            </div>
            <div class="breadcrumbs">
                <ul>
                    <li>
                        <a href="index.php">Dashboard</a>
                        <i class="icon-angle-right"></i>
                    </li>
                    <li>
                        <a href="tempobjects/tempobjects.php">Objects</a>
                    </li>
                </ul>
            </div>
            <div class="row-fluid">
                <div class="span12">
                    <div class="box box-color box-bordered">
                        <div class="box-title">
                            <h3>
                                <i class="icon-group"></i> Objects</h3>
                        </div>
                        <div class="box-content nopadding">

                            <table class="table table-bordered table-striped table-highlight" id="employeeTable">
                                <thead>
                                <tr>
                                    <th width="50">#</th>
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

                                    //var_dump($object);

                                    ?>
                                    <tr>
                                        <td><?php echo $objects[$i]['tmp_id']; ?></td>
                                        <td><?php echo date("jS M Y H:i", strtotime($objects[$i]['credat'])); ?></td>
                                        <td><a href="tempobjects/object-edit.php?tmp_id=<?php echo $objects[$i]['tmp_id'] ?>" class="showObject"><?php echo $totalPrice; ?></a></td>
                                    </tr>
                                    <tr style="display: none">
                                        <td colspan="3">
                                            <div class="objectdetail">

                                                <h5>Basket</h5>

                                                <?php

                                                $totalPrice = 0;

                                                for ($b=0;$b<count($object->items); $b++) {

                                                    $totalPrice += ($object->items[$b]->qty * $object->items[$b]->unipri);

                                                    ?>

                                                    <div class="row-fluid">
                                                        <div class="span3"><?php echo $object->items[$b]->prdnam; ?></div>
                                                        <div class="span1 text-right"><?php echo $object->items[$b]->qty; ?></div>
                                                        <div class="span1 text-right"><?php echo $object->items[$b]->unipri; ?></div>
                                                        <div class="span2 text-right"><?php echo number_format($object->items[$b]->qty * $object->items[$b]->unipri,2); ?></div>
                                                    </div>

                                                    <?php
                                                }
                                                ?>

                                                <div class="row-fluid">
                                                    <div class="span4">&nbsp;</div>
                                                    <div class="span1 text-right"><strong>Total</strong></div>
                                                    <div class="span2 text-right"><?php echo $totalPrice; ?></div>
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

        </div>
    </div>
</div>
</body>
</html>
