<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("classes/products.cls.php");
require_once("classes/product_types.cls.php");
require_once("../attributes/classes/attrgroups.cls.php");
require_once("../attributes/classes/attrlabels.cls.php");
require_once("../attributes/classes/attrvalues.cls.php");
require_once("../system/classes/categories.cls.php");
require_once("../system/classes/subcategories.cls.php");
require_once("../ecommerce/classes/vat.cls.php");


$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$Prd_ID = (isset($_GET['prd_id']) && is_numeric($_GET['prd_id'])) ? $_GET['prd_id'] : NULL;
if (!is_null($Prd_ID)) {

	$TmpPrd = new PrdDAO();
	$productRec = $TmpPrd->select($Prd_ID, NULL, NULL, NULL, NULL, NULL, 'prdnam', true);

}

$TmpAtr = new AtrDAO();
$attrGroups = $TmpAtr->select(NULL, 'PRODUCTGROUP');

$TmpPrt = new PrtDAO();
$productTypes = $TmpPrt->select(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, false);

$TmpSub = new SubDAO();
$subCategories = $TmpSub->selectByTableName('product-category,dog-breeds');

$TmpVat = new VatDAO();
$vatRecs = $TmpVat->select(NULL, NULL, NULL, false);

?>
<!doctype html>
<html>
<head>
<title>Products</title>
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

<script src="js/plugins/tinymce/tinymce.min.js"></script>

<script src="products/js/product-edit.js"></script>

</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
	<?php include('../webparts/website-left.php'); ?>
	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>Product</h1>
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
						<a href="products/products.php">Products</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a href="products/products.php"><?php echo(isset($productRec)) ? $productRec->prdnam : 'New Product'; ?></a>
					</li>
				</ul>
			</div>
			<div class="row-fluid">
				<div class="span6">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-shopping-cart"></i> Products</h3>
							<div class="actions">

                                <a href="#" id="updateProductBtn" class="btn btn-mini" rel="tooltip" title="Update Product"><i class="icon-save"></i></a>
                                <a href="#" id="deleteProductBtn" class="btn btn-mini" rel="tooltip" title="Delete Product"><i class="icon-trash red-text"></i></a>


							</div>
						</div>
						<div class="box-content nopadding">
							
							
							
							<form id="productForm" class="form-horizontal form-bordered form-validate" action="products/products_script.php" data-returnurl="products/products.php">
								<input type="hidden" name="tblnam" value="PRODUCT">
								<input type="hidden" name="tbl_id" value="<?php echo(isset($productRec)) ? $productRec->prt_id : 0; ?>">
								<input type="hidden" name="prd_id" id="id" value="<?php echo(isset($productRec)) ? $productRec->prd_id : 0; ?>">
								
								
								<div class="control-group">
									<label class="control-label">Product Group<small>select the product type</small></label>
									<div class="controls">

                                        <select name="atr_id">

                                            <option value="0" <?php if (isset($productRec) && $productRec->atr_id == 0) echo 'selected'; ?>>No Product Group</option>

                                            <?php

                                            $subNam = '';

                                            $tableLength = count($attrGroups);
                                            for ($i=0;$i<$tableLength;++$i) {

                                                if ($subNam != $attrGroups[$i]['subnam']) {
                                                    if ($i > 0) echo '</optgroup>';
                                                    echo '<optgroup label="'.$attrGroups[$i]['subnam'].'">';
                                                    $subNam = $attrGroups[$i]['subnam'];
                                                }

                                                ?>
                                                <option value="<?php echo $attrGroups[$i]['atr_id']; ?>" <?php if (isset($productRec) && $productRec->atr_id == $attrGroups[$i]['atr_id']) echo 'selected'; ?>><?php echo $attrGroups[$i]['atrnam']; ?></option>
                                            <?php } ?>
                                        </select>

									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Product Type<small>select the product type</small></label>
									<div class="controls">
										<select name="prt_id">
											
											<option value="0" <?php if (isset($productRec) && $productRec->prt_id == 0) echo 'selected'; ?>>No Product Type</option>
											
											<?php
											$tableLength = count($productTypes);
											for ($i=0;$i<$tableLength;++$i) {
											?>
											<option value="<?php echo $productTypes[$i]['prt_id']; ?>" <?php if (isset($productRec) && $productRec->prt_id == $productTypes[$i]['prt_id']) echo 'selected'; ?>><?php echo $productTypes[$i]['prtnam']; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Name<small>individual product name</small></label>
									<div class="controls">
										<input type="text" class="input-large" data-rule-required="true" data-rule-minlength="2" name="prdnam" value="<?php echo(isset($productRec)) ? htmlspecialchars($productRec->prdnam,ENT_QUOTES) : ''; ?>">
									</div>
								</div>

                                <div class="control-group">
                                    <label class="control-label">Reference<small>alternate reference number from external system</small></label>
                                    <div class="controls">
                                        <input type="text" class="input-large" data-rule-minlength="2" name="altref" value="<?php echo(isset($productRec)) ? $productRec->altref : ''; ?>">
                                    </div>
                                </div>

								<div class="control-group">
									<label class="control-label">SEO URL<small>search engine friendly URL</small></label>
									<div class="controls">
										<input type="text" class="input-large" data-rule-required="true" data-rule-minlength="2" name="seourl" value="<?php echo(isset($productRec)) ? $productRec->seourl : ''; ?>">
									</div>
								</div>

                                <div class="control-group">
                                    <label class="control-label">Status<small>product status</small></label>
                                    <div class="controls">
                                        <label class="radio">
                                            <input type="radio" name="sta_id" id="Sta_ID0" value="0" <?php echo(!isset($productRec) || $productRec && $productRec->sta_id == 0) ? 'checked' : ''; ?>>
                                            Active</label>
                                        <label class="radio">
                                            <input type="radio" name="sta_id" id="Sta_ID1" value="1" <?php echo(isset($productRec) && $productRec->sta_id == 1) ? 'checked' : ''; ?>>
                                            Inactive </label>
                                    </div>
                                </div>

                                <h4>Product Description</h4>
                                
								<div class="control-group">
									<label class="control-label hide">Product Description</label>
									<div class="controls nopadding" style="margin: 0;">
										<textarea class="span12" name="prddsc" id="prddsc" rows="20"><?php echo (isset($productRec)) ? $productRec->prddsc : ''; ?></textarea>
									</div>
								</div>
                                
                                <h4>Product Specification</h4>
                                
                                <div class="control-group">
									<label class="control-label hide">Product Specification</label>
									<div class="controls nopadding" style="margin: 0;">
										<textarea class="span12" name="prdspc" id="prdspc" rows="20"><?php echo (isset($productRec)) ? $productRec->prdspc : ''; ?></textarea>
									</div>
								</div>
								
								
                                
                                <div class="control-group">
									<label class="control-label">Product categories<small>Product tags</small></label>
									<div class="controls">
                                        
                                        <select multiple="multiple" class="input-large" name="prdtagselect" id="prdtagselect">
                                        
                                        	<?php
											$tableLength = count($subCategories);
											for ($i=0;$i<$tableLength;++$i) {

                                                $prdTags = explode(",",$productRec->prdtag);

											?>
                                            
                                            <option value="<?php echo $subCategories[$i]['sub_id'] ?>" <?php echo (isset($productRec) && in_array($subCategories[$i]['sub_id'],$prdTags)) ? 'selected' : ''; ?>><?php echo $subCategories[$i]['subnam'] ?></option>
                                            
											<?php } ?>
                                            
                                        </select>
                                        
                                        <input type="hidden" name="prdtag">
                                        
									</div>
								</div>
                                
                                
								<div class="control-group">
									<label class="control-label">Sale Price<small>individual product price</small></label>
									<div class="controls">
										<input type="text" class="input-large" data-rule-required="true" data-rule-number="true" name="unipri" value="<?php echo(isset($productRec)) ? $productRec->unipri : '0'; ?>">
									</div>
								</div>



                                <div class="control-group">
                                    <label class="control-label">YOUTUBE Video<small>YOUTUBE Video ID</small></label>
                                    <div class="controls">
                                        <input type="text" class="input-large" name="altnam" value="<?php echo(isset($productRec)) ? $productRec->altnam : ''; ?>">
                                    </div>
                                </div>



                                <div class="control-group">
                                    <label class="control-label">In Stock<small>current stock of this product variant</small></label>
                                    <div class="controls">
                                        <input type="text" class="input-large" data-rule-required="true" data-rule-number="true" name="in_stk" value="<?php echo(isset($productRec)) ? $productRec->in_stk : '0'; ?>">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">Sort Order<small>the lower number the higher priority</small></label>
                                    <div class="controls">
                                        <input type="text" class="input-large" data-rule-required="true" data-rule-number="true" name="srtord" value="<?php echo(isset($productRec)) ? $productRec->srtord : '1000'; ?>">
                                    </div>
                                </div>

							    <div class="hide">

                                    <div class="control-group">
                                        <label class="control-label">Purchase Price<small>individual purchase price</small></label>
                                        <div class="controls">
                                            <input type="text" class="input-large" data-rule-required="true" data-rule-number="true" name="buypri" value="<?php echo(isset($productRec)) ? $productRec->buypri : '0'; ?>">
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label">Delivery Price<small>individual delivery price</small></label>
                                        <div class="controls">
                                            <input type="text" class="input-large" data-rule-required="true" data-rule-number="true" name="delpri" value="<?php echo(isset($productRec)) ? $productRec->delpri : '0'; ?>">
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label">Weight<small>total weight of item</small></label>
                                        <div class="controls">
                                            <input type="text" class="input-large" data-rule-required="true" data-rule-number="true" name="weight" value="<?php echo(isset($productRec)) ? $productRec->weight : '0'; ?>">
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Stock<small>total stock of this product variant</small></label>
                                        <div class="controls">
                                            <select name="usestk">
                                                <option value="0" selected>Non Stock</option>
                                                <option value="1" >Use Stock</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">On Order<small>stock on order of this product variant</small></label>
                                        <div class="controls">
                                            <input type="text" class="input-large" data-rule-required="true" data-rule-number="true" name="on_ord" value="<?php echo(isset($productRec)) ? $productRec->on_ord : '0'; ?>">
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">On Delivery<small>current stock in transit</small></label>
                                        <div class="controls">
                                            <input type="text" class="input-large" data-rule-required="true" data-rule-number="true" name="on_del" value="<?php echo(isset($productRec)) ? $productRec->on_del : '0'; ?>">
                                        </div>
                                    </div>

                                </div>

                                <div class="control-group">
                                    <label class="control-label">VAT<small>vat rate of product</small></label>
                                    <div class="controls">

                                        <select name="vat_id">

                                            <?php
                                            $tableLength = count($vatRecs);
                                            for ($i=0;$i<$tableLength;++$i) {
                                                ?>
                                                <option value="<?php echo $vatRecs[$i]['vat_id']; ?>" <?php if (isset($productRec) && $productRec->vat_id == $vatRecs[$i]['vat_id']) echo 'selected'; ?>><?php echo $vatRecs[$i]['vatnam']; ?></option>
                                            <?php } ?>

                                        </select>
                                    </div>
                                </div>

							</form>
							
							
							
						</div>
					</div>
				</div>
				
				<div class="span6">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-shopping-cart"></i> Product Attributes</h3>
						</div>
						<div class="box-content nopadding">
							<form id="attlEntryForm" class="form-horizontal form-bordered attributeForm" action="attributes/attribute-entry_script.php">
							
							
							</form>	
						</div>
					</div>


                        <div class="box box-color box-bordered" id="relatedProductBox">
                            <div class="box-title">
                                <h3>
                                    <i class="icon-shopping-cart"></i> Related Products</h3>
                            </div>
                            <div class="box-content nopadding">

                                <form id="relatedForm" class="form-horizontal form-bordered form-validate" action="system/related_script.php">
                                    <input type="hidden" name="tblnam" value="PRODUCT">
                                    <input type="hidden" name="tbl_id" value="<?php echo(isset($productRec)) ? $productRec->prd_id : 0; ?>">
                                    <input type="hidden" name="ref_id">

                                    <div class="control-group">
                                        <label class="control-label"><i class="icon icon-search"></i> Search<small>start typing to see results</small></label>
                                        <div class="controls">
                                            <input type="text" class="input-block-level autocomplete" name="refnam" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label">Selected Product</label>
                                        <div class="controls">
                                            <input type="text" class="input-block-level autocomplete" id="relatedName" readonly>
                                        </div>
                                    </div>



                                    <div class="form-actions">
                                        <button type="submit" class="btn btn-primary">Relate</button>
                                    </div>
                                </form>
                            </div>
                            <div class="box-content">

                                <div id="relatedProductList">

                                </div>

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

                                <ul class="gallery gallery-dynamic galleryList" id="galleryImages">

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


            <div class="row-fluid">
                <div class="span12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                <i class="icon-picture"></i> Product PDFs </h3>
                        </div>
                        <div class="box-content">

                            <ul id="galleryPDFs" class="unstyled gallerylist">

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
                            <div id="pdfplupload">
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
