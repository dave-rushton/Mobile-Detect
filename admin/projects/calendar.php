<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("../system/classes/places.cls.php");
require_once("../system/classes/people.cls.php");
require_once("../projects/classes/bookings.cls.php");
require_once("../projects/classes/tasks.cls.php");
require_once("../system/classes/subcategories.cls.php");

require_once("../products/classes/products.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpBtk = new BtkDAO();
$tasks = $TmpBtk->select(NULL, NULL, NULL, NULL, false);

$TmpPla = new PlaDAO();
$places = $TmpPla->select(NULL, 'PROJECT', NULL, NULL, 0, false);

$TmpPpl = new PplDAO();
$people = $TmpPpl->select(NULL, 'EMP', NULL, NULL, false);

$TmpPrd = new PrdDAO();
$products = $TmpPrd->select(NULL, NULL, NULL, 'PRODUCT', NULL, NULL, false);

$TmpPpl = new PplDAO();
//$employee = $TmpPpl->select(NULL, 'EMP', NULL, NULL, false);

require_once('../system/classes/related.cls.php');
$RelDao = new RelDAO();
$employees = $RelDao->select(NULL, 'USR', NULL, 'EMP', NULL, false);

$TmpSub = new SubDAO();
$subCategories = $TmpSub->selectByTableName('booking-category');

$minutes = 60;
$hours = $minutes * 60;
$days = $hours * 24;
$years = $days * 365;

?>
<!doctype html>
<html>
<head>
<title>Bookings Calendar</title>
<?php include('../webparts/headdata.php'); ?>

<link rel="stylesheet" href="css/plugins/fullcalendar/fullcalendar.css">
<link rel="stylesheet" href="css/plugins/fullcalendar/fullcalendar.print.css" media="print">
<link rel="stylesheet" href="css/plugins/datepicker/datepicker.css">
<link rel="stylesheet" href="css/plugins/colorpicker/colorpicker.css">

<script src="js/plugins/fullcalendar/fullcalendar.min.js"></script>
<script src="js/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="js/plugins/colorpicker/bootstrap-colorpicker.js"></script>

<script src="js/system.date.js"></script>
</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
	<?php include('../webparts/bookings-left.php'); ?>
	<div id="main">
		<div class="container-fluid">
			<div class="page-header hide">
				<div class="pull-left">
					<h1>Calendar</h1>
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
						<a>Bookings</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a href="projects/calendar.php">Calendar</a>
					</li>
				</ul>
			</div>
			<div id="calendarScreen">

				<div class="row-fluid">
					<div class="span4">

						<div class="box">
							<div class="box-title">
								<h3><i class="icon-filter"></i> Filter</h3>
							</div>
							<div class="box-content">

								<?php
								$tableLength = count($employees);
								for ($i=0;$i<$tableLength;++$i) {

									$employee = $TmpPpl->select($employees[$i]['ref_id'], NULL, NULL, NULL, true);

									?>
									<label class="checkbox">
										<input type="checkbox" name="emp_id[]" value="<?php echo $employee->ppl_id ?>" checked>
										<?php echo $employee->pplnam; ?></label>
								<?php } ?>

							</div>
						</div>
					</div>
				</div>

				<div class="row-fluid">
					<div class="span12">

						<div class="box">
							<div class="box-title">
								<h3>
									<i class="icon-calendar"></i> Calendar</h3>
								<!--<div class="actions">
									<a href="projects/bookings-edit.php" class="btn btn-mini" rel="tooltip" title="New Booking"><i class="icon-file"></i></a>
								</div>-->
							</div>
							<div class="box-content nopadding">
								<div class="calendar" id="calendar-holder">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row-fluid hide" id="bookingFormScreen">
				<div class="span12">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-time"></i> Booking Form</h3>
							<div class="actions">
								<a href="#" class="btn btn-mini" rel="tooltip" title="Delete" id="deleteBooking"><i class="icon-trash red-text"></i></a>
								<a href="#" class="btn btn-mini" rel="tooltip" title="Cancel" id="cancelBooking"><i class="icon-remove"></i></a>
								<a href="#" class="btn btn-mini" rel="tooltip" title="Update Booking" id="updateBooking"><i class="icon-save"></i></a>
							</div>
						</div>
						<div class="box-content nopadding">
							<form action="projects/bookings_script.php" id="bookingForm" class="form-horizontal form-bordered">
								<input type="hidden" name="boo_id" value="0">
								<input type="hidden" name="tblnam" value="PROJECT">
								<input type="hidden" name="refnam" value="EMP">
								<input type="hidden" name="begdat" value="">
								<input type="hidden" name="enddat" value="">
								<div class="control-group">
									<label class="control-label">Project<small>select active project</small></label>
									<div class="controls">
										<select data-placeholder="Select a project..." name="tbl_id" class="input-block-level">
											<option value="0">N/A</option>
											<?php
											$tableLength = count($places);
											for ($i=0;$i<$tableLength;++$i) {
											?>
											<option data-placol="<?php echo $places[$i]['placol']; ?>" value="<?php echo $places[$i]['pla_id']; ?>"><?php echo $places[$i]['planam']; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Employee<small>select task owner</small></label>
									<div class="controls">
										<select data-placeholder="Select an employee..." name="ref_id" class="input-block-level">


											<?php
											$tableLength = count($employees);
											for ($i=0;$i<$tableLength;++$i) {

												$employee = $TmpPpl->select($employees[$i]['ref_id'], NULL, NULL, NULL, true);

												?>

												<option value="<?php echo $employee->ppl_id ?>"><?php echo $employee->pplnam; ?></option>

											<?php } ?>

											<option value="0">N/A</option>
										</select>
									</div>
								</div>
								
								<?php //include('../ecommerce/product_search_inc.php'); ?>
									
								
								<div class="control-group">
									<label class="control-label">Product<small>select product</small></label>
									<div class="controls">
										<select name="prd_id" class="input-block-level">
											<option value="0">No Product</option>
											<?php 
											$tableLength = count($products);
											for ($i=0;$i<$tableLength;++$i) { 
											?>
											
											<option value="<?php echo $products[$i]['prd_id']; ?>"><?php echo $products[$i]['prdnam']; ?></option>
											
											<?php } ?>
											
										</select>
									</div>
								</div>
								
								
								<div class="control-group">
									<label class="control-label">Description<small>short description of booking</small></label>
									<div class="controls">
										<textarea class="input-block-level" name="boodsc" rows="6"></textarea>
									</div>
								</div>



                                <div class="control-group">
                                    <label class="control-label">Booking categories
                                        <small>booking tags</small>
                                    </label>

                                    <div class="controls">

                                        <select multiple="multiple" class="input-block-level" name="bootagselect"
                                                id="bootagselect">

                                            <?php
                                            $tableLength = count($subCategories);
                                            for ($i = 0; $i < $tableLength; ++$i) {

                                                $prdTags = array();

                                                //$prdTags = explode(",", $productTypeRec->prttag);

                                                ?>

                                                <option
                                                    value="<?php echo $subCategories[$i]['sub_id'] ?>" <?php echo (isset($productTypeRec) && in_array($subCategories[$i]['sub_id'], $prdTags)) ? 'selected' : ''; ?>><?php echo $subCategories[$i]['subnam'] ?></option>

                                            <?php } ?>

                                        </select>

                                        <input type="hidden" name="botag">

                                    </div>
                                </div>



                                <div class="control-group">
                                    <label class="control-label">Object<small>booking object</small></label>
                                    <div class="controls">
                                        <input type="text" class="input-block-level" name="booobj" value="">
                                    </div>
                                </div>

								<div class="control-group">
									<label class="control-label">All Day<small>block whole day</small></label>
									<div class="controls">
										<label class="checkbox"><input type="checkbox" name="allday" value="1" /></label>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Start Date<small>booking start date</small></label>
									<div class="controls">
										<input type="text" class="input-block-level" name="begdatdsp" value="1">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Start Time<small>booking start time</small></label>
									<div class="controls">
										<input type="text" class="input-block-level" name="begtim" value="">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">End Date<small>booking end date</small></label>
									<div class="controls">
										<input type="text" class="input-block-level" name="enddatdsp" value="">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">End Time<small>booking end time</small></label>
									<div class="controls">
										<input type="text" class="input-block-level" name="endtim" value="">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Status<small>booking status</small></label>
									<div class="controls">
										<select name="sta_id" class="input-block-level">
											<option value="0">New Record</option>
											<optgroup label="Schedule">
											<option value="1">In Progress</option>
											<option value="2">Complete</option>
											<option value="3">With Client</option>
											</optgroup>
											<optgroup label="Event">
											<option value="11">Confirmed</option>
											<option value="12">Cancelled</option>
											<option value="13">Denied</option>
											<option value="14">Accepted</option>
											</optgroup>
											<optgroup label="Payment">
											<option value="50">To Invoice</option>
											<option value="51">Invoiced</option>
											<option value="52">Paid</option>
											<option value="53">Rejected</option>
											</optgroup>
										</select>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Colour<small>calendar colour</small></label>
									<div class="controls">
									
										<select name="boocol" class="input-block-level">
											<option value="#e51400">Red</option>
											<option value="#f8a31f">Orange</option>
											<option value="#393">Green</option>
											<option value="#a05000">Brown</option>
											<option value="#368ee0">Blue</option>
											<option value="#8cbf26">Lime</option>
											<option value="#00aba9">Teal</option>
											<option value="#ff0097">Purple</option>
											<option value="#e671b8">Pink</option>
											<option value="#a200ff">Magenta</option>
											<option value="#333">Grey</option>
											<option value="#204e81">Dark Blue</option>
											<option value="#e63a3a">Light Red</option>
											<option value="#666">Light Grey</option>
											<option value="#2c5e7b">Sat Blue</option>
											<option value="#56af45">Sat Green</option>
										</select>
										
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>


<div id="tooltip" class="popover bottom hide" style="width: 200px;">
	<div class="arrow"></div>
	<h3 class="popover-title" id="ttttl">Default popover</h3>
	<div class="popover-content" id="tttxt1">
		
		<dl class="dl-vertical">
			<dt>Client</dt>
			<dd id="popCusNam"></dd>
			<dt>Project</dt>
			<dd id="popProNam"></dd>
			<dt>Description</dt>
			<dd id="popBooDsc"></dd>
			<dt>Duration</dt>
			<dd id="popBooDur"></dd>
			<dt>Product</dt>
			<dd id="popPrdNam"></dd>
			<dt>Price</dt>
			<dd id="popUniPri"></dd>
		</dl>
		
	</div>
</div>

<div class="modal hide fade" id="createBookingModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h3>Booking</h3>
	</div>
	<form action="<?php echo $patchworks->pwRoot; ?>projects/bookings_script.php" id="bookingForm" class="form-horizontal" novalidate data-returnurl="projects/bookings.calendar.php">
		<input type="hidden" name="boo_id" id="Boo_ID" value="0" />
		<div class="modal-body">
			<fieldset>
				
				<div class="hide">
				
				<input type="hidden" class="input-large" name="reftbl" id="RefTbl" value="EMP">
				<input type="hidden" class="input-large" name="btk_id" id="Btk_ID" value="0">
				<input type="hidden" class="input-large" name="ref_id" id="Ref_ID" value="0">
				
				<div class="control-group">
					<label class="control-label">Table Name</label>
					<div class="controls">
						<input type="text" class="input-large" name="tblnam" id="TblNam" value="PROJECT">
					</div>
				</div>
				
				</div>
				
				<div class="control-group">
					<label class="control-label">Venue</label>
					<div class="controls">
						<select name="tbl_id" id="Tbl_ID">
							<?php
							$tableLength = count($places);
							for ($i=0;$i<$tableLength;++$i) {
							?>
							<option value="<?php echo $places[$i]['pla_id']; ?>"><?php echo $places[$i]['planam'].' ('.$places[$i]['comnam'].')'; ?></option>
							<?php
							}
							?>
						</select>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label">Employee</label>
					<div class="controls">
						<select name="ref_id" id="Ref_ID">

							<?php
							$tableLength = count($employees);
							for ($i=0;$i<$tableLength;++$i) {

								$employee = $TmpPpl->select($employees[$i]['ref_id'], NULL, NULL, NULL, true);

								?>

								<option value="<?php echo $employee->ppl_id ?>"><?php echo $employee->pplnam; ?></option>

							<?php } ?>


						</select>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label">Booking Title</label>
					<div class="controls">
						<input type="text" class="input-large" name="boodsc" id="BooDsc" value="">
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label">Booking Date</label>
					<div class="controls">
						<input type="text" class="input-large" name="actdat" id="ActDat" value="">
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label">All Day?</label>
					<div class="controls">
						<label class="checkbox">
							<input type="checkbox" name="allday" value="1" id="allDayCB">
							All Day Booking </label>
					</div>
				</div>
				
				<div id="allDayDiv">
				
					<div class="control-group">
						<label class="control-label">Start</label>
						<div class="controls">
							<input type="text" class="input-large" name="begtim" id="BegTim" value="00:00">
							<input type="hidden" class="input-large" name="begdat" id="BegDat" value="">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">End</label>
						<div class="controls">
							<input type="text" class="input-large" name="endtim" id="EndTim" value="00:00">
							<input type="hidden" class="input-large" name="enddat" id="EndDat">
						</div>
					</div>
				
				</div>
				
				<div class="control-group hide">
					<label class="control-label">Set Reminder</label>
					<div class="controls">
						<select class="input-large" name="remtim" id="RemTim">
							<option value="0">None</option>
							<!--<option value="<?php echo (5*$minutes); ?>">5 Minutes</option>
							<option value="<?php echo (10*$minutes); ?>">10 Minutes</option>-->
							<option value="<?php echo (15*$minutes); ?>">15 Minutes</option>
							<option value="<?php echo (30*$minutes); ?>">30 Minutes</option>
							<option value="<?php echo (1*$hours); ?>">1 Hour</option>
							<option value="<?php echo (2*$hours); ?>">2 Hours</option>
							<option value="<?php echo (3*$hours); ?>">3 Hours</option>
							<option value="<?php echo (4*$hours); ?>">4 Hours</option>
							<option value="<?php echo (5*$hours); ?>">5 Hours</option>
							<option value="<?php echo (6*$hours); ?>">6 Hours</option>
							<option value="<?php echo (7*$hours); ?>">7 Hours</option>
							<option value="<?php echo (8*$hours); ?>">8 Hours</option>
							<option value="<?php echo (1*$days); ?>">1 Day</option>
							<option value="<?php echo (2*$days); ?>">2 Days</option>
							<option value="<?php echo (3*$days); ?>">3 Days</option>
							<option value="<?php echo (4*$days); ?>">4 Days</option>
							<option value="<?php echo (5*$days); ?>">5 Days</option>
							<option value="<?php echo (7*$days); ?>">1 Week</option>
							<option value="<?php echo (14*$days); ?>">2 Weeks</option>
						</select>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label">Status</label>
					<div class="controls">
						<select class="input-large" name="sta_id" id="Sta_ID">
						
							<option value="0">In Progress</option>
							<option value="1">Completed</option>
							<option value="2">Waiting On Someone</option>
							<option value="3">Deferred</option>
							
						</select>
					</div>
				</div>
				
			</fieldset>
		</div>
		<div class="modal-footer">
			<a href="#" class="btn" data-dismiss="modal" id="cancelBookingModal"><i class="icon-remove" id="cancelBookingLink"></i> Cancel</a>
			<button type="submit" class="btn btn-primary" name="action" value="update"  id="createBookingBtn"><i class="icon-save"></i> Create</button>
		</div>
	</form>
</div>
<input type="hidden" id="webRootUrl" value="<?php echo $patchworks->pwRoot; ?>" />

</body>
</html>
<script src="projects/js/calendar.js"></script>