<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once('../website/classes/pagecontent.cls.php'); 

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$PgcDao = new PgcDAO();
$content = $PgcDao->selectGeneric();

?>
<!doctype html>
<html>
<head>
<title>Generic Content</title>
<?php include('../webparts/headdata.php'); ?>
</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
	<?php include('../webparts/website-left.php'); ?>
	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>Generic Content</h1>
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
						<a>Website</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a href="website/generic-content.php">Generic Content</a>
					</li>
				</ul>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-comments"></i> Generic Content</h3>
							<div class="actions">
								<a href="website/generic-content-edit.php" class="btn btn-mini" rel="tooltip" title="New Content"><i class="icon-plus"></i></a>
							</div>
						</div>
						<div class="box-content nopadding">
							<table class="table table-bordered table-striped table-highlight" id="contentTable">
							<thead>
								<tr>
									<th>Name</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$tableLength = count($content);
								for ($i=0;$i<$tableLength;++$i) {
								?>
								<tr>
									<td><a href="website/generic-content-edit.php?pgc_id=<?php echo $content[$i]['pgc_id'] ?>"><?php echo $content[$i]['pgcttl'] ?></a></td>
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
