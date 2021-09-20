<?php 

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("../system/classes/places.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpPla = new PlaDAO();
$projects = NULL;
$projects = $TmpPla->selectPlaceBookings(NULL, 'PROJECT', NULL, NULL, 0, false); 

?>


<div id="left" class="sidebar-fixed">
	
	<div class="subnav" id="projectNav">

        <div class="subnav-title">
            <a href="#" class="toggle-subnav"><i class="icon-angle-down"></i><span>Content</span></a>
        </div>

        <ul class="subnav-menu">
            <li>
                <a href="projects/dashboard.php">Dashboard</a>
                <a href="projects/calendar.php">Calendar</a>
                <a href="projects/projects.php">Projects</a>
            </li>
        </ul>

		<div class="subnav-title">
			<a href="#" class='toggle-subnav'><i class="icon-angle-down"></i><span>Projects</span></a>
		</div>
		<ul class="subnav-menu">
            <?php
			$tableLength = count($projects);
			for ($i=0;$i<$tableLength;++$i) {
			?>
			
			<li class="dropdown <?php if ( basename($_SERVER['PHP_SELF'], ".php") == 'calendar') echo 'external-event'; ?>" data-pla_id="<?php echo $projects[$i]['pla_id']; ?>" data-placol="<?php echo $projects[$i]['placol']; ?>" data-planam="<?php echo $projects[$i]['planam']; ?>">
				<a href="#" data-toggle="dropdown"><?php echo $projects[$i]['planam'] ?></a>
				<ul class="dropdown-menu">
					<li>
						<a href="projects/project-edit.php?cus_id=<?php echo $projects[$i]['tbl_id']; ?>&pla_id=<?php echo $projects[$i]['pla_id'] ?>">Edit</a>
					</li>
					<li>
						<a href="projects/dashboard.php#inactive" class="inactiveLink" data-pla_id="<?php echo $projects[$i]['pla_id'] ?>">Set as Inactive</a>
					</li>
					
					<?php if (strlen($projects[$i]['plaurl']) > 0 ) { ?>
					<li>
						<a href="<?php echo $projects[$i]['plaurl']; ?>" target="_blank">Launch Website</a>
					</li>
					<?php } ?>
					

				</ul>
			</li>
			
			<?php } ?>
			
		</ul>
	</div>
	
</div>

<script>

function inactiveProject(iPla_ID) {

	alert('JS click ' + iPla_ID);
	
	return false;
	
}

$(document).ready(function(){
//$(window).load(function() {
//$(function(){
	
	$('.inactiveLink').each(function(){
		
		$(this).bind('click', function(e){
			alert($(this).data('pla_id'));
		});
		
//		$(this).click(function(e){
//			
//			e.preventDefault();
//		
//			alert($(this).data('pla_id'));
//		
//			$(this).closest('.dropdown').fadeOut('fast');
//		
//		});
		
	});
	
//	$('.inactiveLink').click(function(e){
//		e.preventDefault();
//	});
//	
//	$('#projectNav').on('click', '.inactiveLink', function(e){
//		
//		e.preventDefault();
//		
//		alert($(this).data('pla_id'));
//		
//		$(this).closest('.dropdown').fadeOut('fast');
//		
//	});
	
//	$('.inactiveLink').click(function(e){
//		e.preventDefault();
//		
//		alert($(this).data('pla_id'));
//		
//		$(this).closest('.dropdown').fadeOut('fast');
		
//		$.ajax({
//			url: 'system/places_script.php',
//			data: 'action=update&ajax=true&pla_id='+$(this).data('pla_id')+'&sta_id=1',
//			type: 'POST',
//			async: false,
//			success: function( data ) {
//				
//				//alert( data );
//				
//				try {
//					
//					var result = JSON.parse(data);
//					
//					$.msgGrowl ({
//						type: result.type
//						, title: result.title
//						, text: result.description
//					});
//					
//					
//				} catch(Ex) {
//					
//					$.msgGrowl ({
//						type: 'error'
//						, title: 'Error'
//						, text: Ex
//					});
//				}
//	
//			},
//			error: function (x, e) {
//				throwAjaxError(x, e);
//				
//			}
//		});
		
//	});

	

	
});

</script>