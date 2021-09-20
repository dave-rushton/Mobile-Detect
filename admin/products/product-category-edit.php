<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("../system/classes/categories.cls.php");
require_once("../system/classes/subcategories.cls.php");


$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');


$TblNam = 'product-category';
$TmpCat = new CatDAO();
$categoryRec = $TmpCat->select(NULL,$TblNam,NULL,NULL,true);

if (is_null($categoryRec->cat_id)) {
	
	$CatObj = new stdClass();
		
	$CatObj->cat_id = 0;
	$CatObj->tblnam = 'product-category';
	$CatObj->tbl_id = 0;
	$CatObj->catnam = 'Product Category';
	$CatObj->seourl = 'product-category';
	$CatObj->keywrd = 'Product Category';
	$CatObj->keydsc = 'Product Category';
	$CatObj->sta_id = 0;
	$Cat_ID = $TmpCat->update($CatObj);
	
} else {
	$Cat_ID = $categoryRec->cat_id;
}

//$Cat_ID = (isset($_REQUEST['cat_id']) && is_numeric($_REQUEST['cat_id'])) ? $_REQUEST['cat_id'] : NULL;
$Sub_ID = (isset($_REQUEST['sub_id']) && is_numeric($_REQUEST['sub_id'])) ? $_REQUEST['sub_id'] : NULL;
$SubDao = new SubDAO();
if (!is_null($Sub_ID)) $SubObj = $SubDao->select(NULL, $Sub_ID, NULL, NULL, true);

?>
<!doctype html>
<html>
<head>
<title>Product Category : <?php echo(isset($SubObj)) ? $SubObj->subnam : 'New Product Category'; ?></title>
<?php include('../webparts/headdata.php'); ?>


<!-- colorbox -->
<link rel="stylesheet" href="css/plugins/colorbox/colorbox.css">

<!-- colorbox -->
<script src="js/plugins/colorbox/jquery.colorbox-min.js"></script>
<!-- masonry -->
<script src="js/plugins/masonry/jquery.masonry.min.js"></script>
<!-- imagesloaded -->
<script src="js/plugins/imagesLoaded/jquery.imagesloaded.min.js"></script>

<!-- Plupload -->
<link rel="stylesheet" href="css/plugins/plupload/jquery.plupload.queue.css">
<!-- PLUpload -->
<script src="js/plugins/plupload/plupload.full.js"></script>
<script src="js/plugins/plupload/jquery.plupload.queue.js"></script>

<script src="products/js/product-category-edit.js"></script>
</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
	<?php include('../webparts/website-left.php'); ?>
	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>Product Category</h1>
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
						<a>Products</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a href="products/product-category.php">Product Categories</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a><?php echo(isset($SubObj)) ? $SubObj->subnam : 'New Product Category'; ?></a>
					</li>
				</ul>
			</div>
			<div class="row-fluid">
				<div class="span6">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-comments"></i> Product Category</h3>
							<div class="actions">
								<a href="#" id="updateSubCategoryBtn" class="btn btn-mini" rel="tooltip" title="Update"><i class="icon-save"></i></a>
								<a href="#" id="deleteSubCategoryBtn" class="btn btn-mini" rel="tooltip" title="Delete"><i class="icon-trash"></i></a>
							</div>
						</div>
						<div class="box-content">
							<form action="system/subcategories_script.php" id="subCategoriesForm" class="form-horizontal" data-returnurl="products/product-category.php">
								<input type="hidden" name="sub_id" id="id" value="<?php echo(isset($SubObj)) ? $SubObj->sub_id : '0'; ?>">
								<input type="hidden" name="cat_id" value="<?php echo $Cat_ID; ?>">
								<div class="control-group">
									<label class="control-label">Product Category Name</label>
									<div class="controls">
										<input type="text" class="input-block-level" name="subnam" value="<?php echo(isset($SubObj)) ? $SubObj->subnam : ''; ?>">
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
            <div class="row-fluid">
				<div class="span12">
					<div class="box">
						<div class="box-title">
							<h3>
								<i class="icon-picture"></i> Gallery Images </h3>
						</div>
						<div class="box-content">
						
							<ul class="gallery gallery-dynamic" id="galleryImages">
								
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<div class="box" <?php // echo($galleryRec) ? '' : 'style="display: none;"'; ?>>
						<div class="box-title">
							<h3><i class="icon-th"></i> Multi File upload</h3>
							<div class="actions">
								<a class="btn btn-mini content-slideUp" href="#">
									<i class="icon-angle-down"></i>
								</a>
							</div>
						</div>
						<div class="box-content nopadding">
							<div id="plupload">
							</div>
						</div>
					</div>
				</div>
			</div>
            
			
		</div>
	</div>
</div>
</body>
</html>
