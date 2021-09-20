<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 

require_once("classes/users.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$searchUserID = (isset($_GET['usr_id']) && is_numeric($_GET['usr_id'])) ? $_GET['usr_id'] : NULL;
$searchUserName = (isset($_GET['usrnam'])) ? $_GET['usrnam'] : NULL;

$TmpUsr = new UsrDAO();
$users = $TmpUsr->select($searchUserID, $searchUserName);

?>
<!doctype html>
<html>
<head>
<title>User Listing</title>
<?php include('../webparts/headdata.php'); ?>
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
						<a href="users.php">Users</a>
					</li>
				</ul>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-group"></i> Administration Users</h3>
							<div class="actions">
								<a href="system/user-edit.php" class="btn btn-mini" rel="tooltip" title="New User"><i class="icon-plus"></i></a>
							</div>
						</div>
						<div class="box-content nopadding">
							<table class="table table-bordered table-striped table-highlight" id="userTable">
							<thead>
								<tr>
									<th>#</th>
									<th>User Name</th>
									<th>Email</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$tableLength = count($users);
								for ($i=0;$i<$tableLength;++$i) {
								?>
								<tr>
									<td><?php echo $users[$i]['usr_id']; ?></td>
									<td><a href="<?php $patchworks->pwRoot; ?>system/user-edit.php?usr_id=<?php echo $users[$i]['usr_id']; ?>"><?php echo $users[$i]['usrnam']; ?></a></td>
									<td><?php echo $users[$i]['usrema']; ?></td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
						</div>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>
</body>
</html>
