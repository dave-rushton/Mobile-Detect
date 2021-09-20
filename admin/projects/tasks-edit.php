<?php
require_once("../config/patchworks.php");
require_once("../projects/classes/tasks.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpBtk = new BtkDAO();
$editTaskID = (isset($_GET['btk_id']) && is_numeric($_GET['btk_id'])) ? $_GET['btk_id'] : NULL;
$taskRec = NULL;
if (!is_null($editTaskID)) $taskRec = $TmpBtk->select($editTaskID, NULL, NULL, NULL, true); 

$returnURL = (isset($_GET['returnurl'])) ? $_GET['returnurl'] : 'tasks/tasks.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Task Form | PatchWorks V5.0</title>

<?php include('../includes/pw.headdata.php'); ?>
<link href="./js/plugins/msgGrowl/css/msgGrowl.css" rel="stylesheet">
<link href="./js/plugins/msgAlert/css/msgAlert.css" rel="stylesheet">
<link href="./js/plugins/lightbox/themes/evolution-dark/jquery.lightbox.css" rel="stylesheet">  
<link href="./js/plugins/plupload/js/jquery.ui.plupload/css/jquery.ui.plupload.css" type="text/css" />

<script src="./js/plugins/lightbox/jquery.lightbox.js"></script>
<script src="./js/plugins/msgGrowl/js/msgGrowl.js"></script>
<script src="./js/plugins/msgAlert/js/msgAlert.js"></script>
<script src="./js/plugins/validate/jquery.validate.js"></script>

<script src="./projects/js/tasks-edit.js"></script>
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
					<a href="projects/tasks.php">Tasks</a>
					<span class="divider">/</span>
				</li>
				<li class="active">Task Form</li>
			</ul>
		</div>
		<div class="row" id="taskFormRow">
			<form action="<?php echo $patchworks->pwRoot; ?>projects/tasks_script.php" id="taskForm" class="form-horizontal" novalidate="novalidate" data-returnurl="projects/tasks.php">
				<input type="hidden" name="btk_id" id="id" value="<?php echo($taskRec) ? $taskRec->btk_id : 0; ?>" />
				<div class="span6">
					<div class="widget highlight widget-form">
						<div class="widget-header">
							<h3>
								<i class="icon-pushpin"></i> Tasks Form</h3>
							<div class="widget-actions">
							
									<button type="submit" class="btn btn-primary" name="action" value="update"><i class="icon-save"></i> Update</button>
									<a href="#" id="deleteTaskBtn" class="btn btn-warning"><i class="icon-trash"></i> Delete</a>
									</div>
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
									<label class="control-label">Title</label>
									<div class="controls">
										<input type="text" class="input-large" name="btkttl" id="BtkTtl" value="<?php echo($taskRec) ? $taskRec->btkttl : ''; ?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Description</label>
									<div class="controls">
										<textarea class="input-large" name="btkdsc" id="BtkDsc"><?php echo($taskRec) ? $taskRec->btkdsc : ''; ?></textarea>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Duration</label>
									<div class="controls">
										<select class="input-large" name="btkdur" id="BtkDur">
											<option value="900" <?php echo($taskRec && $taskRec->btkdur == 900) ? 'selected' : ''; ?>>15 Minutes</option>
											<option value="1800" <?php echo($taskRec && $taskRec->btkdur == 1800) ? 'selected' : ''; ?>>30 Minutes</option>
											<option value="3600" <?php echo($taskRec && $taskRec->btkdur == 3600) ? 'selected' : ''; ?>>1 Hour</option>
											<option value="7200" <?php echo($taskRec && $taskRec->btkdur == 7200) ? 'selected' : ''; ?>>2 Hours</option>
											<option value="10800" <?php echo($taskRec && $taskRec->btkdur == 10800) ? 'selected' : ''; ?>>3 Hours</option>
											<option value="14400" <?php echo($taskRec && $taskRec->btkdur == 14400) ? 'selected' : ''; ?>>4 Hours</option>
											<option value="18000" <?php echo($taskRec && $taskRec->btkdur == 18000) ? 'selected' : ''; ?>>5 Hours</option>
											<option value="21600" <?php echo($taskRec && $taskRec->btkdur == 21600) ? 'selected' : ''; ?>>6 Hours</option>
											<option value="25200" <?php echo($taskRec && $taskRec->btkdur == 25200) ? 'selected' : ''; ?>>7 Hours</option>
											<option value="28800" <?php echo($taskRec && $taskRec->btkdur == 28800) ? 'selected' : ''; ?>>8 Hours</option>
										</select>
									</div>
								</div>
								
							</fieldset>
						</div>
						<!-- /widget-content -->
						
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