<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("../ecommerce/classes/order.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

?>
<!doctype html>
<html>
<head>
<title>eCommerce Dashboard</title>
<?php include('../webparts/headdata.php'); ?>

<!-- Flot -->
<script src="js/plugins/flot/jquery.flot.min.js"></script>
<script src="js/plugins/flot/jquery.flot.resize.min.js"></script>
<script src="ecommerce/js/dashboard.js"></script>

<style>

#lineChart1 div.xAxis div.tickLabel 
{   
	margin-left: 2em;
	width: 40px !important;
    transform: rotate(-90deg);
    -ms-transform:rotate(-90deg); /* IE 9 */
    -moz-transform:rotate(-90deg); /* Firefox */
    -webkit-transform:rotate(-90deg); /* Safari and Chrome */
    -o-transform:rotate(-90deg); /* Opera */
    /*rotation-point:50% 50%;*/ /* CSS3 */
    /*rotation:270deg;*/ /* CSS3 */
}

</style>

</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
	<?php include('../webparts/ecommerce-left.php'); ?>
	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>eCommerce Dashboard</h1>
				</div>
				<div class="pull-right">
					<?php //include('../webparts/sales-info.php'); ?>
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
					</li>
				</ul>
			</div>
			<div class="row-fluid">

				<div class="span12">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-folder-close-alt"></i> Invoices Due</h3>
							<div class="actions">
								<!--<a href="website/forms-edit.php" class="btn btn-mini" rel="tooltip" title="New Form"><i class="icon-file"></i></a>-->
							</div>
						</div>
						<div class="box-content nopadding">
							<table class="table table-condensed" id="ordersTable">
								<thead>
									<tr>
										<th>Order No</th>
										<th>Customer</th>
										<th>Invoice Date</th>
										<th>Invoice Time</th>
										<th style="text-align: right;">Amount</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody id="ordersBody">
									<?php
									$TmpOrd = new OrdDAO();
									$orders = $TmpOrd->select(NULL, NULL, NULL, '0,10,20', false);
									
									$tableLength = count($orders);
									for ($i=0;$i<$tableLength;++$i) {
									
									?>
									<tr <?php if ($orders[$i]['sta_id'] < 20 && strtotime(date('Y-m-d')) > strtotime($orders[$i]['duedat']) ) echo 'class="error"'; ?>>
									
										<td><a href="ecommerce/order-edit.php?ord_id=<?php echo $orders[$i]['ord_id']; ?>" class="editOrderLnk" data-ord_id="<?php echo $orders[$i]['ord_id']; ?>"><?php echo str_pad($orders[$i]['ord_id'], 8, "0", STR_PAD_LEFT); ?></a></td>
										<td><?php echo $orders[$i]['cusnam']; ?></td>
										<td><?php echo date("jS M Y", strtotime($orders[$i]['invdat'])); ?></td>
										<td><?php echo date("H:i", strtotime($orders[$i]['invdat'])); ?></td>
										<td style="text-align: right;">&pound;<span class="orderTotalCalc"><?php echo number_format($orders[$i]['ordtot'],2); ?></span></td>
										<td>
										<?php 
	
										$status = 'UNKNOWN';
										switch ($orders[$i]['sta_id']) {
											case 0:
												echo "Active";
												break;
											case 10:
												echo "Invoiced";
												break;
											case 20:
												echo "Paid";
												break;
										}
										
										$datediff = '';
										if ($orders[$i]['sta_id'] == 10 && !is_null($orders[$i]['duedat']) && $orders[$i]['duedat'] != '') {
											$now = time();
											$your_date = strtotime($orders[$i]['duedat']);
											$datediff = $your_date - $now;
											$datediff = ceil($datediff/(60*60*24));
											if ($datediff > 0) {
												echo ' <small class="pull-right label label-info">due in '.$datediff.' day(s)</small>';
											} else {
												echo ' <small class="pull-right label label-important">'.$datediff.' day(s) overdue</small>';
											}
										}
										
										?>
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						
							
						</div>
					</div>
					
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-bar-chart"></i> <span id="curYr_Num"></span> Invoice Chart</h3>
							<div class="actions">
								<a href="#" class="btn btn-mini custom-checkbox" id="incActive">Include Active<i class="icon-check-empty"></i></a>
								<a href="#" class="btn btn-mini" id="prevYear" rel="tooltip" title="Previous Year"><i class="icon-angle-left"></i></a>
								<a href="#" class="btn btn-mini" id="nextYear" rel="tooltip" title="Next Year"><i class="icon-angle-right"></i></a>
							</div>
						</div>
						<div class="box-content">
							<div id="lineChart" class="flot medium">
							</div>
						</div>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>
</body>
</html>
