<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("classes/articles.cls.php");
require_once("classes/pages.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpArt = new ArtDAO();
$PagDAO = new PagDAO();
$Art_ID = (isset($_GET['art_id']) && is_numeric($_GET['art_id'])) ? $_GET['art_id'] : NULL;
$articles = $TmpArt->select($Art_ID, NULL, NULL, NULL, false);

$artpag = $PagDAO->select(NULL,NULL,NULL,18);


$artpag = $artpag[0];
$arturl = $artpag['seourl'];




?>
<!doctype html>
<html>
<head>
<title>Article Listing</title>
<?php include('../webparts/headdata.php'); ?>
    <script src="js/plugins/datatable/jquery.dataTables.min.js"></script>
    <script src="js/plugins/datatable/TableTools.min.js"></script>
    <script src="js/plugins/datatable/ColReorder.min.js"></script>
    <script src="js/plugins/datatable/ColVis.min.js"></script>
    <script src="js/plugins/datatable/jquery.dataTables.columnFilter.js"></script>
    <script src="js/plugins/datepicker/bootstrap-datepicker.js"></script>
    <script src="js/system.date.js"></script>
    <script src="website/js/articles.js"></script>
</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
	<?php include('../webparts/website-left.php'); ?>
	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>Website News</h1>
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
						<a href="website/articles.php">News</a>
					</li>
				</ul>
			</div>
			<div class="row-fluid">
				<div class="span12">
                    <div class="box box-color box-bordered">
                        <div class="box-title">
                            <h3> <i class="icon-tags"></i>Website News</h3>
                            <div class="actions">
                                <a href="website/articles-edit.php" class="btn btn-mini" rel="tooltip" title="New Article"><i class="icon-plus"></i></a>
                                <!--								<a href="ecommerce/order.export.php" class="btn btn-mini" rel="tooltip" title="Order Export" target="_blank"><i class="icon-download"></i></a>-->
                            </div>
                        </div>

                        <div class="box-content nopadding">

                            <table class="table table-nomargin table-striped" id="articlesTable">
                                <thead>
                                <tr>
                                    <th style="width: 160px">Published</th>
                                    <th style="width: 160px">Published</th>
                                    <th>Article Name</th>
                                    <th>Category</th>
                                    <th>Social Media</th>
                                    <th>Preview</th>
                                </tr>
                                </thead>
                                <tbody id="articlesBody">

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
