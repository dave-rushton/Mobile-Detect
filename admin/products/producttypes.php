<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("classes/product_types.cls.php");


$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpPrt = new PrtDAO();
$productTypes = $TmpPrt->select(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, false);

?>
<!doctype html>
<html>
<head>
<title>Product Types</title>
<?php include('../webparts/headdata.php'); ?>


</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
	<?php include('../webparts/website-left.php'); ?>
	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>Product Types</h1>
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
						<a href="products/dashboard.php">Products Dashboard</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a href="products/product_types.php">Product Types</a>
					</li>
				</ul>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-sitemap"></i> Product Types</h3>
							<div class="actions">
								<a href="products/producttype-edit.php" class="btn btn-mini" rel="tooltip" title="New Product Type"><i class="icon-plus"></i></a>
							</div>
						</div>
						<div class="box-content nopadding">
							<table class="table table-bordered table-striped table-highlight" id="productTypeTable">
							<thead>
								<tr>
									<th>Product Type</th>
									<th width="50"></th>
								</tr>
							</thead>
							<tbody id="productTypeBody">
								<?php
								$tableLength = count($productTypes);
								for ($i=0;$i<$tableLength;++$i) {
								?>
								<tr>
									<td width="100%;"><a href="products/producttype-edit.php?prt_id=<?php echo $productTypes[$i]['prt_id'] ?>"><?php echo $productTypes[$i]['prtnam']; ?></a></td>
									<td><a href="#" class="sortOrder" data-sub_id="<?php echo $productTypes[$i]['prt_id'] ?>"><i class="icon-reorder"></i></a></td>
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
