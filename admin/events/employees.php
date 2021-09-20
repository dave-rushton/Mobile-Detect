<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("../system/classes/people.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpPpl = new PplDAO();
$employee = $TmpPpl->select(NULL, 'EMP', NULL, NULL, false); 

?>
<!doctype html>
<html>
<head>
<title>Employees</title>
<?php include('../webparts/headdata.php'); ?>
<!-- dataTables -->
<script src="js/plugins/datatable/jquery.dataTables.min.js"></script>
<script src="js/plugins/datatable/TableTools.min.js"></script>
<script src="js/plugins/datatable/ColReorder.min.js"></script>
<script src="js/plugins/datatable/ColVis.min.js"></script>
<script src="js/plugins/datatable/jquery.dataTables.columnFilter.js"></script>

<script src="bookings/js/employees.js"></script>
</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-orange">
<div class="container-fluid" id="content">
	<?php include('../webparts/bookings-left.php'); ?>
	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>Employees</h1>
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
						<a href="events/employees.php">Employees</a>
					</li>
				</ul>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-group"></i> Employees</h3>
							<div class="actions">
								<a href="events/employee-edit.php" class="btn btn-mini" rel="tooltip" title="New Employee"><i class="icon-file"></i></a>
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
										<td><a href="events/employee-edit.php?ppl_id=<?php echo $employee[$i]['ppl_id'] ?>"><?php echo $employee[$i]['pplnam'] ?></a></td>
									</tr>
									<?php } ?>
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
