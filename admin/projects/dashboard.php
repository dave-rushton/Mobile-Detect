<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("../projects/classes/bookings.cls.php");
require_once("../system/classes/places.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpBoo = new BooDAO();

$BegDat = date('Y-m-d', strtotime('monday this week'));
$EndDat = date('Y-m-d', strtotime('sunday this week'));

$TblNam = NULL;
$Tbl_ID = NULL;
$RefNam = NULL;
$Ref_ID = NULL;
$Sta_ID = NULL;

$bookings = $TmpBoo->select(NULL, $BegDat, $EndDat, $TblNam, $Tbl_ID, $RefNam, $Ref_ID, $Sta_ID, false);

$totalHours = 0;
$tableLength = count($bookings);
for ($i=0;$i<$tableLength;++$i) {
	$hourdiff = round((strtotime($bookings[$i]['enddat']) - strtotime($bookings[$i]['begdat']))/3600, 2);
	$totalHours += $hourdiff;
}
$tw_totalHours = $totalHours;
$tw_availability = ($totalHours / 50) * 100;


$BegDat = date('Y-m-d', strtotime('monday next week'));
$EndDat = date('Y-m-d', strtotime('sunday next week'));

$bookings = $TmpBoo->select(NULL, $BegDat, $EndDat, $TblNam, $Tbl_ID, $RefNam, $Ref_ID, $Sta_ID, false);

$totalHours = 0;
$tableLength = count($bookings);
for ($i=0;$i<$tableLength;++$i) {
	$hourdiff = round((strtotime($bookings[$i]['enddat']) - strtotime($bookings[$i]['begdat']))/3600, 2);
	$totalHours += $hourdiff;
}
$nw_totalHours = $totalHours;
$nw_availability = ($totalHours / 50) * 100;

$TmpPla = new PlaDAO();

?>
<!doctype html>
<html>
<head>
<title>Bookings Dashboard</title>
<?php include('../webparts/headdata.php'); ?>
<link rel="stylesheet" href="css/plugins/easy-pie-chart/jquery.easy-pie-chart.css">
<script src="js/plugins/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>
<!-- Flot -->
<script src="js/plugins/flot/jquery.flot.min.js"></script>
<script src="js/plugins/flot/jquery.flot.stack.js"></script>
<script src="js/plugins/flot/jquery.flot.resize.min.js"></script>
<script src="js/system.date.js"></script>
<script src="projects/js/dashboard.js"></script>
<style>
#lineChart div.xAxis div.tickLabel {
	padding-right: 20px;
	margin-left: -10px;
	width: 40px !important;
	transform: rotate(-90deg);
	-ms-transform: rotate(-90deg); /* IE 9 */
	-moz-transform: rotate(-90deg); /* Firefox */
	-webkit-transform: rotate(-90deg); /* Safari and Chrome */
	-o-transform: rotate(-90deg); /* Opera *//*rotation-point:50% 50%;*/ /* CSS3 */
    /*rotation:270deg;*/ /* CSS3 */
}
</style>
</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-orange" data-layout-topbar="fixed">
<div class="container-fluid" id="content">
	<?php include('../webparts/bookings-left.php'); ?>
	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>Bookings Dashboard</h1>
				</div>
				<div class="pull-right">
					<?php //include('../webparts/bookings-left.php'); ?>
				</div>
			</div>
			<div class="breadcrumbs">
				<ul>
					<li> <a href="index.php">Dashboard</a> <i class="icon-angle-right"></i> </li>
					<li> <a>Bookings Dashboard</a> </li>
				</ul>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<div id="placeholder" style="width: 100%; height: 200px; display: none;"></div>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span8">
					<h3>Projects</h3>
					<ul class="tiles">
						
						<!--<li class="blue">
							<a href="ecommerce/projects.php"><span class='count'><i class="icon-angle-right"></i></span><span class='name'>Projects</span></a>
						</li>-->
						
						<?php
						
						$projects = NULL;
						$projects = $TmpPla->selectPlaceStats(NULL, 'PROJECT', NULL, NULL, 0, false, false); 
						
						$tableLength = count($projects);
						for ($i=0;$i<$tableLength;++$i) {
							
							$completion = (is_numeric($projects[$i]['rooms']) && $projects[$i]['rooms'] > 0) ? number_format(($projects[$i]['tothrs'] / $projects[$i]['rooms']) * 100,2) : 0;
							
						?>
						<li class="orange <?php echo ($completion > 0) ? 'has-chart' : ''; ?>" style="background: <?php echo $projects[$i]['placol']; ?>">
							<?php if ($completion > 0) { ?>
							<span class="label label-info" style="font-size: 10px; font-weight: normal;"><?php echo number_format($projects[$i]['tothrs'],2) .'/'. number_format($projects[$i]['rooms'],2); ?></span>
							<?php } ?>
							<a href="projects/project-edit.php?cus_id=<?php echo $projects[$i]['tbl_id']; ?>&pla_id=<?php echo $projects[$i]['pla_id'] ?>"> <span>
							<?php if ($completion > 0) { ?>
							<div class="chart projectChart" data-percent="<?php echo $completion; ?>" data-color="#ffffff" data-trackcolor="<?php echo $projects[$i]['placol']; ?>">
								<?php echo $completion; ?>%
							</div>
							<?php } else { ?>
							<i class="icon-paste"></i>
							<?php } ?>
							</span> <span class="name"><?php echo $projects[$i]['planam'] ?></span> </a> </li>
						<?php } ?>
					</ul>
					<div class="clearfix"></div>
					
					
				</div>
				<div class="span4">
				
					<h3>Planned Work</h3>
					<ul class="stats">
								
					<?php
					$planned = $TmpPla->selectPlaceBookings(NULL, 'PROJECT', NULL, NULL, 0, false, true); 
					$tableLength = count($planned);
					$plannedHours = 0;
					$plannedCharge = 0;
					for ($i=0;$i<$tableLength;++$i) {
						
						$plannedHours += $planned[$i]['tothrs'];
						
						//$plannedHours += $planned[$i]['tothrs']*$planned[$i]['prdcst'];
						
						//$completion = (is_numeric($planned[$i]['rooms']) && $planned[$i]['rooms'] > 0) ? number_format(($planned[$i]['tothrs'] / $planned[$i]['rooms']) * 100,2) : 0;
						
					?>
					
					<li class="orange" style="background: <?php echo $planned[$i]['placol']; ?>; margin-bottom: 10px;">
						<a href="projects/project-edit.php?cus_id=<?php echo $planned[$i]['tbl_id']; ?>&pla_id=<?php echo $planned[$i]['pla_id'] ?>">
						<i class="icon-time"></i>
						<div class="details">
							<span class="big"><?php echo number_format($planned[$i]['tothrs'],2); ?></span>
							<span><?php echo $planned[$i]['planam'] ?></span>
						</div>
						</a>
					</li>
					
					<?php } ?>
					
					
					
				</ul>
				
				<div style="clear: both;"></div>
				
				<div class="alert alert-info">
					<p><strong>Total: </strong><?php echo $plannedHours; ?> hrs</p>
					<p><strong>Charge: </strong><?php echo $plannedCharge; ?> hrs</p>
				</div>
					
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<div class="box box-color box-bordered satblue">
						<div class="box-title">
							<h3> <i class="icon-bar-chart"></i> Booking Earnings</h3>
							<div class="actions">
								<!--<a href="website/forms-edit.php" class="btn btn-mini" rel="tooltip" title="New Form"><i class="icon-file"></i></a>-->
							</div>
						</div>
						<div class="box-content">
							<div id="lineChart" class="flot medium">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span4">
				
					<div class="box box-color satgreen box-bordered">
						<div class="box-title">
							<h3> <i class="icon-time"></i> Weekly Availability <span id="avlPercent"></span></h3>
							<div class="actions">
								<a href="#" class="btn btn-mini" id="prevWeek" rel="tooltip" title="Previous Week"><i class="icon-angle-left"></i></a> <a href="#" class="btn btn-mini" id="nextWeek" rel="tooltip" title="Next Week"><i class="icon-angle-right"></i></a>
							</div>
						</div>
						<div class="box-content" style="background: #f3f3f3;">
							<form id="bookingDashForm" class="hide">
								<input type="text" name="begdat" class="input-small">
								<input type="text" name="enddat" class="input-small">
							</form>
							<div class="pagestats bar">
								<span id="monAvl"></span>
								<div class="progress small">
									<div id="day1" style="width:40%" class="bar bar-blue">
									</div>
								</div>
							</div>
							<div class="pagestats bar">
								<span id="tueAvl"></span>
								<div class="progress small">
									<div id="day2" style="width:80%" class="bar bar-red">
									</div>
								</div>
							</div>
							<div class="pagestats bar">
								<span id="wedAvl"></span>
								<div class="progress small">
									<div id="day3" style="width:30%" class="bar bar-blue">
									</div>
								</div>
							</div>
							<div class="pagestats bar">
								<span id="thuAvl"></span>
								<div class="progress small">
									<div id="day4" style="width:40%" class="bar bar-blue">
									</div>
								</div>
							</div>
							<div class="pagestats bar">
								<span id="friAvl"></span>
								<div class="progress small">
									<div id="day5" style="width:66%" class="bar bar-orange">
									</div>
								</div>
							</div>
							<div class="pagestats bar">
								<span id="satAvl"></span>
								<div class="progress small">
									<div id="day6" style="width:5%" class="bar bar-green">
									</div>
								</div>
							</div>
							<div class="pagestats bar">
								<span id="sunAvl"></span>
								<div class="progress small">
									<div id="day0" style="width:15%" class="bar bar-satgreen">
									</div>
								</div>
							</div>
							<ul class="pagestats style-3" style="display: none;">
								<li id="avlData">
									<div class="spark">
										<div class="chart" data-percent="<?php echo $tw_availability; ?>" >
											<?php echo $tw_availability; ?>%
										</div>
									</div>
									<div class="bottom">
										<span class="name"><?php echo $tw_totalHours; ?> hrs</span>
									</div>
								</li>
								<li style="display: none;">
									<div class="spark">
										<div class="chart" data-percent="<?php echo $nw_availability; ?>" >
											<?php echo $nw_availability; ?>%
										</div>
									</div>
									<div class="bottom">
										<span class="name">Next Week<br>
										(<?php echo $nw_totalHours; ?> hrs)</span>
									</div>
								</li>
							</ul>
						</div>
					</div>
				
					
				</div>
				<div class="span8">
				
					<div class="box box-bordered box-color lime">
						<div class="box-title">
							<h3> <i class="icon-calendar"></i> Weekly Dependancy </h3>
							<div class="actions">
								
							</div>
						</div>
						<div class="box-content" id="actList">
						
							<div id="dependancyChart" class="flot medium">
							</div>
						
						</div>
					</div>
				
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

$(function() {
	
	
	var projectArray = [];
	var projectTicks = [];
	
	var pronam = [];
	var tothrs = [];
	var plnhrs = [];
	var esthrs = [];
	
	$.ajax({
		url: "projects/json/project.dependancy.php",
		type: "GET",
		
		//data: hldData + '&cuspro=PRO',
		
		//data: 'pla_id=' + $('[name="pla_id"]', customerForm).val(),
		async: true,
		success: function(data) {
		
			//alert(data);
			
			var jsonArray = JSON.parse(data);
			
			for (i=0;i<jsonArray.length;i++) {
				
				if ( jsonArray[i].proest > 0 ) {
				
				
					pronam.push([i, jsonArray[i].pronam]);
					tothrs.push([i, jsonArray[i].tothrs]);
					plnhrs.push([i, jsonArray[i].plnhrs]);
					esthrs.push([i, jsonArray[i].proest]);
					
				}
			}
			projectTicks.push(pronam);
			
			projectArray.push(tothrs);
			projectArray.push(plnhrs);
			projectArray.push(esthrs);
			
			//plotWithOptions();
		
		},
		error: function (x, e) {
			throwAjaxError(x, e);
		}
	});
	

	var stack = true,
		bars = true,
		lines = false,
		steps = false;

	function plotWithOptions() {
		$.plot("#placeholder", projectArray, {
			series: {
				stack: true,
				lines: {
					show: lines,
					fill: true,
					steps: steps
				},
				bars: {
					show: bars,
					barWidth: 0.6
				}
			},
			xaxis: {  
				axisLabel: "Project",         
				ticks: projectTicks
			}
		});
	}
	
});

</script>

</body>
</html>
