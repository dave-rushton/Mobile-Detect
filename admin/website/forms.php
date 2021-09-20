<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("../attributes/classes/attrgroups.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpAtr = new AtrDAO();
$attrGroups = $TmpAtr->select(NULL, 'FORM');

?>
<!doctype html>
<html>
<head>
<title>Form Listing</title>
<?php include('../webparts/headdata.php'); ?>
<script src="website/js/forms.js"></script>
</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
	<?php include('../webparts/website-left.php'); ?>
	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>Website Forms</h1>
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
						<a>Website</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a href="website/forms.php">Forms</a>
					</li>
				</ul>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-comments"></i> Website Forms</h3>
							<div class="actions">
								<a href="website/forms-edit.php" class="btn btn-mini" rel="tooltip" title="New Form"><i class="icon-plus"></i></a>
							</div>
						</div>
						<div class="box-content nopadding">
							<table class="table table-bordered table-striped table-highlight" id="formsTable">
							<thead>
								<tr>
									<th>Name</th>
									<th></th>
								</tr>
							</thead>
							<tbody id="formsBody">
								<?php

								$tableLength = count($attrGroups);
								for ($i=0;$i<$tableLength;++$i) {
								?>
								<tr>
									<td><a href="<?php $patchworks->pwRoot; ?>website/forms-edit.php?atr_id=<?php echo $attrGroups[$i]['atr_id']; ?>"><?php echo $attrGroups[$i]['atrnam']; ?></a></td>
									<td>
									<a href="website/webcontacts.php?atr_id=<?php echo $attrGroups[$i]['atr_id']; ?>" class="btn btn-success btn-mini" rel="tooltip" title="View Replies"><i class="icon-comments"></i></a>
									<a href="#" class="btn btn-danger btn-mini deleteFormBtn" data-atr_id="<?php echo $attrGroups[$i]['atr_id']; ?>" rel="tooltip" title="Delete"><i class="icon-trash"></i></a>
									</td>
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
