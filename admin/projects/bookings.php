<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("../system/classes/places.cls.php");
require_once("../system/classes/people.cls.php");
require_once("../system/classes/statuscodes.cls.php");
require_once("../projects/classes/bookings.cls.php");
require_once("../products/classes/products.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$BegDat = (isset($_GET['begdat']) ) ? $_GET['begdat'] : NULL;
$EndDat = (isset($_GET['enddat']) ) ? $_GET['enddat'] : NULL;

$Tbl_ID = (isset($_GET['tbl_id']) && is_numeric($_GET['tbl_id'])) ? $_GET['tbl_id'] : NULL;
$TblNam = (isset($_GET['tblnam']) && !empty($_GET['tblnam'])) ? $_GET['tblnam'] : NULL;
$Ref_ID = (isset($_GET['ref_id']) && is_numeric($_GET['ref_id'])) ? $_GET['ref_id'] : NULL;
$RefNam = (isset($_GET['refnam']) && !empty($_GET['refnam'])) ? $_GET['refnam'] : NULL;

//$TmpBoo = new BooDAO();
//$bookings = $TmpBoo->select(NULL, $BegDat, $EndDat, $TblNam, $Tbl_ID, $RefNam, $Ref_ID, false);

$TmpPla = new PlaDAO();
$places = $TmpPla->select(NULL, 'PROJECT', NULL, NULL, 0, false);

$TmpPpl = new PplDAO();
$people = $TmpPpl->select(NULL, 'EMP', NULL, NULL, false);

$TmpPrd = new PrdDAO();
$products = $TmpPrd->select(NULL, NULL, NULL, NULL, false);

$TmpSta = new StaDAO();
$statusCodes = $TmpSta->select(NULL, 'BOOKING', false);

?>
<!doctype html>
<html>
<head>
<title>Bookings Listing</title>
<?php include('../webparts/headdata.php'); ?>

<link rel="stylesheet" href="css/plugins/datatable/TableTools.css">
<link rel="stylesheet" href="css/plugins/datepicker/datepicker.css">
<link rel="stylesheet" href="css/plugins/colorpicker/colorpicker.css">

<link rel="stylesheet" href="css/plugins/timepicker/bootstrap-timepicker.min.css">
<script src="js/plugins/timepicker/bootstrap-timepicker.min.js"></script>

<script src="js/plugins/datatable/jquery.dataTables.min.js"></script>
<script src="js/plugins/datatable/TableTools.min.js"></script>
<script src="js/plugins/datatable/ColReorder.min.js"></script>
<script src="js/plugins/datatable/ColVis.min.js"></script>
<script src="js/plugins/datatable/jquery.dataTables.columnFilter.js"></script>
<script src="js/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="js/plugins/colorpicker/bootstrap-colorpicker.js"></script>
<script src="js/system.date.js"></script>
<script src="projects/js/bookings.js"></script>
</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
	<?php include('../webparts/bookings-left.php'); ?>
	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>Bookings</h1>
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
						<a>Bookings</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a href="projects/bookings.php">Listing</a>
					</li>
				</ul>
			</div>
			
			<div class="row-fluid adminScreen" id="listingScreen">
				<div class="span12">
					<div class="box">
						<div class="box-title">
							<h3>
								<i class="icon-filter"></i> Bookings Filter</h3>
							<div class="actions">
								<a href="#" class="btn" rel="tooltip" title="Search Bookings"><i class="icon-search"></i></a>
							</div>
						</div>
						<div class="box-content">
							<form action="projects/bookings_script.php" id="searchForm" class="form-vertical orientation-fixed">
								<div class="span3">
									<div class="control-group">
										<label class="control-label"><input type="checkbox" name="usedat" checked style="margin: 0"> Use dates</label>
										<div class="controls">
											
											<input type="text" name="begdat" class="input-small">
											<input type="text" name="enddat" class="input-small">
											<a href="#" id="prevWeek" class="btn btn-mini"><i class="icon-angle-left"></i></a>
											<a href="#" id="nextWeek" class="btn btn-mini"><i class="icon-angle-right"></i></a>
										</div>
									</div>
								</div>
								<div class="span3">
									<input type="hidden" name="tblnam" value="PROJECT">
									<div class="control-group">
										<label class="control-label">Project</label>
										<div class="controls">
											<select data-placeholder="Select a project..." name="tbl_id">
												<option value="">N/A</option>
												<?php
												$tableLength = count($places);
												for ($i=0;$i<$tableLength;++$i) {
												?>
												<option value="<?php echo $places[$i]['pla_id']; ?>"><?php echo $places[$i]['planam']; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>
								<div class="span3">
									<div class="control-group">
										<label class="control-label">Employee</label>
										<div class="controls">
											<select data-placeholder="Select an employee..." name="ref_id">
												<option value="">N/A</option>
												<?php
												$tableLength = count($people);
												for ($i=0;$i<$tableLength;++$i) {
												?>
												<option value="<?php echo $people[$i]['ppl_id']; ?>"><?php echo $people[$i]['pplnam']; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>
								<div class="span3">
									<div class="control-group">
										<label class="control-label">Status</label>
										<div class="controls">
											<select name="sta_id">
												<option value="">Show All</option>
												<option value="0">New Record</option>
												<?php
												$tableLength = count($statusCodes);
												for ($i=0;$i<$tableLength;++$i) {
												?>
												<option value="<?php echo $statusCodes[$i]['sta_id'] ?>"><?php echo $statusCodes[$i]['stanam'] ?></option>
												<?php } ?>
											
												<!--<optgroup label="Schedule">
												<option value="1">In Progress</option>
												<option value="2">Complete</option>
												<option value="3">With Client</option>
												</optgroup>
												<optgroup label="Event">
												<option value="11">Confirmed</option>
												<option value="12">Cancelled</option>
												<option value="13">Denied</option>
												<option value="14">Accepted</option>
												</optgroup>
												<optgroup label="Payment">
												<option value="50">To Invoice</option>
												<option value="51">Invoiced</option>
												<option value="52">Paid</option>
												<option value="53">Rejected</option>
												</optgroup>-->
											</select>
										</div>
									</div>
								</div>
							</form>
							
							<dl class="dl-horizontal">
								<dt>Total Duration</dt>
								<dd id="totalDuration"></dd>
							</dl>
							
						</div>
					</div>
					
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-time"></i> Bookings Listing</h3>
							<div class="actions">
								<a href="#" id="newBookingBtn" class="btn btn-mini" rel="tooltip" title="New Booking"><i class="icon-plus"></i></a>
							</div>
						</div>
						<div class="box-content nopadding">
							<table class="table table-nomargin table-condensed" id="bookingsTable">
								<thead>
									<tr>
										<th width="20"><input type="checkbox" id="selAllBoo"></th>
										<th>Date</th>
										<th>Sort Date</th>
										<th>Time</th>
										<th>Duration</th>
										<th>Project</th>
										<th>Employee</th>
										<th>Status</th>
										<th></th>
									</tr>
								</thead>
								<tbody id="bookingsBody">
								</tbody>
							</table>
						</div>
					</div>
					
					<div class="box box-bordered box-color" id="changeStatusDiv">
						<div class="box-title">
							<h3>
								<i class="icon-time"></i> Bookings Status Update</h3>
							<div class="actions">
								<a href="#" id="updateStatusBtn" class="btn btn-mini" rel="tooltip" title="Update Booking Status"><i class="icon-save"></i></a>
							</div>
						</div>
						<div class="box-content nopadding">
							
							
							<form  class="form-horizontal form-bordered" id="changeStatusForm">
								<div class="control-group">
									<label class="control-label">Status</label>
									<div class="controls">
										<select name="sta_id">
											<option value="">Show All</option>
											<option value="0">New Record</option>
											<?php
											$tableLength = count($statusCodes);
											for ($i=0;$i<$tableLength;++$i) {
											?>
											<option value="<?php echo $statusCodes[$i]['sta_id'] ?>"><?php echo $statusCodes[$i]['stanam'] ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
							</form>
							
							
							
						</div>
					</div>
					
				</div>
			</div>
			<div class="row-fluid adminScreen hide" id="bookingFormScreen">
				<div class="span12">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-shopping-cart"></i> Booking Form</h3>
							<div class="actions">
								<a href="#listingScreen" class="btn btn-mini screenSelect" rel="tooltip" title="Cancel"><i class="icon-remove"></i></a>
								<a href="#" class="btn btn-mini" rel="tooltip" title="Update Booking" id="updateBooking"><i class="icon-save"></i></a>
							</div>
						</div>
						<div class="box-content nopadding">
							<form action="projects/bookings_script.php" id="bookingForm" class="form-horizontal form-bordered">
								<input type="hidden" name="boo_id" value="0">
								<input type="hidden" name="tblnam" value="PROJECT">
								<input type="hidden" name="refnam" value="EMP">
								<input type="hidden" name="begdat" value="">
								<input type="hidden" name="enddat" value="">
								<div class="control-group">
									<label class="control-label">Project<small>select active project</small></label>
									<div class="controls">
										<select data-placeholder="Select a project..." name="tbl_id" class="input-block-level">
											<option value="0">N/A</option>
											<?php
											$tableLength = count($places);
											for ($i=0;$i<$tableLength;++$i) {
											?>
											<option value="<?php echo $places[$i]['pla_id']; ?>"><?php echo $places[$i]['planam']; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Employee<small>select task owner</small></label>
									<div class="controls">
										<select data-placeholder="Select an employee..." name="ref_id" class="input-block-level">
											<option value="0">N/A</option>
											<?php
											$tableLength = count($people);
											for ($i=0;$i<$tableLength;++$i) {
											?>
											<option value="<?php echo $people[$i]['ppl_id']; ?>"><?php echo $people[$i]['pplnam']; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Product<small>select product</small></label>
									<div class="controls">
										<select data-placeholder="Select an product..." name="prd_id" class="input-block-level">
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
									<label class="control-label">Description<small>short description of booking</small></label>
									<div class="controls">
										<textarea class="input-block-level" name="boodsc" rows="6"></textarea>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Start Date<small>booking start date</small></label>
									<div class="controls">
										<input type="text" class="input-block-level" name="begdatdsp" value="1">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Start Time<small>booking start time</small></label>
									<div class="controls">
										<div class="bootstrap-timepicker">
										<input type="text" class="input-block-level" name="begtim" value="">
										</div>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">End Date<small>booking end date</small></label>
									<div class="controls">
										<input type="text" class="input-block-level" name="enddatdsp" value="">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">End Time<small>booking end time</small></label>
									<div class="controls">
										<div class="bootstrap-timepicker">
										<input type="text" class="input-block-level" name="endtim" value="">
										</div>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Status<small>booking status</small></label>
									<div class="controls">
										<select name="sta_id" class="input-block-level">
											<option value="0">New Record</option>
											<optgroup label="Schedule">
											<option value="1">In Progress</option>
											<option value="2">Complete</option>
											<option value="3">With Client</option>
											</optgroup>
											<optgroup label="Event">
											<option value="11">Confirmed</option>
											<option value="12">Cancelled</option>
											<option value="13">Denied</option>
											<option value="14">Accepted</option>
											</optgroup>
											<optgroup label="Payment">
											<option value="50">To Invoice</option>
											<option value="51">Invoiced</option>
											<option value="52">Paid</option>
											<option value="53">Rejected</option>
											</optgroup>
										</select>
									</div>
								</div>
								
								
								<div class="control-group">
									<label class="control-label">Color<small>calendar colour</small></label>
									<div class="controls">
										<select name="boocol" class="input-block-level">
											<option value="#e51400">Red</option>
											<option value="#f8a31f">Orange</option>
											<option value="#393">Green</option>
											<option value="#a05000">Brown</option>
											<option value="#368ee0">Blue</option>
											<option value="#8cbf26">Lime</option>
											<option value="#00aba9">Teal</option>
											<option value="#ff0097">Purple</option>
											<option value="#e671b8">Pink</option>
											<option value="#a200ff">Magenta</option>
											<option value="#333">Grey</option>
											<option value="#204e81">Dark Blue</option>
											<option value="#e63a3a">Light Red</option>
											<option value="#666">Light Grey</option>
											<option value="#2c5e7b">Sat Blue</option>
											<option value="#56af45">Sat Green</option>
										</select>
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

<div id="chkHTML"></div>

</body>
</html>
