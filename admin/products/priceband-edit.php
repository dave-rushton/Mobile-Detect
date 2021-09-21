<?php 

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("../system/classes/places.cls.php");


$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpPla = new PlaDAO();
$customers = $TmpPla->select(NULL, 'CUS', NULL, NULL);

?>
<!doctype html>
<html>
<head>
<title>Price Band</title>
<?php include('../webparts/headdata.php'); ?>


    <link rel="stylesheet" href="css/plugins/datatable/TableTools.css">

    <style>

        #variantBox .slimScrollDiv {
            border-bottom: none;
            padding-bottom: 3px;
        }

    </style>

    <script src="js/plugins/datatable/jquery.dataTables.min.js"></script>
    <script src="js/plugins/datatable/TableTools.min.js"></script>
    <script src="js/plugins/datatable/ColReorderWithResize.js"></script>
    <script src="js/plugins/datatable/ColVis.min.js"></script>
    <script src="js/plugins/datatable/jquery.dataTables.columnFilter.js"></script>
    <script src="js/plugins/datatable/jquery.dataTables.grouping.js"></script>

    <script src="js/plugins/garlic/garlic.min.js"></script>

    <script src="products/js/priceband-edit.js"></script>

</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
	<?php include('../webparts/website-left.php'); ?>
	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>Price Band</h1>
				</div>
			</div>
			<div class="breadcrumbs">
				<ul>
					<li>
						<a href="index.php">Dashboard</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a href="">Products Dashboard</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a href="products/pricebands.php">Price Bands</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a>Edit Price Band</a>
					</li>
				</ul>
			</div>

            <div class="row-fluid">

                <div class="span6">

                    <div class="box box-color box-bordered">
                        <div class="box-title">
                            <h3>
                                <i class="icon-user"></i> Customer</h3>
                            <div class="actions">
                            </div>
                        </div>
                        <div class="box-content nopadding">
                            <form id="customerSelectForm" class="form-horizontal form-bordered">


                                <div class="control-group">
                                    <label class="control-label">Customer<small>select for customer prices</small></label>
                                    <div class="controls">
                                        <select class="input-block-level chosen-select" name="pla_id">

                                            <option value="">No Customer Selected</option>
                                            <?php
                                            $tableLength = count($customers);
                                            for ($i=0;$i<$tableLength;++$i) {
                                                ?>
                                                <option value="<?php echo $customers[$i]['pla_id']; ?>"><?php echo $customers[$i]['comnam']; ?></option>
                                            <?php } ?>

                                        </select>
                                    </div>
                                </div>


                            </form>
                        </div>
                    </div>

                </div>

            </div>

			<div class="row-fluid">

                <div class="span4">

                    <div class="box box-color box-bordered">
                        <div class="box-title">
                            <h3>
                                <i class="icon-sitemap"></i> Products</h3>
                            <div class="actions">
                            </div>
                        </div>
                        <div class="box-content nopadding">

                            <table class="table table-striped" id="productTypeTable" style="border-bottom: none;">
                                <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Product Name</th>
                                    <th></th>
                                    <th width="50">Price</th>
                                    <th>Ref.</th>
                                </tr>
                                </thead>
                                <tbody id="productTypeBody">

                                </tbody>
                            </table>

                        </div>
                    </div>

                </div>


                <div class="span3" style="display: none">

                    <div class="box box-color box-bordered" id="variantBox">
                        <div class="box-title">
                            <h3>
                                <i class="icon-tasks"></i> Variants</h3>
                            <div class="actions">
                            </div>
                        </div>
                        <div class="box-content nopadding" id="productWrapper">

                            <table
                                class="table table-bordered table-striped table-highlight table-condensed"
                                id="productTable">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Price</th>
                                </tr>
                                </thead>
                                <tbody id="productBody">


                                </tbody>
                            </table>

                        </div>
                    </div>

                </div>


                <div class="span8">

                    <div class="box box-color box-bordered">
                        <div class="box-title">
                            <h3>
                                <i class="icon-tasks"></i> Band</h3>
                            <div class="actions">

                                <input type="text" id="bndsiz" style="margin-bottom: 0;">

                            </div>
                        </div>
                        <div class="box-content nopadding">

                            <table class="table table-bordered table-striped table-highlight table-condensed" id="priceBandTable">
                                <thead>
                                <tr>
                                    <th>Variant</th>
                                    <th style="width: 80px;">Qty</th>
                                    <th style="width: 80px;">Price</th>
                                    <th style="width: 20px"></th>
                                </tr>
                                </thead>
                                <tbody id="priceBandBody">

                                </tbody>
                            </table>


                            <form action="products/priceband_script.php" id="priceBandForm" class="form-horizontal form-bordered" data-returnurl="products/pricebands.php">

                                <input type="hidden" name="prb_id" id="id" value="0">
                                <input type="hidden" class="input-small" name="cus_id" value="">
                                <input type="hidden" class="input-small" name="prt_id" value="">
                                <input type="hidden" class="input-small" name="begdat" value="">
                                <input type="hidden" class="input-small" name="enddat" value="">
                                <input type="hidden" name="prityp" value="A">
                                <input type="hidden" name="sta_id" value="0">

                                <div class="control-group">
                                    <label class="control-label">Variant</small></label>
                                    <div class="controls">

                                        <select name="prd_id" id="productSelect" class="input-block-level"></select>

                                    </div>
                                </div>


                                <div id="priceBandConfig">

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
