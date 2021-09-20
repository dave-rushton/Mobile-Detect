<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("../system/classes/places.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpPla = new PlaDAO();

$editVenueID = (isset($_GET['pla_id']) && is_numeric($_GET['pla_id'])) ? $_GET['pla_id'] : NULL;
$venueRec = NULL;
if (!is_null($editVenueID)) $venueRec = $TmpPla->select($editVenueID, NULL, NULL, NULL, NULL, true);

?>
<!doctype html>
<html>
<head>
<title>Venue :<?php echo($venueRec) ? $venueRec->planam : 'New Venue'; ?></title>
<?php include('../webparts/headdata.php'); ?>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDjZSf7lI4D80NIwFMozDDABq-tSkGgKIs&sensor=false"></script>
<script src="js/plugins/gmap/gmap3.min.js"></script>
<script src="js/plugins/gmap/gmap3-menu.js"></script>
<script src="js/plugins/datatable/jquery.dataTables.min.js"></script>
<script src="events/js/venue-edit.js"></script>
</head>
<?php include('../webparts/navigation.php'); ?>
<body>
<div class="container-fluid" id="content">
	<?php include('../webparts/website-left.php'); ?>
	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>Venue : <?php echo($venueRec) ? $venueRec->planam : 'New Venue'; ?></h1>
				</div>
				<div class="pull-right">
					<?php include('../webparts/index-info.php'); ?>
				</div>
			</div>
			<div class="breadcrumbs">
				<ul>
					<li> <a href="index.php">Dashboard</a> <i class="icon-angle-right"></i> </li>
					<li> <a href="events/venues.php">Venues</a> <i class="icon-angle-right"></i> </li>
					<li> <a><?php echo($venueRec) ? $venueRec->planam : 'New Venue'; ?></a> </li>
				</ul>
			</div>
			<div class="row-fluid">
				<form action="system/places_script.php" id="venueForm" class="form-horizontal form-bordered" data-returnurl="events/venues.php">
					<div class="span5">
						<div class="box box-color box-bordered">
							<div class="box-title">
								<h3> <i class="icon-shopping-cart"></i> Venue</h3>
								<div class="actions">
									<a href="#" id="updateVenueBtn" class="btn btn-mini" rel="tooltip" title="Update"><i class="icon-save"></i></a> <a href="#" id="deleteVenueBtn" class="btn btn-mini" rel="tooltip" title="Delete"><i class="icon-trash"></i></a>
								</div>
							</div>
							<div class="box-content nopadding">
								<input type="hidden" name="pla_id" id="id" value="<?php echo($venueRec) ? $venueRec->pla_id : '0'; ?>">
								<input type="hidden" name="tblnam" value="VENUE">
								<input type="hidden" name="tbl_id" value="<?php echo($venueRec) ? $venueRec->pla_id : '0'; ?>">
								<div class="control-group">
									<label class="control-label">Venue Name</label>
									<div class="controls">
										<input type="text" class="input-large" name="comnam" value="<?php echo ($venueRec) ? $venueRec->comnam : ''; ?>">
									</div>
								</div>
								<div class="control-group" style="display: none">
									<label class="control-label">Venue Name</label>
									<div class="controls">
										<input type="text" class="input-large" name="planam" value="<?php echo($venueRec) ? $venueRec->planam : ''; ?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Email Address</label>
									<div class="controls">
										<input type="text" class="input-large" name="plaema" value="<?php echo($venueRec) ? $venueRec->plaema : ''; ?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="password">Telephone</label>
									<div class="controls">
										<input type="text" class="input-large" name="platel" value="<?php echo($venueRec) ? $venueRec->platel : ''; ?>">
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
									<label class="control-label">Status</label>
									<div class="controls">
										<label class="radio">
											<input type="radio" name="sta_id" value="0" <?php echo(!$venueRec || ($venueRec && $venueRec->sta_id == 0)) ? 'checked' : ''; ?>>
											Active</label>
										<label class="radio">
											<input type="radio" name="sta_id" value="1" <?php echo($venueRec && $venueRec->sta_id == 1) ? 'checked' : ''; ?>>
											In-Active </label>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Capacity</label>
									<div class="controls">
										<input type="text" class="input-large" name="rooms" value="<?php echo($venueRec) ? $venueRec->rooms : '1'; ?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Colour</label>
									<div class="controls">
										<select name="placol">
											<option value="#e51400" <?php echo($venueRec && $venueRec->placol == '#e51400') ? 'selected' : ''; ?>>Red</option>
											<option value="#f8a31f" <?php echo($venueRec && $venueRec->placol == '#f8a31f') ? 'selected' : ''; ?>>Orange</option>
											<option value="#393" <?php echo($venueRec && $venueRec->placol == '#393') ? 'selected' : ''; ?>>Green</option>
											<option value="#a05000" <?php echo($venueRec && $venueRec->placol == '#a05000') ? 'selected' : ''; ?>>Brown</option>
											<option value="#368ee0" <?php echo($venueRec && $venueRec->placol == '#368ee0') ? 'selected' : ''; ?>>Blue</option>
											<option value="#8cbf26" <?php echo($venueRec && $venueRec->placol == '#8cbf26') ? 'selected' : ''; ?>>Lime</option>
											<option value="#00aba9" <?php echo($venueRec && $venueRec->placol == '#00aba9') ? 'selected' : ''; ?>>Teal</option>
											<option value="#ff0097" <?php echo($venueRec && $venueRec->placol == '#ff0097') ? 'selected' : ''; ?>>Purple</option>
											<option value="#e671b8" <?php echo($venueRec && $venueRec->placol == '#e671b8') ? 'selected' : ''; ?>>Pink</option>
											<option value="#a200ff" <?php echo($venueRec && $venueRec->placol == '#a200ff') ? 'selected' : ''; ?>>Magenta</option>
											<option value="#333" <?php echo($venueRec && $venueRec->placol == '#333') ? 'selected' : ''; ?>>Grey</option>
											<option value="#204e81" <?php echo($venueRec && $venueRec->placol == '#204e81') ? 'selected' : ''; ?>>Dark Blue</option>
											<option value="#e63a3a" <?php echo($venueRec && $venueRec->placol == '#e63a3a') ? 'selected' : ''; ?>>Light Red</option>
											<option value="#666" <?php echo($venueRec && $venueRec->placol == '#666') ? 'selected' : ''; ?>>Light Grey</option>
											<option value="#2c5e7b" <?php echo($venueRec && $venueRec->placol == '#2c5e7b') ? 'selected' : ''; ?>>Sat Blue</option>
											<option value="#56af45" <?php echo($venueRec && $venueRec->placol == '#56af45') ? 'selected' : ''; ?>>Sat Green</option>
										</select>
									</div>
								</div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary"><i class="icon-save"></i> Update </button>
                                </div>

							</div>
						</div>
						<div class="box box-color box-bordered green">
							<div class="box-title">
								<h3>
									<i class="icon-pushpin"></i> Address</h3>
								<div class="actions">
									<a href="#" class="btn btn-mini" id="adrControl"><i class="icon-angle-down"></i></a>
									<!--<a href="ecommerce/events-edit.php" class="btn btn-mini" rel="tooltip" title="New Event"><i class="icon-file"></i></a>-->
								</div>
							</div>
							<div class="box-content nopadding" id="adrInputs" style="display: none;">
								<div class="control-group">
									<label class="control-label">Address</label>
									<div class="controls">
										<input type="text" class="input-block-level input-margin-bottom" name="adr1" value="<?php echo($venueRec) ? $venueRec->adr1 : ''; ?>">
										<input type="text" class="input-block-level input-margin-bottom" name="adr2" value="<?php echo($venueRec) ? $venueRec->adr2 : ''; ?>">
										<input type="text" class="input-block-level input-margin-bottom" name="adr3" value="<?php echo($venueRec) ? $venueRec->adr3 : ''; ?>">
										<input type="text" class="input-block-level input-margin-bottom" name="adr4" value="<?php echo($venueRec) ? $venueRec->adr4 : ''; ?>">
										<input type="text" class="input-block-level" name="ctynam" value="<?php echo($venueRec) ? $venueRec->ctynam : ''; ?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Postcode</label>
									<div class="controls">
										<div class="input-append">
											<input type="text" class="input-large" name="pstcod" value="<?php echo($venueRec) ? $venueRec->pstcod : ''; ?>">
											<button id="geoLocate" class="btn"><i class="icon-map-marker"></i></button>
										</div>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Google Data</label>
									<div class="controls">
										<input type="text" class="input-large input-margin-bottom" name="goolat" id="GooLat" value="<?php echo($venueRec) ? $venueRec->goolat : ''; ?>">
										<input type="text" class="input-large input-margin-bottom" name="goolng" id="GooLng" value="<?php echo($venueRec) ? $venueRec->goolng : ''; ?>">
									</div>
								</div>
							</div>
							<div class="box-content nopadding">
								
								<div id="map_canvas" style="height: 400px;">
								</div>
								
							</div>
						</div>
					</div>
					<div class="span7">

					</div>
				</form>
			</div>
		</div>
	</div>
</div>
</body>
</html>
