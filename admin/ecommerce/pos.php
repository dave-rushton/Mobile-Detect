<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("../ecommerce/classes/order.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$currConv = 1;
$dispCurr = '&pound;';

require_once("../products/classes/product_types.cls.php");
require_once("../products/classes/products.cls.php");
require_once("../../pages/shoppingcart/classes/shoppingcart.cls.php");
$shoppingcart = new shoppingCart();

$TmpPrd = new PrdDAO();


?>
<!doctype html>
<html>
<head>
    <title>POS</title>
    <?php include('../webparts/headdata.php'); ?>
    <script src="ecommerce/js/pos.js"></script>
</head>
<?php //include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid nav-hidden" id="content">
    <?php //include('../webparts/ecommerce-left.php'); ?>
    <div id="main">
        <div class="container-fluid">
            <div class="page-header">
                <div class="pull-left">
                    <h1>Welcome to <?php echo $patchworks->customerName; ?></h1>
                </div>
            </div>
            <div class="breadcrumbs hide">
                <ul>
                    <li>
                        <a>Dashboard</a>
                        <i class="icon-angle-right"></i>
                    </li>
                    <li>
                        <a>eCommerce</a>
                        <i class="icon-angle-right"></i>
                    </li>
                    <li>
                        <a>POS</a>
                    </li>
                </ul>
            </div>
            <div class="row-fluid">


                <div class="span9">

                    <div class="box box-color box-bordered">
                        <div class="box-title">
                            <h3>
                                <i class="icon-shopping-cart"></i> Products</h3>

                            <div class="actions">

                            </div>
                        </div>
                        <div class="box-content nopadding">

                            <form action="#" id="searchForm" class="form-horizontal form-bordered">

                                <div class="control-group">
                                    <label class="control-label">Product Search
                                        <small></small>
                                    </label>

                                    <div class="controls">
                                        <input type="text" class="input-large" name="srcnam" value=""
                                               autocomplete="off">
                                    </div>
                                </div>

                            </form>

                        </div>
                        <div class="box-content nopadding">

                            <div id="basketTableWrapper">
                                <table class="table table-condensed" id="basketTable">
                                    <thead>
                                    <tr>
                                        <th style="width: 30px;"></th>
                                        <th>Product</th>
                                        <th style="text-align: right; width: 120px;">Price</th>
                                        <th style="text-align: right; width: 120px;">Units</th>
                                        <th style="text-align: right; width: 120px;">Total</th>
                                    </tr>
                                    </thead>
                                    <tbody id="basketBody">

                                    </tbody>
                                </table>

                            </div>

                            <div id="printWrapper" style="text-align: center; display: none; padding: 0px; ">

                                <p style="font-size: 12px;"><img src="<?php echo $patchworks->webRoot; ?>/pages/img/print_logo.jpg" alt="">
                                    <br>
                                    <br>
                                    Wellingborough Trains<br><small>26 Market Street<br>
                                        Wellingborough<br>
                                        Northamptonshire</small>
                                    <br>
                                    <br>
                                    <span id="printBasket"></span>
                                    <br>
                                    <span id="printPrice" style="bold;">&pound;0.00></span>
                                    <br>
                                    <br>
                                    <small>please call again or visit us online
                                        <br>
                                        www.wellingboroughtrains.co.uk</small>
                                </p>

                            </div>

                        </div>
                    </div>

                </div>

                <div class="span3">
                    <div class="box box-color box-bordered">
                        <div class="box-title">
                            <h3>
                                <i class="icon-plus"></i> Quick Add</h3>

                            <div class="actions">

                            </div>
                        </div>
                        <div class="box-content nopadding">

                            <form action="#" id="addForm" class="form-horizontal form-bordered">

                                <div class="control-group">
                                    <label class="control-label">Product Name
                                        <small></small>
                                    </label>

                                    <div class="controls">
                                        <input type="text" class="input-large" name="linedescription"
                                               value="Shop Product" autocomplete="off">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">Product Price
                                        <small></small>
                                    </label>

                                    <div class="controls">
                                        <input type="number" step="any" class="input-large" name="lineamount"
                                               value="0.00" autocomplete="off">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">Units
                                        <small></small>
                                    </label>

                                    <div class="controls">
                                        <input type="number" step="any" class="input-large" name="quantity" value="1"
                                               autocomplete="off">
                                    </div>
                                </div>

                                <div class="form-actions" style="padding-left: 20px;">
                                    <button type="submit" class="btn btn-primary"><i class="icon-save"></i> Add</button>
                                </div>

                            </form>

                        </div>
                    </div>

                    <div class="box box-color box-bordered green">
                        <div class="box-title">
                            <h3>
                                <i class="icon-money"></i> Tendered</h3>

                            <div class="actions">

                            </div>
                        </div>
                        <div class="box-content nopadding">

                            <form action="#" id="tenderForm" class="form-horizontal form-bordered">

                                <div class="control-group">
                                    <label class="control-label">Units
                                        <small></small>
                                    </label>

                                    <div class="controls">
                                        <input type="number" step="any" class="input-large" name="taken" value="0.00"
                                               autocomplete="off">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Change
                                        <small></small>
                                    </label>

                                    <div class="controls">
                                        <input type="number" class="input-large" name="change" value="0.00" disabled
                                               autocomplete="off">
                                    </div>
                                </div>

                            </form>

                        </div>
                    </div>


                    <div class="box box-color box-bordered blue">
                        <div class="box-title">
                            <h3>
                                <i class="icon-check"></i> POS Actions</h3>

                            <div class="actions">

                            </div>
                        </div>
                        <div class="box-content nopadding">

                            <form action="#" class="form-horizontal form-bordered">

                                <div class="form-actions" style="margin-top: 0; padding-left: 20px;">
                                    <button type="submit" class="btn btn-primary" id="buyButton"><i
                                            class="icon-check"></i> Confirm Sale
                                    </button>
                                    <button type="submit" class="btn btn-primary" id="printButton"><i
                                            class="icon-print"></i></button>
                                </div>

                            </form>

                        </div>
                    </div>


                </div>

            </div>

        </div>
    </div>
</div>
</body>
</html>
