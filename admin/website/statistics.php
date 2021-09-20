<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("../attributes/classes/attrgroups.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

?>
<!doctype html>
<html>
<head>
<title>Website Statistics</title>
<?php include('../webparts/headdata.php'); ?>

<!-- Flot -->
<script src="js/plugins/flot/jquery.flot.min.js"></script>
<script src="js/plugins/flot/jquery.flot.resize.min.js"></script>

<script src="website/js/statistics.js"></script>

<style>

#visitorLineChart div.xAxis div.tickLabel 
{   
	padding-right: 10px;
	width: 80px;
    transform: rotate(-90deg);
    -ms-transform:rotate(-90deg); /* IE 9 */
    -moz-transform:rotate(-90deg); /* Firefox */
    -webkit-transform:rotate(-90deg); /* Safari and Chrome */
    -o-transform:rotate(-90deg); /* Opera */
    /*rotation-point:50% 50%;*/ /* CSS3 */
    /*rotation:270deg;*/ /* CSS3 */
}

</style>

</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
	<?php include('../webparts/website-left.php'); ?>
	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>Website Statistics</h1>
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
						<a>Statistics</a>
					</li>
				</ul>
			</div>
			<div class="row-fluid">
				<div class="span4">
					
					<div class="box box-color satgreen box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-wrench"></i> Summary</h3>
							<div class="actions">
								<!--<a href="website/forms-edit.php" class="btn btn-mini" rel="tooltip" title="New Form"><i class="icon-file"></i></a>-->
							</div>
						</div>
						<div class="box-content">
					
							<ul class="tiles tiles-center nomargin">
								<li class="blue">
									<span class="label label-info" id="StatSiteVisits">0</span>
									<a href="#"><span><i class="icon-signin"></i></span><span class="name">Visits</span></a>
								</li>
								<li class="satgreen">
									<span class="label label-info" id="StatUniqueVisits">0</span>
									<a href="#"><span><i class="icon-bolt"></i></span><span class="name">Unique Visitors</span></a>
								</li>
								<li class="darkblue">
									<span class="label label-important" id="StatPageViews">0</span>
									<a href="#"><span><i class="icon-eye-open"></i></span><span class="name">Page Views</span></a>
								</li>
								<li class="lightred">
									<span class="label label-inverse" id="StatBounceRate">0</span>
									<a href="#"><span><i class="icon-reply"></i></span><span class="name">Bounce Rate</span></a>
								</li>
							</ul>
						</div>
					</div>
				
				</div>
				<div class="span8">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-comments"></i> Statistics</h3>
							<div class="actions">
								<!--<a href="website/forms-edit.php" class="btn btn-mini" rel="tooltip" title="New Form"><i class="icon-file"></i></a>-->
							</div>
						</div>
						<div class="box-content">
							<div id="visitorLineChart" class="flot medium">
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="row-fluid">
				<div class="span4">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-comments"></i> Referrers</h3>
							<div class="actions">
								<!--<a href="website/forms-edit.php" class="btn btn-mini" rel="tooltip" title="New Form"><i class="icon-file"></i></a>-->
							</div>
						</div>
						<div class="box-content nopadding">
							<table class="table table-striped table-bordered responsive">
								<thead>
									<tr>
										<th>Referrer</th>
										<th>Unique</th>
									</tr>
								</thead>
								<tbody id="TopReferrersList">
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="span4">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-comments"></i> Top Pages</h3>
							<div class="actions">
								<!--<a href="website/forms-edit.php" class="btn btn-mini" rel="tooltip" title="New Form"><i class="icon-file"></i></a>-->
							</div>
						</div>
						<div class="box-content nopadding">
							<table class="table table-striped table-bordered responsive">
								<thead>
									<tr>
										<th>Page</th>
										<th>visits</th>
									</tr>
								</thead>
								<tbody id="MostVisitedPagesList">
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="span4">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-comments"></i> Top Search Terms</h3>
							<div class="actions">
								<!--<a href="website/forms-edit.php" class="btn btn-mini" rel="tooltip" title="New Form"><i class="icon-file"></i></a>-->
							</div>
						</div>
						<div class="box-content nopadding">
							<table class="table table-striped table-bordered responsive">
								<thead>
									<tr>
										<th>Page</th>
										<th>visits</th>
									</tr>
								</thead>
								<tbody id="SearchTermsList">
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
