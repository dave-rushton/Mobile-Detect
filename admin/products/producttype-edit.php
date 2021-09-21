<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("classes/product_types.cls.php");
require_once("../attributes/classes/attrgroups.cls.php");
require_once("../attributes/classes/attrlabels.cls.php");
require_once("../attributes/classes/attrvalues.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpPrt = new PrtDAO();
$editProductTypeID = (isset($_GET['prt_id']) && is_numeric($_GET['prt_id'])) ? $_GET['prt_id'] : NULL;
$productTypeRec = NULL;
if (!is_null($editProductTypeID)) $productTypeRec = $TmpPrt->select($editProductTypeID, NULL, NULL, NULL, NULL, NULL, NULL, NULL, true); 

$TmpAtr = new AtrDAO();
$attrGroups = $TmpAtr->select(NULL, 'PRODUCTGROUP');

?>
<!doctype html>
<html>
<head>
<title>Product Type</title>
<?php include('../webparts/headdata.php'); ?>

<!-- masonry -->
<script src="js/plugins/masonry/jquery.masonry.min.js"></script>
<!-- imagesloaded -->
<script src="js/plugins/imagesLoaded/jquery.imagesloaded.min.js"></script>

<!-- Plupload -->
<link rel="stylesheet" href="css/plugins/plupload/jquery.plupload.queue.css">
<!-- PLUpload -->
<script src="js/plugins/plupload/plupload.full.js"></script>
<script src="js/plugins/plupload/jquery.plupload.queue.js"></script>

<!-- CKEditor -->
<script src="js/plugins/ckeditor/ckeditor.js"></script>

<script src="products/js/producttype-edit.js"></script>
</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
	<?php include('../webparts/product-left.php'); ?>
	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>Product Type</h1>
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
						<a href="products/dashbboard.php">Products Dashboard</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a href="products/producttypes.php">Product Types</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a href="products/producttype-edit.php"><?php echo($productTypeRec) ? $productTypeRec->prtnam : 'New Product Type'; ?></a>
					</li>
				</ul>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<div class="box box-color box-bordered" id="attrGroupBox">
						<div class="box-title">
							<h3>
								<i class="icon-sitemap"></i> Product Type</h3>
							<div class="actions">
								<a href="#" id="updateProductType" class="btn btn-mini" rel="tooltip" title="Update"><i class="icon-save"></i></a>
							</div>
						</div>
						<div class="box-content nopadding">
							<form class="form-horizontal form-bordered form-validate" method="POST" action="products/product_types_script.php" id="productTypeForm" data-returnurl="products/producttypes.php">
								<input type="hidden" name="prt_id" id="id" value="<?php echo($productTypeRec) ? $productTypeRec->prt_id : 0; ?>" />
								<input type="hidden" class="input-large" name="tblnam" id="TblNam" value="">
								<input type="hidden" class="input-large" name="tbl_id" id="Tbl_ID" value="0">
								<div class="control-group">
									<label class="control-label">Group<small>select the product group</small></label>
									<div class="controls">
										<select name="atr_id">
											
											<option value="0" <?php if ($productTypeRec && $productTypeRec->atr_id == 0) echo 'selected'; ?>>No Product Group</option>
											
											<?php
											$tableLength = count($attrGroups);
											for ($i=0;$i<$tableLength;++$i) {
											?>
											<option value="<?php echo $attrGroups[$i]['atr_id']; ?>" <?php if ($productTypeRec && $productTypeRec->atr_id == $attrGroups[$i]['atr_id']) echo 'selected'; ?>><?php echo $attrGroups[$i]['atrnam']; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Product Name<small>enter a name for this product</small></label>
									<div class="controls">
										<input type="text" class="input-large" name="prtnam" value="<?php echo($productTypeRec) ? $productTypeRec->prtnam : ''; ?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label hide">Product Description</label>
									<div class="controls nopadding" style="margin: 0;">
										<textarea class="span12" name="prtdsc" id="prtdsc"><?php echo ($productTypeRec) ? $productTypeRec->prtdsc : ''; ?></textarea>
									</div>
								</div>
								
								<div id="priceOptions" class="hide">
									<div class="control-group">
										<label class="control-label">Sale Price<small>default price charged to customers</small></label>
										<div class="controls">
											<input type="text" class="input-large" name="unipri" value="<?php echo($productTypeRec) ? $productTypeRec->unipri : '0'; ?>">
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Purchase Price<small>default purchase price of product</small></label>
										<div class="controls">
											<input type="text" class="input-large" name="buypri" value="<?php echo($productTypeRec) ? $productTypeRec->buypri : '0'; ?>">
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Delivery Price<small>default delivery price</small></label>
										<div class="controls">
											<input type="text" class="input-large" name="delpri" value="<?php echo($productTypeRec) ? $productTypeRec->delpri : '0'; ?>">
										</div>
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Status<small>product status</small></label>
									<div class="controls">
										<label class="radio">
											<input type="radio" name="sta_id" id="Sta_ID0" value="0" <?php echo(!$productTypeRec || $productTypeRec && $productTypeRec->sta_id == 0) ? 'checked' : ''; ?>>
											Active</label>
										<label class="radio">
											<input type="radio" name="sta_id" id="Sta_ID1" value="1" <?php echo($productTypeRec && $productTypeRec->sta_id == 1) ? 'checked' : ''; ?>>
											Inactive </label>
									</div>
								</div>
								
								<div id="stockOptions" class="hide">
									<div class="control-group">
										<label class="control-label">Stock</label>
										<div class="controls">
											<select name="usestk">
												<option value="0" <?php echo($productTypeRec && $productTypeRec->usestk == 0) ? 'selected' : ''; ?>>Non Stock</option>
												<option value="1" <?php echo($productTypeRec && $productTypeRec->usestk == 1) ? 'selected' : ''; ?>>Use Stock</option>
											</select>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">In Stock</label>
										<div class="controls">
											<input type="text" class="input-large" data-rule-required="true" data-rule-number="true" name="in_stk" value="0">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">On Order</label>
										<div class="controls">
											<input type="text" class="input-large" data-rule-required="true" data-rule-number="true" name="on_ord" value="0">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">On Delivery</label>
										<div class="controls">
											<input type="text" class="input-large" data-rule-required="true" data-rule-number="true" name="on_del" value="0">
										</div>
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">SEO URL</label>
									<div class="controls">
										<input type="text" class="input-large" name="seourl" value="<?php echo($productTypeRec) ? $productTypeRec->seourl : ''; ?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">SEO Keywords</label>
									<div class="controls">
										<textarea name="seokey"><?php echo($productTypeRec) ? $productTypeRec->seokey : ''; ?></textarea>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">SEO Description</label>
									<div class="controls">
										<textarea name="seodsc"><?php echo($productTypeRec) ? $productTypeRec->seodsc : ''; ?></textarea>
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
