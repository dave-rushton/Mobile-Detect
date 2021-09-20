<?php 

require_once('../../config/config.php');
require_once('../patchworks.php');

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

?>
<!doctype html>
<html>
<head>
<title>Category Listing</title>
<?php include('../webparts/headdata.php'); ?>
<script src="system/js/categories.js"></script>
</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
	<?php include('../webparts/system-left.php'); ?>
	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>Categories</h1>
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
						<a>System</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a href="users.php">Categories</a>
					</li>
				</ul>
			</div>
			<div class="row-fluid">
				<div class="span6">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-bookmark"></i> Categories</h3>
							<div class="actions">
								<form action="system/categories_script.php" id="quickCreateCategory" class="form-horizontal form-validate" style="margin:0;">
									<div class="control-group" style="margin:0;">
										<div class="controls">
											<div class="input-append">
												<input type="text" placeholder="Create Category" data-rule-required="true" data-rule-minlength="2" name="catnam" class="input-medium">
												<a href="#" class="add-on" id="createCategoryBtn"><i class="icon icon-plus"></i></a>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
						<div class="box-content nopadding">
							<table class="table table-bordered table-striped" id="categoriesTable">
								<thead>
									<tr>
										<th>Table</th>
										<th>Name</th>
										<th width="30"></th>
									</tr>
								</thead>
								<tbody id="categoriesBody">
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="span6">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-bookmark-empty"></i> Sub Categories</h3>
							<div class="actions">
								<form action="system/subcategories_script.php" id="quickCreateSubCategory" class="form-horizontal form-validate" style="margin:0;">
									<input type="hidden" name="sub_id" value="0" />
									<input type="hidden" name="cat_id" />
									<div class="control-group" style="margin:0;">
										<div class="controls" style="margin: 0;">
											<div class="input-append">
												<input type="text" placeholder="Sub Category" data-rule-required="true" data-rule-minlength="2" name="subnam" class="input-medium">
												<a href="#" class="add-on" id="createSubCategoryBtn"><i class="icon icon-plus"></i></a>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
						<div class="box-content nopadding">
							<table class="table table-bordered table-striped" id="subCategoriesTable">
								<thead>
									<tr>
										<th>Name</th>
										<th width="30"></th>
									</tr>
								</thead>
								<tbody id="subCategoriesBody">
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
