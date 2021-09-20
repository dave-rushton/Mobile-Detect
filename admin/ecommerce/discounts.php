<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("../products/classes/discounts.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpDis = new DisDAO();
$discounts = $TmpDis->select();

?>
<!doctype html>
<html>
<head>
<title>Product Discounts</title>
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
					<h1>Product Discounts</h1>
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
						<a>Products</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a href="products/discounts.php">Product Discounts</a>
					</li>
				</ul>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-gift"></i> Product Discounts</h3>
							<div class="actions">
								<a href="ecommerce/discount-edit.php" class="btn btn-mini" rel="tooltip" title="New Discount"><i class="icon-plus-sign"></i></a>
							</div>
						</div>
						<div class="box-content nopadding">
							<table class="table table-bordered table-striped table-highlight" id="articleCatTable">
							<thead>
								<tr>
									<th>Product Discount Name</th>
                                    <th>Type</th>
                                    <th>Amount</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$tableLength = count($discounts);
								for ($i=0;$i<$tableLength;++$i) {
								?>
								<tr>
									<td><a href="ecommerce/discount-edit.php?dis_id=<?php echo $discounts[$i]['dis_id'] ?>"><?php echo $discounts[$i]['disnam'].' : '.$discounts[$i]['discod'] ?></a></td>
                                    <td><?php echo ($discounts[$i]['pctamt'] == 'A') ? 'Amount' : 'Percentage'; ?></td>
                                    <td><?php echo ($discounts[$i]['pctamt'] == 'A') ? '&pound;'.$discounts[$i]['disamt'] : $discounts[$i]['disamt'].'%'; ?></td>
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
