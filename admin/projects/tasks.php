<?php
require_once("../config/patchworks.php");
require_once("../projects/classes/tasks.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: login.php');

$TmpBtk = new BtkDAO();
$tasks = $TmpBtk->select(NULL, NULL, NULL, NULL, false);

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Tasks | PatchWorks V5.0</title>
<?php include('../includes/pw.headdata.php'); ?>

<link href="./js/plugins/datatables/DT_bootstrap.css" rel="stylesheet">

<script src="./js/plugins/datatables/jquery.dataTables.js"></script>
<script src="./js/plugins/datatables/DT_bootstrap.js"></script>
<script>

$(function(){
	
	$('#tasksTable').dataTable( {
		sDom: "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
		sPaginationType: "bootstrap",
		oLanguage: {
			"sLengthMenu": "_MENU_ records per page"
		}
	});
		
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
					<a href="/">Dashboard</a>
					<span class="divider">/</span>
				</li>
				<li class="active">Bookings</li>
			</ul>
		</div>
		<div class="row">
			<div class="span12">
				<div class="widget widget-table">
					<div class="widget-header">
						<h3><i class="icon-pushpin"></i> Tasks</h3>
					</div>
					<div class="widget-toolbar">
						<a href="projects/tasks-edit.php" class="btn btn-primary btn-small"><i class="icon-plus"></i> Create Task</a>
					</div>
					<div class="widget-content">
						<table class="table table-bordered table-striped table-highlight" id="tasksTable">
							<thead>
								<tr>
									<th>Task</th>
									<th>Duration</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$tableLength = count($tasks);
								for ($i=0;$i<$tableLength;++$i) {
								?>
								<tr>
									<td><a href="<?php $patchworks->pwRoot; ?>projects/tasks-edit.php?btk_id=<?php echo $tasks[$i]['btk_id']; ?>"><?php echo $tasks[$i]['btkttl']; ?></a></td>
									<td><?php echo $tasks[$i]['btkdur']; ?></td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
					<div class="widget-toolbar">
						<a href="#" class="btn btn-primary btn-small"><i class="icon-print"></i> Print</a>
					</div>
				</div>
			</div>
		</div>
		
	</div>
</div>
<?php include('../includes/pw.footer.php'); ?>
</body>
</html>
