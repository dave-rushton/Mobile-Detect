<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("../system/classes/subcategories.cls.php");


$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');


$Cat_ID = (isset($_REQUEST['cat_id']) && is_numeric($_REQUEST['cat_id'])) ? $_REQUEST['cat_id'] : NULL;
$Sub_ID = (isset($_REQUEST['sub_id']) && is_numeric($_REQUEST['sub_id'])) ? $_REQUEST['sub_id'] : NULL;
$SubDao = new SubDAO();
if (!is_null($Sub_ID)) $SubObj = $SubDao->select(NULL, $Sub_ID, NULL, NULL, true);

?>
<!doctype html>
<html>
<head>
<title>Department : <?php echo(isset($SubObj)) ? $SubObj->subnam : 'New Department'; ?></title>
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

<script src="products/js/department-edit.js"></script>
</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
	<?php include('../webparts/website-left.php'); ?>
	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>Department</h1>
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
						<a href="products/dashboard.php">Products Dashboard</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a href="products/departments.php">Departments</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a><?php echo(isset($SubObj)) ? $SubObj->subnam : 'New Department'; ?></a>
					</li>
				</ul>
			</div>
			<div class="row-fluid">
				<div class="span6">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-comments"></i> Department</h3>
							<div class="actions">
								<a href="#" id="updateSubCategoryBtn" class="btn btn-mini" rel="tooltip" title="Update"><i class="icon-save"></i></a>
								<a href="#" id="deleteSubCategoryBtn" class="btn btn-mini" rel="tooltip" title="Delete"><i class="icon-trash"></i></a>
							</div>
						</div>
						<div class="box-content">
							<form action="system/subcategories_script.php" id="subCategoriesForm" class="form-horizontal" data-returnurl="products/departments.php">
								<input type="hidden" name="sub_id" id="id" value="<?php echo(isset($SubObj)) ? $SubObj->sub_id : '0'; ?>">
								<input type="hidden" name="cat_id" value="<?php echo $Cat_ID; ?>">
								<div class="control-group">
									<label class="control-label">Department Name</label>
									<div class="controls">
										<input type="text" class="input-block-level" name="subnam" value="<?php echo(isset($SubObj)) ? $SubObj->subnam : ''; ?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">SEO URL</label>
									<div class="controls">
										<input type="text" class="input-block-level" name="seourl" value="<?php echo(isset($SubObj)) ? $SubObj->seourl : ''; ?>">
									</div>
								</div>

                                <div class="control-group">
                                    <label class="control-label">Status<small>department status</small></label>
                                    <div class="controls">
                                        <label class="radio">
                                            <input type="radio" name="sta_id" id="Sta_ID0" value="0" <?php echo(!isset($SubObj) || isset($SubObj) && $SubObj->sta_id == 0) ? 'checked' : ''; ?>>
                                            Active</label>
                                        <label class="radio">
                                            <input type="radio" name="sta_id" id="Sta_ID1" value="1" <?php echo(isset($SubObj) && $SubObj->sta_id == 1) ? 'checked' : ''; ?>>
                                            Inactive </label>
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

                    <div id="galleryImagesDiv">

                        <div class="box">
                            <div class="box-title">
                                <h3>
                                    <i class="icon-picture"></i> Gallery Images </h3>
                                <div class="actions">
                                    <a href="#" id="addImageBtn" class="btn btn-mini" rel="tooltip" title="Add Images"><i class="icon-plus"></i></a>
                                </div>
                            </div>
                            <div class="box-content">

                                <ul class="gallery gallery-dynamic" id="galleryImages">

                                </ul>
                            </div>
                        </div>

                    </div>

                    <div id="uploadImagesDiv" style="display: none;">

                        <div class="box">
                            <div class="box-title">
                                <h3>
                                    <i class="icon-picture"></i> Add Images To Gallery</h3>
                                <div class="actions">
                                    <a href="#" id="updateGalleryImagesBtn" class="btn btn-mini" rel="tooltip" title="Update"><i class="icon-save"></i></a>
                                    <a href="#" id="cancelGalleryImagesBtn" class="btn btn-mini" rel="tooltip" title="Cancel"><i class="icon-remove"></i></a>
                                </div>
                            </div>
                            <div class="box-content">

                                <ul class="gallery gallery-dynamic" id="imagelisting">

                                </ul>

                            </div>
                        </div>

                        <div class="box">
                            <div class="box-title">
                                <h3><i class="icon-th"></i> Multi File upload</h3>
                            </div>
                            <div class="box-content nopadding">
                                <div id="plupload" data-resize="<?php echo $patchworks->productImageSizes; ?>">
                                </div>
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
