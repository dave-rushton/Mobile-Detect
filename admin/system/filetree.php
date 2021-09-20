<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

?>
<!doctype html>
<html>
<head>
<title>System Filetree</title>
<?php include('../webparts/headdata.php'); ?>
<!-- Elfinder -->
<link rel="stylesheet" href="js/plugins/elfinder/css/elfinder.min.css">
<link rel="stylesheet" href="js/plugins/elfinder/css/theme.css">

</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
	<?php include('../webparts/system-left.php'); ?>
	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>System Filetree</h1>
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
						<a href="users.php">Filetree</a>
					</li>
				</ul>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-sitemap"></i> Filetree</h3>
						</div>
						<div class="box-content nopadding">
							<div id="fileTree"></div>
						</div>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>
</body>


<!-- Elfinder -->
<script src="js/plugins/elfinder/js/elfinder.min.js"></script>
<script>

$(function(){

	$('#fileTree').elfinder({
		url:'js/plugins/elfinder/php/connector.minimal.php'
	});

});

</script>

</html>
