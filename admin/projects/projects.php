<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("../system/classes/places.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

?>
<!doctype html>
<html>
<head>
<title>Projects</title>
<?php include('../webparts/headdata.php'); ?>
<!-- dataTables -->
<script src="js/plugins/datatable/jquery.dataTables.min.js"></script>
<script src="js/plugins/datatable/TableTools.min.js"></script>
<script src="js/plugins/datatable/ColReorder.min.js"></script>
<script src="js/plugins/datatable/ColVis.min.js"></script>
<script src="js/plugins/datatable/jquery.dataTables.columnFilter.js"></script>


<script>

var projectsTable;

$(function(){
	
	$('#projectBody').on('change', '.proactive', function(){
		
		if ( $(this).prop('checked') ) { $(this).closest('tr').removeClass('error'); } else { $(this).closest('tr').addClass('error'); }
		
		var staID = ($(this).prop('checked')) ? '0' : '1';
		
		$.ajax({
			url: 'system/places_script.php',
			data: 'action=update&ajax=true&pla_id=' + $(this).val() + '&sta_id=' + staID,
			type: 'POST',
			async: false,
			success: function( data ) {
				
				try {
					
					var result = JSON.parse(data);
					
					$.msgGrowl ({
						type: result.type
						, title: result.title
						, text: result.description
					});
					
				} catch(Ex) {
					
					$.msgGrowl ({
						type: 'error'
						, title: 'Error'
						, text: Ex
					});
				}

			},
			error: function (x, e) {
				alert(x + ' ' + e);
				throwAjaxError(x, e);
				
			}
		});
		
	});
	
	$('#allProjects').click(function(e){
		e.preventDefault();
		getProjects();
	});
	
	getProjects();

});
	
function getProjects() {
	
	var staID = ($('#allProjects').hasClass('checkbox-active')) ? null : 0;
	
	//alert( 'tblnam=PROJECT&tbl_id='+$('[name="pla_id"]', customerForm).val()+'&sta_id=0' );

	$.ajax({
		url: 'projects/projects_table.php',
		data: 'tblnam=PROJECT&sta_id='+staID,
		type: 'GET',
		async: false,
		success: function( data ) {
			
			try { projectsTable.fnDestroy(); } catch (ex) {}
			
			$('#projectBody').html( data );
			
			projectsTable = $('#projectsTable').dataTable({"iDisplayLength": 50, "bDestroy": true});
			
		}
	});

    $('#projectsTable').prev().find('input').focus();

}

</script>

</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">

	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>Projects</h1>
				</div>
				<div class="pull-right">
					<?php include('../webparts/bookings-left.php'); ?>
				</div>
			</div>
			<div class="breadcrumbs">
				<ul>
					<li>
						<a href="index.php">Dashboard</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a href="projects/dashboard.php">Bookings</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a>Projects</a>
					</li>
				</ul>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<div class="box box-color box-bordered darkblue">
						<div class="box-title">
							<h3>
								<i class="icon-paste"></i> Projects</h3>
							<div class="actions">
								<a href="#" class="btn btn-mini custom-checkbox checkbox-active" id="allProjects">Show All<i class="icon-check-empty"></i></a>
								<a href="projects/project-edit.php" id="createProjectBtn" class="btn btn-mini" rel="tooltip" title="New Project"><i class="icon-plus-sign"></i></a>
							</div>
						</div>
						<div class="box-content nopadding">
						
							<table class="table" id="projectsTable">
								<thead>
									<tr>
										<th width="30"></th>
										<th>Project</th>
										<th>Client</th>
										<th style="text-align: right;">Estimate</th>
										<th style="text-align: right;">Current</th>
										<th style="text-align: right;">Planned</th>
										<th style="text-align: right;">Due</th>
										<th style="text-align: right;">%</th>
										<th></th>
									</tr>
								</thead>
								<tbody id="projectBody">
									
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
