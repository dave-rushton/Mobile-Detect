<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("../system/classes/places.cls.php");
require_once("../system/classes/people.cls.php");

require_once("../projects/classes/tasks.cls.php");

require_once("../projects/classes/bookings.cls.php");
require_once("../products/classes/products.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$BegDat = (isset($_GET['begdat']) ) ? $_GET['begdat'] : NULL;
$EndDat = (isset($_GET['enddat']) ) ? $_GET['enddat'] : NULL;

$Tbl_ID = (isset($_GET['tbl_id']) && is_numeric($_GET['tbl_id'])) ? $_GET['tbl_id'] : NULL;
$TblNam = (isset($_GET['tblnam']) && !empty($_GET['tblnam'])) ? $_GET['tblnam'] : NULL;
$Ref_ID = (isset($_GET['ref_id']) && is_numeric($_GET['ref_id'])) ? $_GET['ref_id'] : NULL;
$RefNam = (isset($_GET['refnam']) && !empty($_GET['refnam'])) ? $_GET['refnam'] : NULL;

//$TmpBoo = new BooDAO();
//$bookings = $TmpBoo->select(NULL, $BegDat, $EndDat, $TblNam, $Tbl_ID, $RefNam, $Ref_ID, false);

$TmpPla = new PlaDAO();
$places = $TmpPla->select(NULL, 'PROJECT', NULL, NULL, NULL, false);

$TmpPpl = new PplDAO();
$people = $TmpPpl->select(NULL, 'EMP', NULL, NULL, false);

$TmpBtk = new BtkDAO();
$tasks = $TmpBtk->select(NULL, NULL, NULL, NULL, false);

?>
<!doctype html>
<html>
<head>
<title>Task Listing</title>
<?php include('../webparts/headdata.php'); ?>

<link rel="stylesheet" href="css/plugins/datatable/TableTools.css">
<link rel="stylesheet" href="css/plugins/datepicker/datepicker.css">

<script src="js/plugins/datatable/jquery.dataTables.min.js"></script>
<script src="js/plugins/datatable/TableTools.min.js"></script>
<script src="js/plugins/datatable/ColReorder.min.js"></script>
<script src="js/plugins/datatable/ColVis.min.js"></script>
<script src="js/plugins/datatable/jquery.dataTables.columnFilter.js"></script>
<script src="js/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="js/system.date.js"></script>
<script>

var taskForm;
var taskTable;

$(function(){
	
	taskForm = $('#taskForm');
	
	//$('[name="begdatdsp"], [name="enddatdsp"]', taskForm).datepicker({ format: 'yyyy-mm-dd', weekStart: 1 });
	
	$('#tasksBody').on('click', '.selectTaskLnk', function(e){
		e.preventDefault();
		
		var btkId = $(this).data('btk_id');
		
		$.ajax({
			url: 'projects/tasks_script.php',
			data: 'action=select&ajax=true&btk_id=' + btkId,
			type: 'POST',
			async: false,
			success: function (data) {
				
				//alert(data);
				
				var task = JSON.parse(data);
				
				$('[name="btk_id"]', taskForm).val( task.btk_id );
				$('[name="tbl_id"]', taskForm).val( task.tbl_id );
				$('[name="ref_id"]', taskForm).val( task.ref_id );
				
				$('[name="btkdur"]', taskForm).val( task.btkdur );
				
				$('[name="btkttl"]', taskForm).val( task.btkttl );
				$('[name="btkdsc"]', taskForm).val( task.btkdsc );
				
				$('[name="sta_id"]', taskForm).val( task.sta_id );
				
				changeScreen('#taskFormScreen');
				
			},
			error: function (x, e) {
				throwAjaxError(x, e);
			}
		});
		
	});
	
	$('#tasksBody').on('click', '.deleteTaskBtn', function(e){
		e.preventDefault();
		
		var btkId = $(this).data('btk_id');
		
		$.msgAlert ({
			type: 'warning'
			, title: 'Delete This Task'
			, text: 'Are you sure you wish to permanently remove this booking from the database?'
			, callback: function () {
		
				$.ajax({
					url: 'projects/tasks_script.php',
					data: 'action=delete&ajax=true&btk_id=' + btkId,
					type: 'POST',
					async: false,
					success: function (data) {
						
						getTasks();
						
					},
					error: function (x, e) {
						throwAjaxError(x, e);
					}
				});
			}
		});
		
	});
	
	$('#updateTask').click(function(e){
		e.preventDefault();
		taskForm.submit();
	});
	
	taskForm.validate({
        rules: {
            btkttl: {
                minlength: 2,
                required: true
            },
			btkdur: {
                min: 0.25,
                required: true
            }
        }
		,
        focusCleanup: false,

        highlight: function (label) {
            $(label).closest('.control-group').removeClass('success').addClass('error');
        },
        success: function (label) {
            label.text('OK!').addClass('valid').closest('.control-group').addClass('success');
        },
        errorPlacement: function (error, element) {
            error.appendTo(element.parents('.controls'));
        },
		submitHandler: function(form) {
			
		}
    });
	
	taskForm.submit(function(e){
	
		e.preventDefault();
		
		if (taskForm.valid() ) {
		
			$.ajax({
				url: 'projects/tasks_script.php',
				data: 'action=update&ajax=true&' + taskForm.serialize(),
				type: 'POST',
				async: false,
				success: function (data) {
	
					var result = JSON.parse(data);
	
					$.msgGrowl({
						type: result.type,
						title: result.title,
						text: result.description
					});
					
					getTasks();
					changeScreen('#listingScreen');
	
				},
				error: function (x, e) {
					throwAjaxError(x, e);
				}
			});
		
		} else {
			
			$.msgGrowl({
				type: 'ERROR',
				title: 'Form Error',
				text: 'There is an error in the form'
			});
			
		}
		
	});
	
	$('#newTaskBtn').click(function(e){
	
		e.preventDefault();
		
		$('[name="btk_id"]', taskForm).val( 0 );
		$('[name="tbl_id"]', taskForm).val( 0 );
		$('[name="ref_id"]', taskForm).val( 0 );
		
		$('[name="btkdsc"]', taskForm).val( '' );
		$('[name="sta_id"]', taskForm).val( 0 );
		
		changeScreen('#taskFormScreen');
		
	});
	
	$('.screenSelect').click(function(e){
		e.preventDefault();
		changeScreen($(this).attr("href"));
	});
	
	$('[name="tbl_id"]', $('#searchForm')).change(function(){
		getTasks();
	});
	$('[name="ref_id"]', $('#searchForm')).change(function(){
		getTasks();
	});
	$('[name="sta_id"]', $('#searchForm')).change(function(){
		getTasks();
	});
	
	getTasks();
	
});

function changeScreen(screenID) {
	
	$('.adminScreen').fadeOut();
	setTimeout( function() { $(screenID).fadeIn(200, function(){ resize_chosen();}); } , 400);
	
		
}

function getTasks() {
	
	$.ajax({
		url: 'projects/tasks_table.php',
		data: 'action=select&tblnam=PROJECT&tbl_id=' + $('[name="tbl_id"]', $('#searchForm')).val() + '&ref_id=' + $('[name="ref_id"]', $('#searchForm')).val() + '&sta_id=' + $('[name="sta_id"]', $('#searchForm')).val(),
		type: 'GET',
		async: false,
		success: function (data) {
			
			try { taskTable.fnDestroy(); } catch (Ex) { }
			$('#tasksBody').html( data );
			taskTable = $("table#tasksTable").dataTable({"bDestroy": true});
			
		},
		error: function (x, e) {
			throwAjaxError(x, e);
		}
	});
	
}

</script>
</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
	<?php include('../webparts/bookings-left.php'); ?>
	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>Tasks</h1>
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
						<a href="projects/tasklist.php">Tasks</a>
					</li>
				</ul>
			</div>
			
			<div class="row-fluid adminScreen" id="listingScreen">
				<div class="span12">
					<div class="box">
						<div class="box-title">
							<h3>
								<i class="icon-filter"></i> Task Filter</h3>
							<div class="actions">
								<a href="#" class="btn" rel="tooltip" title="Search Bookings"><i class="icon-search"></i></a>
							</div>
						</div>
						<div class="box-content">
							<form action="projects/tasks_script.php" id="searchForm" class="">
								<div class="span4">
								<input type="hidden" name="tblnam" value="PROJECT">
								<div class="control-group">
									<label class="control-label">Project</label>
									<div class="controls">
										<select data-placeholder="Select a project..." name="tbl_id">
											<option value="">N/A</option>
											<?php
											$tableLength = count($places);
											for ($i=0;$i<$tableLength;++$i) {
											?>
											<option value="<?php echo $places[$i]['pla_id']; ?>"><?php echo $places[$i]['planam']; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								</div>
								<div class="span4">
								<div class="control-group">
									<label class="control-label">Employee</label>
									<div class="controls">
										<select data-placeholder="Select an employee..." name="ref_id">
											<option value="">N/A</option>
											<?php
											$tableLength = count($people);
											for ($i=0;$i<$tableLength;++$i) {
											?>
											<option value="<?php echo $people[$i]['ppl_id']; ?>"><?php echo $people[$i]['pplnam']; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								</div>
								<div class="span4">
								<div class="control-group">
									<label class="control-label">Status</label>
									<div class="controls">
										<select name="sta_id">
											<option value="">Show All</option>
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
								</div>
							</form>
						</div>
					</div>
					
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-tasks"></i> Task Listing</h3>
							<div class="actions">
								<a href="#" id="newTaskBtn" class="btn btn-mini" rel="tooltip" title="New Task"><i class="icon-plus"></i></a>
							</div>
						</div>
						<div class="box-content nopadding">
							<table class="table table-nomargin table-condensed table-striped" id="tasksTable">
								<thead>
									<tr>
										<th width="10"><input type="checkbox"></th>
										<th width="20">Duration</th>
										<th>Task</th>
										<th>Project</th>
										<th>Employee</th>
										<th></th>
									</tr>
								</thead>
								<tbody id="tasksBody">
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="row-fluid adminScreen hide" id="taskFormScreen">
				<div class="span12">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-tasks"></i> Task Form</h3>
							<div class="actions">
								<a href="#listingScreen" class="btn btn-mini screenSelect" rel="tooltip" title="Cancel"><i class="icon-remove"></i></a>
								<a href="#" class="btn btn-mini" rel="tooltip" title="Update Task" id="updateTask"><i class="icon-save"></i></a>
							</div>
						</div>
						<div class="box-content">
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
								<div class="control-group">
									<label class="control-label">Status</label>
									<div class="controls">
										<select name="sta_id">
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
									<label class="control-label">Project</label>
									<div class="controls">
										<select data-placeholder="Select a project..." name="tbl_id">
											<option value="0">N/A</option>
											<?php
											$tableLength = count($places);
											for ($i=0;$i<$tableLength;++$i) {
											?>
											<option value="<?php echo $places[$i]['pla_id']; ?>"><?php echo $places[$i]['planam']; ?></option>
											<?php } ?>
										</select>
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
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
