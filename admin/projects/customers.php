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
<title>Clients</title>
<?php include('../webparts/headdata.php'); ?>
<!-- dataTables -->
<script src="js/plugins/datatable/jquery.dataTables.min.js"></script>
<script src="js/plugins/datatable/TableTools.min.js"></script>
<script src="js/plugins/datatable/ColReorder.min.js"></script>
<script src="js/plugins/datatable/ColVis.min.js"></script>
<script src="js/plugins/datatable/jquery.dataTables.columnFilter.js"></script>

<script src="projects/js/customers.js"></script>
</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-orange">
<div class="container-fluid" id="content">
	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>Clients</h1>
				</div>
				<div class="pull-right">
					<?php include('../webparts/website-left.php'); ?>
				</div>
			</div>
			<div class="breadcrumbs">
				<ul>
					<li>
						<a href="index.php">Dashboard</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a href="projects/dashboard.php">Dashboard</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a href="projects/customers.php">Clients</a>
					</li>
				</ul>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-shopping-cart"></i> Clients</h3>
							<div class="actions">
								<a href="projects/customers-edit.php" class="btn btn-mini" rel="tooltip" title="New Client"><i class="icon-plus-sign"></i></a>
							</div>
						</div>
						<div class="box-content nopadding">
						
							<table class="table" id="customerTable">
								<thead>
									<tr>
										<th>Customer Name</th>
										<th>Company Name</th>
									</tr>
								</thead>
								<tbody id="customerBody">
									<?php
									$tableLength = count($customers);
									for ($i=0;$i<$tableLength;++$i) {
									?>
									<tr>
										<td><a href="projects/customers-edit.php?pla_id=<?php echo $customers[$i]['pla_id']; ?>"><?php echo $customers[$i]['planam']; ?></a></td>
										<td><?php echo $customers[$i]['comnam']; ?></td>
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
