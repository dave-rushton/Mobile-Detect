<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("../system/classes/statuscodes.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

?>
<!doctype html>
<html>
<head>
<title>Booking Status</title>
<?php include('../webparts/headdata.php'); ?>
<!-- dataTables -->
<script src="js/plugins/datatable/jquery.dataTables.min.js"></script>
<script src="js/plugins/datatable/TableTools.min.js"></script>
<script src="js/plugins/datatable/ColReorder.min.js"></script>
<script src="js/plugins/datatable/ColVis.min.js"></script>
<script src="js/plugins/datatable/jquery.dataTables.columnFilter.js"></script>

<script src="projects/js/statuscodes.js"></script>

</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
	<?php include('../webparts/bookings-left.php'); ?>
	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>Booking Status</h1>
				</div>
				<div class="pull-right">
					<?php include('../webparts/bookings-left.php'); ?>
				</div>
			</div>
			<div class="breadcrumbs">
				<ul>
					<li>
						<a href="index.php">Dashboard</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a href="projects/dashboard.php">Bookings</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a>Booking Status</a>
					</li>
				</ul>
			</div>
			
			<div class="row-fluid">
				<div class="span7">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-checkbox"></i> Create Status</h3>
							<div class="actions">
								<a href="#" class="btn btn-mini" id="createStatusBtn"><i class="icon-save"></i></a>
							</div>
						</div>
						<div class="box-content nopadding">
							<form action="system/statuscodes_script.php" id="statusCodeForm" class="form-horizontal form-bordered">
								<input type="hidden" name="sta_id" value="0">
								<input type="hidden" name="tblnam" value="TASKS">
								<div class="control-group">
									<label class="control-label">Booking Status</label>
									<div class="controls">
										<input type="text" class="input-large" name="stanam" value="">
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			
			<div class="row-fluid">
				<div class="span3">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-checkbox"></i> Booking Status</h3>
							<div class="actions">
								<!--<a href="#" class="btn btn-mini custom-checkbox" id="allProjects">Show All<i class="icon-check-empty"></i></a>-->
							</div>
						</div>
						<div class="box-content nopadding">
						
							<table class="table table-condensed" id="statusCodeTable">
								<thead>
									<tr>
										<th>Booking Status</th>
										<th width="30"></th>
									</tr>
								</thead>
								<tbody id="statusCodeBody">
									
									
								</tbody>
							</table>
						
						</div>
					</div>
				</div>
				
				<div class="span4">
					<div class="box box-color box-bordered darkblue">
						<div class="box-title">
							<h3>
								<i class="icon-checkbox"></i> Booking Status</h3>
							<div class="actions">
								<!--<a href="#" class="btn btn-mini custom-checkbox" id="allProjects">Show All<i class="icon-check-empty"></i></a>-->
							</div>
						</div>
						<div class="box-content nopadding">
						
							<table class="table table-condensed" id="statusFlowTable">
								<thead>
									<tr>
										<th>Status Flow</th>
										<th width="30"></th>
									</tr>
								</thead>
								<tbody id="statusFlowBody">
									
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
