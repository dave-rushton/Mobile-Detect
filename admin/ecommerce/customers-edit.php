<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("../system/classes/places.cls.php");
require_once("../system/classes/people.cls.php");
require_once("../ecommerce/classes/order.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpPla = new PlaDAO();
$editPlaceID = (isset($_GET['pla_id']) && is_numeric($_GET['pla_id'])) ? $_GET['pla_id'] : NULL;
$placeRec = NULL;
if (!is_null($editPlaceID)) $placeRec = $TmpPla->select($editPlaceID, NULL, NULL, NULL, NULL, true); 

//if (!is_null($editPlaceID)) $projects = $TmpPla->select(NULL, 'PROJECT', $editPlaceID, NULL, 0, false); 

$TmpPpl = new PplDAO();
if (!is_null($editPlaceID)) $employee = $TmpPpl->select(NULL, 'EMP', $editPlaceID, NULL, false); 

$TmpOrd = new OrdDAO();
if (!is_null($editPlaceID)) $orders = $TmpOrd->select(NULL, 'CUS', $editPlaceID, NULL, false);

?>
<!doctype html>
<html>
<head>
<title><?php echo($placeRec) ? $placeRec->planam : 'New Customer'; ?> : PatchWorks Customer Maintenance </title>
<?php include('../webparts/headdata.php'); ?>

<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDjZSf7lI4D80NIwFMozDDABq-tSkGgKIs&sensor=false"></script>
<script src="js/plugins/gmap/gmap3.min.js"></script>
<script src="js/plugins/gmap/gmap3-menu.js"></script>
<script src="ecommerce/js/customers-edit.js"></script>
</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
	<?php include('../webparts/ecommerce-left.php'); ?>
	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>Customer : <?php echo($placeRec) ? $placeRec->planam : 'New Customer'; ?></h1>
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
						<a>eCommerce</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a href="ecommerce/customers.php">Customers</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a><?php echo($placeRec) ? $placeRec->planam : 'New Customer'; ?></a>
					</li>
				</ul>
			</div>
			<div class="row-fluid">
				<form action="system/places_script.php" id="customerForm" class="form-horizontal form-bordered" data-returnurl="ecommerce/customers.php">
					<div class="span6">
						<div class="box box-color box-bordered">
							<div class="box-title">
								<h3>
									<i class="icon-shopping-cart"></i> Customer</h3>
								<div class="actions">
									<a href="#" id="updateCustomerBtn" class="btn btn-mini" rel="tooltip" title="Update"><i class="icon-save"></i></a>
									<a href="#" id="deleteCustomerBtn" class="btn btn-mini" rel="tooltip" title="Delete"><i class="icon-trash"></i></a>
								</div>
							</div>
							<div class="box-content nopadding">
								<input type="hidden" name="pla_id" id="id" value="<?php echo($placeRec) ? $placeRec->pla_id : '0'; ?>">
								<input type="hidden" name="tblnam" value="CUS">
								<input type="hidden" name="tbl_id" value="0">
								<div class="control-group">
									<label class="control-label">Company Name<small>Main name used on invoicing</small></label>
									<div class="controls">
										<input type="text" class="input-large" name="comnam" value="<?php echo($placeRec) ? $placeRec->comnam : ''; ?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Place Name<small>identifying name</small></label>
									<div class="controls">
										<input type="text" class="input-large" name="planam" value="<?php echo($placeRec) ? $placeRec->planam : ''; ?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Email Address<small>email correspondance</small></label>
									<div class="controls">
										<input type="text" class="input-large" name="plaema" value="<?php echo($placeRec) ? $placeRec->plaema : ''; ?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="password">Telephone<small>telephone correspondance</small></label>
									<div class="controls">
										<input type="text" class="input-large" name="platel" value="<?php echo($placeRec) ? $placeRec->platel : ''; ?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Password<small>where applicable</small></label>
									<div class="controls">
										<input type="text" class="input-large" name="paswrd"/>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="confirm">Confirm Password<small>where applicable</small></label>
									<div class="controls">
										<input type="text" class="input-large" name="pascnf" id="PasCnf">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Status<small>current customer status</small></label>
									<div class="controls">
										<label class="radio">
											<input type="radio" name="sta_id" value="0" <?php echo($placeRec && $placeRec->sta_id == 0) ? 'checked' : ''; ?>>
											Active</label>
										<label class="radio">
											<input type="radio" name="sta_id" value="1" <?php echo($placeRec && $placeRec->sta_id == 1) ? 'checked' : ''; ?>>
											In-Active </label>
									</div>
								</div>
							</div>
						</div>
						
						<div class="box box-color box-bordered">
							<div class="box-title">
								<h3>
									<i class="icon-pushpin"></i> Address</h3>
								<div class="actions">
									<a href="#" class="btn btn-mini" id="adrControl"><i class="icon-angle-down"></i></a>
									<!--<a href="ecommerce/customers-edit.php" class="btn btn-mini" rel="tooltip" title="New Customer"><i class="icon-file"></i></a>-->
								</div>
							</div>
							<div class="box-content nopadding" id="adrInputs" style="display: none;">
								
								
								
									<div class="control-group">
										<label class="control-label">Address</label>
										<div class="controls">
											<input type="text" class="input-block-level input-margin-bottom" name="adr1" value="<?php echo($placeRec) ? $placeRec->adr1 : ''; ?>">
											<input type="text" class="input-block-level input-margin-bottom" name="adr2" value="<?php echo($placeRec) ? $placeRec->adr2 : ''; ?>">
											<input type="text" class="input-block-level input-margin-bottom" name="adr3" value="<?php echo($placeRec) ? $placeRec->adr3 : ''; ?>">
											<input type="text" class="input-block-level input-margin-bottom" name="adr4" value="<?php echo($placeRec) ? $placeRec->adr4 : ''; ?>">
											<input type="text" class="input-block-level" name="ctynam" value="<?php echo($placeRec) ? $placeRec->ctynam : ''; ?>">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Postcode</label>
										<div class="controls">
											<div class="input-append">
												<input type="text" class="input-large" name="pstcod" value="<?php echo($placeRec) ? $placeRec->pstcod : ''; ?>">
												<button id="geoLocate" class="btn"><i class="icon-map-marker"></i></button>
											</div>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Google Data</label>
										<div class="controls">
											<input type="text" class="input-large input-margin-bottom" name="goolat" id="GooLat" value="<?php echo($placeRec) ? $placeRec->goolat : ''; ?>">
											<input type="text" class="input-large input-margin-bottom" name="goolng" id="GooLng" value="<?php echo($placeRec) ? $placeRec->goolng : ''; ?>">
										</div>
									</div>
								
								
								
							</div>
							<div class="box-content nopadding">
								
								<div id="map_canvas" style="height: 400px;">
								</div>
								
							</div>
						</div>
						
					</div>
					<div class="span6">
					
						<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-group"></i> Orders</h3>
							<div class="actions">
								<!--<a href="ecommerce/employee-edit.php?cus_id=<?php echo($placeRec) ? $placeRec->pla_id : '0'; ?>" id="createEmployeeBtn" class="btn btn-mini" rel="tooltip" title="New Employee"><i class="icon-plus-sign"></i></a>-->
							</div>
						</div>
						<div class="box-content nopadding">
						
							<table class="table table-nomargin table-striped" id="ordersTable">
								<thead>
									<tr>
										<th>Order No</th>
										<th>Invoice Date</th>
										<th>Due Date</th>
										<th style="text-align: right;">Amount</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody id="ordersBody">
									
									<?php
									
									$tableLength = count($orders);
									$totForAdv = 0;
									$advPayDat = 0;
									for ($i=0;$i<$tableLength;++$i) {
										
										$ordTot = str_replace(',', '', number_format($orders[$i]['ordtot'], 2));
										$totForAdv += (float)$ordTot;
									?>
									
									<tr <?php if ($orders[$i]['sta_id'] < 20 && strtotime(date('Y-m-d')) > strtotime($orders[$i]['duedat']) ) echo 'class="error"'; ?>>
										<td><a href="ecommerce/order-edit.php?ord_id=<?php echo $orders[$i]['ord_id']; ?>" class="editOrderLnk" data-ord_id="<?php echo $orders[$i]['ord_id']; ?>"><?php echo str_pad($orders[$i]['ord_id'], 8, "0", STR_PAD_LEFT); ?></a></td>
										<td><?php echo date("jS M Y", strtotime($orders[$i]['invdat'])); ?></td>
										<td><?php echo date("jS M Y", strtotime($orders[$i]['duedat'])); ?></td>
										<td style="text-align: right;">&pound;<span class="orderTotalCalc"><?php echo $ordTot; ?></span></td>
										<td>
										<?php 
										
										$now = strtotime($orders[$i]['paydat']);
										$your_date = strtotime($orders[$i]['duedat']);
										$datediff = $your_date - $now;
										$datediff = ceil($datediff/(60*60*24));
										
										$status = 'UNKNOWN';
										switch ($orders[$i]['sta_id']) {
											case 0:
												echo "Active";
												break;
											case 10:
												echo "Invoiced";
												break;
											case 20:
												$advPayDat += $datediff;
												echo "Paid"; //<br><small>".$datediff."</small>";
												break;
										}
										
										?>
										</td>
									</tr>
									
									<?php } ?>
									
									
								
								</tbody>
                                <?php 
								if (is_numeric($tableLength) && $tableLength > 0) {
								?>
								<tfoot>
									<tr>
										<td colspan="3">Adv:</td>
										<td style="text-align: right;">&pound;<?php echo number_format($totForAdv / $tableLength, 2); ?></th>
										<td></td>
									</tr>
									<tr>
										<td colspan="3">Adv Payment:</td>
										<td style="text-align: right;">
											<?php 
											$advPayDat = round($advPayDat / $tableLength); 
											echo ( $advPayDat < 0 ) ? ($advPayDat*-1).' day(s) late' : $advPayDat.' days early'; 
											?>
										</th>
										<td></td>
									</tr>
								</tfoot>
                                <?php } ?>
							</table>
						
						</div>
					</div>
					
						
						
						
						<div class="box box-color box-bordered">
							<div class="box-title">
								<h3>
									<i class="icon-group"></i> Employees</h3>
								<div class="actions">
									<a href="ecommerce/employee-edit.php?cus_id=<?php echo($placeRec) ? $placeRec->pla_id : '0'; ?>" id="createEmployeeBtn" class="btn btn-mini" rel="tooltip" title="New Employee"><i class="icon-plus-sign"></i></a>
								</div>
							</div>
							<div class="box-content nopadding">
							
								<table class="table table-bordered table-striped table-highlight" id="employeeTable">
									<thead>
										<tr>
											<th>Employee Name</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$tableLength = count($employee);
										for ($i=0;$i<$tableLength;++$i) {
										?>
										<tr>
											<td><a href="ecommerce/employee-edit.php?cus_id=<?php echo($placeRec) ? $placeRec->pla_id : '0'; ?>&ppl_id=<?php echo $employee[$i]['ppl_id'] ?>"><?php echo $employee[$i]['pplnam'] ?></a></td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							
							</div>
						</div>
						
						
					</div>
				</form>
			</div>
			
			
			
		</div>
	</div>
</div>
</body>
</html>
