<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("../system/classes/places.cls.php");
require_once("../products/classes/products.cls.php");
require_once("../ecommerce/classes/order.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpPla = new PlaDAO();
$customers = $TmpPla->select(NULL, 'CUS', NULL, NULL);

$TmpPrd = new PrdDAO();
$products = $TmpPrd->select(NULL, NULL, NULL, NULL, false);

$TmpOrd = new OrdDAO();
$orders = $TmpOrd->select();

?>
<!doctype html>
<html>
<head>
<title>Enquiries</title>
<?php include('../webparts/headdata.php'); ?>
<link rel="stylesheet" type="text/css" href="css/plugins/datatable/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="css/plugins/datepicker/datepicker.css">

<!-- dataTables -->
<script src="js/plugins/datatable/jquery.dataTables.min.js"></script>
<script src="js/plugins/datatable/TableTools.min.js"></script>
<script src="js/plugins/datatable/ColReorder.min.js"></script>
<script src="js/plugins/datatable/ColVis.min.js"></script>
<script src="js/plugins/datatable/jquery.dataTables.columnFilter.js"></script>
<script src="js/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="js/system.date.js"></script>
<script src="ecommerce/js/orders.js"></script>
</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid nav-hidden" id="content">
	<?php //include('../webparts/index-left.php'); ?>



    <div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>Enquiry Maintenance</h1>
				</div>
				<div class="pull-right">
					<div id="left">
						<form action="system/searchresults.php" method="GET" class='search-form'>
							<div class="search-pane">
								<input type="text" name="keyword" placeholder="Search here...">
								<button type="submit"><i class="icon-search"></i></button>
							</div>
						</form>
						<div class="subnav">
							<div class="subnav-title">
								<a href="#" class='toggle-subnav'><i class="icon-angle-down"></i><span>Enquiries</span></a>
							</div>
							<ul class="subnav-menu">
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="breadcrumbs">
				<ul>
					<li> <a href="index.php">Dashboard</a> <i class="icon-angle-right"></i> </li>
					<li> <a href="ecommerce/dashboard.php">eCommerce</a> <i class="icon-angle-right"></i> </li>
					<li> <a href="ecommerce/orders.php">Orders</a> </li>
				</ul>
			</div>
			<div class="row-fluid orderScreen" id="orderSearchDiv">
				<div class="span12">

                    <div class="row-fluid">
                        <div class="span12">
                            <div class="box box-color box-bordered blue">
                                <div class="box-title">
                                    <h3>
                                        <i class="icon-filter"></i>
                                        Filter Enquries
                                    </h3>
                                    <div class="actions">
                                        <a href="#" class="btn btn-mini content-slideUp" id="searchDisplayBtn"><i class="icon-angle-down"></i></a>
                                    </div>
                                </div>
                                <div class="box-content nopadding" style="display: none;">

                                    <form class="form-horizontal form-bordered" id="searchForm">

                                        <div class="control-group hide">
                                            <label class="control-label">Checkboxes</label>
                                            <div class="controls">
                                                <label class="checkbox">
                                                    <input type="checkbox" name="tmpsta_id[]" value="0" checked> Active
                                                </label>
                                                <label class="checkbox">
                                                    <input type="checkbox" name="tmpsta_id[]" value="10" checked> Invoiced
                                                </label>
                                                <label class="checkbox">
                                                    <input type="checkbox" name="tmpsta_id[]" value="20" checked> Paid
                                                </label>
                                                <label class="checkbox">
                                                    <input type="checkbox" name="tmpsta_id[]" value="30"> Despatched
                                                </label>
                                                <label class="checkbox">
                                                    <input type="checkbox" name="tmpsta_id[]" value="90"> Cancelled
                                                </label>
                                            </div>
                                        </div>

                                        <input type="hidden" name="sta_id">

                                        <div class="control-group">
                                            <label for="textfield" class="control-label">Order No.</label>
                                            <div class="controls">
                                                <input type="text" name="ord_id" class="input-xlarge">
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label for="textfield" class="control-label">Customer Name</label>
                                            <div class="controls">
                                                <input type="text" name="cusnam" class="input-xlarge">
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label for="textfield" class="control-label">Search Start Date</label>
                                            <div class="controls">
                                                <div class="input-append">
                                                    <input type="text" class="input-block-level" name="begdat" value="">
                                                    <button class="btn" type="button" id="clearSearchStartDateBtn">Clear</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label for="textfield" class="control-label">Search End Date</label>
                                            <div class="controls">
                                                <div class="input-append">
                                                    <input type="text" class="input-block-level" name="enddat" value="">
                                                    <button class="btn" type="button" id="clearSearchEndDateBtn">Clear</button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-actions">
                                            <button type="submit" class="btn btn-primary">Search</button>
                                        </div>

                                    </form>

                                </div>
                            </div>
                        </div>
                        <div class="span6 hide">

                            <div class="box box-color box-bordered blue">
                                <div class="box-title">
                                    <h3>
                                        <i class="icon-random"></i>
                                        Change Status
                                    </h3>
                                    <div class="actions">
                                        <a href="#" class="btn btn-mini content-slideUp" id="statusDisplayBtn"><i class="icon-angle-down"></i></a>
                                    </div>
                                </div>
                                <div class="box-content nopadding" style="display: none;">

                                    <form class="form-horizontal form-bordered" id="changeStatusForm">

                                        <input type="hidden" name="action" value="changestatus">
                                        <input type="hidden" name="ord_id" value="">

                                        <div class="control-group">
                                            <label class="control-label">Change Order Status</label>
                                            <div class="controls">
                                                <select name="sta_id" class="input-block-level">

                                                    <option value="0">Active</option>
                                                    <option value="10">Invoiced</option>
                                                    <option value="20">Paid</option>
                                                    <option value="30" selected>Despatched</option>
                                                    <option value="90">Cancelled</option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <button type="submit" class="btn btn-primary">Update Order Status</button>
                                        </div>

                                    </form>

                                </div>
                            </div>

                        </div>
                    </div>






					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3> <i class="icon-tags"></i> Enquiries</h3>
							<div class="actions">
								<a href="#" class="btn btn-mini" rel="tooltip" title="Refresh" id="refreshTableBtn"><i class="icon-refresh"></i></a>
<!--								<a href="ecommerce/order.export.php" class="btn btn-mini" rel="tooltip" title="Order Export" target="_blank"><i class="icon-download"></i></a>-->
							</div>
						</div>

						<div class="box-content nopadding">

							<table class="table table-nomargin table-striped" id="ordersTable">
								<thead>
                                    <tr>
                                        <th></th>
										<th style="width: 80px">Enquiry No</th>
										<th>Customer</th>
										<th>Enquiry Date</th>
<!--										<th style="text-align: right;">Amount</th>-->
<!--                                        <th style="text-align: right;">Delivery</th>-->
<!--										<th>Status</th>-->
									</tr>
								</thead>
								<tbody id="ordersBody">
								</tbody>
							</table>

						</div>
					</div>

				</div>
			</div>

		</div>
	</div>
</div>
<div id="returnHTML">
</div>
</body>
</html>