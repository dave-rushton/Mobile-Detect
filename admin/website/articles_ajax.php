<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("../system/classes/places.cls.php");
require_once("../ecommerce/classes/products.cls.php");
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
<title>Orders</title>
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
<script src="ecommerce/js/orders_ajax.js"></script>
</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid nav-hidden" id="content">
	<?php //include('../webparts/index-left.php'); ?>
	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>Order Maintenance</h1>
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
								<a href="#" class='toggle-subnav'><i class="icon-angle-down"></i><span>Orders</span></a>
							</div>
							<ul class="subnav-menu">
								<li> <a href="#orderSearchDiv" class="screenSelect">Search Orders</a> </li>
								<li> <a href="#" id="createNewOrderBtn">Create Order</a> </li>
								<li> <a href="#">Invoice Run</a> </li>
								<li> <a href="#">Customer Status</a> </li>
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
				<div class="span10">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3> <i class="icon-tags"></i> Orders</h3>
							<div class="actions">
								<a href="#orderEditDiv" class="btn btn-mini screenSelect" rel="tooltip" title="New Order"><i class="icon-plus-sign"></i></a>
							</div>
						</div>
						<div class="box-content nopadding">
							<table class="table" id="ordersTable">
								<thead>
									<tr>
										<th>Order No</th>
										<th>Customer</th>
										<th>Invoice Date</th>
										<th>Inv Date Sort</th>
										<th>Due Date</th>
										<th>Due Date Sort</th>
										<th style="text-align: right;">Amount</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody id="ordersBody">
								</tbody>
							</table>
						</div>
					</div>
					<div class="box">
						<div class="box-title">
							<h3> <i class="icon-money"></i> Summary</h3>
							<div class="actions">
							</div>
						</div>
						<div class="box-content">
							<dl class="dl-horizontal">
								<dt>Total Orders</dt>
								<dd id="ordersCount"></dd>
								<dt>Total</dt>
								<dd id="ordersTotal"></dd>
							</dl>
						</div>
					</div>
				</div>
				<div class="span2">
					<div class="box">
						<div class="box-title">
							<h3> <i class="icon-filter"></i> Filter</h3>
							<div class="actions">
							</div>
						</div>
						<div class="box-content">
							<form id="orderFilterForm" class="form-vertical orientation-fixed">
								<div class="control-group">
									<label class="control-label">Status</label>
									<div class="controls">
										<input type="hidden" name="sta_id">
										<label class="checkbox">
											<input type="checkbox" name="tmpsta_id" value="0" checked>
											Active </label>
										<label class="checkbox">
											<input type="checkbox" name="tmpsta_id" value="10" checked>
											Invoiced </label>
										<label class="checkbox">
											<input type="checkbox" name="tmpsta_id" value="20">
											Paid </label>
										<label class="checkbox">
											<input type="checkbox" name="tmpsta_id" value="99">
											Cancelled </label>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Customer</label>
									<div class="controls">
										<select class="input-block-level">
											<option>Show All</option>
										</select>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Status</label>
									<div class="controls">
										<select class="input-block-level">
											<option>Show All</option>
										</select>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="row-fluid orderScreen" id="orderEditDiv" style="display: none;">
				<div class="span12">
					<div class="box">
						<div class="box-title">
							<h3> <i class="icon-bookmark-empty"></i> Sales Order </h3>
							<div class="actions">
								<!--<a href="#orderSearchDiv" class="btn btn-mini screenSelect" rel="tooltip" title="Search"><i class="icon-search"></i></a>-->
								
							</div>
						</div>
						<div class="box-content">
							<form action="ecommerce/order_edit_script.php" id="orderForm" class="form-horizontal" novalidate>
								<input type="hidden" name="ord_id" id="id" value="0">
								<input type="hidden" name="ordtyp" value="SALE">
								<input type="hidden" name="tblnam" value="CUS">
								<input type="hidden" name="tbl_id">
								<input type="hidden" name="cusnam">
								<input type="hidden" name="adr1">
								<input type="hidden" name="adr2">
								<input type="hidden" name="adr3">
								<input type="hidden" name="adr4">
								<input type="hidden" name="pstcod">
								<input type="hidden" name="payadr1">
								<input type="hidden" name="payadr2">
								<input type="hidden" name="payadr3">
								<input type="hidden" name="payadr4">
								<input type="hidden" name="paypstcod">
								<div class="invoice-info">
									<!--<div class="invoice-from">
										<span>From</span>
										<strong>iDo Software</strong>
										<address>
										29 Foxglove Close <br>
										Rushden <br>
										Northampton <br>
										NN10 0TS
										</address>
									</div>-->
									<div class="invoice-from">
										<span>To</span>
										<div id="customerAddressDiv">
											</address>
										</div>
										<a href="#" class="btn btn-small" id="selectCustomerBtn"><i class="icon-search"></i> Select Customer</a>
									</div>
									<div class="invoice-infos">
										<div class="control-group">
											<label class="control-label">Invoice Date</label>
											<div class="controls">
												<input type="text" name="invdat" class="input-block-level" id="InvDat" value="<?php echo date('Y-m-d'); ?>">
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Due Date</label>
											<div class="controls">
												<input type="text" name="duedat" class="input-block-level" id="DueDat" value="<?php echo date('Y-m-d'); ?>">
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Paid Date</label>
											<div class="controls">
												<input type="text" name="paydat" class="input-block-level" id="PayDat" value="<?php echo strtotime("+1 week", strtotime(date('Y-m-d'))); ?>">
											</div>
										</div>
										<div class="control-group hide">
											<label class="control-label">Terms</label>
											<div class="controls">
												<textarea class="input-block-level" rows="6" name="paytrm"></textarea>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Status</label>
											<div class="controls">
												<select name="sta_id">
													<option value="0">Active</option>
													<option value="10">Invoiced</option>
													<option value="20">Paid</option>
													<option value="99">Cancelled</option>
												</select>
											</div>
										</div>
									</div>
								</div>
								<div style="clear: both">
								</div>
								<table class="table table-striped table-invoice">
									<thead>
										<tr>
											<th>Qty</th>
											<th>Unit</th>
											<th>Product</th>
											<th>Description</th>
											<th class="price">Price</th>
											<th class="total">Total</th>
											<th width="30"></th>
										</tr>
									</thead>
									<tbody id="orderLineTable">
									</tbody>
									<tbody>
										<tr>
											<td colspan="5"></td>
											<td align="right"></td>
											<td><a href="#" class="btn btn-mini btn-success" id="newOrderLine" rel="tooltip" title="New Order Line"><i class="icon-plus"></i></a></td>
										</tr>
										<tr>
											<td colspan="5"><select class="input-medium" name="vatrat">
													<option value="0">No Vat</option>
													<option value="20.00">Standard VAT</option>
												</select></td>
											<td class='taxes'><p> <span class="light">Subtotal</span> <span>&pound;<span id="subTotal">0.00</span></span> </p>
												<p> <span class="light">Tax</span> <span>&pound;<span id="vatTotal">0.00</span></span> </p>
												<p> <span class="light">Total</span> <span class="totalprice"> &pound;<span id="netTotal">0.00</span> </span> </p></td>
											<td></td>
										</tr>
										<tr>
											<td colspan="4"></td>
											<td align="right"><div class="pull-right">
													<a href="ecommerce/print_order.php" target="_blank" id="printOrderBtn" class="btn" rel="tooltip" title="Print"><i class="icon-print"></i></a> <a href="#" id="updateOrderBtn" class="btn" rel="tooltip" title="Update"><i class="icon-save"></i></a>
												</div></td>
											<td></td>
										</tr>
									</tbody>
								</table>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="row-fluid orderScreen" id="orderLineEditDiv" style="display: none;">
				<div class="span5">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3> <i class="icon-tags"></i> Order Line</h3>
							<div class="actions">
								<a href="#orderEditDiv" class="btn btn-mini screenSelect" rel="tooltip" title="Cancel"><i class="icon-arrow-left"></i></a> <a href="#" class="btn btn-mini" rel="tooltip" title="Update Order Line" id="updateOrderLineBtn"><i class="icon-save"></i></a>
							</div>
						</div>
						<div class="box-content">
							<form action="ecommerce/orders_script.php" id="orderLineForm" class="form-horizontal" novalidate>
								<input type="hidden" name="newoln" value="0">
								<div class="control-group">
									<label class="control-label">Product</label>
									<div class="controls">
										<select data-placeholder="Select a product..." class="chzn-select" name="prd_id">
											<option value=""></option>
											<?php
											$tableLength = count($products);
											for ($i=0;$i<$tableLength;++$i) {
											?>
											<option value="<?php echo $products[$i]['prd_id']; ?>"><?php echo $products[$i]['prdnam']; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Description</label>
									<div class="controls">
										<textarea class="input-large" name="olndsc"></textarea>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Quantity</label>
									<div class="controls">
										<input type="text" class="input-large disabled" name="numuni" value="1">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Price</label>
									<div class="controls">
										<input type="text" class="input-large disabled" name="unipri" value="">
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal hide fade" id="selectCustomerModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h3>Select Customer</h3>
	</div>
	<form action="system/places_script.php" id="selectCustomerForm" class="form-horizontal" novalidate>
		<div class="modal-body">
			<fieldset>
				<input type="hidden" name="tblnam" value="CUS">
				<input type="hidden" name="tbl_id" value="0">
				<div class="control-group">
					<label class="control-label">Customer</label>
					<div class="controls">
						<select data-placeholder="Select a customer..." class="chzn-select" name="pla_id">
							<option value="0">New Customer</option>
							<?php
							$tableLength = count($customers);
							for ($i=0;$i<$tableLength;++$i) {
							?>
							<option value="<?php echo $customers[$i]['pla_id']; ?>"><?php echo $customers[$i]['planam']; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Customer</label>
					<div class="controls">
						<input type="text" class="input-large disabled" name="comnam" value="">
						<input type="hidden" name="cusnam" value="">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Place</label>
					<div class="controls">
						<input type="text" class="input-large disabled" name="planam" value="">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Address</label>
					<div class="controls">
						<input type="text" class="input-large input-margin-bottom" name="adr1" value="">
						<input type="text" class="input-large input-margin-bottom" name="adr2" value="">
						<input type="text" class="input-large input-margin-bottom" name="adr3" value="">
						<input type="text" class="input-large" name="adr4" value="">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Postcode</label>
					<div class="controls">
						<input type="text" class="input-large" name="pstcod" value="">
					</div>
				</div>
			</fieldset>
		</div>
		<div class="modal-footer">
			<a href="#" class="btn" data-dismiss="modal"><i class="icon-remove" id="cancelSelectCustomerLink"></i> Cancel</a>
			<button type="submit" class="btn btn-primary" name="action" value="update"><i class="icon-save"></i> Update</button>
		</div>
	</form>
</div>
<div class="modal hide fade" id="orderLineModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h3>Order Line</h3>
	</div>
	<form action="ecommerce/orders_script.php" id="orderLineForm" class="form-horizontal" novalidate>
		<div class="modal-body" style="height: 500px;">
			<fieldset>
				<input type="hidden" name="newoln" value="0">
				<div class="control-group">
					<label class="control-label">Product</label>
					<div class="controls">
						<select data-placeholder="Select a product..." class="chzn-select" name="prd_id">
							<option value=""></option>
							<?php
							$tableLength = count($products);
							for ($i=0;$i<$tableLength;++$i) {
							?>
							<option value="<?php echo $products[$i]['prd_id']; ?>"><?php echo $products[$i]['prdnam']; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Description</label>
					<div class="controls">
						<textarea class="input-large" name="olndsc"></textarea>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Quantity</label>
					<div class="controls">
						<input type="text" class="input-large disabled" name="numuni" value="1">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Price</label>
					<div class="controls">
						<input type="text" class="input-large disabled" name="unipri" value="">
					</div>
				</div>
			</fieldset>
		</div>
		<div class="modal-footer">
			<a href="#" class="btn" data-dismiss="modal"><i class="icon-remove" id="cancelOrderLineLink"></i> Cancel</a>
			<button type="submit" class="btn btn-primary" name="action" value="update"><i class="icon-save"></i> Update</button>
		</div>
	</form>
</div>
<div id="returnHTML">
</div>
</body>
</html>