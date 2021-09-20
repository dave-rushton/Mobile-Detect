<?php
require_once("../../config/config.php");
require_once("../patchworks.php");
require_once("../system/classes/users.cls.php");
require_once("../projects/classes/tasks.cls.php");

require_once("../system/classes/places.cls.php");
//require_once("../people/classes/people.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpBtk = new BtkDAO();
$tasks = $TmpBtk->select(NULL, NULL, NULL, NULL, false);

$TmpPla = new PlaDAO();
$places = $TmpPla->select(NULL, 'VEN', NULL, NULL, 0, false);

//$TmpPpl = new PplDAO();
//$people = $TmpPpl->select(NULL, 'EMP', NULL, NULL, false);

$minutes = 60;
$hours = $minutes * 60;
$days = $hours * 24;
$years = $days * 365;

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>PatchWorks | Bookings Calendar</title>
<?php include('../includes/pw.headdata.php'); ?>
<link href="./js/plugins/fullcalendar/fullcalendar.css" rel="stylesheet">
<link href="./css/pages/calendar.css" rel="stylesheet">
<link href="./js/plugins/msgGrowl/css/msgGrowl.css" rel="stylesheet">
<link href="./js/plugins/msgAlert/css/msgAlert.css" rel="stylesheet">
<script src="./js/plugins/fullcalendar/fullcalendar.min.js"></script>
<script src="./js/plugins/msgGrowl/js/msgGrowl.js"></script>
<script src="./js/plugins/msgAlert/js/msgAlert.js"></script>
<script src="./js/plugins/validate/jquery.validate.js"></script>
<script src="./js/jquery.timebox.js"></script>
<script src="./projects/js/bookings.calendar.js"></script>

<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

</head>

<body class="theme-red">
<?php include('../includes/pw.header.php'); ?>
<?php include('../includes/users.menu.php'); ?>
<div id="content">
	<div class="container">
		<div id="page-title" class="clearfix">
			<ul class="breadcrumb">
				<li>
					<a href="./">Home</a>
					<span class="divider">/</span>
				</li>
				<li>
					<a href="./bookings.php">Bookings</a>
					<span class="divider">/</span>
				</li>
				<li class="active">Calendar</li>
			</ul>
		</div>
		<!-- /.page-title -->
		
		<div class="row">
			<div class="span9">
				<div class="widget widget-fullcalendar">
					<div class="widget-header">
						<h3>
							<i class="icon-calendar"></i> Bookings Calendar </h3>
					</div>
					<!-- /.widget-header -->
					
					<div class="widget-content">
						<div id="calendar-holder">
						</div>
						<!-- /#calendar-holder -->
						
					</div>
					<!-- /widget-content -->
					
				</div>
				<!-- /widget -->
				
			</div>
			<!-- /.span8 -->
			
			<div class="span3">
				
				<div class="widget">
					<div class="widget-header">
						<h3>
							<i class="icon-circle-arrow-right"></i> Venue Selection </h3>
					</div>
					<div class="widget-content">
						<form id="bookingSearchForm" class="form-vertical" novalidate="novalidate">
							<fieldset>
							
								<div class="control-group">
									<label class="control-label">Venue</label>
									<div class="controls">
										<select id="placeSelect">
											<option value="">Show all</option>
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
											<option value="">Show all</option>
											<?php
											$tableLength = count($people);
											for ($i=0;$i<$tableLength;++$i) {
											?>
											<option value="<?php echo $people[$i]['ppl_id']; ?>"><?php echo $people[$i]['pplnam']; ?></option>
											<?php
											}
											?>
										</select>
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Status</label>
									<div class="controls">
										<select class="input-large" name="srcsta_id" id="SrcSta_ID">
											<option value="0">Active</option>
											<option value="1">InActive</option>
										</select>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
				<!-- /.widget -->
				
				<div class="widget">
					<div class="widget-header">
						<h3>
							<i class="icon-tasks"></i> Outstanding Tasks </h3>
					</div>
					<div class="widget-content">
						<ul id="taskList">
						<?php
						$tableLength = count($tasks);
						for ($i=0;$i<$tableLength;++$i) {
						?>
							<li class="external-event" data-btk_id="<?php echo $tasks[$i]['btk_id']; ?>" data-btkdur="<?php echo $tasks[$i]['btkdur']; ?>"><?php echo $tasks[$i]['btkttl']; ?></li>
						<?php
						}
						?>
						</ul>
					</div>
				</div>
			
				
				
				<div class="widget widget-minicalendar">
					<div class="widget-header">
						<h3>
							<i class="icon-external-link"></i> Quick Event</h3>
						<div class="widget-actions">
							<a href="#createBookingModal" data-toggle="modal" class="btn btn-small" id="addBookingLink"><i class="icon-plus"></i> Add Booking</a>
						</div>
						<!-- /.widget-actions -->
					</div>
					<!-- /.widget-header -->
					
					<div class="widget-content">
						<div id="datepicker-inline">
						</div>
						<!-- /#datepicker-inline -->
					</div>
					<!-- /.widget-content -->
					
				</div>
				<!-- /.widget -->
				
			</div>
			<!-- /.span4 -->
			
		</div>
		<!-- /.row -->
		
	</div>
	<!-- /.container -->
	
</div>
<!-- /#content -->

<div class="modal fade" id="createBookingModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h3>Booking</h3>
	</div>
	<form action="<?php echo $patchworks->pwRoot; ?>projects/bookings_script.php" id="bookingForm" class="form-horizontal" novalidate="novalidate" data-returnurl="projects/bookings.calendar.php">
		<input type="hidden" name="boo_id" id="Boo_ID" value="0" />
		<div class="modal-body">
			<fieldset>
				
				<div class="hide">
				
				<input type="hidden" class="input-large" name="reftbl" id="RefTbl" value="VEN">
				<input type="hidden" class="input-large" name="btk_id" id="Btk_ID" value="0">
				<input type="hidden" class="input-large" name="ref_id" id="Ref_ID" value="0">
				
				<div class="control-group">
					<label class="control-label">Table Name</label>
					<div class="controls">
						<input type="text" class="input-large" name="tblnam" id="TblNam" value="VEN">
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
							$tableLength = count($people);
							for ($i=0;$i<$tableLength;++$i) {
							?>
							<option value="<?php echo $people[$i]['ppl_id']; ?>"><?php echo $people[$i]['pplnam']; ?></option>
							<?php
							}
							?>
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
				
				<div class="control-group">
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
			<a href="#" class="btn" data-dismiss="modal"><i class="icon-remove" id="cancelBookingLink"></i> Cancel</a>
			<button type="submit" class="btn btn-primary" name="action" value="update"  id="createBookingBtn"><i class="icon-save"></i> Create</button>
		</div>
	</form>
</div>
<input type="hidden" id="webRootUrl" value="<?php echo $patchworks->pwRoot; ?>" />
<?php include('../includes/pw.footer.php'); ?>
</body>
</html>
