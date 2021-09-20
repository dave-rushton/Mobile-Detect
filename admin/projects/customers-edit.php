<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("../system/classes/places.cls.php");
require_once("../system/classes/people.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpPla = new PlaDAO();
$editPlaceID = (isset($_GET['pla_id']) && is_numeric($_GET['pla_id'])) ? $_GET['pla_id'] : NULL;
$placeRec = NULL;
if (!is_null($editPlaceID)) $placeRec = $TmpPla->select($editPlaceID, NULL, NULL, NULL, NULL, true); 

if (!is_null($editPlaceID)) $projects = $TmpPla->select(NULL, 'PROJECT', $editPlaceID, NULL, 0, false); 

$TmpPpl = new PplDAO();
$employee = NULL;
if (!is_null($editPlaceID)) $employee = $TmpPpl->select(NULL, 'CONTACT', $editPlaceID, NULL, false); 


?>
<!doctype html>
<html>
<head>
<title>Client : <?php echo($placeRec) ? $placeRec->planam : 'New Client'; ?></title>
<?php include('../webparts/headdata.php'); ?>

<script src="js/plugins/flot/jquery.flot.min.js"></script>
<script src="js/plugins/flot/jquery.flot.resize.min.js"></script>
<script src="js/system.date.js"></script>

<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDjZSf7lI4D80NIwFMozDDABq-tSkGgKIs&sensor=false"></script>
<script src="js/plugins/gmap/gmap3.min.js"></script>
<script src="js/plugins/gmap/gmap3-menu.js"></script>
<script src="projects/js/customers-edit.js"></script>
</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-orange">
<div class="container-fluid" id="content">

	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>Client : <?php echo($placeRec) ? $placeRec->planam : 'New Customer'; ?></h1>
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
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a><?php echo($placeRec) ? $placeRec->planam : 'New Client'; ?></a>
					</li>
				</ul>
			</div>
			<div class="row-fluid">
				<form action="system/places_script.php" id="customerForm" class="form-horizontal form-bordered" data-returnurl="projects/customers.php">
					<div class="span6">
						<div class="box box-color box-bordered">
							<div class="box-title">
								<h3>
									<i class="icon-user"></i> Client</h3>
								<div class="actions">
									<a href="#" id="updateCustomerBtn" class="btn btn-mini" rel="tooltip" title="Update"><i class="icon-save"></i></a>
									<a href="#" id="deleteCustomerBtn" class="btn btn-mini" rel="tooltip" title="Delete"><i class="icon-trash"></i></a>
								</div>
							</div>
							<div class="box-content nopadding">
								<input type="hidden" name="pla_id" id="id" value="<?php echo($placeRec) ? $placeRec->pla_id : '0'; ?>">
								<input type="hidden" name="tblnam" value="CUS">
								<input type="hidden" name="tbl_id" value="0">
								<div class="control-group">
									<label class="control-label">Company Name<small>invoice name</small></label>
									<div class="controls">
										<input type="text" class="input-large" name="comnam" value="<?php echo($placeRec) ? $placeRec->comnam : ''; ?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Client Name<small>identify name</small></label>
									<div class="controls">
										<input type="text" class="input-large" name="planam" value="<?php echo($placeRec) ? $placeRec->planam : ''; ?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Email Address<small>email correspondance</small></label>
									<div class="controls">
										<input type="text" class="input-large" name="plaema" value="<?php echo($placeRec) ? $placeRec->plaema : ''; ?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="password">Telephone<small>telephone correspondance</small></label>
									<div class="controls">
										<input type="text" class="input-large" name="platel" value="<?php echo($placeRec) ? $placeRec->platel : ''; ?>">
									</div>
								</div>
								<div class="control-group hide">
									<label class="control-label">Password</label>
									<div class="controls">
										<input type="text" class="input-large" name="paswrd"/>
									</div>
								</div>
								<div class="control-group hide">
									<label class="control-label" for="confirm">Confirm Password</label>
									<div class="controls">
										<input type="text" class="input-large" name="pascnf" id="PasCnf">
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Colour<small>calendar colour</small></label>
									<div class="controls">
										<select name="placol" class="input-block-level">
											<option value="#e51400" <?php echo($placeRec && $placeRec->placol == '#e51400') ? 'selected' : ''; ?>>Red</option>
											<option value="#f8a31f" <?php echo($placeRec && $placeRec->placol == '#f8a31f') ? 'selected' : ''; ?>>Orange</option>
											<option value="#393" <?php echo($placeRec && $placeRec->placol == '#393') ? 'selected' : ''; ?>>Green</option>
											<option value="#a05000" <?php echo($placeRec && $placeRec->placol == '#a05000') ? 'selected' : ''; ?>>Brown</option>
											<option value="#368ee0" <?php echo($placeRec && $placeRec->placol == '#368ee0') ? 'selected' : ''; ?>>Blue</option>
											<option value="#8cbf26" <?php echo($placeRec && $placeRec->placol == '#8cbf26') ? 'selected' : ''; ?>>Lime</option>
											<option value="#00aba9" <?php echo($placeRec && $placeRec->placol == '#00aba9') ? 'selected' : ''; ?>>Teal</option>
											<option value="#ff0097" <?php echo($placeRec && $placeRec->placol == '#ff0097') ? 'selected' : ''; ?>>Purple</option>
											<option value="#e671b8" <?php echo($placeRec && $placeRec->placol == '#e671b8') ? 'selected' : ''; ?>>Pink</option>
											<option value="#a200ff" <?php echo($placeRec && $placeRec->placol == '#a200ff') ? 'selected' : ''; ?>>Magenta</option>
											<option value="#333" <?php echo($placeRec && $placeRec->placol == '#333') ? 'selected' : ''; ?>>Grey</option>
											<option value="#204e81" <?php echo($placeRec && $placeRec->placol == '#204e81') ? 'selected' : ''; ?>>Dark Blue</option>
											<option value="#e63a3a" <?php echo($placeRec && $placeRec->placol == '#e63a3a') ? 'selected' : ''; ?>>Light Red</option>
											<option value="#666" <?php echo($placeRec && $placeRec->placol == '#666') ? 'selected' : ''; ?>>Light Grey</option>
											<option value="#2c5e7b" <?php echo($placeRec && $placeRec->placol == '#2c5e7b') ? 'selected' : ''; ?>>Sat Blue</option>
											<option value="#56af45" <?php echo($placeRec && $placeRec->placol == '#56af45') ? 'selected' : ''; ?>>Sat Green</option>
										</select>
										
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Client Status</label>
									<div class="controls">
										<label class="radio">
											<input type="radio" name="sta_id" value="0" <?php echo(!$placeRec || ($placeRec && $placeRec->sta_id == 0)) ? 'checked' : ''; ?>>
											Active</label>
										<label class="radio">
											<input type="radio" name="sta_id" value="1" <?php echo($placeRec && $placeRec->sta_id == 1) ? 'checked' : ''; ?>>
											In-Active </label>
									</div>
								</div>
							</div>
						</div>
						
						<div class="box box-color box-bordered green">
							<div class="box-title">
								<h3>
									<i class="icon-pushpin"></i> Address</h3>
								<div class="actions">
									<a href="#" class="btn btn-mini" id="adrControl"><i class="icon-angle-down"></i></a>
									<!--<a href="ecommerce/customers-edit.php" class="btn btn-mini" rel="tooltip" title="New Customer"><i class="icon-file"></i></a>-->
								</div>
							</div>
							<div class="box-content nopadding" id="adrInputs" style="display: none;">
								
								
								
									<div class="control-group">
										<label class="control-label">Address</label>
										<div class="controls">
											<input type="text" class="input-large input-margin-bottom" name="adr1" value="<?php echo($placeRec) ? $placeRec->adr1 : ''; ?>">
											<input type="text" class="input-large input-margin-bottom" name="adr2" value="<?php echo($placeRec) ? $placeRec->adr2 : ''; ?>">
											<input type="text" class="input-large input-margin-bottom" name="adr3" value="<?php echo($placeRec) ? $placeRec->adr3 : ''; ?>">
											<input type="text" class="input-large input-margin-bottom" name="adr4" value="<?php echo($placeRec) ? $placeRec->adr4 : ''; ?>">
											<input type="text" class="input-large" name="ctynam" value="<?php echo($placeRec) ? $placeRec->ctynam : ''; ?>">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Postcode</label>
										<div class="controls">
											<div class="input-append">
												<input type="text" class="input-large" name="pstcod" value="<?php echo($placeRec) ? $placeRec->pstcod : ''; ?>">
												<button id="geoLocate" class="btn"><i class="icon-map-marker"></i></button>
											</div>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Google Data</label>
										<div class="controls">
											<input type="text" class="input-large input-margin-bottom" name="goolat" id="GooLat" value="<?php echo($placeRec) ? $placeRec->goolat : ''; ?>">
											<input type="text" class="input-large input-margin-bottom" name="goolng" id="GooLng" value="<?php echo($placeRec) ? $placeRec->goolng : ''; ?>">
										</div>
									</div>
								
								
								
							</div>
							<div class="box-content nopadding">
								
								<div id="map_canvas" style="height: 400px;">
								</div>
								
							</div>
						</div>
						
					</div>
					<div class="span6">
					
						<div class="box box-color box-bordered teal">
							<div class="box-title">
								<h3>
									<i class="icon-paste"></i> Projects</h3>
								<div class="actions">
									<a href="#" class="btn btn-mini custom-checkbox" id="allProjects">Show All<i class="icon-check-empty"></i></a>
									<a href="projects/project-edit.php?cus_id=<?php echo($placeRec) ? $placeRec->pla_id : '0'; ?>" id="createProjectBtn" class="btn btn-mini" rel="tooltip" title="New Project"><i class="icon-plus-sign"></i></a>
								</div>
							</div>
							<div class="box-content nopadding">
							
								<table class="table table-bordered table-striped table-highlight" id="projectTable">
									<thead>
										<tr>
											<th>Project Name</th>
										</tr>
									</thead>
									<tbody id="projectBody">
										
									</tbody>
								</table>
							
							</div>
						</div>
					
						<div class="box box-color box-bordered darkblue">
							<div class="box-title">
								<h3>
									<i class="icon-group"></i> Contacts</h3>
								<div class="actions">
									<a href="ecommerce/employee-edit.php?cus_id=<?php echo($placeRec) ? $placeRec->pla_id : '0'; ?>" id="createEmployeeBtn" class="btn btn-mini" rel="tooltip" title="New Employee"><i class="icon-plus-sign"></i></a>
								</div>
							</div>
							<div class="box-content nopadding">
							
								<table class="table table-bordered table-striped table-highlight" id="employeeTable">
									<thead>
										<tr>
											<th>Name</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$tableLength = count($employee);
										for ($i=0;$i<$tableLength;++$i) {
										?>
										<tr>
											<td><a href="ecommerce/employee-edit.php?cus_id=<?php echo($placeRec) ? $placeRec->pla_id : '0'; ?>&ppl_id=<?php echo $employee[$i]['ppl_id'] ?>"><?php echo $employee[$i]['pplnam'] ?></a></td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							
							</div>
						</div>
						
						<div class="box box-bordered box-color lime">
							<div class="box-title">
								<h3> <i class="icon-calendar"></i> Weekly Dependancy </h3>
								<div class="actions">
									
								</div>
							</div>
							<div class="box-content" id="actList">
							
								<div id="lineChart" class="flot medium">
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
