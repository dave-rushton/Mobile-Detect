<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("../system/classes/places.cls.php");
require_once("../system/classes/people.cls.php");
require_once("../events/classes/bookings.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpPla = new PlaDAO();
$venues = $TmpPla->select(NULL, 'VENUE', NULL, NULL, 0, false);

$events = $TmpPla->select(NULL, 'EVT', NULL, NULL, 0, false);

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

<script src="events/js/calendar.js"></script>

</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
	<?php include('../webparts/website-left.php'); ?>
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

			<div id="calendarScreen">
				<div class="row-fluid">
					<div class="span12">
						<div class="box">
							<div class="box-title">
								<h3>
									<i class="icon-calendar"></i> Calendar</h3>
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
							<form action="events/bookings_script.php" id="bookingForm" class="form-horizontal form-bordered">
								<input type="hidden" name="boo_id" value="0">
								<input type="hidden" name="tblnam" value="EVENT">
								<input type="hidden" name="refnam" value="EVT">
								<input type="hidden" name="begdat" value="">
								<input type="hidden" name="enddat" value="">
                                <div class="control-group">
                                    <label class="control-label">Event<small>select event</small></label>
                                    <div class="controls">
                                        <select data-placeholder="Select an employee..." name="tbl_id" class="input-block-level">
                                            <option value="0">N/A</option>
                                            <?php
                                            $tableLength = count($events);
                                            for ($i=0;$i<$tableLength;++$i) {
                                                ?>
                                                <option value="<?php echo $events[$i]['pla_id']; ?>"><?php echo $events[$i]['planam']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
								<div class="control-group">
									<label class="control-label">Venue<small>select active project</small></label>
									<div class="controls">
										<select data-placeholder="Select a project..." name="ref_id" class="input-block-level">
											<option value="0">N/A</option>
											<?php
											$tableLength = count($venues);
											for ($i=0;$i<$tableLength;++$i) {
											?>
											<option data-placol="<?php echo $venues[$i]['placol']; ?>" value="<?php echo $venues[$i]['pla_id']; ?>"><?php echo $venues[$i]['planam']; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								
								<?php //include('../ecommerce/product_search_inc.php'); ?>
									
								
								<div class="control-group hide">
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

                                <div class="hide">
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

                                </div>

								<div class="control-group hide">
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

<input type="hidden" id="webRootUrl" value="<?php echo $patchworks->pwRoot; ?>" />

</body>
</html>