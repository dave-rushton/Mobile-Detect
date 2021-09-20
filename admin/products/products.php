<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("classes/products.cls.php");


$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

//$TmpPrd = new PrdDAO();
//$products = $TmpPrd->select(NULL, NULL, NULL, NULL, NULL, NULL, 'prdnam', false, NULL, NULL);

?>
<!doctype html>
<html>
<head>
    <title>Products</title>
    <?php include('../webparts/headdata.php'); ?>

    <link rel="stylesheet" href="css/plugins/datatable/TableTools.css">

    <script src="js/plugins/datatable/jquery.dataTables.min.js"></script>
    <script src="js/plugins/datatable/TableTools.min.js"></script>
    <script src="js/plugins/datatable/ColReorderWithResize.js"></script>
    <script src="js/plugins/datatable/ColVis.min.js"></script>
    <script src="js/plugins/datatable/jquery.dataTables.columnFilter.js"></script>
    <script src="js/plugins/datatable/jquery.dataTables.grouping.js"></script>

    <script src="js/plugins/garlic/garlic.min.js"></script>

    <script>

        $(function(){

            var oTable = $('#productTable').dataTable({
                "bServerSide": true,
                "sServerMethod": "GET",
                "sAjaxSource": "products/products_table.php",
                "sAjaxDataProp": "aaData",
                "iDisplayLength": 9999,
                "aoColumnDefs": [
                    { "bVisible": true, "aTargets": [ 0 ] },
                    { "bVisible": false, "aTargets": [ 1 ] },
                    { "bVisible": true, "aTargets": [ 2 ] },
                    { "bVisible": true, "aTargets": [ 3 ] }
                ],
                "fnRowCallback": function( nRow, aData, iDisplayIndex ) {

                    $('td:eq(1)', nRow).html('<a href="products/product-edit.php?prd_id=' + aData[1] + '">' + aData[2] + '</a>');
                    //$('td:eq(2)', nRow).html('<a href="products/productgroup-edit.php?atr_id=' + aData[3] + '">' + aData[4] + '</a>');

                    return nRow;
                }
            });

            $( 'input', $('#productTable_wrapper') ).garlic();
            $( 'input', $('#productTable_wrapper') ).keyup();

        });

    </script>

</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
    <?php include('../webparts/website-left.php'); ?>
    <div id="main">
        <div class="container-fluid">
            <div class="page-header">
                <div class="pull-left">
                    <h1>Products</h1>
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
                        <a href="products/dashboard.php">Products Dashboard</a>
                        <i class="icon-angle-right"></i>
                    </li>
                    <li>
                        <a href="products/products.php">Products</a>
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
                                    <th width="160">Image</th>
                                    <th>Product ID</th>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>In Stock</th>
                                </tr>
                                </thead>

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
