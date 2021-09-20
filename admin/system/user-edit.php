<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 

require_once("classes/users.cls.php");

require_once("../webparts/modules.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpUsr = new UsrDAO();

$Usr_ID = (isset($_GET['usr_id']) && is_numeric($_GET['usr_id'])) ? $_GET['usr_id'] : 0;

$userRec = $TmpUsr->select($Usr_ID, NULL, true); 
$UsrAcc = array();
if ($userRec) $UsrAcc = explode(",",$userRec->usracc);


require_once("../system/classes/people.cls.php");

$TmpPpl = new PplDAO();
$employee = $TmpPpl->select(NULL, 'EMP', NULL, NULL, false); 

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
<div class="container-fluid" id="content">
	<?php include('../webparts/system-left.php'); ?>
	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>Users</h1>
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
						<a>System</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a href="system/users.php">Users</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a>Users</a>
					</li>
				</ul>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<div class="box" id="userFormBox">
						<div class="box-title">
							<h3><i class="icon-user"></i> Administration User form</h3>
							<div class="actions">
								<a href="#" id="updateUserBtn" class="btn btn-mini" rel="tooltip" title="Update"><i class="icon-save"></i></a>
								<a href="#" id="deleteUserBtn" class="btn btn-mini" rel="tooltip" title="Delete"><i class="icon-trash"></i></a>
							</div>
						</div>
						<div class="box-content">
							<form class="form-horizontal form-validate" method="POST" action="system/users_script.php" id="userForm" data-returnurl="system/users.php">
								<div class="span6">
									
									<input type="hidden" name="usr_id" id="id" value="<?php echo ($userRec) ? $userRec->usr_id : 0; ?>" />
									<input type="hidden" name="usracc" id="UsrAcc" value="<?php echo ($userRec) ? $userRec->usracc : 0; ?>" />
								
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
									<div class="control-group">
										<label class="control-label">Status</label>
										<div class="controls">
											<label class="radio">
												<input type="radio" name="sta_id" value="0" <?php echo ((!$userRec) || ($userRec && $userRec->sta_id == 0)) ? 'checked' : ''; ?>>
												Active</label>
											<label class="radio">
												<input type="radio" name="sta_id" value="1" <?php echo ($userRec && $userRec->sta_id == 1) ? 'selected' : ''; ?>>
												In-Active </label>
										</div>
									</div>
									
									
									<div class="control-group">
										<label class="control-label">Employees</label>
										<div class="controls">
											<?php
											$tableLength = count($employee);
											for ($i=0;$i<$tableLength;++$i) {
											?>
											<label class="checkbox">
												<input type="checkbox" name="emp_id[]" value="<?php echo $employee[$i]['ppl_id'] ?>">
												<?php echo $employee[$i]['pplnam'] ?></label>
											<?php } ?>
										</div>
									</div>
								</div>
								<div class="span6">
									<div class="control-group">
										<label class="control-label">Access<small>Allow user to access:</small></label>
										<div class="controls">
											
                                            <!-- WEBSITE -->

                                            <?php

                                            for ($m=0; $m<count($modules); $m++) {

                                                if (!isset($modules['items']) || !is_array($modules['items'])) continue;

                                                for ($i=0; $i<count($modules['items']); $i++) {

                                                ?>

                                                <label class="checkbox">
                                                    <input type="checkbox" name="accessCheckbox" value="<?php echo $modules[$m]['name']; ?>:<?php echo $modules[$m]['items'][$i]['name']; ?>" class="subModuleCB" <?php if (in_array('website:templates', $UsrAcc, true)) echo 'checked' ?>>
                                                    Website - Templates </label>

                                                <?php
                                                }
                                            }

                                            ?>

                                            
											<label class="checkbox hide">
												<input type="checkbox" name="accessCheckbox" value="website:templates" class="subModuleCB" <?php if (in_array('website:templates', $UsrAcc, true)) echo 'checked' ?>>
												Website - Templates </label>
											<label class="checkbox">
												<input type="checkbox" name="accessCheckbox" value="website:sitemap" class="subModuleCB" <?php if (in_array('website:sitemap', $UsrAcc, true)) echo 'checked' ?>>
												Website - Sitemap </label>
											<label class="checkbox">
												<input type="checkbox" name="accessCheckbox" value="website:articles" class="subModuleCB" <?php if (in_array('website:articles', $UsrAcc, true)) echo 'checked' ?>>
												Website - Articles </label>
											<label class="checkbox">
												<input type="checkbox" name="accessCheckbox" value="website:artcats" class="subModuleCB" <?php if (in_array('website:artcats', $UsrAcc, true)) echo 'checked' ?>>
												Website - Article Categories </label>
											<label class="checkbox">
												<input type="checkbox" name="accessCheckbox" value="website:forms" class="subModuleCB" <?php if (in_array('website:forms', $UsrAcc, true)) echo 'checked' ?>>
												Website - Forms </label>
											<label class="checkbox">
												<input type="checkbox" name="accessCheckbox" value="website:galleries" class="subModuleCB" <?php if (in_array('website:galleries', $UsrAcc, true)) echo 'checked' ?>>
												Website - Galleries </label>
											<label class="checkbox">
												<input type="checkbox" name="accessCheckbox" value="website:stats" class="subModuleCB" <?php if (in_array('website:stats', $UsrAcc, true)) echo 'checked' ?>>
												Website - Stats </label>
											
                                            <!-- BOOKINGS -->
                                            
											<label class="checkbox">
												<input type="checkbox" name="accessCheckbox" value="bookings:projects" class="subModuleCB" <?php if (in_array('bookings:projects', $UsrAcc, true)) echo 'checked' ?>>
												Bookings - Projects </label>
                                            <label class="checkbox">
												<input type="checkbox" name="accessCheckbox" value="bookings:tasks" class="subModuleCB" <?php if (in_array('bookings:tasks', $UsrAcc, true)) echo 'checked' ?>>
												Bookings - Tasks </label>
											<label class="checkbox">
												<input type="checkbox" name="accessCheckbox" value="bookings:listing" class="subModuleCB" <?php if (in_array('bookings:listing', $UsrAcc, true)) echo 'checked' ?>>
												Bookings - Listing </label>
											<label class="checkbox">
												<input type="checkbox" name="accessCheckbox" value="bookings:calendar" class="subModuleCB" <?php if (in_array('bookings:calendar', $UsrAcc, true)) echo 'checked' ?>>
												Bookings - Calendar </label>
                                               
                                            <!-- ECOMMERCE -->
                                            
                                            <label class="checkbox">
												<input type="checkbox" name="accessCheckbox" value="products:groups" class="subModuleCB" <?php if (in_array('products:groups', $UsrAcc, true)) echo 'checked' ?>>
												Products - Product Groups</label>
											<label class="checkbox">
												<input type="checkbox" name="accessCheckbox" value="products:products" class="subModuleCB" <?php if (in_array('products:products', $UsrAcc, true)) echo 'checked' ?>>
												Products - Products</label>
											<label class="checkbox">
												<input type="checkbox" name="accessCheckbox" value="sales:customers" class="subModuleCB" <?php if (in_array('sales:customers', $UsrAcc, true)) echo 'checked' ?>>
												Customers - Customers</label>
											<label class="checkbox">
												<input type="checkbox" name="accessCheckbox" value="sales:orders" class="subModuleCB" <?php if (in_array('sales:orders', $UsrAcc, true)) echo 'checked' ?>>
												Customers - Orders</label>
                                                
                                            <!-- PROPERTIES -->
                                            <!--
											<label class="checkbox">
												<input type="checkbox" name="accessCheckbox" value="properties:properties" class="subModuleCB" <?php if (in_array('properties:properties', $UsrAcc, true)) echo 'checked' ?>>
												Properties - Properties</label>
											<label class="checkbox">
												<input type="checkbox" name="accessCheckbox" value="properties:clients" class="subModuleCB" <?php if (in_array('properties:clients', $UsrAcc, true)) echo 'checked' ?>>
												Properties - Clients</label>
											<label class="checkbox">
												<input type="checkbox" name="accessCheckbox" value="properties:propertysearch" class="subModuleCB" <?php if (in_array('properties:propertysearch', $UsrAcc, true)) echo 'checked' ?>>
												Properties - Property Search</label>
											<label class="checkbox">
												<input type="checkbox" name="accessCheckbox" value="suppliers:suppliers" class="subModuleCB" <?php if (in_array('suppliers:suppliers', $UsrAcc, true)) echo 'checked' ?>>
												Suppliers - Suppliers</label>
											-->
                                            
                                            <!-- SYSTEM -->
												
											<label class="checkbox">
												<input type="checkbox" name="accessCheckbox" value="system:users" class="subModuleCB" <?php if (in_array('system:users', $UsrAcc, true)) echo 'checked' ?>>
												System - Users </label>
											<label class="checkbox">
												<input type="checkbox" name="accessCheckbox" value="system:categories" class="subModuleCB" <?php if (in_array('system:categories', $UsrAcc, true)) echo 'checked' ?>>
												System - Categories </label>
											<label class="checkbox">
												<input type="checkbox" name="accessCheckbox" value="system:attributes" class="subModuleCB" <?php if (in_array('system:attributes', $UsrAcc, true)) echo 'checked' ?>>
												System - Attributes </label>
											<label class="checkbox">
												<input type="checkbox" name="accessCheckbox" value="system:people" class="subModuleCB" <?php if (in_array('system:people', $UsrAcc, true)) echo 'checked' ?>>
												System - People </label>
											<label class="checkbox">
												<input type="checkbox" name="accessCheckbox" value="system:places" class="subModuleCB" <?php if (in_array('system:places', $UsrAcc, true)) echo 'checked' ?>>
												System - Places </label>
											<label class="checkbox">
												<input type="checkbox" name="accessCheckbox" value="system:filetree" class="subModuleCB" <?php if (in_array('system:filetree', $UsrAcc, true)) echo 'checked' ?>>
												System - Filetree </label>
											
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
