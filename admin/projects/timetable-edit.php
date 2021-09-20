<?php
require_once("../config/patchworks.php");
require_once("../projects/classes/tasks.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$taskRec = NULL;

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Venue | Timetable</title>

<?php include('../includes/pw.headdata.php'); ?>
<link href="./js/plugins/msgGrowl/css/msgGrowl.css" rel="stylesheet">
<link href="./js/plugins/msgAlert/css/msgAlert.css" rel="stylesheet">
<link href="./js/plugins/lightbox/themes/evolution-dark/jquery.lightbox.css" rel="stylesheet">  
<link href="./js/plugins/plupload/js/jquery.ui.plupload/css/jquery.ui.plupload.css" type="text/css" />

<script src="./js/plugins/lightbox/jquery.lightbox.js"></script>
<script src="./js/plugins/msgGrowl/js/msgGrowl.js"></script>
<script src="./js/plugins/msgAlert/js/msgAlert.js"></script>
<script src="./js/plugins/validate/jquery.validate.js"></script>

<script src="./js/jquery.timebox.js"></script>
<script src="./js/system.date.js"></script>

<script>

$(function(){
	
	var now = new Date();
	var mins = now.getMinutes();
	var quarterHours = Math.round(mins/15);
	if (quarterHours == 4)
	{
		now.setHours(now.getHours()+1);
	}
	var rounded = (quarterHours*15)%60;
	now.setMinutes(rounded);
	
//	alert(js2mysqlTime(now));
	
	$('#BegTim').val(js2mysqlTime(now));
	$('#TtbDur').val('01:00');
	
	$('#BegTim, #TtbDur').timebox();
	
});

</script>
</head>

<body class="theme-red">
<?php include('../includes/pw.header.php'); ?>
<?php include('../includes/users.menu.php'); ?>
<div id="content">
	<div class="container">
		<div id="page-title" class="clearfix">
			<ul class="breadcrumb">
				<li>
					<a href="./">Dashboard</a>
					<span class="divider">/</span>
				</li>
				<li>
					Bookings
					<span class="divider">/</span>
				</li>
				<li class="active">Timetable</li>
			</ul>
		</div>
		<div class="row" id="taskFormRow">
			<form action="<?php echo $patchworks->pwRoot; ?>projects/tasks_script.php" id="taskForm" class="form-horizontal" novalidate="novalidate" data-returnurl="<?php echo $returnURL; ?>">
				<input type="hidden" name="btk_id" id="id" value="<?php echo($taskRec) ? $taskRec->btk_id : 0; ?>" />
				<div class="span6">
					<div class="widget highlight widget-form">
						<div class="widget-header">
							<h3>
								<i class="icon-calendar"></i> Timetable Form</h3>
						</div>
						<div class="widget-content">
							<fieldset>
								<div class="control-group">
									<label class="control-label">Table Name</label>
									<div class="controls">
										<input type="text" class="input-large" name="tblnam" id="TblNam" value="<?php echo($taskRec) ? $taskRec->tblnam : ''; ?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Table ID</label>
									<div class="controls">
										<input type="text" class="input-large" name="tbl_id" id="Tbl_ID" value="<?php echo($taskRec) ? $taskRec->tbl_id : ''; ?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Start Time</label>
									<div class="controls">
										<input type="text" class="input-large" name="begtim" id="BegTim" value="<?php echo($taskRec) ? $taskRec->btkttl : ''; ?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Duration</label>
									<div class="controls">
										<input type="text" class="input-large" name="ttbdur" id="TtbDur" value="<?php echo($taskRec) ? $taskRec->btkttl : ''; ?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">EndTime</label>
									<div class="controls">
										<textarea class="input-large" name="endtim" id="EndTim"><?php echo($taskRec) ? $taskRec->btkdsc : ''; ?></textarea>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Day</label>
									<div class="controls">
										<select class="input-large" name="btkdur" id="BtkDur">
											<option value="1" <?php echo($taskRec && $taskRec->btkdur == 1) ? 'selected' : ''; ?>>Monday</option>
											<option value="2" <?php echo($taskRec && $taskRec->btkdur == 2) ? 'selected' : ''; ?>>Tuesday</option>
											<option value="3" <?php echo($taskRec && $taskRec->btkdur == 3) ? 'selected' : ''; ?>>Wednesday</option>
											<option value="4" <?php echo($taskRec && $taskRec->btkdur == 4) ? 'selected' : ''; ?>>Thursday</option>
											<option value="5" <?php echo($taskRec && $taskRec->btkdur == 5) ? 'selected' : ''; ?>>Friday</option>
											<option value="6" <?php echo($taskRec && $taskRec->btkdur == 6) ? 'selected' : ''; ?>>Saturday</option>
											<option value="7" <?php echo($taskRec && $taskRec->btkdur == 7) ? 'selected' : ''; ?>>Sunday</option>
											
										</select>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Table Category</label>
									<div class="controls">
										<input type="text" class="input-large" name="tblcat" id="TblCat" value="<?php echo($taskRec) ? $taskRec->btkdsc : ''; ?>" />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Table SubCategory</label>
									<div class="controls">
										<input type="text" class="input-large" name="tblsub" id="TblSub" value="<?php echo($taskRec) ? $taskRec->btkdsc : ''; ?>" />
									</div>
								</div>
								
							</fieldset>
						</div>
						<!-- /widget-content -->
						
					</div>
					
					<div class="widget highlight widget-form">
						<div class="widget-header">
							<h3><i class="icon-tasks"></i> Actions</h3>
						</div>
						
						<div class="widget-content">
							<fieldset>
								<div class="form-actions">
									<button type="submit" class="btn btn-primary" name="action" value="update"><i class="icon-save"></i> Update</button>
									<div class="floatRight">
									<a href="#deletePlaceModal" data-toggle="modal" class="btn btn-warning"><i class="icon-trash"></i> Delete</a>
									</div>
								</div>
							</fieldset>
						</div>
					</div>
					
				</div>
				
			</form>
		</div>
		
	</div>
</div>
<?php include('../includes/pw.footer.php'); ?>

<div class="modal fade hide" id="deleteTaskModal">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h3>Delete Task Record</h3>
  </div>
  <div class="modal-body">
    <p>Are you sure you wish to delete this task record?</p>
    
    <p>This action will physically delete the task record from the database</p>
  </div>
  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal"><i class="icon-remove"></i> Cancel</a>
    <a href="#" id="deleteTaskBtn" class="btn btn-danger"><i class="icon-ok"></i> Delete</a>
  </div>
</div>

</body>
</html>