<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("../products/classes/products.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpPrd = new PrdDAO();
$products = $TmpPrd->select(NULL, NULL, NULL, NULL, NULL, NULL, NULL, false, NULL, NULL);


?>
<!doctype html>
<html>
<head>
    <title>Product Report</title>
    <?php include('../webparts/headdata.php'); ?>

    <script src="js/plugins/datatable/jquery.dataTables.min.js"></script>
    <script src="js/plugins/datatable/TableTools.min.js"></script>
    <script src="js/plugins/datatable/ColReorder.min.js"></script>
    <script src="js/plugins/datatable/ColVis.min.js"></script>
    <script src="js/plugins/datatable/jquery.dataTables.columnFilter.js"></script>

</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
    <?php include('../webparts/website-left.php'); ?>
    <div id="main">
        <div class="container-fluid">
            <div class="page-header">
                <div class="pull-left">
                    <h1>Product Report (No Price)</h1>
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
                </ul>
            </div>
            <div class="row-fluid">
                <div class="span12">
                    <div class="box box-color box-bordered">
                        <div class="box-title">
                            <h3>
                                <i class="icon-shopping-cart"></i> Baskets</h3>
                            <div class="actions">
                                <a href="custom/baskets-edit.php" class="btn btn-mini" rel="tooltip" title="New Basket"><i class="icon-plus"></i></a>
                            </div>
                        </div>
                        <div class="box-content nopadding">
                            <table class="table table-bordered table-striped table-highlight" id="basketTable">
                                <thead>
                                <tr>
                                    <th>Basket Name</th>
                                    <th>Price</th>
                                </tr>
                                </thead>
                                <tbody id="basketBody">

                                <?php


                                $tableLength = count($products);
                                for ($i=0;$i<$tableLength;++$i) {

                                    if ($products[$i]['unipri'] > 0) continue;

                                    ?>

                                    <tr>
                                        <td>
                                            <a href="products/product-edit.php?prd_id=<?php echo $products[$i]['prd_id']; ?>"><?php echo $products[$i]['prdnam']; ?></a>
                                        </td>
                                        <td>
                                            <?php echo $products[$i]['unipri']; ?>
                                        </td>
                                    </tr>

                                    <?php
                                }
                                ?>

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
