<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("../system/classes/subcategories.cls.php");
require_once("../products/classes/product_types.cls.php");
require_once("../attributes/classes/attrgroups.cls.php");
require_once("../attributes/classes/attrlabels.cls.php");
require_once("../attributes/classes/attrvalues.cls.php");
require_once("../products/classes/structure.cls.php");
require_once("../reviews/classes/reviews.cls.php");
require_once("../ecommerce/classes/vat.cls.php");
require_once("../gallery/classes/gallery.cls.php");
require_once("../gallery/classes/uploads.cls.php");
require_once("../custom/saw-options/classes/saw-option.cls.php");
require_once("../custom/saw-categories/variables.php");
require_once("../custom/saw-categories/classes/articles.cls.php");

$variables = new variables();
$ListingDAO = new ListingDAO();

$TmpGal = new GalDAO();
$TmpOperationType = new OperationType();


$galleries = $TmpGal->select(null, 'WEBGALLERY', null, null, false);

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) {
    header('location: ../login.php');
}

$TmpPrt = new PrtDAO();
$editProductTypeID = (isset($_GET['prt_id']) && is_numeric($_GET['prt_id'])) ? $_GET['prt_id'] : null;

$productTypeRec = null;
$attrGroupRec = null;
$attrLabelRec = null;

if (!is_null($editProductTypeID)) {
    $productTypeRec = $TmpPrt->select($editProductTypeID, null, null, null, null, null, null, null, true);
}

$TmpAtr = new AtrDAO();
$attrGroups = $TmpAtr->select(null, 'PRODUCTGROUP');
$productTypeAttr = $TmpAtr->select(null, 'PRODUCTTYPE', $editProductTypeID, null, true, null, null, null);

$TmpStr = new StrDAO();

$TmpVat = new VatDAO();
$vatRates = $TmpVat->select(null, date("Y-m-d"), 0, false);

$TmpRev = new RevDAO();
$reviews = $TmpRev->select(null, 'PRODUCT', $editProductTypeID, false);

$TmpSub = new SubDAO();

$subCategories = $TmpSub->selectByTableName('product-category');

$manufacture = $TmpSub->selectByTableName('manufacturer-types');
?>
<!doctype html>
<html>
    <head>
        <title>Product</title>
        <?php include('../webparts/headdata.php'); ?>

        <!-- colorbox -->
        <link rel="stylesheet" href="css/plugins/colorbox/colorbox.css">

        <style>
            .row-fluid .span4 {
                width: 32.3%;
                margin-left: 1%;
            }

            .updateProduct {
                padding: 5px 10px;
                display: inline-block;
            }

            .tabs-wrapper {
                color: #fff;
                cursor: pointer;
                float: left;
                width: 100%;
            }

            .tabs {
                clear: both;
                float: left !important;
                margin-top: 5px !important;
            }

            .tab {
                cursor: pointer;
                background-color: #b8004a;
                color: #fff;
                border: 1px solid #b8004a;
                float: left;
                padding: 4px 8px;
                font-weight: bold;
                /*text-transform: uppercase;*/
            }

            .tab:hover, .active {
                background-color: #fff;
                color: #b8004a;
                font-weight: bold;
            }

            .inner-box-custom {
                float: left;
                width: 100%;
                box-sizing: border-box;
            }

            .inner {
                padding: 0 15px;
            }

            .inner-box-custom .row-fluid:nth-child(2n+1) {
                background-color: #eaeaea;
            }

            .inner-box-custom .row-fluid:first-child {
                background-color: #b8004a;
                color: #fff;
                font-weight: bold;
                text-align: center;
            }

            .inner-box-custom .row-fluid {
                padding: 5px 15px;
                margin-bottom: 15px;
                box-sizing: border-box;
            }

            input[type="radio"], input[type="checkbox"] {
                margin-top: 0;
                margin-right: 5px;
            }

            .filters-options {
                margin: 0;
                padding: 0;
            }

            .filters-options li {
                float: left;
                list-style: none;
                width: 33.33%;
            }


            .control-group {
                float: left;
                width: 100%;
                clear: both;
            }

            #buildStructure {

            }

            #buildStructure ul {
                /*margin: 0;*/
                /*padding: 0;*/
                margin-top: 3px;
            }

            #buildStructure ul li {
                /*margin: 0;*/
                /*padding: 0;*/
                list-style: none;
                margin-bottom: 3px;
            }

            #buildStructure ul li a {

            }

            #buildStructure ul li a input {
                margin: 0 3px 0 0;
            }

            textarea {
                width: 100%;
                box-sizing: border-box;
            }

        </style>


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

        <script src="products/js/producttype-edit.js"></script>
    </head>
    <?php include('../webparts/navigation.php'); ?>
    <body class="theme-red">
        <div class="container-fluid" id="content">

            <div id="main">
                <div class="container-fluid">
                    <div class="page-header">
                        <div class="pull-left">
                            <h1>Product</h1>
                        </div>
                        <div class="pull-right">
                            <?php include('../webparts/website-left.php'); ?>
                        </div>
                    </div>
                    <div class="breadcrumbs">
                        <ul>
                            <li>
                                <a href="index.php">Dashboard</a> <i class="icon-angle-right"></i>
                            </li>

                            <li>
                                <a href="products/producttypes.php">Products</a> <i class="icon-angle-right"></i>
                            </li>
                            <li>
                                <a><?php echo ($productTypeRec) ? $productTypeRec->prtnam : 'New Machine'; ?></a>
                            </li>
                        </ul>
                    </div>
                    <div class="row-fluid">

                    </div>
                    <div class="row-fluid">
                        <div class="span12">


                            <div class="row-fluid">
                                <form class="form-horizontal form-bordered form-validate" method="POST"
                                      action="products/product_types_script.php" id="productEntryForm"
                                      data-returnurl="products/producttypes.php">
                                    <div class="span4">


                                        <div class="box box-color box-bordered" id="attrGroupBox">
                                            <div class="box-title">
                                                <h3>
                                                    <i class="icon-shopping-cart"></i> Machine</h3>

                                                <div class="actions">
                                                    <a href="products/products-edit.php" class="updateProduct"
                                                       class="btn btn-mini"
                                                       rel="tooltip" title="Update"><i class="icon-save"></i></a>
                                                    <a href="#" id="deleteProductType" class="btn btn-mini" rel="tooltip"
                                                       title="Update"><i
                                                                class="icon-trash"></i></a>
                                                </div>
                                            </div>
                                            <div class="box-content nopadding">

                                                <input type="hidden" name="prt_id" id="id"
                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->prt_id : 0; ?>" />
                                                <input type="hidden" class="input-block-level" name="tblnam" id="TblNam"
                                                       value="PRODUCT">
                                                <input type="hidden" class="input-block-level" name="tbl_id" id="Tbl_ID"
                                                       value="0">

                                                <div class="control-group hide">
                                                    <label class="control-label">Group <small>select the product
                                                            group</small> </label>

                                                    <div class="controls">
                                                        <select name="atr_id">

                                                            <option value="0">N/A</option>

                                                            <?php

                                                            $tableLength = count($attrGroups);
                                                            for ($i = 0; $i < $tableLength; ++$i) {
                                                                ?>
                                                                <option
                                                                        value="<?php echo $attrGroups[$i]['atr_id']; ?>" <?php if ($productTypeRec && $productTypeRec->atr_id == $attrGroups[$i]['atr_id']) {
                                                                    echo 'selected';
                                                                } ?>><?php echo $attrGroups[$i]['atrnam']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="control-group">
                                                    <label class="control-label">Machine Name: <small>enter a name for
                                                            this machine</small> </label>

                                                    <div class="controls">
                                                        <input type="text" class="input-block-level" name="prtnam"
                                                               value="<?php echo ($productTypeRec) ? $productTypeRec->prtnam : ''; ?>"
                                                               data-rule-minlength="3" data-rule-maxlength="200"
                                                               data-rule-required="true">
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label">Done <small>This is for backend use
                                                            only</small> </label>


                                                    <div class="controls">
                                                        <select name="done">
                                                            <?php if (!empty($productTypeRec->done)) {
                                                                echo "<option  value=''>No</option>";
                                                                echo "<option selected value='Done'>Yes</option>";
                                                            } ?>
                                                            <?php if (empty($productTypeRec->done)) {
                                                                echo "<option selected value=''>No</option>";
                                                                echo "<option value='Done'>Yes</option>";

                                                            } ?>

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label">Machine Code: <small>enter a code for
                                                            this machine</small> </label>

                                                    <div class="controls">
                                                        <input type="text" class="input-block-level" name="machine_code"
                                                               value="<?php echo ($productTypeRec) ? $productTypeRec->machine_code : ''; ?>"
                                                               data-rule-minlength="0" data-rule-maxlength="80">
                                                    </div>
                                                </div>


                                                <div class="control-group">
                                                    <label class="control-label">Status <small>Machine Status</small>
                                                    </label>

                                                    <div class="controls">
                                                        <label class="radio">
                                                            <input type="radio" name="sta_id" id="Sta_ID0"
                                                                   value="0" <?php echo (!$productTypeRec || $productTypeRec && $productTypeRec->sta_id == 0) ? 'checked' : ''; ?>>
                                                            Active</label> <label class="radio">
                                                            <input type="radio" name="sta_id" id="Sta_ID1"
                                                                   value="1" <?php echo ($productTypeRec && $productTypeRec->sta_id == 1) ? 'checked' : ''; ?>>
                                                            Inactive </label>
                                                    </div>
                                                </div>

                                                <div class="control-group">
                                                    <label class="control-label">SEO URL <small>Https address
                                                            bar</small> </label>

                                                    <div class="controls">
                                                        <input type="text" class="input-block-level" name="seourl"
                                                               value="<?php echo ($productTypeRec) ? $productTypeRec->seourl : ''; ?>"
                                                               data-rule-minlength="3" data-rule-maxlength="200"
                                                               data-rule-required="true">
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label"> Machine Subcategory <small>*</small>
                                                    </label>

                                                    <div class="controls">
                                                        <input type="text" class="input-block-level" name="machine_subcategory"
                                                               value="<?php echo ($productTypeRec) ? $productTypeRec->machine_subcategory : ''; ?>"
                                                               data-rule-minlength="0" data-rule-maxlength="200"
                                                               data-rule-required="">
                                                    </div>
                                                </div>

                                                <div class="control-group">
                                                    <label class="control-label"> Machine Accessory <small>*</small>
                                                    </label>

                                                    <div class="controls">
                                                        <label>
                                                            <?php

                                                            $checked = "";
                                                            if (!empty($productTypeRec)) {
                                                                if ($productTypeRec->machine_accessory == "1" || $productTypeRec->machine_accessory == "Y") {
                                                                    $checked = "checked='checked'";
                                                                }
                                                            }
                                                            ?>
                                                            <input type="checkbox" <?php echo $checked; ?>
                                                                   class="input-block-level"
                                                                   name="machine_accessory"
                                                                   value="1"> This is a machine accessory </label>

                                                    </div>
                                                </div>
                                                <div class="control-group hide">
                                                    <label class="control-label"> Heading One <small>*</small> </label>

                                                    <div class="controls">
                                                        <input type="text" class="input-block-level" name="heading_one"
                                                               value="<?php echo ($productTypeRec) ? $productTypeRec->heading_one : ''; ?>"
                                                               data-rule-minlength="0" data-rule-maxlength="100"
                                                               data-rule-required="">
                                                    </div>
                                                </div>
                                                <div class="control-group hide">
                                                    <label class="control-label"> Heading Two <small>*</small> </label>

                                                    <div class="controls">
                                                        <input type="text" class="input-block-level" name="heading_two"
                                                               value="<?php echo ($productTypeRec) ? $productTypeRec->heading_two : ''; ?>"
                                                               data-rule-minlength="0" data-rule-maxlength="100"
                                                               data-rule-required="">
                                                    </div>
                                                </div>


                                                <div class="control-group">
                                                    <label class="control-label">Listing Description:
                                                        <small style="width: 300px">Shows on product category
                                                            page</small> </label>
                                                    <?php

                                                    ?>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label hide">Listing Description:</label>

                                                    <div class="controls nopadding" style="margin: 0;">

                                        <textarea class="ckeditor span12 customfield" name="maindsc" id="maindsc"
                                                  style="height: 300px"><?php echo (isset($productTypeRec->prtobj)) ? $patchworks->getJSONVariable(
                                                $productTypeRec->prtobj,
                                                'maindsc',
                                                false
                                            ) : ''; ?></textarea>
                                                    </div>
                                                </div>


                                                <div class="control-group hide">
                                                    <label class="control-label"> Feature One <small>*</small> </label>

                                                    <div class="controls">
                                                        <input type="text" class="input-block-level" name="feature_one"
                                                               value="<?php echo ($productTypeRec) ? $productTypeRec->feature_one : ''; ?>"
                                                               data-rule-minlength="0" data-rule-maxlength="100"
                                                               data-rule-required="">
                                                    </div>
                                                </div>
                                                <div class="control-group hide">
                                                    <label class="control-label"> Feature Two <small>*</small> </label>

                                                    <div class="controls">
                                                        <input type="text" class="input-block-level" name="feature_two"
                                                               value="<?php echo ($productTypeRec) ? $productTypeRec->feature_two : ''; ?>"
                                                               data-rule-minlength="0" data-rule-maxlength="100"
                                                               data-rule-required="">
                                                    </div>
                                                </div>


                                                <div class="control-group">
                                                    <label class="control-label">Overview:</label> *
                                                    <?php

                                                    ?>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label hide">Overview:</label>

                                                    <div class="controls nopadding" style="margin: 0;">

                                        <textarea class="ckeditor span12 " name="overview" id="overview"
                                                  style="height: 300px"><?php echo $productTypeRec->overview; ?></textarea>
                                                    </div>
                                                </div>

                                                <?php
                                                //TODO REVIEW THIS FIELD
                                                ?>
                                                <div class="control-group">
                                                    <label class="control-label">Detailed Description:</label>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label hide">Detailed Description: *</label>

                                                    <div class="controls nopadding" style="margin: 0;">

                                       <textarea class="ckeditor span12" name="prtspc" id="prtspc"
                                                 style="height: 300px"><?php echo ($productTypeRec) ? $productTypeRec->prtspc : ''; ?></textarea>
                                                    </div>
                                                </div>

                                                <div class="control-group">
                                                    <label class="control-label">Standard Specification:</label>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label hide">Standard Specification:</label>

                                                    <div class="controls nopadding" style="margin: 0;">
                                        <textarea class="ckeditor span12" name="prtdsc" id="prtdsc"
                                                  style="height: 300px"><?php echo ($productTypeRec) ? $productTypeRec->prtdsc : ''; ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label">Technical Features:</label>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label hide">Technical Features:</label>

                                                    <div class="controls nopadding" style="margin: 0;">
                                        <textarea class="ckeditor span12" name="technical_features"
                                                  id="technical_features"
                                                  style="height: 300px"><?php echo ($productTypeRec) ? $productTypeRec->technical_features : ''; ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label">Optional Features:</label>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label hide">Optional Features:</label>

                                                    <div class="controls nopadding" style="margin: 0;">
                                                        <textarea class="ckeditor span12" name="optional_features"
                                                          id="optional_features"
                                                          style="height: 300px"><?php echo ($productTypeRec) ? $productTypeRec->optional_features : ''; ?></textarea>
                                                    </div>
                                                </div>


                                                <div class="control-group">
                                                    <label class="control-label">Quote:</label>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label hide">Quote:</label>

                                                    <div class="controls nopadding" style="margin: 0;">
                                                        <textarea class="ckeditor span12 customfield" name="quote"
                                                                  id="quote"
                                                                  style="height: 300px"><?php echo (isset($productTypeRec->prtobj)) ? $patchworks->getJSONVariable(
                                                                   $productTypeRec->prtobj,
                                                                   'quote',
                                                                   false
                                                               ) : '';?></textarea>
                                                    </div>
                                                </div>

                                                <div class="control-group ">
                                                    <label class="control-label">Quote by</label>

                                                    <div class="controls">
                                                        <input type="text" class="input-block-level customfield"
                                                               name="quote_by"
                                                               value="<?php echo (isset($productTypeRec->prtobj)) ? $patchworks->getJSONVariable(
                                                                   $productTypeRec->prtobj,
                                                                   'quote_by',
                                                                   false
                                                               ) : ''; ?>">

                                                    </div>
                                                </div>






                                                <div class="control-group hide">
                                                    <label class="control-label">VAT Rate <small></small> </label>

                                                    <div class="controls">

                                                        <select name="vat_id" class="input-block-level">
                                                            <option value="0">No VAT</option>
                                                            <?php

                                                            for ($v = 0; $v < count($vatRates); $v++) {
                                                                $defaultVat = (!isset($productTypeRec->vat_id) && $vatRates[$v]['defvat'] == 1) ? 'selected' : '';

                                                                ?>
                                                                <option
                                                                        value="<?php echo $vatRates[$v]['vat_id']; ?>" <?php echo (isset($productTypeRec->vat_id) && ($vatRates[$v]['vat_id'] == $productTypeRec->vat_id)) ? ' selected ' : $defaultVat; ?>><?php echo $vatRates[$v]['vatnam'] . ' (' . $vatRates[$v]['vatrat'] . '%)'; ?></option>
                                                                <?php

                                                            }
                                                            ?>
                                                        </select>

                                                    </div>
                                                </div>

                                                <div class="control-group hide">
                                                    <label class="control-label">Purchase Price <small>default purchase
                                                            price of product</small> </label>

                                                    <div class="controls">
                                                        <input type="text" class="input-block-level" name="buypri"
                                                               data-rule-number="true"
                                                               value="<?php echo ($productTypeRec) ? $productTypeRec->buypri : '0'; ?>">
                                                    </div>
                                                </div>

                                                <div class="control-group hide">
                                                    <label class="control-label">Delivery Price <small>default delivery
                                                            price</small> </label>

                                                    <div class="controls">
                                                        <input type="text" class="input-block-level" name="delpri"
                                                               data-rule-number="true"
                                                               value="<?php echo ($productTypeRec) ? $productTypeRec->delpri : '0'; ?>">
                                                    </div>
                                                </div>
                                                <!--</div>-->


                                                <div class="control-group ">
                                                    <label class="control-label">Home Page <small></small> </label>

                                                    <div class="controls">
                                                        <label class="checkbox"> <input type="checkbox" name="hompag"
                                                                                        value="1" <?php echo ($productTypeRec && $productTypeRec->hompag == 1) ? 'checked' : ''; ?>>
                                                            Show on shop home page? </label>
                                                    </div>
                                                </div>


                                                <div id="stockOptions" class="hide">
                                                    <div class="control-group">
                                                        <label class="control-label">Stock</label>

                                                        <div class="controls">
                                                            <select name="usestk">
                                                                <option
                                                                        value="0" <?php echo ($productTypeRec && $productTypeRec->usestk == 0) ? 'selected' : ''; ?>>
                                                                    Non Stock
                                                                </option>
                                                                <option
                                                                        value="1" <?php echo ($productTypeRec && $productTypeRec->usestk == 1) ? 'selected' : ''; ?>>
                                                                    Use Stock
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="control-group">
                                                        <label class="control-label">In Stock</label>

                                                        <div class="controls">
                                                            <input type="text" class="input-block-level"
                                                                   data-rule-required="true"
                                                                   data-rule-number="true" name="in_stk" value="0">
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label">On Order</label>

                                                        <div class="controls">
                                                            <input type="text" class="input-block-level"
                                                                   data-rule-required="true"
                                                                   data-rule-number="true" name="on_ord" value="0">
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label">On Delivery</label>

                                                        <div class="controls">
                                                            <input type="text" class="input-block-level"
                                                                   data-rule-required="true"
                                                                   data-rule-number="true" name="on_del" value="0">
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>

                                    </div>
                                    <div class="span4">
                                        <div class="box box-color box-bordered" id="attrGlroupBox">

                                            <div class="box-title">
                                                <h3>
                                                    <i class="icon-shopping-cart"></i> Technical Specifications
                                                </h3>

                                                <div class="actions">
                                                    <a href="products/products-edit.php" class="updateProduct"
                                                       class="btn btn-mini" rel="tooltip" title="" data-original-title="Update"><i
                                                                class="icon-save"></i></a>
                                                    <a href="#" id="deleteProductType" class="btn btn-mini" rel="tooltip"
                                                       title="" data-original-title="Update"><i class="icon-trash"></i></a>
                                                </div>
                                            </div>

                                            <div class="box-content nopadding">

                                                <div class="control-group hide">
                                                    <label class="control-label"> Manufacturer <small>Manufacturer of
                                                            the machine.</small> </label>
                                                    <div class="controls">
                                                        <input type="text" readonly class="input-block-level "
                                                               name="manufacturer"
                                                               value="<?php echo ($productTypeRec) ? $productTypeRec->manufacturer : ''; ?>">
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label"> Manufacturer <small>Manufacturer of
                                                            the machine</small> </label>
                                                    <div class="controls">
                                                        <select class="input-block-level " name="manufacturer">
                                                            <option value="">Select a Manufacturer</option>
                                                            <?php
                                                            foreach ($manufacture as $m) {
                                                                $selected = "";
                                                                if ($m['sub_id'] == $productTypeRec->manufacturer) {
                                                                    $selected = "selected";
                                                                }
                                                                echo "<option " . $selected . " value='" . $m['sub_id'] . "'>" . $m['subnam'] . "</option>";
                                                            }

                                                            ?>
                                                        </select>

                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label">Operation Type</label>
                                                    <div class="controls">
                                                        <input type="text" class="input-block-level " name="operation_type"
                                                               value="<?php echo ($productTypeRec) ? $productTypeRec->operation_type : ''; ?>">
                                                    </div>
                                                </div>


                                                <div class="control-group">
                                                    <label class="control-label">Material Type</label>
                                                    <div class="controls">
                                                        <input type="text" class="input-block-level " name="material_type"
                                                               value="<?php echo ($productTypeRec) ? $productTypeRec->material_type : ''; ?>">
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label">Materials</label>
                                                    <div class="controls">
                                                        <input type="text" class="input-block-level " name="materials"
                                                               value="<?php echo ($productTypeRec) ? $productTypeRec->materials : ''; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="box-title">
                                                <h3>
                                                    <i class="icon-shopping-cart"></i> Machine Specific Information
                                                </h3>
                                                <ul class="tabs" style="margin-top: 0;">
                                                    <?php
                                                    $class = ($productTypeRec->machine_type == "bandsaw") ? "active" : '';
                                                    echo '<li class="tab ' . $class . '" data-select="bandsaw">';
                                                    echo 'Bandsaw';
                                                    echo '</li>';

                                                    $class = ($productTypeRec->machine_type == "steel-worker") ? "active" : '';
                                                    echo '<li class="tab ' . $class . '" data-select="steel-worker">';
                                                    echo 'Steelworker';
                                                    echo '</li>';

                                                    $class = ($productTypeRec->machine_type == "metal-forming") ? "active" : '';
                                                    echo '<li class="tab  ' . $class . '" data-select="metal-forming">';
                                                    echo ' Metal Forming';
                                                    echo '</li>';

                                                    $class = ($productTypeRec->machine_type == "circular") ? "active" : '';
                                                    echo '<li class="tab  ' . $class . '" data-select="circular">';
                                                    echo ' Circular';
                                                    echo '</li>';

                                                    $class = ($productTypeRec->machine_type == "machining-centre") ? "active" : '';
                                                    echo '<li class="tab  ' . $class . '" data-select="machining-centre">';
                                                    echo ' Machining Centre';
                                                    echo '</li>';

                                                    $class = ($productTypeRec->machine_type == "aluminium") ? "active" : '';
                                                    echo '<li class="tab  ' . $class . '" data-select="aluminium">';
                                                    echo ' Aluminium';
                                                    echo '</li>';

                                                    $class = ($productTypeRec->machine_type == "material-handling") ? "active" : '';
                                                    echo '<li class="tab  ' . $class . '" data-select="material-handling">';
                                                    echo ' Material Handling';
                                                    echo '</li>';
                                                    ?>

                                                </ul>
                                            </div>


                                            <div class="box-content nopadding">

                                                <div class="control-group hide">
                                                    <label class="control-label ">Machine Type:

                                                    </label>

                                                    <div class="controls nopadding" style="margin: 0;">
                                                        <select name="machine_type">
                                                            <option value="bandsaw"
                                                                    data-screen="bandsaw" <?php echo ($productTypeRec->machine_type == "bandsaw") ? "selected='selected'" : ''; ?>>
                                                                Bandsaw
                                                            </option>
                                                            <option value="steel-worker"
                                                                    data-screen="steel-worker" <?php echo ($productTypeRec->machine_type == "steel-worker") ? "selected='selected'" : ''; ?>>
                                                                Steelworker
                                                            </option>
                                                            <option value="metal-forming"
                                                                    data-screen="metal-forming" <?php echo ($productTypeRec->machine_type == "metal-forming") ? "selected='selected'" : ''; ?>>
                                                                Metal Forming
                                                            </option>
                                                            <option value="circular"
                                                                    data-screen="bandsaw" <?php echo ($productTypeRec->machine_type == "circular") ? "selected='selected'" : ''; ?>>
                                                                Circular
                                                            </option>
                                                            <option value="machining-centre"
                                                                    data-screen="bandsaw" <?php echo ($productTypeRec->machine_type == "machining-centre") ? "selected='selected'" : ''; ?>>
                                                                Machining Centre
                                                            </option>
                                                            <option value="aluminium"
                                                                    data-screen="bandsaw" <?php echo ($productTypeRec->machine_type == "aluminium") ? "selected='selected'" : ''; ?>>
                                                                Aluminium
                                                            </option>
                                                            <option value="material-handling"
                                                                    data-screen="material-handling" <?php echo ($productTypeRec->machine_type == "material-handling") ? "selected='selected'" : ''; ?>>
                                                                Material Handling
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="custom-tab" id="tab-material-handling">
                                                    <div class="control-group">
                                                        <label class="control-label">Height (mm)</label>
                                                        <div class="controls">
                                                            <input type="text" class="input-block-level " name="mh_length" value="<?php echo $productTypeRec->mh_length?>">
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label">Width (mm)</label>
                                                        <div class="controls">
                                                            <input type="text" class="input-block-level " name="mh_width" value="<?php echo $productTypeRec->mh_width?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="custom-tab" id="tab-bandsaw">
                                                    <div class="inner-box-custom">
                                                        <div class="row-fluid">
                                                            <div class="span2">
                                                                #
                                                            </div>
                                                            <div class="span2">
                                                                90
                                                            </div>
                                                            <div class="span2">
                                                                45 Left
                                                            </div>
                                                            <div class="span2">
                                                                45 Right
                                                            </div>
                                                            <div class="span2">
                                                                60 Left
                                                            </div>
                                                            <div class="span2">
                                                                60 Right
                                                            </div>
                                                        </div>
                                                        <div class="row-fluid">
                                                            <div class="span2">
                                                                Round Section
                                                            </div>
                                                            <div class="span2">
                                                                <input type="text" class="input-block-level "
                                                                       name="capacity_round_90"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->capacity_round_90 : ''; ?>">
                                                            </div>
                                                            <div class="span2">
                                                                <input type="text" class="input-block-level "
                                                                       name="capacity_round_45_left"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->capacity_round_45_left : ''; ?>">
                                                            </div>
                                                            <div class="span2">
                                                                <input type="text" class="input-block-level "
                                                                       name="capacity_round_45_right"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->capacity_round_45_right : ''; ?>">
                                                            </div>
                                                            <div class="span2">
                                                                <input type="text" class="input-block-level "
                                                                       name="capacity_round_60_left"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->capacity_round_60_left : ''; ?>">
                                                            </div>
                                                            <div class="span2">
                                                                <input type="text" class="input-block-level "
                                                                       name="capacity_round_60_right"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->capacity_round_60_right : ''; ?>">
                                                            </div>
                                                        </div>

                                                        <div class="row-fluid">
                                                            <div class="span2">
                                                                Rectangular Section Horizontal
                                                            </div>
                                                            <div class="span2">
                                                                <input type="text" class="input-block-level "
                                                                       name="capacity_rec_horizontal_90"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->capacity_rec_horizontal_90 : ''; ?>">

                                                            </div>
                                                            <div class="span2">
                                                                <input type="text" class="input-block-level "
                                                                       name="capacity_rec_horizontal_45_left"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->capacity_rec_horizontal_45_left : ''; ?>">

                                                            </div>
                                                            <div class="span2">
                                                                <input type="text" class="input-block-level "
                                                                       name="capacity_rec_horizontal_45_right"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->capacity_rec_horizontal_45_right : ''; ?>">

                                                            </div>
                                                            <div class="span2">
                                                                <input type="text" class="input-block-level "
                                                                       name="capacity_rec_horizontal_60_left"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->capacity_rec_horizontal_60_left : ''; ?>">

                                                            </div>
                                                            <div class="span2">
                                                                <input type="text" class="input-block-level "
                                                                       name="capacity_rec_horizontal_60_right"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->capacity_rec_horizontal_60_right : ''; ?>">

                                                            </div>
                                                        </div>
                                                        <div class="row-fluid">
                                                            <div class="span2">
                                                                Rectangular Section Vertical
                                                            </div>
                                                            <div class="span2">
                                                                <input type="text" class="input-block-level "
                                                                       name="capacity_rec_vertical_90"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->capacity_rec_vertical_90 : ''; ?>">
                                                            </div>
                                                            <div class="span2">
                                                                <input type="text" class="input-block-level "
                                                                       name="capacity_rec_vertical_45_left"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->capacity_rec_vertical_45_left : ''; ?>">
                                                            </div>
                                                            <div class="span2">
                                                                <input type="text" class="input-block-level "
                                                                       name="capacity_rec_vertical_45_right"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->capacity_rec_vertical_45_right : ''; ?>">
                                                            </div>
                                                            <div class="span2">
                                                                <input type="text" class="input-block-level "
                                                                       name="capacity_rec_vertical_60_left"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->capacity_rec_vertical_60_left : ''; ?>">
                                                            </div>
                                                            <div class="span2">
                                                                <input type="text" class="input-block-level "
                                                                       name="capacity_rec_vertical_60_right"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->capacity_rec_vertical_60_right : ''; ?>">

                                                            </div>
                                                        </div>
                                                        <div class="row-fluid">
                                                            <div class="span2">
                                                                Square Section
                                                            </div>
                                                            <div class="span2">
                                                                <input type="text" class="input-block-level "
                                                                       name="capacity_rec_square_90"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->capacity_rec_square_90 : ''; ?>">
                                                            </div>
                                                            <div class="span2">
                                                                <input type="text" class="input-block-level "
                                                                       name="capacity_rec_square_45_left"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->capacity_rec_square_45_left : ''; ?>">
                                                            </div>
                                                            <div class="span2">
                                                                <input type="text" class="input-block-level "
                                                                       name="capacity_rec_square_45_right"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->capacity_rec_square_45_right : ''; ?>">
                                                            </div>
                                                            <div class="span2">
                                                                <input type="text" class="input-block-level "
                                                                       name="capacity_rec_square_60_left"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->capacity_rec_square_60_left : ''; ?>">
                                                            </div>
                                                            <div class="span2">
                                                                <input type="text" class="input-block-level "
                                                                       name="capacity_rec_square_60_right"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->capacity_rec_square_60_right : ''; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="row-fluid">
                                                            <div class="span2">
                                                                Solid Round 90
                                                            </div>
                                                            <div class="span2">
                                                                <input type="text" class="input-block-level "
                                                                       name="capacity_solid_90_round"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->capacity_solid_90_round : ''; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="row-fluid">
                                                            <div class="span2">
                                                                Solid Rectangle 90
                                                            </div>
                                                            <div class="span2">
                                                                <input type="text" class="input-block-level "
                                                                       name="capacity_solid_90_rec"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->capacity_solid_90_rec : ''; ?>">

                                                            </div>
                                                        </div>
                                                        <div class="row-fluid">
                                                            <div class="span2">
                                                                Solid Square 90
                                                            </div>
                                                            <div class="span2">
                                                                <input type="text" class="input-block-level "
                                                                       name="capacity_solid_90_square"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->capacity_solid_90_square : ''; ?>">

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="inner">
                                                        <div class="row-fluid">
                                                            <div class="span2">
                                                                Blade Type:
                                                            </div>
                                                            <div class="span10">
                                                                <input type="text" class="input-block-level "
                                                                       name="blade_type"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->blade_type : ''; ?>">

                                                            </div>
                                                        </div>
                                                        <div class="row-fluid">
                                                            <div class="span2">
                                                                Blade Size:
                                                            </div>
                                                            <div class="span3">
                                                                <input type="text" class="input-block-level "
                                                                       name="spec_blade_size_1"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->spec_blade_size_1 : ''; ?>">

                                                            </div>
                                                            <div class="span3">
                                                                <input type="text" class="input-block-level "
                                                                       name="spec_blade_size_2"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->spec_blade_size_2 : ''; ?>">

                                                            </div>
                                                            <div class="span3">
                                                                <input type="text" class="input-block-level "
                                                                       name="spec_blade_size_3"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->spec_blade_size_3 : ''; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="row-fluid">
                                                            <div class="span2">
                                                                Blade Speed:
                                                            </div>
                                                            <div class="span10">
                                                                <input type="text" class="input-block-level "
                                                                       name="blade_speed"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->blade_speed : ''; ?>">

                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>
                                                <div class="custom-tab" id="tab-steel-worker">
                                                    <div class="inner-box-custom">
                                                        <div class="control-group">
                                                            <label class="control-label">Steel Flatbarshear 1</label>
                                                            <div class="controls">
                                                                <input type="text" class="input-block-level "
                                                                       name="flatbar_shear_1"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->flatbar_shear_1 : ''; ?>">
                                                            </div>
                                                        </div>


                                                        <div class="control-group">
                                                            <label class="control-label">Steel Flatbarshear 2</label>
                                                            <div class="controls">
                                                                <input type="text" class="input-block-level "
                                                                       name="flatbar_shear_2"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->flatbar_shear_2 : ''; ?>">
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <label class="control-label">Steel Punching 1</label>
                                                            <div class="controls">
                                                                <input type="text" class="input-block-level " name="punching_1"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->punching_1 : ''; ?>">
                                                            </div>
                                                        </div>


                                                        <div class="control-group">
                                                            <label class="control-label">Steel Punching 2</label>
                                                            <div class="controls">
                                                                <input type="text" class="input-block-level " name="punching_2"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->punching_2 : ''; ?>">
                                                            </div>
                                                        </div>


                                                        <div class="control-group">
                                                            <label class="control-label">Steel Rectangular
                                                                Notching</label>
                                                            <div class="controls">
                                                                <input type="text" class="input-block-level "
                                                                       name="rectangular_notching"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->rectangular_notching : ''; ?>">
                                                            </div>
                                                        </div>


                                                        <div class="control-group">
                                                            <label class="control-label">Steel Triangular
                                                                Notching</label>
                                                            <div class="controls">
                                                                <input type="text" class="input-block-level "
                                                                       name="triangular_notching"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->triangular_notching : ''; ?>">
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <label class="control-label">Steel Angle Shear 90</label>
                                                            <div class="controls">
                                                                <input type="text" class="input-block-level "
                                                                       name="angle_shear_90"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->angle_shear_90 : ''; ?>">
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <label class="control-label">Steel Angle Shear 45</label>
                                                            <div class="controls">
                                                                <input type="text" class="input-block-level "
                                                                       name="angle_shear_45"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->angle_shear_45 : ''; ?>">
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <label class="control-label">Steel Bending</label>
                                                            <div class="controls">
                                                                <input type="text" class="input-block-level " name="bending"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->bending : ''; ?>">
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <label class="control-label">Steel Solid Bar</label>
                                                            <div class="controls">
                                                                <input type="text" class="input-block-level " name="solid_bar"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->solid_bar : ''; ?>">
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <label class="control-label">Steel Angle Shearing
                                                                Power</label>
                                                            <div class="controls">
                                                                <input type="text" class="input-block-level "
                                                                       name="angle_shearing_power"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->angle_shearing_power : ''; ?>">
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <label class="control-label">Steel Angle Shear
                                                                Tonnage</label>
                                                            <div class="controls">
                                                                <input type="text" class="input-block-level "
                                                                       name="angle_shearing_tonnage"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->angle_shearing_tonnage : ''; ?>">
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <label class="control-label">Steel Punching Power</label>
                                                            <div class="controls">
                                                                <input type="text" class="input-block-level "
                                                                       name="punching_power"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->punching_power : ''; ?>">

                                                            </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <label class="control-label">Steel Angle Optional
                                                                Blade</label>
                                                            <div class="controls">
                                                                <input type="text" class="input-block-level "
                                                                       name="angle_optional_blade"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->angle_optional_blade : ''; ?>">
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <label class="control-label">Steel Throat Depth</label>
                                                            <div class="controls">
                                                                <input type="text" class="input-block-level "
                                                                       name="throat_depth"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->throat_depth : ''; ?>">
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>


                                                <div class="custom-tab" id="tab-metal-forming">
                                                    <div class="inner-box-custom">
                                                        <div class="control-group">
                                                            <label class="control-label">Spec Pre Bending</label>
                                                            <div class="controls">
                                                                <input type="text" class="input-block-level "
                                                                       name="spec_pre_bending"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->spec_pre_bending : ''; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <label class="control-label">Spec Top Roll</label>
                                                            <div class="controls">
                                                                <input type="text" class="input-block-level "
                                                                       name="spec_top_roll"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->spec_top_roll : ''; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <label class="control-label">Spec Bottom Roll</label>
                                                            <div class="controls">
                                                                <input type="text" class="input-block-level "
                                                                       name="spec_bottom_roll"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->spec_bottom_roll : ''; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <label class="control-label">Spec Side Roll</label>
                                                            <div class="controls">
                                                                <input type="text" class="input-block-level "
                                                                       name="spec_side_roll"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->spec_side_roll : ''; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <label class="control-label">Spec Bending Speed</label>
                                                            <div class="controls">
                                                                <input type="text" class="input-block-level "
                                                                       name="spec_bending_speed"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->spec_bending_speed : ''; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <label class="control-label">Spec Rolls</label>
                                                            <div class="controls">
                                                                <input type="text" class="input-block-level " name="spec_rolls"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->spec_rolls : ''; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <label class="control-label">Spec Shaft</label>
                                                            <div class="controls">
                                                                <input type="text" class="input-block-level " name="spec_shaft"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->spec_shaft : ''; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <label class="control-label">Spec Max Section</label>
                                                            <div class="controls">
                                                                <input type="text" class="input-block-level "
                                                                       name="spec_max_section"
                                                                       value="<?php echo ($productTypeRec) ? $productTypeRec->spec_max_section : ''; ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="box box-color box-bordered" id="attrGlroupBox">
                                            <div class="box-title">
                                                <h3>
                                                    <i class="icon-shopping-cart"></i> Other Information</h3>
                                                <div class="actions">
                                                    <a href="products/products-edit.php" class="updateProduct"
                                                       class="btn btn-mini" rel="tooltip" title="" data-original-title="Update"><i
                                                                class="icon-save"></i></a>
                                                    <a href="#" id="deleteProductType" class="btn btn-mini" rel="tooltip"
                                                       title="" data-original-title="Update"><i class="icon-trash"></i></a>
                                                </div>
                                            </div>
                                            <div class="box-content nopadding">

                                                <div class="control-group">
                                                    <label class="control-label">Operation:</label>
                                                    <div class="controls">
                                                        <select name="operation">
                                                            <option value="H" <?php echo ($productTypeRec->operation == "H") ? "selected='selected'" : ''; ?>>
                                                                Horizontal
                                                            </option>
                                                            <option value="V" <?php echo ($productTypeRec->operation == "V") ? "selected='selected'" : ''; ?>>
                                                                Vertical
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>


                                                <div class="control-group">
                                                    <label class="control-label">With materials handling system:</label>
                                                    <div class="controls">
                                                        <select name="materials_hand">
                                                            <option value="N" <?php echo ($productTypeRec->materials_hand == "N") ? "selected='selected'" : ''; ?>>
                                                                No
                                                            </option>
                                                            <option value="Y" <?php echo ($productTypeRec->materials_hand == "Y") ? "selected='selected'" : ''; ?>>
                                                                Yes
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="control-group">
                                                    <label class="control-label">Tonnage</label>
                                                    <div class="controls">
                                                        <input type="text" class="input-block-level " name="tonnage"
                                                               value="<?php echo ($productTypeRec) ? $productTypeRec->tonnage : ''; ?>">
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label">Throat Capacity</label>
                                                    <div class="controls">
                                                        <input type="text" class="input-block-level " name="throat_capacity"
                                                               value="<?php echo ($productTypeRec) ? $productTypeRec->throat_capacity : ''; ?>">
                                                    </div>
                                                </div>

                                                <div class="control-group">
                                                    <label class="control-label">Power Input</label>
                                                    <div class="controls">
                                                        <input type="text" class="input-block-level " name="power_supply"
                                                               value="<?php echo ($productTypeRec) ? $productTypeRec->power_supply : ''; ?>">
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label">Table Height</label>
                                                    <div class="controls">
                                                        <input type="text" class="input-block-level " name="table_height"
                                                               value="<?php echo ($productTypeRec) ? $productTypeRec->table_height : ''; ?>">
                                                    </div>
                                                </div>


                                                <div class="control-group">
                                                    <label class="control-label">Table Size</label>
                                                    <div class="controls">
                                                        <input type="text" class="input-block-level " name="table_size"
                                                               value="<?php echo ($productTypeRec) ? $productTypeRec->table_size : ''; ?>">
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label">Motor</label>
                                                    <div class="controls">
                                                        <input type="text" class="input-block-level " name="motor"
                                                               value="<?php echo ($productTypeRec) ? $productTypeRec->motor : ''; ?>">
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label">Hydraulic Motor Type</label>
                                                    <div class="controls">
                                                        <input type="text" class="input-block-level "
                                                               name="hydraulic_motor_type"
                                                               value="<?php echo ($productTypeRec) ? $productTypeRec->hydraulic_motor_type : ''; ?>">
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label">Hydraulic Tank</label>
                                                    <div class="controls">
                                                        <input type="text" class="input-block-level " name="hydraulic_tank"
                                                               value="<?php echo ($productTypeRec) ? $productTypeRec->hydraulic_tank : ''; ?>">
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label">Coolant Motor</label>
                                                    <div class="controls">
                                                        <input type="text" class="input-block-level " name="coolant_motor"
                                                               value="<?php echo ($productTypeRec) ? $productTypeRec->coolant_motor : ''; ?>">
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label">Coolant Tank</label>
                                                    <div class="controls">
                                                        <input type="text" class="input-block-level " name="coolant_tank"
                                                               value="<?php echo ($productTypeRec) ? $productTypeRec->coolant_tank : ''; ?>">
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label">Coolant Pump</label>
                                                    <div class="controls">
                                                        <input type="text" class="input-block-level " name="coolant_pump"
                                                               value="<?php echo ($productTypeRec) ? $productTypeRec->coolant_pump : ''; ?>">
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label">Feeding Stroke</label>
                                                    <div class="controls">
                                                        <input type="text" class="input-block-level " name="feeding_stroke"
                                                               value="<?php echo ($productTypeRec) ? $productTypeRec->feeding_stroke : ''; ?>">
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label">Weight</label>
                                                    <div class="controls">
                                                        <input type="text" class="input-block-level " name="weight"
                                                               value="<?php echo ($productTypeRec) ? $productTypeRec->weight : ''; ?>">
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label">Machine Dimensions</label>
                                                    <div class="controls">
                                                        <input type="text" class="input-block-level " name="machine_dimensions"
                                                               value="<?php echo ($productTypeRec) ? $productTypeRec->machine_dimensions : ''; ?>">
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label">Machine Dimensions 1</label>
                                                    <div class="controls">
                                                        <input type="text" class="input-block-level "
                                                               name="machine_dimensions_1"
                                                               value="<?php echo ($productTypeRec) ? $productTypeRec->machine_dimensions_1 : ''; ?>">
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label">Machine Dimensions 2</label>
                                                    <div class="controls">
                                                        <input type="text" class="input-block-level "
                                                               name="machine_dimensions_2"
                                                               value="<?php echo ($productTypeRec) ? $productTypeRec->machine_dimensions_2 : ''; ?>">
                                                    </div>
                                                </div>


                                                <div class="control-group hide">
                                                    <label class="control-label">Blade Size</label>
                                                    <div class="controls">
                                                        <input type="text" class="input-block-level " name="blade_size"
                                                               value="<?php echo ($productTypeRec) ? $productTypeRec->blade_size : ''; ?>">
                                                    </div>
                                                </div>

                                                <div class="control-group hide">
                                                    <label class="control-label">Dimensions Speed</label>
                                                    <div class="controls">
                                                        <input type="text" class="input-block-level " name="dimensions_speed"
                                                               value="<?php echo ($productTypeRec) ? $productTypeRec->dimensions_speed : ''; ?>">
                                                    </div>
                                                </div>


                                                <div class="control-group hide">
                                                    <label class="control-label">Product categories <small>Product
                                                            tags</small> </label>

                                                    <div class="controls">

                                                        <select multiple="multiple" class="input-large" name="prttagselect"
                                                                id="prttagselect">

                                                            <?php

                                                            $tableLength = count($subCategories);
                                                            for ($i = 0; $i < $tableLength; ++$i) {
                                                                $prdTags = explode(",", $productTypeRec->prttag);

                                                                ?>

                                                                <option
                                                                        value="<?php echo $subCategories[$i]['sub_id'] ?>" <?php echo (isset($productTypeRec) && in_array(
                                                                        $subCategories[$i]['sub_id'],
                                                                        $prdTags
                                                                    )) ? 'selected' : ''; ?>><?php echo $subCategories[$i]['subnam'] ?></option>

                                                            <?php } ?>

                                                        </select>

                                                        <input type="hidden" name="prttag">

                                                    </div>
                                                </div>

                                                <!--<div id="priceOptions">-->
                                                <!--                                <div class="control-group">-->
                                                <!--                                    <label class="control-label">Sale Price-->
                                                <!--                                        <small>default price charged to customers</small>-->
                                                <!--                                    </label>-->
                                                <!---->
                                                <!--                                    <div class="controls">-->
                                                <!--                                        <input type="text" class="input-block-level" name="unipri"-->
                                                <!--                                               data-rule-number="true"-->
                                                <!--                                               value="-->
                                                <?php //echo ($productTypeRec) ? $productTypeRec->unipri : '0'; ?><!--">-->
                                                <!--                                    </div>-->
                                                <!--                                </div>-->

                                                <div class="control-group hide">
                                                    <label class="control-label">Sale Price <small>default price charged
                                                            to customers</small> </label>

                                                    <div class="controls">
                                                        <div class="input-append">
                                                            <input type="text" class="input-small" name="unipri"
                                                                   value="<?php echo ($productTypeRec) ? $productTypeRec->unipri : '0'; ?>">
                                                            <button class="btn" type="button" rel="tooltip" title=""
                                                                    data-original-title="Update Products Sale Price"
                                                                    id="updateSalePriceBtn"><i
                                                                        class="icon icon-chevron-right"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="control-group ">

                                                    <div class="control-group ">
                                                        <div class="controls">
                                                            Supported Video Types<br /> <small>
                                                                https://www.youtube.com/watch?v=VIDEO_ID<br />
                                                                https://vimeo.com/VIDEO_ID </small>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="control-group ">
                                                    <label class="control-label">Youtube / Vimeo (1)</label>

                                                    <div class="controls">
                                                        <input type="text" class="input-block-level customfield"
                                                               name="youtube"
                                                               value="<?php echo (isset($productTypeRec->prtobj)) ? $patchworks->getJSONVariable(
                                                                   $productTypeRec->prtobj,
                                                                   'youtube',
                                                                   false
                                                               ) : ''; ?>">

                                                    </div>
                                                </div>
                                                <div class="control-group ">
                                                    <label class="control-label">Youtube / Vimeo (2)</label>

                                                    <div class="controls">
                                                        <input type="text" class="input-block-level customfield"
                                                               name="youtube2"
                                                               value="<?php echo (isset($productTypeRec->prtobj)) ? $patchworks->getJSONVariable(
                                                                   $productTypeRec->prtobj,
                                                                   'youtube2',
                                                                   false
                                                               ) : ''; ?>">

                                                    </div>
                                                </div>
                                                <div class="control-group ">
                                                    <label class="control-label">Youtube / Vimeo (3)

                                                    </label>

                                                    <div class="controls">

                                                        <input type="text" class="input-block-level customfield"
                                                               name="youtube3"
                                                               value="<?php echo (isset($productTypeRec->prtobj)) ? $patchworks->getJSONVariable(
                                                                   $productTypeRec->prtobj,
                                                                   'youtube3',
                                                                   false
                                                               ) : ''; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="box box-color box-bordered" id="attrGroupBox">
                                            <div class="box-title">
                                                <h3>
                                                    <i class="icon-shopping-cart"></i> Machine Meta Info</h3>
                                                <div class="actions">
                                                    <a href="products/products-edit.php" class="updateProduct"
                                                       class="btn btn-mini"
                                                       rel="tooltip" title="Update"><i class="icon-save"></i></a>
                                                    <a href="#" id="deleteProductType" class="btn btn-mini" rel="tooltip"
                                                       title="Update"><i
                                                                class="icon-trash"></i></a>
                                                </div>
                                            </div>
                                            <div class="box-content" style="padding:5px 10px;">
                                                <div class="control-group">
                                                    <label class="control-label">Machine Title: <small>enter a title for
                                                            this machine</small> </label>

                                                    <div class="controls">
                                                        <input type="text" class="input-block-level" name="machine_title"
                                                               value="<?php echo ($productTypeRec) ? $productTypeRec->machine_title : ''; ?>"
                                                               data-rule-minlength="0" data-rule-maxlength="80"
                                                               data-rule-required="">
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label">SEO Keywords</label>

                                                    <div class="controls">
                                        <textarea name="seokey"
                                                  class="input-block-level"><?php echo ($productTypeRec) ? $productTypeRec->seokey : ''; ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label">SEO Description</label>

                                                    <div class="controls">
                                        <textarea name="seodsc"
                                                  class="input-block-level"><?php echo ($productTypeRec) ? $productTypeRec->seodsc : ''; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </form>
                                <div class="span4">
                                    <div class="row-fluid">
                                        <div class="span12">

                                            <div class="box box-color box-bordered">
                                                <div class="box-title" style="margin-top: 0;">
                                                    <h3>
                                                        <i class="icon-cogs"></i> Machine Properties
                                                    </h3>
                                                    <ul class="tabs">
                                                        <li class="active">
                                                            <a href="#t10" data-toggle="tab">Images</a>
                                                        </li>
                                                        <!--                                <li>-->
                                                        <!--                                    <a href="#t12" data-toggle="tab">Attributes</a>-->
                                                        <!--                                </li>-->
                                                        <li>
                                                            <a href="#t7" data-toggle="tab" style="display:none;">Variants</a>
                                                        </li>
                                                        <li class="">
                                                            <a href="#t8" data-toggle="tab">Structure</a>
                                                        </li>
                                                        <li class="">
                                                            <a href="#t9" data-toggle="tab">Related Products</a>
                                                        </li>
                                                        <li class="hide">
                                                            <a href="#t5" data-toggle="tab">Reviews</a>
                                                        </li>
                                                        <li class="">
                                                            <a href="#t11" data-toggle="tab">Downloads</a>
                                                        </li>
                                                        <li class="">
                                                            <a href="#t13" data-toggle="tab">Filters</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="box-content nopadding">
                                                    <div class="tab-content">

                                                        <div class="tab-pane" id="t13">

                                                            <div id="attrLabelTableBox">
                                                                <div class="box-title" style="margin-top: 0;">
                                                                    <div class="actions">

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="inner" style="padding: 15px;">
                                                                <?php

                                                                $filters_lists = $ListingDAO->select();

                                                                $filters = $productTypeRec->filters;
                                                                $filters_array = explode(",", $filters);
                                                                foreach ($filters_lists as $filters_list) {
                                                                    ?>
                                                                    <div style="clear: both; width: 100%;">
                                                                        <p>
                                                                            <b><?php echo $filters_list['saw_category_ttl']; ?></b>
                                                                        </p>
                                                                    </div>

                                                                    <?php


                                                                    ?>

                                                                    <ul class="filters-options">
                                                                        <?php

                                                                        $OperationType = $TmpOperationType->select(
                                                                            null,
                                                                            false,
                                                                            $filters_list['saw_category_id']
                                                                        );
                                                                        foreach ($OperationType as $type) {
                                                                            echo "<li>";
                                                                            echo "<label>";
                                                                            $attr = "";
                                                                            if (in_array($type['operation_type_id'], $filters_array)) {
                                                                                $attr = "checked";
                                                                            }
                                                                            echo "<input type='checkbox' " . $attr . " value='" . $type['operation_type_id'] . "' class='filters_type'>";
                                                                            echo $type['operation_type_name'];
                                                                            echo "</label>";
                                                                            echo "</li>";
                                                                        }
                                                                        ?>
                                                                    </ul>
                                                                    <?php

                                                                }
                                                                ?>
                                                                <hr>
                                                            </div>
                                                        </div>

                                                        <div class="tab-pane" id="t12">

                                                            <div id="attrLabelTableBox">
                                                                <div class="box-title" style="margin-top: 0;">
                                                                    <div class="actions">

                                                                        <a href="#" class="btn btn-mini" id="createAttrLabelBtn"
                                                                           rel="tooltip"
                                                                           title="New Attribute"><i class="icon-plus-sign"></i></a>

                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div id="attrLabelBox" style="display: none;">

                                                                <div class="box-title" style="margin-top: 0;">
                                                                    <div class="actions">

                                                                        <a href="#" class="btn btn-mini" id="cancelAttrLabelBtn"
                                                                           rel="tooltip"
                                                                           title="Cancel"><i class="icon-remove-sign"></i></a>
                                                                        <a href="#" class="btn btn-mini" id="updateAttrLabelBtn"
                                                                           rel="tooltip"
                                                                           title="Update"><i class="icon-save"></i></a>

                                                                    </div>
                                                                </div>

                                                                <form class="form-horizontal form-bordered form-validate"
                                                                      action="<?php echo $patchworks->pwRoot; ?>attributes/attrlabel_script.php"
                                                                      id="attrLabelForm">
                                                                    <input type="hidden" name="atr_id" id="atrId"
                                                                           value="<?php echo ($productTypeAttr) ? $productTypeAttr->atr_id : 0; ?>" />

                                                                    <input type="hidden" name="atl_id" id="id" value="0" />
                                                                    <input type="hidden" name="srtord" value="" />
                                                                    <input type="hidden" name="atllst" value="" />

                                                                    <div class="control-group">
                                                                        <label class="control-label">Label <small>attribute
                                                                                name/label</small> </label>

                                                                        <div class="controls">
                                                                            <input type="text" name="atllbl"
                                                                                   data-rule-required="true"
                                                                                   data-rule-minlength="2" value="">
                                                                        </div>
                                                                    </div>
                                                                    <div class="control-group">
                                                                        <label class="control-label">Help <small>help
                                                                                text for form</small> </label>

                                                                        <div class="controls">
                                                    <textarea class="input-block-level" rows="4"
                                                              name="atldsc"></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="control-group">
                                                                        <label class="control-label">Input Type <small>select
                                                                                input method</small> </label>
                                                                        <div class="controls">
                                                                            <select name="atltyp">
                                                                                <option value="text">Text</option>
                                                                                <option value="textarea">Description
                                                                                </option>
                                                                                <option value="checkbox">Checkbox
                                                                                </option>
                                                                                <optgroup label="Lists">
                                                                                    <option value="select">Select List
                                                                                    </option>
                                                                                    <option value="radio">Radio List
                                                                                    </option>
                                                                                </optgroup>
                                                                                <optgroup label="Special">
                                                                                    <option value="date">Date</option>
                                                                                    <option value="WYSIWYG">WYSIWYG
                                                                                    </option>
                                                                                </optgroup>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div id="AtlLstDiv">
                                                                        <div class="control-group">
                                                                            <label class="control-label">Add Entry
                                                                                <small>add items to list</small>
                                                                            </label>

                                                                            <div class="controls">
                                                                                <div class="input-append">
                                                                                    <input type="text" id="AddAltLst"
                                                                                           class="input-medium" />
                                                                                    <button class="btn" type="button"
                                                                                            id="addAltLst"
                                                                                            rel="tooltip"
                                                                                            title="Add Entry To List"><i
                                                                                                class="icon icon-plus"></i>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="control-group">
                                                                            <label class="control-label">Entry List
                                                                                <small>items in list</small> </label>

                                                                            <div class="controls">
                                                                                <ul id="AtrLst_UL"
                                                                                    style="padding: 0; margin: 0;">
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <textarea id="AtlLst" name="atllst" class="hide"></textarea>

                                                                    <div class="control-group">
                                                                        <label class="control-label">Required? <small>field
                                                                                is mandatory</small> </label>

                                                                        <div class="controls">
                                                                            <label class="checkbox">
                                                                                <input type="checkbox" name="atlreq" value="1">
                                                                                Required </label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="control-group">
                                                                        <label class="control-label">Show on website
                                                                            <small>display field to user</small>
                                                                        </label>

                                                                        <div class="controls">
                                                                            <label class="checkbox">
                                                                                <input type="checkbox" name="srcabl" value="1">
                                                                                Visible to users?</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="control-group">
                                                                        <label class="control-label">Search Type <small>how
                                                                                to search the data</small> </label>

                                                                        <div class="controls">
                                                                            <select name="srctyp">
                                                                                <option value="text">Text</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="control-group">
                                                                        <label class="control-label">Specialist Field
                                                                            <small>if field is used for
                                                                                functionality</small> </label>

                                                                        <div class="controls">
                                                                            <label class="checkbox">
                                                                                <input type="checkbox" name="atlspc" value="1">
                                                                                Special Field </label>
                                                                        </div>
                                                                    </div>
                                                                </form>

                                                            </div>

                                                        </div>

                                                        <div class="tab-pane" id="t11">

                                                            <div class="row-fluid">
                                                                <div class="span12">

                                                                    <table class="table table-bordered table-striped table-highlight"
                                                                           id="downloadsTable">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>File Name</th>
                                                                                <th style="width: 120px;"></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody id="productDownloads">

                                                                        </tbody>
                                                                    </table>

                                                                </div>
                                                            </div>
                                                            <div class="row-fluid">
                                                                <div class="span12">
                                                                    <div id="fileplupload">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>

                                                        <div class="tab-pane" id="t5">

                                                            <table class="table table-bordered table-striped table-highlight"
                                                                   id="reviewsTable">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Name</th>
                                                                        <th>Description</th>
                                                                        <th>Rating</th>
                                                                        <th></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="formsBody">
                                                                    <?php

                                                                    $tableLength = count($reviews);
                                                                    for ($i = 0; $i < $tableLength; ++$i) {
                                                                        ?>
                                                                        <tr>
                                                                            <td>
                                                                                <a href="<?php $patchworks->pwRoot; ?>reviews/reviews-edit.php?rev_id=<?php echo $reviews[$i]['rev_id']; ?>"><?php echo $reviews[$i]['revttl']; ?>
                                                                                    _</a></td>
                                                                            <td><?php echo $reviews[$i]['revdsc']; ?></td>
                                                                            <td><?php echo $reviews[$i]['rating']; ?></td>
                                                                            <td>
                                                                                <a href="#"
                                                                                   class="btn btn-danger btn-mini deleteReviewBtn"
                                                                                   data-rev_id="<?php echo $reviews[$i]['rev_id']; ?>"
                                                                                   rel="tooltip"
                                                                                   title="Delete"><i class="icon-trash"></i></a>
                                                                            </td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                </tbody>
                                                            </table>

                                                        </div>

                                                        <div class="tab-pane active" id="t10">
                                                            <div id="uploadImagesDiv">
                                                                <div class="box-title" style="margin-top: 0;">
                                                                    <div class="actions">
                                                                        <a href="#" id="addImageBtn" class="btn btn-mini"
                                                                           rel="tooltip"
                                                                           title=""
                                                                           style="" data-original-title="Add Images"><i
                                                                                    class="icon-plus"></i></a>
                                                                        <a href="#" id="updateGalleryImagesBtn"
                                                                           class="btn btn-mini"
                                                                           rel="tooltip" title=""
                                                                           data-original-title="Update"><i
                                                                                    class="icon-save"></i></a>
                                                                        <a href="#" id="cancelGalleryImagesBtn"
                                                                           class="btn btn-mini"
                                                                           rel="tooltip" title=""
                                                                           data-original-title="Cancel"><i
                                                                                    class="icon-remove"></i></a>
                                                                    </div>
                                                                </div>
                                                                <div class="box" id="productimagepicker">
                                                                    <div class="box-content">
                                                                        <form class="form-vertical form-validate form-bordered"
                                                                              method="POST"
                                                                              id="gallerySearchForm" novalidate="novalidate">
                                                                            <input name="img_id" type="hidden">
                                                                            <input name="imginp" type="hidden">
                                                                            <div class="control-group">
                                                                                <label class="control-label">Image
                                                                                    <small>Choose an image</small>
                                                                                </label>
                                                                                <div class="controls">
                                                                                    <div class="input-append">
                                                                                        <select id="action" class="input-large">
                                                                                            <option value="gallery">
                                                                                                Galleries
                                                                                            </option>
                                                                                            <option value="article">
                                                                                                Articles
                                                                                            </option>
                                                                                            <option value="event">Events
                                                                                            </option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="input-append gal-options"
                                                                                         id="gallery-select">
                                                                                        <select name="gal_id1"
                                                                                                class="input-large">
                                                                                            <option value="0">Search
                                                                                                Global Gallery
                                                                                            </option>
                                                                                            <?php

                                                                                            $tableLength = count($galleries);
                                                                                            for ($i = 0; $i < $tableLength; ++$i) {
                                                                                                ?>
                                                                                                <option
                                                                                                        value="<?php echo $galleries[$i]['gal_id']; ?>"><?php echo $galleries[$i]['galnam']; ?></option>
                                                                                            <?php } ?>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="input-append gal-options"
                                                                                         id="article-select"
                                                                                         style="display:none;">
                                                                                        <select name="gal_id1"
                                                                                                class="input-large">
                                                                                            <?php

                                                                                            $tableLength = count($articles);
                                                                                            for ($i = 0; $i < $tableLength; ++$i) {
                                                                                                ?>
                                                                                                <option
                                                                                                        value="<?php echo $articles[$i]['art_id']; ?>"><?php echo $articles[$i]['artttl']; ?></option>
                                                                                            <?php } ?>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="input-append gal-options"
                                                                                         id="event-select"
                                                                                         style="display:none;">
                                                                                        <select name="gal_id1"
                                                                                                class="input-large">
                                                                                            <?php

                                                                                            $tableLength = count($events);
                                                                                            for ($i = 0; $i < $tableLength; ++$i) {
                                                                                                ?>
                                                                                                <option
                                                                                                        value="<?php echo $events[$i]['pla_id']; ?>"><?php echo $events[$i]['planam']; ?></option>
                                                                                            <?php } ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="control-group">
                                                                                <label class="control-label">Keywords
                                                                                    <small>search gallery images</small>
                                                                                </label>
                                                                                <div class="controls">
                                                                                    <div class="input-append">
                                                                                        <input name="keywrd" id="keywrd1"
                                                                                               placeholder="Keyword Search..."
                                                                                               class="input-large" type="text">
                                                                                        <button class="btn" type="submit">
                                                                                            <i
                                                                                                    class="icon-search"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="control-group selfclear">
                                                                                <ul class="gallery gallery-dynamic masonry"
                                                                                    id="searchCoverImagelisting"
                                                                                    style="position: relative; height: 0px;">
                                                                                </ul>
                                                                            </div>
                                                                        </form>
                                                                        <ul class="gallery gallery-dynamic" id="imagelisting">
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row-fluid">
                                                                <div class="span12">
                                                                    <ul class="gallery gallery-dynamic" id="galleryImages"></ul>
                                                                </div>
                                                            </div>
                                                            <div class="row-fluid">
                                                                <div class="span12">
                                                                    <div id="plupload"
                                                                         data-imgsiz="<?php echo $patchworks->productImageSizes; ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="tab-pane " id="t7">


                                                            <div id="variantTableBox">

                                                                <div class="box-title" style="margin-top: 0;">
                                                                    <div class="actions">

                                                                        <a href="#" class="btn btn-mini" id="createVariantBtn"
                                                                           rel="tooltip"
                                                                           title="New Variant"><i
                                                                                    class="icon-plus-sign"></i></a>

                                                                    </div>
                                                                </div>
                                                                <table
                                                                        class="table table-bordered table-striped table-highlight table-condensed"
                                                                        id="productTable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Name</th>
                                                                            <th>Price</th>
                                                                            <th width="20"></th>
                                                                            <th width="20"></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="productBody">


                                                                    </tbody>
                                                                </table>
                                                            </div>

                                                            <div id="variantBox" style="display: none;">

                                                                <div class="box-title" style="margin-top: 0;">
                                                                    <div class="actions">

                                                                        <a href="#" class="btn btn-mini" id="cancelVariantBtn"
                                                                           rel="tooltip"
                                                                           title="Cancel"><i class="icon-remove-sign"></i></a>
                                                                        <a href="#" class="btn btn-mini" id="updateVariantBtn"
                                                                           rel="tooltip"
                                                                           title="Update"><i class="icon-save"></i></a>

                                                                    </div>
                                                                </div>

                                                                <form id="productForm"
                                                                      class="form-horizontal form-bordered form-validate"
                                                                      action="products/products_script.php">
                                                                    <input type="hidden" name="tblnam" value="PRODUCT">
                                                                    <input type="hidden" name="tbl_id"
                                                                           value="<?php echo ($productTypeRec) ? $productTypeRec->prt_id : 0; ?>">
                                                                    <input type="hidden" name="atr_id"
                                                                           value="<?php echo ($productTypeAttr) ? $productTypeAttr->atr_id : 0; ?>">
                                                                    <input type="hidden" name="prd_id" value="0">
                                                                    <input type="hidden" name="prt_id"
                                                                           value="<?php echo ($productTypeRec) ? $productTypeRec->prt_id : 0; ?>">

                                                                    <div class="control-group">
                                                                        <label class="control-label">Name <small>individual
                                                                                product name</small> </label>

                                                                        <div class="controls">
                                                                            <input type="text" class="input-block-level"
                                                                                   data-rule-required="true"
                                                                                   data-rule-minlength="2"
                                                                                   name="prdnam" value="">
                                                                        </div>
                                                                    </div>

                                                                    <div class="control-group">
                                                                        <label class="control-label">Reference
                                                                            <small></small> </label>
                                                                        <div class="controls">
                                                                            <input type="text" class="input-block-level"
                                                                                   name="altref"
                                                                                   value="">
                                                                        </div>
                                                                    </div>

                                                                    <div class="control-group">
                                                                        <label class="control-label">SEO URL</label>

                                                                        <div class="controls">
                                                                            <input type="text" class="input-block-level"
                                                                                   name="seourl"
                                                                                   value=""
                                                                                   data-rule-minlength="3"
                                                                                   data-rule-maxlength="200"
                                                                                   data-rule-required="true">
                                                                        </div>
                                                                    </div>


                                                                    <div class="control-group">
                                                                        <label class="control-label">Sale Price <small>individual
                                                                                product price</small> </label>

                                                                        <div class="controls">
                                                                            <input type="text" class="input-block-level"
                                                                                   data-rule-required="true"
                                                                                   data-rule-number="true"
                                                                                   name="unipri" value="0">
                                                                        </div>
                                                                    </div>

                                                                    <div class="control-group">
                                                                        <label class="control-label">Purchase Price
                                                                            <small></small> </label>

                                                                        <div class="controls">
                                                                            <input type="text" class="input-block-level"
                                                                                   name="buypri"
                                                                                   data-rule-number="true"
                                                                                   value="0">
                                                                        </div>
                                                                    </div>

                                                                    <div class="control-group hide">
                                                                        <label class="control-label">Delivery Price
                                                                            <small>delivery price</small> </label>

                                                                        <div class="controls">
                                                                            <input type="text" class="input-block-level"
                                                                                   data-rule-required="true"
                                                                                   data-rule-number="true"
                                                                                   name="delpri" value="0">
                                                                        </div>
                                                                    </div>


                                                                    <div class="control-group hide">
                                                                        <label class="control-label">Stock <small>total
                                                                                stock of this product variant</small>
                                                                        </label>

                                                                        <div class="controls">
                                                                            <select name="usestk">
                                                                                <option value="0" selected>Non Stock
                                                                                </option>
                                                                                <option value="1">Use Stock</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="control-group">
                                                                        <label class="control-label">In Stock <small>current
                                                                                stock of this product variant</small>
                                                                        </label>

                                                                        <div class="controls">
                                                                            <input type="text" class="input-block-level"
                                                                                   data-rule-required="true"
                                                                                   data-rule-number="true"
                                                                                   name="in_stk" value="0">
                                                                        </div>
                                                                    </div>
                                                                    <div class="control-group hide">
                                                                        <label class="control-label">On Order <small>stock
                                                                                on order of this product
                                                                                variant </small> </label>

                                                                        <div class="controls">
                                                                            <input type="text" class="input-block-level"
                                                                                   data-rule-required="true"
                                                                                   data-rule-number="true"
                                                                                   name="on_ord" value="0">
                                                                        </div>
                                                                    </div>
                                                                    <div class="control-group hide">
                                                                        <label class="control-label">On Delivery <small>current
                                                                                stock in transit</small> </label>

                                                                        <div class="controls">
                                                                            <input type="text" class="input-block-level"
                                                                                   data-rule-required="true"
                                                                                   data-rule-number="true"
                                                                                   name="on_del" value="0">
                                                                        </div>
                                                                    </div>


                                                                    <div class="control-group hide">
                                                                        <label class="control-label">Weight
                                                                            <small></small> </label>

                                                                        <div class="controls">
                                                                            <input type="text" class="input-block-level"
                                                                                   data-rule-required="true"
                                                                                   data-rule-number="true"
                                                                                   name="weight" value="0">
                                                                        </div>
                                                                    </div>

                                                                </form>

                                                                <form id="attlEntryForm"
                                                                      class="form-horizontal form-bordered attributeForm"
                                                                      action="attributes/attribute-entry_script.php">
                                                                </form>

                                                            </div>

                                                        </div>
                                                        <div class="tab-pane" id="t8">

                                                            <div style="display: block; padding-top: 10px;">

                                                                <div id="buildStructure">
                                                                    <?php

                                                                    $TmpStr->buildStructure(0, null, null, null, true);
                                                                    ?>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="tab-pane" id="t9">


                                                            <form id="relatedForm"
                                                                  class="form-horizontal form-bordered form-validate"
                                                                  action="system/related_script.php">
                                                                <input type="hidden" name="tblnam" value="PRDTYPE">
                                                                <input type="hidden" name="tbl_id"
                                                                       value="<?php echo (isset($productTypeRec)) ? $productTypeRec->prt_id : 0; ?>">
                                                                <input type="hidden" name="ref_id">

                                                                <div class="control-group">
                                                                    <label class="control-label"><i
                                                                                class="icon icon-search"></i> Search
                                                                        <small>start typing to see results</small>
                                                                    </label>

                                                                    <div class="controls">
                                                                        <input type="text"
                                                                               class="input-block-level autocomplete"
                                                                               name="refnam"
                                                                               autocomplete="off">
                                                                    </div>
                                                                </div>

                                                                <div class="control-group">
                                                                    <label class="control-label">Selected
                                                                        Product</label>

                                                                    <div class="controls">
                                                                        <input type="text"
                                                                               class="input-block-level autocomplete"
                                                                               id="relatedName"
                                                                               readonly>
                                                                    </div>
                                                                </div>

                                                                <div class="form-actions">
                                                                    <button type="submit" class="btn btn-primary">Relate
                                                                    </button>
                                                                </div>
                                                            </form>


                                                            <div id="relatedProductList" style="padding: 10px 20px;">

                                                            </div>

                                                        </div>

                                                    </div>
                                                </div>
                                            </div>


                                            <form class="form-horizontal form-validate" action="#" id="languageForm"
                                                  style="display: none">
                                                <div class="box">
                                                    <div class="box-title">
                                                        <h3>
                                                            <i class="icon-comments"></i> Language
                                                        </h3>
                                                        <ul class="tabs">
                                                            <li class="active">
                                                                <a href="#french" class="changelanguagelink"
                                                                   data-toggle="tab">French</a>
                                                            </li>
                                                            <li>
                                                                <a href="#german" class="changelanguagelink"
                                                                   data-toggle="tab">German</a>
                                                            </li>
                                                            <li class="">
                                                                <a href="#spanish" class="changelanguagelink"
                                                                   data-toggle="tab">Spanish</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="box-content">

                                                        <div class="tab-content">
                                                            <div class="tab-pane active" id="french">

                                                                <div class="control-group">
                                                                    <label class="control-label">French Name</label>

                                                                    <div class="controls">
                                                                        <input type="text" class="input-block-level customfield"
                                                                               name="fr_prdnam"
                                                                               value="<?php echo (isset($productTypeRec->prtobj)) ? $patchworks->getJSONVariable(
                                                                                   $productTypeRec->prtobj,
                                                                                   'fr_prdnam',
                                                                                   false
                                                                               ) : ''; ?>">
                                                                    </div>
                                                                </div>


                                                                <div class="control-group">
                                                                    <label class="control-label">SEO Keywords</label>

                                                                    <div class="controls">

                                                <textarea name="fr_keywrd"
                                                          class="input-block-level customfield"><?php echo (isset($productTypeRec->prtobj)) ? $patchworks->getJSONVariable(
                                                        $productTypeRec->prtobj,
                                                        'fr_keywrd',
                                                        false
                                                    ) : ''; ?></textarea>

                                                                    </div>
                                                                </div>


                                                                <div class="control-group">
                                                                    <label class="control-label">SEO Description</label>

                                                                    <div class="controls">

                                                <textarea name="fr_seodsc"
                                                          class="input-block-level customfield"><?php echo (isset($productTypeRec->prtobj)) ? $patchworks->getJSONVariable(
                                                        $productTypeRec->prtobj,
                                                        'fr_seodsc',
                                                        false
                                                    ) : ''; ?></textarea>

                                                                    </div>
                                                                </div>

                                                                <div class="control-group">
                                                                    <label class="control-label">Product
                                                                        Description</label>

                                                                    <div class="controls">

                                                <textarea name="fr_prddsc" class="input-block-level tinymce customfield"
                                                          id="fr_prddsc"><?php echo (isset($productTypeRec->prtobj)) ? $patchworks->getJSONVariable(
                                                        $productTypeRec->prtobj,
                                                        'fr_prddsc',
                                                        false
                                                    ) : ''; ?></textarea>

                                                                    </div>
                                                                </div>

                                                                <div class="control-group">
                                                                    <label class="control-label">Product
                                                                        Specification</label>

                                                                    <div class="controls">

                                                <textarea name="fr_prdspc" class="input-block-level tinymce customfield"
                                                          id="fr_prdspc"><?php echo (isset($productTypeRec->prtobj)) ? $patchworks->getJSONVariable(
                                                        $productTypeRec->prtobj,
                                                        'fr_prdspc',
                                                        false
                                                    ) : ''; ?></textarea>

                                                                    </div>
                                                                </div>


                                                            </div>

                                                            <div class="tab-pane" id="german">

                                                                <div class="control-group">
                                                                    <label class="control-label">German Name</label>

                                                                    <div class="controls">
                                                                        <input type="text" class="input-block-level customfield"
                                                                               name="ge_prdnam"
                                                                               value="<?php echo (isset($productTypeRec->prtobj)) ? $patchworks->getJSONVariable(
                                                                                   $productTypeRec->prtobj,
                                                                                   'ge_prdnam',
                                                                                   false
                                                                               ) : ''; ?>">
                                                                    </div>
                                                                </div>

                                                                <div class="control-group">
                                                                    <label class="control-label">SEO Keywords</label>

                                                                    <div class="controls">

                                                <textarea name="ge_keywrd"
                                                          class="input-block-level customfield"><?php echo (isset($productTypeRec->prtobj)) ? $patchworks->getJSONVariable(
                                                        $productTypeRec->prtobj,
                                                        'ge_keywrd',
                                                        false
                                                    ) : ''; ?></textarea>

                                                                    </div>
                                                                </div>

                                                                <div class="control-group">
                                                                    <label class="control-label">SEO Description</label>

                                                                    <div class="controls">

                                                <textarea name="ge_seodsc"
                                                          class="input-block-level customfield"><?php echo (isset($productTypeRec->prtobj)) ? $patchworks->getJSONVariable(
                                                        $productTypeRec->prtobj,
                                                        'ge_seodsc',
                                                        false
                                                    ) : ''; ?></textarea>

                                                                    </div>
                                                                </div>

                                                                <div class="control-group">
                                                                    <label class="control-label">Product
                                                                        Description</label>

                                                                    <div class="controls">

                                                <textarea name="ge_prddsc" class="input-block-level tinymce customfield"
                                                          id="ge_prddsc"><?php echo (isset($productTypeRec->prtobj)) ? $patchworks->getJSONVariable(
                                                        $productTypeRec->prtobj,
                                                        'ge_prddsc',
                                                        false
                                                    ) : ''; ?></textarea>

                                                                    </div>
                                                                </div>

                                                                <div class="control-group">
                                                                    <label class="control-label">Product
                                                                        Specification</label>

                                                                    <div class="controls">

                                                <textarea name="ge_prdspc" class="input-block-level tinymce customfield"
                                                          id="ge_prdspc"><?php echo (isset($productTypeRec->prtobj)) ? $patchworks->getJSONVariable(
                                                        $productTypeRec->prtobj,
                                                        'ge_prdspc',
                                                        false
                                                    ) : ''; ?></textarea>

                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="tab-pane" id="spanish">

                                                                <div class="control-group">
                                                                    <label class="control-label">Spanish Name</label>

                                                                    <div class="controls">
                                                                        <input type="text" class="input-block-level customfield"
                                                                               name="sp_prdnam"
                                                                               value="<?php echo (isset($productTypeRec->prtobj)) ? $patchworks->getJSONVariable(
                                                                                   $productTypeRec->prtobj,
                                                                                   'sp_prdnam',
                                                                                   false
                                                                               ) : ''; ?>">
                                                                    </div>
                                                                </div>

                                                                <div class="control-group">
                                                                    <label class="control-label">SEO Keywords</label>

                                                                    <div class="controls">

                                                <textarea name="sp_keywrd"
                                                          class="input-block-level customfield"><?php echo (isset($productTypeRec->prtobj)) ? $patchworks->getJSONVariable(
                                                        $productTypeRec->prtobj,
                                                        'sp_keywrd',
                                                        false
                                                    ) : ''; ?></textarea>

                                                                    </div>
                                                                </div>

                                                                <div class="control-group">
                                                                    <label class="control-label">SEO Description</label>

                                                                    <div class="controls">

                                                <textarea name="sp_seodsc"
                                                          class="input-block-level customfield"><?php echo (isset($productTypeRec->prtobj)) ? $patchworks->getJSONVariable(
                                                        $productTypeRec->prtobj,
                                                        'sp_seodsc',
                                                        false
                                                    ) : ''; ?></textarea>

                                                                    </div>
                                                                </div>

                                                                <div class="control-group">
                                                                    <label class="control-label">Product
                                                                        Description</label>

                                                                    <div class="controls">

                                                <textarea name="sp_prddsc" class="input-block-level tinymce customfield"
                                                          id="sp_prddsc"><?php echo (isset($productTypeRec->prtobj)) ? $patchworks->getJSONVariable(
                                                        $productTypeRec->prtobj,
                                                        'sp_prddsc',
                                                        false
                                                    ) : ''; ?></textarea>

                                                                    </div>
                                                                </div>

                                                                <div class="control-group">
                                                                    <label class="control-label">Product
                                                                        Specification</label>

                                                                    <div class="controls">

                                                <textarea name="sp_prdspc" class="input-block-level tinymce customfield"
                                                          id="sp_prdspc"><?php echo (isset($productTypeRec->prtobj)) ? $patchworks->getJSONVariable(
                                                        $productTypeRec->prtobj,
                                                        'sp_prdspc',
                                                        false
                                                    ) : ''; ?></textarea>

                                                                    </div>
                                                                </div>

                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                            </form>


                                        </div>

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
                    <button type="submit" class="btn btn-primary" name="action" value="update" id="updateImageBtn"><i
                                class="icon-save"></i> Update
                    </button>
                </div>
            </form>
        </div>

        <input type="hidden" id="webRoot" value="<?php echo $patchworks->webRoot; ?>">

    </body>
</html>
