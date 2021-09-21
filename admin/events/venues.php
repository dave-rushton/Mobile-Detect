<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("../system/classes/places.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpPla = new PlaDAO();
$venues = $TmpPla->select(NULL, 'VENUE', NULL, NULL);

?>
<!doctype html>
<html>
<head>
<title>Venues</title>
<?php include('../webparts/headdata.php'); ?>
<!-- dataTables -->
<script src="js/plugins/datatable/jquery.dataTables.min.js"></script>
<script src="js/plugins/datatable/TableTools.min.js"></script>
<script src="js/plugins/datatable/ColReorder.min.js"></script>
<script src="js/plugins/datatable/ColVis.min.js"></script>
<script src="js/plugins/datatable/jquery.dataTables.columnFilter.js"></script>

<script src="venues/js/venues.js"></script>
</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-green">
<div class="container-fluid" id="content">
	<?php include('../webparts/website-left.php'); ?>
	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>Venues</h1>
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
						<a href="events/dashboard.php">Events Dashboard</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a href="events/venues.php">Venues</a>
					</li>
				</ul>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-calendar"></i> Venues</h3>
							<div class="actions">
								<a href="events/venue-edit.php" class="btn btn-mini" rel="tooltip" title="New Venue"><i class="icon-plus-sign"></i></a>
							</div>
						</div>
						<div class="box-content nopadding">
						
							<table class="table" id="venuesTable">
								<thead>
									<tr>
										<th>Name</th>
									</tr>
								</thead>
								<tbody id="venuesBody">
									<?php
									$tableLength = count($venues);
									for ($i=0;$i<$tableLength;++$i) {
									?>
									<tr>
										<td><a href="events/venue-edit.php?pla_id=<?php echo $venues[$i]['pla_id']; ?>"><?php echo $venues[$i]['planam']; ?></a></td>
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