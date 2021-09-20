<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("../system/classes/places.cls.php");
require_once("../system/classes/people.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpPla = new PlaDAO();
$customers = $TmpPla->select(NULL, 'CUS', NULL, NULL, 0, false);
$projects = $TmpPla->select(NULL, 'PROJECT', NULL, NULL, 0, false);

$TmpPpl = new PplDAO();
$employees = $TmpPpl->select(NULL, 'EMP', NULL, NULL, false);

?>
<!doctype html>
<html>
<head>
<title>Bookings Reports</title>
<?php include('../webparts/headdata.php'); ?>

<link rel="stylesheet" href="css/plugins/datatable/TableTools.css">
<link rel="stylesheet" href="css/plugins/datepicker/datepicker.css">

<script src="js/plugins/datatable/jquery.dataTables.min.js"></script>
<script src="js/plugins/datatable/TableTools.min.js"></script>
<script src="js/plugins/datatable/ColReorder.min.js"></script>
<script src="js/plugins/datatable/ColVis.min.js"></script>
<script src="js/plugins/datatable/jquery.dataTables.columnFilter.js"></script>
<script src="js/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="js/plugins/colorpicker/bootstrap-colorpicker.js"></script>
<script src="js/system.date.js"></script>
<script src="projects/js/reports.js"></script>
</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
	<?php include('../webparts/bookings-left.php'); ?>
	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>Reports</h1>
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
						<a>Reports</a>
					</li>
				</ul>
			</div>
			
			<div class="row-fluid">
				<div class="span12">
					<div class="box">
						<div class="box-title">
							<h3>
								<i class="icon-filter"></i> Timesheet</h3>
						</div>
						<div class="box-content">
							<form action="projects/reports/print_timesheet.php" id="timesheetForm" class="form-vertical orientation-fixed">
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
									<div class="control-group">
										<label class="control-label">Customers</label>
										<div class="controls">
											<select data-placeholder="Select a customer..." name="cus_id">
												<option value="">N/A</option>
												<?php
												$tableLength = count($customers);
												for ($i=0;$i<$tableLength;++$i) {
												?>
												<option value="<?php echo $customers[$i]['pla_id']; ?>"><?php echo $customers[$i]['planam']; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>
								<div class="span3">
									<div class="control-group">
										<label class="control-label">Project</label>
										<div class="controls">
											<select data-placeholder="Select a project..." name="pro_id">
												<option value="">N/A</option>
												<?php
												$tableLength = count($projects);
												for ($i=0;$i<$tableLength;++$i) {
												?>
												<option value="<?php echo $projects[$i]['pla_id']; ?>"><?php echo $projects[$i]['planam']; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>
								<div class="span3">
									<div class="control-group">
										<label class="control-label">Employee</label>
										<div class="controls">
											<select data-placeholder="Select an employee..." name="emp_id">
												<option value="">N/A</option>
												<?php
												$tableLength = count($employees);
												for ($i=0;$i<$tableLength;++$i) {
												?>
												<option value="<?php echo $employees[$i]['ppl_id']; ?>"><?php echo $employees[$i]['pplnam']; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>
								
								<button type="submit">Run Report</button>
								
							</form>
							
						</div>
					</div>
					
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-time"></i> Bookings Listing</h3>
						</div>
						<div class="box-content nopadding" id="reportBox">
							
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
