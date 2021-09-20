<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("../attributes/classes/attrgroups.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpAtr = new AtrDAO();
$attrGroups = $TmpAtr->select(NULL, 'PRODUCTGROUP');

?>
<!doctype html>
<html>
<head>
<title>Product Groups</title>
<?php include('../webparts/headdata.php'); ?>
<!-- dataTables -->
<script src="js/plugins/datatable/jquery.dataTables.min.js"></script>
<script src="js/plugins/datatable/TableTools.min.js"></script>
<script src="js/plugins/datatable/ColReorder.min.js"></script>
<script src="js/plugins/datatable/ColVis.min.js"></script>
<script src="js/plugins/datatable/jquery.dataTables.columnFilter.js"></script>

<script src="products/js/productgroups.js"></script>
</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
	<?php include('../webparts/website-left.php'); ?>
	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>Product Groups</h1>
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
						<a href="ecommerce/productgroups.php">Product Groups</a>
					</li>
				</ul>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-shopping-cart"></i> Product Groups</h3>
							<div class="actions">
								<a href="products/productgroup-edit.php" class="btn btn-mini" rel="tooltip" title="New product Group"><i class="icon-plus"></i></a>
							</div>
						</div>
						<div class="box-content nopadding">
							<table class="table table-bordered table-striped table-highlight" id="productGroupTable">
								<thead>
									<tr>
										<th>Name</th>
                                        <th>Department</th>
										<th>Description</th>
										<th></th>
									</tr>
								</thead>
								<tbody id="productGroupBody">
									<?php
									$tableLength = count($attrGroups);
									for ($i=0;$i<$tableLength;++$i) {
									?>
									<tr>
										<td width="50%"><a href="<?php $patchworks->pwRoot; ?>products/productgroup-edit.php?atr_id=<?php echo $attrGroups[$i]['atr_id']; ?>"><?php echo $attrGroups[$i]['atrnam']; ?> <?php if ($attrGroups[$i]['sta_id'] == 1) { echo '<div class="badge badge-important">INACTIVE</div>';} ?></a></td>
                                        <td width="50%"><?php echo $attrGroups[$i]['subnam']; ?></td>
                                        <td width="50%"><?php echo $attrGroups[$i]['atrdsc']; ?></td>
                                        <td><a href="#" class="sortOrder" data-atr_id="<?php echo $attrGroups[$i]['atr_id'] ?>"><i class="icon-reorder"></i></a></td>
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
