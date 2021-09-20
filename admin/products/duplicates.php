<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("classes/products.cls.php");


$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpPrd = new PrdDAO();
$products = $TmpPrd->duplicatePrt();

?>
<!doctype html>
<html>
<head>
    <title>Duplicates</title>
    <?php include('../webparts/headdata.php'); ?>
</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
    <?php include('../webparts/website-left.php'); ?>
    <div id="main">
        <div class="container-fluid">
            <div class="page-header">
                <div class="pull-left">
                    <h1>Duplicates</h1>
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
                        <a>Products</a>
                        <i class="icon-angle-right"></i>
                    </li>
                    <li>
                        <a href="products/duplicates.php">Duplicates</a>
                    </li>
                </ul>
            </div>
            <div class="row-fluid">
                <div class="span12">
                    <div class="box box-color box-bordered">
                        <div class="box-title">
                            <h3>
                                <i class="icon-shopping-cart"></i> Products</h3>
                            <div class="actions">
                                <a href="products/product-edit.php" class="btn btn-mini" rel="tooltip" title="New Product"><i class="icon-plus"></i></a>
                            </div>
                        </div>
                        <div class="box-content nopadding">

                            <table class="table table-bordered table-striped table-highlight" id="productTable">
                                <thead>
                                <tr>
                                    <th>Product ID</th>
                                    <th>Product</th>
                                    <th>Altref</th>
                                    <th>Price</th>
                                    <th>In Stock</th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php
                                for ($i=0;$i<count($products);$i++) {
                                    ?>
                                    <tr>
                                        <td><?php echo $products[$i]['prt_id']; ?></td>
                                        <td>
                                            <a href="products/producttype-edit.php?prt_id=<?php echo $products[$i]['prt_id']; ?>" target="_blank"><?php echo $products[$i]['prtnam']; ?></a>
                                        </td>
                                        <td><?php echo $products[$i]['seourl']; ?></td>
                                        <td><?php echo $products[$i]['unipri']; ?></td>
                                        <td><?php //echo $products[$i]['in_stk']; ?></td>
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
