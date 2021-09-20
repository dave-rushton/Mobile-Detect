<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 

require_once("classes/users.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpUsr = new UsrDAO();

$userRec = $TmpUsr->select($loggedIn, NULL, true); 
$UsrAcc = array();
if ($userRec) $UsrAcc = explode(",",$userRec->usracc);

?>
<!doctype html>
<html>
<head>
<title>User Admin</title>
<?php include('../webparts/headdata.php'); ?>
<script src="js/jquery.blockUI.js"></script>
<script src="system/js/users-edit.js"></script>
</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid nav-hidden" id="content">
	<?php //include('../webparts/system-left.php'); ?>
	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>User Account</h1>
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
						<a>User Account</a>
					</li>
				</ul>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<div class="box" id="userFormBox">
						<div class="box-title">
							<h3><i class="icon-user"></i> User Account</h3>
							<div class="actions">
								<a href="#" id="updateUserBtn" class="btn btn-mini" rel="tooltip" title="Update"><i class="icon-save"></i></a>
							</div>
						</div>
						<div class="box-content">
							<form class="form-horizontal form-validate" method="POST" action="system/users_script.php" id="userForm" data-returnurl="system/users.php">
								<div class="span6">
									
									<input type="hidden" name="usr_id" id="id" value="<?php echo ($userRec) ? $userRec->usr_id : 0; ?>" />
								
									<div class="control-group">
										<label class="control-label" for="name">Your Name</label>
										<div class="controls">
											<input type="text" class="input-large" name="usrnam" data-rule-required="true" data-rule-minlength="2" value="<?php echo ($userRec) ? $userRec->usrnam : ''; ?>">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="email">Email Address</label>
										<div class="controls">
											<input type="text" class="input-large" name="usrema" data-rule-required="true" data-rule-email="true" value="<?php echo ($userRec) ? $userRec->usrema : ''; ?>">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="password">Password</label>
										<div class="controls">
											<input type="password" class="input-large" name="paswrd" id="paswrd" data-rule-minlength="6">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="confirm">Confirm Password</label>
										<div class="controls">
											<input type="password" class="input-large" name="pascnf" data-rule-minlength="6" data-rule-equalTo="#paswrd">
										</div>
									</div>
								</div>
								
								<br class="clear" />
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
