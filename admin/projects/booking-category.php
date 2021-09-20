<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("../system/classes/categories.cls.php");
require_once("../system/classes/subcategories.cls.php");


$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TblNam = 'booking-category';
$TmpCat = new CatDAO();
$categoryRec = $TmpCat->select(NULL,$TblNam,NULL,NULL,true);

if (!isset($categoryRec->cat_id) || is_null($categoryRec->cat_id)) {
	
	$CatObj = new stdClass();
		
	$CatObj->cat_id = 0;
	$CatObj->tblnam = 'booking-category';
	$CatObj->tbl_id = 0;
	$CatObj->catnam = 'Booking Category';
	$CatObj->seourl = 'booking-category';
	$CatObj->keywrd = 'Booking Category';
	$CatObj->keydsc = 'Booking Category';
	$CatObj->sta_id = 0;
	$Cat_ID = $TmpCat->update($CatObj);
	
} else {
	$Cat_ID = $categoryRec->cat_id;
}

$TmpSub = new SubDAO();
$subCategories = $TmpSub->selectByTableName($TblNam);

?>
<!doctype html>
<html>
<head>
<title>Booking Categories</title>
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
					<h1>Booking Categories</h1>
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
						<a>Projects</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a href="products/product-category.php">Booking Categories</a>
					</li>
				</ul>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-comments"></i> Booking Categories</h3>
							<div class="actions">
								<a href="projects/booking-category-edit.php?cat_id=<?php echo $Cat_ID; ?>" class="btn btn-mini" rel="tooltip" title="New Article"><i class="icon-plus"></i></a>
							</div>
						</div>
						<div class="box-content nopadding">
							<table class="table table-bordered table-striped table-highlight" id="articleCatTable">
							<thead>
								<tr>
									<th>Product Category Name</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$tableLength = count($subCategories);
								for ($i=0;$i<$tableLength;++$i) {
								?>
								<tr>
									<td><a href="projects/booking-category-edit.php?sub_id=<?php echo $subCategories[$i]['sub_id'] ?>&cat_id=<?php echo $subCategories[$i]['cat_id']; ?>"><?php echo $subCategories[$i]['subnam'] ?></a></td>
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
