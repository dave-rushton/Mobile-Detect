<?php 

function getStartAndEndDate($week, $year)
{

    $time = strtotime("1 January $year", time());
    $day = date('w', $time);
    $time += ((7*$week)+1-$day)*24*3600;
    $return[0] = date('jS M Y', $time);
    $time += 6*24*3600;
    $return[1] = date('jS M Y', $time);
    return $return;
}

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("../system/classes/places.cls.php");
require_once("../system/classes/people.cls.php");
require_once("../projects/classes/bookings.cls.php");
require_once("../projects/classes/tasks.cls.php");
require_once("../system/classes/statuscodes.cls.php");
require_once("../system/classes/statusflow.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpPla = new PlaDAO();

$customerID = (isset($_GET['cus_id']) && is_numeric($_GET['cus_id'])) ? $_GET['cus_id'] : NULL;
$customerRec = NULL;
if (!is_null($customerID)) $customerRec = $TmpPla->select($customerID, NULL, NULL, NULL, NULL, true);

$customers = $TmpPla->select(NULL, 'CUS', NULL, NULL, NULL, false);

$editProjectID = (isset($_GET['pla_id']) && is_numeric($_GET['pla_id'])) ? $_GET['pla_id'] : NULL;
$placeRec = NULL;
if (!is_null($editProjectID)) $placeRec = $TmpPla->select($editProjectID, NULL, NULL, NULL, NULL, true);

/* TASKS */
$BtkTbl_ID = (isset($_GET['pla_id']) && is_numeric($_GET['pla_id'])) ? $_GET['pla_id'] : NULL;
$BtkTblNam = 'PROJECT';

$TmpBtk = new BtkDAO();
$tasks = $TmpBtk->select(NULL, $BtkTblNam, $BtkTbl_ID, NULL, false);
$totalTasks = (is_array($tasks)) ? count($tasks) : 0;

$TmpBoo = new BooDAO();

$bookings = $TmpPla->selectPlaceBookings($editProjectID, NULL, NULL, NULL, NULL, NULL);

$totalHours = 0;
$tableLength = count($bookings);
for ($i=0;$i<$tableLength;++$i) {
	$totalHours += $bookings[$i]['tothrs'];
}

$bookings = $TmpBoo->select(NULL, NULL, NULL, 'PROJECT', $editProjectID, NULL, NULL, NULL, false);

$activity = $TmpBoo->hoursByWeek('PROJECT', $editProjectID);


$TmpSta = new StaDAO();
$taskCodes = $TmpSta->select(NULL, 'TASKS', false);


$FloDao = new FloDAO();


$TmpPpl = new PplDAO();
$people = $TmpPpl->select(NULL, 'EMP', NULL, NULL, false);

?>
<!doctype html>
<html>
<head>
<title>Project :<?php echo($placeRec) ? $placeRec->planam : 'New Project'; ?></title>
<?php include('../webparts/headdata.php'); ?>
<link rel="stylesheet" href="css/plugins/datepicker/datepicker.css">
<script src="js/plugins/flot/jquery.flot.min.js"></script>
<script src="js/plugins/flot/jquery.flot.resize.min.js"></script>

<script src="js/plugins/datatable/jquery.dataTables.min.js"></script>
<script src="js/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="js/system.date.js"></script>

<script src="projects/js/project-edit.js"></script>
</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-orange" data-layout-topbar="fixed">
<div class="container-fluid" id="content">
	<?php include('../webparts/bookings-left.php'); ?>
	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>Project : <?php echo($placeRec) ? $placeRec->planam : 'New Project'; ?></h1>
				</div>
				<div class="pull-right">
					<ul class="stats">
						<li class="satgreen"> <i class="icon-time"></i>
							<div class="details">
								<span class="big"><?php echo $totalHours; ?></span> <span>Hours</span>
							</div>
						</li>
						<li class="lightred"> <i class="icon-tasks"></i>
							<div class="details">
								<span class="big"><?php echo count($tasks); ?></span> <span>Tasks</span>
							</div>
						</li>
					</ul>
				</div>
				<div class="pull-right">
					<?php include('../webparts/index-info.php'); ?>
				</div>
			</div>
			<div class="breadcrumbs">
				<ul>
					<li> <a href="index.php">Dashboard</a> <i class="icon-angle-right"></i> </li>
					<li> <a href="projects/dashboard.php">Bookings</a> <i class="icon-angle-right"></i> </li>
					<li> <a href="projects/projects.php">Projects</a> <i class="icon-angle-right"></i> </li>
					<li> <a><?php echo($placeRec) ? $placeRec->planam : 'New Project'; ?></a> </li>
				</ul>
			</div>
			<div class="row-fluid">
				<div class="span4">
					<form action="system/places_script.php" id="projectForm" class="form-vertical form-bordered" data-returnurl="projects/projects.php">
						<div class="box box-color box-bordered">
							<div class="box-title">
								<h3> <i class="icon-paste"></i> Project</h3>
								<div class="actions">
									<a href="#" id="updateProjectBtn" class="btn btn-mini" rel="tooltip" title="Update"><i class="icon-save"></i></a> <a href="#" id="deleteProjectBtn" class="btn btn-mini" rel="tooltip" title="Delete"><i class="icon-trash"></i></a>
								</div>
							</div>
							<div class="box-content nopadding">
								<input type="hidden" name="pla_id" id="id" value="<?php echo($placeRec) ? $placeRec->pla_id : '0'; ?>">
								<input type="hidden" name="tblnam" value="PROJECT">
								<div class="control-group">
									<label class="control-label">Customer<small>selecting customer</small></label>
									<div class="controls">
										<select name="tbl_id" class="input-block-level">
											<option value="0">Select Customer</option>
											<?php
											$tableLength = count($customers);
											for ($i=0;$i<$tableLength;++$i) {
												?>
											<option data-placol="<?php echo $customers[$i]['placol']; ?>" value="<?php echo $customers[$i]['pla_id']; ?>" <?php echo ($placeRec && $placeRec->tbl_id == $customers[$i]['pla_id']) ? 'selected' : ''; ?>><?php echo $customers[$i]['planam']; ?></option>
											<?php } ?>
										</select>
										<input type="hidden" class="input-block-level" name="comnam" value="<?php echo ($placeRec) ? $placeRec->comnam : ($customerRec) ? $customerRec->planam : ''; ?>">
									</div>
								</div>
								<div class="control-group hide">
									<label class="control-label">Project Name</label>
									<div class="controls">
										<input type="text" class="input-block-level" name="comnam" value="<?php echo ($placeRec) ? $placeRec->comnam : ($customerRec) ? $customerRec->planam : ''; ?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Project Name<small>enter the indentifying name of the project</small></label>
									<div class="controls">
										<input type="text" class="input-block-level" name="planam" value="<?php echo($placeRec) ? $placeRec->planam : ''; ?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Project URL<small>quick launch url</small></label>
									<div class="controls">
										<div class="input-append input-xlarge">
											<input type="text" class="input-large" name="plaurl" value="<?php echo($placeRec) ? $placeRec->plaurl : ''; ?>">
											<button class="btn" type="button" id="launchUrl"><i class="icon-external-link"></i></button>
										</div>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Email Address<small>email address of correspondance</small></label>
									<div class="controls">
										<input type="text" class="input-block-level" name="plaema" value="<?php echo($placeRec) ? $placeRec->plaema : ''; ?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="password">Telephone<small>telephone number of correspondance</small></label>
									<div class="controls">
										<input type="text" class="input-block-level" name="platel" value="<?php echo($placeRec) ? $placeRec->platel : ''; ?>">
									</div>
								</div>
								<div class="control-group hide">
									<label class="control-label">Password</label>
									<div class="controls">
										<input type="text" class="input-block-level" name="paswrd"/>
									</div>
								</div>
								<div class="control-group hide">
									<label class="control-label" for="confirm">Confirm Password</label>
									<div class="controls">
										<input type="text" class="input-block-level" name="pascnf" id="PasCnf">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Status<small>current project status</small></label>
									<div class="controls">
										<label class="radio">
											<input type="radio" name="sta_id" value="0" <?php echo(!$placeRec || ($placeRec && $placeRec->sta_id == 0)) ? 'checked' : ''; ?>>
											Active</label>
										<label class="radio">
											<input type="radio" name="sta_id" value="1" <?php echo($placeRec && $placeRec->sta_id == 1) ? 'checked' : ''; ?>>
											In-Active </label>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Estimate<small>time estimate in hours</small></label>
									<div class="controls">
										<input type="text" class="input-block-level" name="rooms" value="<?php echo($placeRec) ? $placeRec->rooms : ''; ?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Start Date<small>estimated start date</small></label>
									<div class="controls">
										<input type="text" class="input-block-level" name="credat" value="<?php echo($placeRec) ? $placeRec->credat : ''; ?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Due Date<small>delivery date</small></label>
									<div class="controls">
										<input type="text" class="input-block-level" name="amndat" value="<?php echo($placeRec) ? $placeRec->amndat : ''; ?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Colour<small>calender colour</small></label>
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
										
										<!--<input type="text" class="colorpick" value="">-->
									</div>
								</div>
							</div>
						</div>
						<div class="box box-color box-bordered hide">
							<div class="box-title">
								<h3> <i class="icon-pushpin"></i> Address</h3>
								<div class="actions">
									<!--<a href="ecommerce/customers-edit.php" class="btn btn-mini" rel="tooltip" title="New Customer"><i class="icon-file"></i></a>-->
								</div>
							</div>
							<div class="box-content">
								<div class="control-group">
									<label class="control-label">Address</label>
									<div class="controls">
										<input type="text" class="input-block-level input-margin-bottom" name="adr1" value="<?php echo($placeRec) ? $placeRec->adr1 : ''; ?>">
										<input type="text" class="input-block-level input-margin-bottom" name="adr2" value="<?php echo($placeRec) ? $placeRec->adr2 : ''; ?>">
										<input type="text" class="input-block-level input-margin-bottom" name="adr3" value="<?php echo($placeRec) ? $placeRec->adr3 : ''; ?>">
										<input type="text" class="input-block-level input-margin-bottom" name="adr4" value="<?php echo($placeRec) ? $placeRec->adr4 : ''; ?>">
										<input type="text" class="input-block-level" name="ctynam" value="<?php echo($placeRec) ? $placeRec->ctynam : ''; ?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Postcode</label>
									<div class="controls">
										<div class="input-append">
											<input type="text" class="input-block-level" name="pstcod" value="<?php echo($placeRec) ? $placeRec->pstcod : ''; ?>">
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
										<input type="text" class="input-block-level input-margin-bottom" name="goolat" id="GooLat" value="<?php echo($placeRec) ? $placeRec->goolat : ''; ?>">
										<input type="text" class="input-block-level input-margin-bottom" name="goolng" id="GooLng" value="<?php echo($placeRec) ? $placeRec->goolng : ''; ?>">
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="span8">
				
					<div class="box box-bordered box-color">
						<div class="box-title">
							<h3> <i class="icon-check"></i> Task Manager </h3>
						</div>
						<div class="box-content nopadding" style="min-height: 400px;" id="taskManagerCtrl">
							<ul class="tabs tabs-inline tabs-left" id="taskTabs">
								<li class="write hidden-480">
									<a href="#createTaskModal" id="createTaskBtn" role="button" data-toggle="modal">New Task</a>
								</li>
								<li class="active"> 
									<a href="#tab_0" data-toggle="tab" data-sta_id="0">
										<i class="icon-inbox"></i> Active
										<small>(<span id="activeTasks_0"></span>)</small>
									</a> 
								</li>
								<?php
								$tableLength = count($taskCodes);
								for ($i=0;$i<$tableLength;++$i) {
								?>
								<li> 
									<a href="#tab_<?php echo $taskCodes[$i]['sta_id'] ?>" data-toggle="tab" data-sta_id="<?php echo $taskCodes[$i]['sta_id'] ?>">
										<i class="<?php echo $taskCodes[$i]['staico'] ?>"></i> <?php echo $taskCodes[$i]['stanam'] ?> 
										<small>(<span id="activeTasks_<?php echo $taskCodes[$i]['sta_id'] ?>"></span>)</small>
									</a> 
								</li>
								<?php } ?>
							</ul>
							<div class="tab-content tab-content-inline">
								
								<div class="tab-pane active" id="tab_0">
									<div class="highlight-toolbar">
										<div class="pull-left">
											<div class="btn-toolbar">
												
												<?php
												$toStatusCodes = $FloDao->select(NULL, 0, NULL, false);
												for ($j=0;$j<count($toStatusCodes);++$j) {
												?>
												<div class="btn-group">
													<a href="#" class="btn flowBtn" rel="tooltip" data-placement="bottom" title="" data-to_sta_id="<?php echo $toStatusCodes[$j]['to_id'] ?>" data-original-title="<?php echo $toStatusCodes[$j]['to_nam'] ?>"><i class="<?php echo $toStatusCodes[$j]['to_ico'] ?>"></i></a>
												</div>
												<?php } ?>
											
												<div class="btn-group">
													<a href="#" class="btn btn-danger deleteTaskBtn" rel="tooltip" data-placement="bottom" title="" data-original-title="Delete"><i class="icon-trash"></i></a>
												</div>
											</div>
										</div>
										<div class="pull-right">
											<div class="btn-toolbar">
												<div class="btn-group hidden-768">
													<div class="dropdown">
														<a href="#" class="btn" data-toggle="dropdown"><i class="icon-cog"></i><span class="caret"></span></a>
														<ul class="dropdown-menu pull-right">
															<li><a href="#">Settings</a></li>
															<li><a href="#">Account settings</a></li>
															<li><a href="#">Email settings</a></li>
															<li><a href="#">Themes</a></li>
															<li><a href="#">Help &amp; FAQ</a></li>
														</ul>
													</div>
												</div>
											</div>
										</div>
									</div>
									<table class="table table-striped table-nomargin table-mail" id="tasksActiveTable_0">
										<thead>
											<tr>
												<th class="table-checkbox hidden-480"> <input type="checkbox" class="sel-all">
												</th>
												<th class="table-icon hidden-480"></th>
												<th>Owner</th>
												<th>Task</th>
												<th class="table-date hidden-480">Est.</th>
											</tr>
										</thead>
										<tbody id="tasksActiveBody_0">
										</tbody>
									</table>
								</div>
								
								<?php
								$tableLength = count($taskCodes);
								for ($i=0;$i<$tableLength;++$i) {
								?>
								<div class="tab-pane" id="tab_<?php echo $taskCodes[$i]['sta_id'] ?>">
								
									
									
									<div class="highlight-toolbar">
										<div class="pull-left">
											<div class="btn-toolbar">
												<div class="btn-group">
													<a href="#" class="btn flowBtn" rel="tooltip" data-placement="bottom" title="Return to active" data-to_sta_id="0" data-original-title="Return to active"><i class="icon-inbox"></i></a>
												</div>
												<?php
												$toStatusCodes = $FloDao->select(NULL, $taskCodes[$i]['sta_id'], NULL, false);
												for ($j=0;$j<count($toStatusCodes);++$j) {
												?>
												<div class="btn-group">
													<a href="#" class="btn flowBtn" rel="tooltip" data-placement="bottom" title="" data-to_sta_id="<?php echo $toStatusCodes[$j]['to_id'] ?>" data-original-title="<?php echo $toStatusCodes[$j]['to_nam'] ?>"><i class="<?php echo $toStatusCodes[$j]['to_ico'] ?>"></i></a>
												</div>
												<?php } ?>
												<div class="btn-group">
													<a href="#" class="btn btn-danger deleteTaskBtn" rel="tooltip" data-placement="bottom" title="" data-original-title="Delete"><i class="icon-trash"></i></a>
												</div>
											</div>
										</div>
										
									</div>
									
									<table class="table table-striped table-nomargin table-mail" id="tasksActiveTable_<?php echo $taskCodes[$i]['sta_id'] ?>">
										<thead>
											<tr>
												<th class="table-checkbox hidden-480"> <input type="checkbox" class="sel-all">
												</th>
												<th class="table-icon hidden-480"></th>
												<th>Owner</th>
												<th>Task</th>
												<th class="table-date hidden-480">Est.</th>
											</tr>
										</thead>
										<tbody id="tasksActiveBody_<?php echo $taskCodes[$i]['sta_id'] ?>">
										</tbody>
									</table>
									
								</div>
								<?php } ?>
							
								
							</div>
						</div>
					</div>
				
					
				</div>
			</div>
			<div class="row-fluid">
				<div class="span4">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3> <i class="icon-reorder"></i> Timeline </h3>
						</div>
						<div class="box-content nopadding scrollable" data-height="300" data-visible="true">
							<ul class="timeline">
								<?php
										
										$tableLength = count($bookings);
										for ($i=0;$i<$tableLength;++$i) {
											$totalHours += $bookings[$i]['tothrs'];
										?>
								<li>
									<div class="timeline-content">
										<div class="left">
											<div class="icon green">
												<i class="icon-time"></i>
											</div>
											<div class="date">
												<?php echo date("d. M",strtotime($bookings[$i]['begdat'])); ?>
											</div>
										</div>
										<div class="activity">
											<div class="user">
												<a href="projects/employee-edit.php?ppl_id=<?php echo $bookings[$i]['ref_id']; ?>"><?php echo $bookings[$i]['pplnam']; ?></a> <span>booked <strong><?php echo number_format($bookings[$i]['tothrs'],2); ?></strong> hours</span>
											</div>
											<p> <?php echo nl2br($bookings[$i]['boodsc']); ?> </p>
											<p><a href="projects/calendar.php?y=<?php echo date("Y",strtotime($bookings[$i]['begdat'])); ?>&m=<?php echo date("m",strtotime($bookings[$i]['begdat'])); ?>&d=<?php echo date("d",strtotime($bookings[$i]['begdat'])); ?>"><i class="icon-angle-right"></i> View In Calendar</a></p>
										</div>
									</div>
									<div class="line">
									</div>
								</li>
								<?php } ?>
							</ul>
						</div>
					</div>
				</div>
				<div class="span8">
					<div class="box box-bordered box-color lime">
						<div class="box-title">
							<h3> <i class="icon-calendar"></i> Weekly Activity </h3>
							<div class="actions">
								<a href="#" class="btn btn-mini" id="actControl"><i class="icon-angle-down"></i></a>
							</div>
						</div>
						<div class="box-content" id="actList">
						
							<div id="lineChart" class="flot medium">
							</div>
						
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</div>
</div>

<div id="createTaskModal" class="modal hide fade" tabindex="-1" role="dialog">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3>Task Management</h3>
	</div>
	<div class="modal-body">
	
		<form action="projects/tasks_script.php" id="taskForm" class="form-horizontal">
			<input type="hidden" name="btk_id" value="0">
			<input type="hidden" name="tblnam" value="PROJECT">
			<input type="hidden" name="reftbl" value="EMP">
			<div class="control-group">
				<label class="control-label">Title</label>
				<div class="controls">
					<input type="text" class="input-block-level" name="btkttl" value="">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label">Description</label>
				<div class="controls">
					<textarea class="input-block-level" name="btkdsc"></textarea>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label">Duration</label>
				<div class="controls">
					<input type="text" class="input-block-level" name="btkdur" value="">
				</div>
			</div>
			<div class="control-group hide">
				<label class="control-label">Status</label>
				<div class="controls">
					<input type="text" name="sta_id" value="0">
				</div>
			</div>
			<div class="control-group hide">
				<label class="control-label">Project</label>
				<div class="controls">
					<input type="text" name="tbl_id" value="<?php echo $editProjectID; ?>">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label">Employee</label>
				<div class="controls">
					<select data-placeholder="Select an employee..." name="ref_id">
						<option value="0">N/A</option>
						<?php
						$tableLength = count($people);
						for ($i=0;$i<$tableLength;++$i) {
						?>
						<option value="<?php echo $people[$i]['ppl_id']; ?>"><?php echo $people[$i]['pplnam']; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label">Due Date</label>
				<div class="controls">
					<div class="input-append">
						<input type="text" name="duedat" value="">
						<a href="#" id="clearDueDateBtn" class="btn" rel="tooltip" data-placement="top" data-original-title="Clear Due Date"><i class="icon-remove"></i></a>
					</div>
				</div>
			</div>
		</form>
	
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
		<button class="btn btn-success" aria-hidden="true" id="updateTaskBtn"><i class="icon-save"></i> Update</button>
	</div>
</div>

</body>
</html>
