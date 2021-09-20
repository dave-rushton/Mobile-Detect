<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("../system/classes/people.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpPpl = new PplDAO();

$editEmployeeID = (isset($_GET['ppl_id']) && is_numeric($_GET['ppl_id'])) ? $_GET['ppl_id'] : NULL;
$peopleRec = NULL;
if (!is_null($editEmployeeID)) $peopleRec = $TmpPpl->select($editEmployeeID, NULL, NULL, NULL, true);

?>
<!doctype html>
<html>
<head>
<title>Employee : <?php echo($peopleRec) ? $peopleRec->pplnam : 'New Employee'; ?></title>
<?php include('../webparts/headdata.php'); ?>
<script src="projects/js/employee-edit.js"></script>
</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-orange">
<div class="container-fluid" id="content">

	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>Employee : <?php echo($peopleRec) ? $peopleRec->pplnam : 'New Employee'; ?></h1>
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
						<a href="projects/dashboard.php">Projects</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a href="projects/employees.php">Employees</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a><?php echo($peopleRec) ? $peopleRec->pplnam : 'New Employee'; ?></a>
					</li>
				</ul>
			</div>
			<div class="row-fluid">
				<form action="system/people_script.php" id="employeeForm" class="form-horizontal form-bordered" data-returnurl="projects/employees.php">
					<div class="span6">
						<div class="box box-color box-bordered">
							<div class="box-title">
								<h3>
									<i class="icon-shopping-cart"></i> Employee</h3>
								<div class="actions">
									<a href="#" id="updateEmployeeBtn" class="btn btn-mini" rel="tooltip" title="Update"><i class="icon-save"></i></a>
									<a href="#" id="deleteEmployeeBtn" class="btn btn-mini" rel="tooltip" title="Delete"><i class="icon-trash"></i></a>
								</div>
							</div>
							<div class="box-content nopadding">
								<input type="hidden" name="ppl_id" id="id" value="<?php echo($peopleRec) ? $peopleRec->ppl_id : '0'; ?>">
								<input type="hidden" name="tblnam" value="EMP">
								<input type="hidden" name="tbl_id" value="0">
								<div class="control-group hide">
									<label class="control-label">Company Name<small>employee company name</small></label>
									<div class="controls">
										<input type="text" class="input-large" name="comnam" value="<?php echo ($peopleRec) ? $peopleRec->pplnam : ''; ?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Employee Name<small>identifying name</small></label>
									<div class="controls">
										<input type="text" class="input-large" name="pplnam" value="<?php echo($peopleRec) ? $peopleRec->pplnam : ''; ?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Email Address<small>receiving email address</small></label>
									<div class="controls">
										<input type="text" class="input-large" name="pplema" value="<?php echo($peopleRec) ? $peopleRec->pplema : ''; ?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="password">Telephone<small>employee phone number</small></label>
									<div class="controls">
										<input type="text" class="input-large" name="ppltel" value="<?php echo($peopleRec) ? $peopleRec->ppltel : ''; ?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Password</label>
									<div class="controls">
										<input type="text" class="input-large" name="paswrd"/>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="confirm">Confirm Password</label>
									<div class="controls">
										<input type="text" class="input-large" name="pascnf" id="PasCnf">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Status<small>employee status</small></label>
									<div class="controls">
										<label class="radio">
											<input type="radio" name="sta_id" value="0" <?php echo(!$peopleRec || ($peopleRec && $peopleRec->sta_id == 0)) ? 'checked' : ''; ?>>
											Active</label>
										<label class="radio">
											<input type="radio" name="sta_id" value="1" <?php echo($peopleRec && $peopleRec->sta_id == 1) ? 'checked' : ''; ?>>
											In-Active </label>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="span6">
						<div class="box box-color box-bordered hide">
							<div class="box-title">
								<h3>
									<i class="icon-pushpin"></i> Address</h3>
								<div class="actions">
									<!--<a href="ecommerce/customers-edit.php" class="btn btn-mini" rel="tooltip" title="New Customer"><i class="icon-file"></i></a>-->
								</div>
							</div>
							<div class="box-content">
								<div class="control-group">
									<label class="control-label">Address</label>
									<div class="controls">
										<input type="text" class="input-large input-margin-bottom" name="adr1" value="<?php echo($peopleRec) ? $peopleRec->adr1 : ''; ?>">
										<input type="text" class="input-large input-margin-bottom" name="adr2" value="<?php echo($peopleRec) ? $peopleRec->adr2 : ''; ?>">
										<input type="text" class="input-large input-margin-bottom" name="adr3" value="<?php echo($peopleRec) ? $peopleRec->adr3 : ''; ?>">
										<input type="text" class="input-large input-margin-bottom" name="adr4" value="<?php echo($peopleRec) ? $peopleRec->adr4 : ''; ?>">
										<input type="text" class="input-large" name="ctynam" value="<?php echo($peopleRec) ? $peopleRec->ctynam : ''; ?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Postcode</label>
									<div class="controls">
										<div class="input-append">
											<input type="text" class="input-large" name="pstcod" value="<?php echo($peopleRec) ? $peopleRec->pstcod : ''; ?>">
											<button id="geoLocate" class="btn"><i class="icon-map-marker"></i></button>
										</div>
									</div>
								</div>
								<div class="control-group">
									<div id="map_canvas" style="height: 200px;">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Google Data</label>
									<div class="controls">
										<input type="text" class="input-large input-margin-bottom" name="goolat" id="GooLat" value="<?php echo($peopleRec) ? $peopleRec->goolat : ''; ?>">
										<input type="text" class="input-large input-margin-bottom" name="goolng" id="GooLng" value="<?php echo($peopleRec) ? $peopleRec->goolng : ''; ?>">
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
			
			
			
		</div>
	</div>
</div>
</body>
</html>
