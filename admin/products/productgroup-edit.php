<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("../attributes/classes/attrgroups.cls.php");
require_once("../system/classes/subcategories.cls.php");
require_once("../system/classes/categories.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpAtr = new AtrDAO();

$editAttrGroup = (isset($_GET['atr_id']) && is_numeric($_GET['atr_id'])) ? $_GET['atr_id'] : NULL;
$attrGroupRec = NULL;
$attrLabelRec = NULL;

if (!is_null($editAttrGroup)) {
	$attrGroupRec = $TmpAtr->select($editAttrGroup, NULL, NULL, NULL, true); 
}

$TblNam = 'shopping-departments';
$CatDao = new CatDAO();
$category = $CatDao->select(NULL, $TblNam, NULL, NULL, true);
$TmpSub = new SubDAO();
$subCategories = $TmpSub->select($category->cat_id, NULL, NULL, NULL, false);


$productCats = $TmpSub->selectByTableName('product-category');

?>
<!doctype html>
<html>
<head>
<title>Product Group</title>
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

<script src="products/js/productgroup-edit.js"></script>
</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
	<?php include('../webparts/website-left.php'); ?>
	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>Product Group</h1>
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
						<a href="products/product-catalogue.php">Product Catalogue</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a href="products/productgroups.php">Product Groups</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a><?php echo($attrGroupRec) ? $attrGroupRec->atrnam : 'New Product Group'; ?></a>
					</li>
				</ul>
			</div>
			<div class="row-fluid">
				<div class="span6">
					<div class="box box-color box-bordered" id="attrGroupBox">
						<div class="box-title">
							<h3>
								<i class="icon-shopping-cart"></i> Product Group</h3>
							<div class="actions">
								<a href="#" id="deleteAttrGroupBtn" class="btn btn-danger btn-mini" rel="tooltip" title="Delete"><i class="icon-trash"></i></a>
								<a href="#" id="updateAttrGroupBtn" class="btn btn-mini" rel="tooltip" title="Save"><i class="icon-save"></i></a>
							</div>
						</div>
						<div class="box-content nopadding">
							<form class="form-horizontal form-bordered form-validate" method="POST" action="attributes/attrgroup_script.php" id="attrGroupForm" data-returnurl="products/productgroups.php">
								<input type="hidden" name="atr_id" id="id" value="<?php echo($attrGroupRec) ? $attrGroupRec->atr_id : 0; ?>" />
								<div class="control-group hide">
									<label class="control-label">Table Name</label>
									<div class="controls">
										<input type="hidden" name="tblnam" value="PRODUCTGROUP">
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Department<small>parent department</small></label>
									<div class="controls">
										<select name="tbl_id">
										
										<option value="0" <?php echo ($attrGroupRec && $attrGroupRec->tbl_id == 0) ? 'selected="selected"' : ''; ?>>No Department</option>
										
										<?php
										
										$tableLength = count($subCategories);
										for ($i=0;$i<$tableLength;++$i) {
											
										?>
										<option value="<?php echo $subCategories[$i]['sub_id']; ?>" <?php echo ($attrGroupRec && $subCategories[$i]['sub_id'] == $attrGroupRec->tbl_id ) ? 'selected="selected"' : ''; ?>><?php echo $subCategories[$i]['subnam']; ?></option>
										<?php } ?>
										</select>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Name<small>product group name</small></label>
									<div class="controls">
										<input type="text" name="atrnam" data-rule-required="true" data-rule-minlength="2" value="<?php echo($attrGroupRec) ? $attrGroupRec->atrnam : ''; ?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Description<small>short description</small></label>
									<div class="controls">
										<textarea name="atrdsc"><?php echo($attrGroupRec) ? $attrGroupRec->atrdsc : ''; ?></textarea>
									</div>
								</div>

                                <div class="control-group">
                                    <label class="control-label">Product categories<small>Product tags</small></label>
                                    <div class="controls">

                                        <select multiple="multiple" class="input-large" name="atrtagselect" id="atrtagselect">

                                            <?php
                                            $tableLength = count($productCats);
                                            for ($i=0;$i<$tableLength;++$i) {
                                                ?>

                                                <option value="<?php echo $productCats[$i]['sub_id'] ?>" <?php echo (isset($attrGroupRec) && is_numeric(strpos($attrGroupRec->atrtag, $productCats[$i]['sub_id']))) ? 'selected' : ''; ?>><?php echo $productCats[$i]['subnam'] ?></option>

                                            <?php } ?>

                                        </select>

                                        <input type="hidden" name="atrtag">

                                    </div>
                                </div>

								
								<div class="control-group">
									<label class="control-label">SEO URL<small>SEO friendly URL</small></label>
									<div class="controls">
										<input type="text" name="seourl" data-rule-required="true" data-rule-minlength="2" value="<?php echo($attrGroupRec) ? $attrGroupRec->seourl : ''; ?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">SEO Keywords<small>SEO keywords</small></label>
									<div class="controls">
										<textarea name="seokey"><?php echo($attrGroupRec) ? $attrGroupRec->seokey : ''; ?></textarea>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">SEO Description<small>SEO descriptions</small></label>
									<div class="controls">
										<textarea name="seodsc"><?php echo($attrGroupRec) ? $attrGroupRec->seodsc : ''; ?></textarea>
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Status<small>current status</small></label>
									<div class="controls">
										<label class="radio">
											<input type="radio" name="sta_id" value="0" <?php echo($attrGroupRec && $attrGroupRec->sta_id == 0) ? 'checked' : ''; ?>>
											Active</label>
										<label class="radio">
											<input type="radio" name="sta_id" value="1" <?php echo(!$attrGroupRec || $attrGroupRec && $attrGroupRec->sta_id == 1) ? 'checked' : ''; ?>>
											In-Active </label>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="span6">
					<div class="box box-color box-bordered darkblue" id="attrLabelTableBox">
						<div class="box-title">
							<h3>
								<i class="icon-tasks"></i> Attributes</h3>
							<div class="actions">
								<a href="#" id="createAttrLabelBtn" class="btn btn-mini" <?php echo (!$attrGroupRec) ? 'style="display: none;"' : ''; ?> rel="tooltip" title="New Article"><i class="icon-plus"></i></a>
							</div>
						</div>
						<div class="box-content nopadding">
							<table class="table table-bordered table-striped table-highlight" id="attrLabelTable">
								<thead>
									<tr>
										
										<th width="20"></th>
										<th>Label</th>
										<th>Type</th>
										<th width="20"></th>
									</tr>
								</thead>
								<tbody id="attrLabelBody">
									
								</tbody>
							</table>
						</div>
					</div>
					<div class="box box-color box-bordered satgreen" id="attrLabelBox" style="display: none;">
						<div class="box-title">
							<h3>
								<i class="icon-check"></i> Attribute Form</h3>
							<div class="actions">
								<a href="#" id="cancelAttrLabelBtn" class="btn btn-mini" rel="tooltip" title="Cancel"><i class="icon-remove-sign"></i></a>
								<a href="#" id="updateAttrLabelBtn" class="btn btn-mini" rel="tooltip" title="Update"><i class="icon-save"></i></a>
							</div>
						</div>
						<div class="box-content nopadding">
							<form class="form-horizontal form-bordered form-validate" action="<?php echo $patchworks->pwRoot; ?>attributes/attrlabel_script.php" id="attrLabelForm">
								<input type="hidden" name="atr_id" id="atrId" value="<?php echo($attrGroupRec) ? $attrGroupRec->atr_id : 0; ?>" />
								<input type="hidden" name="atl_id" id="id" value="0" />
								<input type="hidden" name="srtord" value="" />
								<input type="hidden" name="atllst" value="" />
								<div class="control-group">
									<label class="control-label">Label<small>attribute name/label</small></label>
									<div class="controls">
										<input type="text" name="atllbl" data-rule-required="true" data-rule-minlength="2" value="">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Help<small>help text for form</small></label>
									<div class="controls">
										<textarea class="input-block-level" rows="4" name="atldsc"></textarea>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Input Type<small>select input method</small></label>
									<div class="controls">
										<select name="atltyp">
											<option value="text">Text</option>
											<option value="textarea">Description</option>
											<option value="checkbox">Checkbox</option>
											<optgroup label="Lists">
											<option value="select">Select List</option>
											<option value="radio">Radio List</option>
											</optgroup>
											<optgroup label="Special">
											<option value="date">Date</option>
											<option value="WYSIWYG">WYSIWYG</option>
											</optgroup>
										</select>
									</div>
								</div>
								<div id="AtlLstDiv">
									<div class="control-group">
										<label class="control-label">Add Entry<small>add items to list</small></label>
										<div class="controls">
											<div class="input-append">
												<input type="text" id="AddAltLst" class="input-medium" />
												<button class="btn" type="button" id="addAltLst" rel="tooltip" title="Add Entry To List"><i class="icon icon-plus"></i></button>
											</div>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Entry List<small>items in list</small></label>
										<div class="controls">
											<ul id="AtrLst_UL" style="padding: 0; margin: 0;">
											</ul>
										</div>
									</div>
								</div>
								<textarea id="AtlLst" name="atllst" class="hide"></textarea>
								<div class="control-group">
									<label class="control-label">Required?<small>field is mandatory</small></label>
									<div class="controls">
										<label class="checkbox">
											<input type="checkbox" name="atlreq" value="1">
											Required </label>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Show on website<small>display field to user</small></label>
									<div class="controls">
										<label class="checkbox">
											<input type="checkbox" name="srcabl" value="1">
											Visible to users?</label>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Search Type<small>how to search the data</small></label>
									<div class="controls">
										<select name="srctyp">
											<option value="text">Text</option>
										</select>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Specialist Field<small>if field is used for functionality</small></label>
									<div class="controls">
										<label class="checkbox">
											<input type="checkbox" name="atlspc" value="1">
											Special Field </label>
									</div>
								</div>
							</form>
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








<!--					<div class="box">-->
<!--						<div class="box-title">-->
<!--							<h3>-->
<!--								<i class="icon-picture"></i> Gallery Images </h3>-->
<!--						</div>-->
<!--						<div class="box-content">-->
<!--						-->
<!--							<ul class="gallery gallery-dynamic" id="galleryImages">-->
<!--								-->
<!--							</ul>-->
<!--						</div>-->
<!--					</div>-->


				</div>
			</div>



			<div class="row-fluid hide">
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


<div class="modal hide fade" id="imageModal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h3>Image Detail</h3>
    </div>
    <form action="gallery/upload_script.php" id="imageForm" class="form-horizontal" novalidate>
        <input type="hidden" name="upl_id" />
        <div class="modal-body">
            <fieldset>

                <div class="control-group">
                    <label class="control-label">Title</label>
                    <div class="controls">
                        <input type="text" class="input-block-level" name="uplttl">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Description</label>
                    <div class="controls">
                        <textarea class="input-block-level" name="upldsc"></textarea>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Link</label>
                    <div class="controls">
                        <input type="text" class="input-block-level" name="urllnk">
                    </div>
                </div>

            </fieldset>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn" data-dismiss="modal"><i class="icon-remove"></i> Cancel</a>
            <button type="submit" class="btn btn-primary" name="action" value="update" id="updateImageBtn"><i class="icon-save"></i> Update</button>
        </div>
    </form>
</div>


</body>
</html>
