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







$userAuth = new AuthDAO();



$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);



if ($loggedIn == 0) header('location: ../login.php');







$TmpPrt = new PrtDAO();



$editProductTypeID = (isset($_GET['prt_id']) && is_numeric($_GET['prt_id'])) ? $_GET['prt_id'] : NULL;







$productTypeRec = NULL;



$attrGroupRec = NULL;



$attrLabelRec = NULL;







if (!is_null($editProductTypeID)) {



    $productTypeRec = $TmpPrt->select($editProductTypeID, NULL, NULL, NULL, NULL, NULL, NULL, NULL, true);



}







$TmpAtr = new AtrDAO();



$attrGroups = $TmpAtr->select(NULL, 'PRODUCTGROUP');



$productTypeAttr = $TmpAtr->select(NULL, 'PRODUCTTYPE', $editProductTypeID, NULL, true, NULL, NULL, NULL);







$TmpStr = new StrDAO();







$TmpVat = new VatDAO();



$vatRates = $TmpVat->select(NULL, date("Y-m-d"), 0, false);







$TmpRev = new RevDAO();



$reviews = $TmpRev->select(NULL, 'PRODUCT', $editProductTypeID, false);







$TmpSub = new SubDAO();



$subCategories = $TmpSub->selectByTableName('product-category');







?>



<!doctype html>



<html>



<head>



    <title>Product</title>



    <?php include('../webparts/headdata.php'); ?>







    <!-- colorbox -->



    <link rel="stylesheet" href="css/plugins/colorbox/colorbox.css">











    <style>







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



                        <a href="index.php">Dashboard</a>



                        <i class="icon-angle-right"></i>



                    </li>



                    <li>



                        <a>eCommerce</a>



                        <i class="icon-angle-right"></i>



                    </li>



                    <li>



                        <a href="products/producttypes.php">Products</a>



                        <i class="icon-angle-right"></i>



                    </li>



                    <li>



                        <a><?php echo ($productTypeRec) ? $productTypeRec->prtnam : 'New Product'; ?></a>



                    </li>



                </ul>



            </div>



            <div class="row-fluid">



                <div class="span6">



                    <div class="box box-color box-bordered" id="attrGroupBox">



                        <div class="box-title">



                            <h3>



                                <i class="icon-shopping-cart"></i> Product</h3>







                            <div class="actions">



                                <a href="products/products-edit.php" id="updateProduct" class="btn btn-mini"



                                   rel="tooltip" title="Update"><i class="icon-save"></i></a>



                                <a href="#" id="deleteProductType" class="btn btn-mini" rel="tooltip" title="Update"><i



                                        class="icon-trash"></i></a>



                            </div>



                        </div>



                        <div class="box-content nopadding">



                            <form class="form-horizontal form-bordered form-validate" method="POST"



                                  action="products/product_types_script.php" id="productEntryForm"



                                  data-returnurl="products/producttypes.php">



                                <input type="hidden" name="prt_id" id="id"



                                       value="<?php echo ($productTypeRec) ? $productTypeRec->prt_id : 0; ?>"/>



                                <input type="hidden" class="input-block-level" name="tblnam" id="TblNam"



                                       value="PRODUCT">



                                <input type="hidden" class="input-block-level" name="tbl_id" id="Tbl_ID" value="0">







                                <div class="control-group">



                                    <label class="control-label">Group



                                        <small>select the product group</small>



                                    </label>







                                    <div class="controls">



                                        <select name="atr_id">







                                            <option value="0">N/A</option>







                                            <?php



                                            $tableLength = count($attrGroups);



                                            for ($i = 0; $i < $tableLength; ++$i) {



                                                ?>



                                                <option



                                                    value="<?php echo $attrGroups[$i]['atr_id']; ?>" <?php if ($productTypeRec && $productTypeRec->atr_id == $attrGroups[$i]['atr_id']) echo 'selected'; ?>><?php echo $attrGroups[$i]['atrnam']; ?></option>



                                            <?php } ?>



                                        </select>



                                    </div>



                                </div>







                                <div class="control-group">



                                    <label class="control-label">Product Name



                                        <small>enter a name for this product</small>



                                    </label>







                                    <div class="controls">



                                        <input type="text" class="input-block-level" name="prtnam"



                                               value="<?php echo ($productTypeRec) ? $productTypeRec->prtnam : ''; ?>"



                                               data-rule-minlength="3" data-rule-maxlength="200"



                                               data-rule-required="true">



                                    </div>



                                </div>



                                <div class="control-group">



                                    <label class="control-label">SEO URL</label>







                                    <div class="controls">



                                        <input type="text" class="input-block-level" name="seourl"



                                               value="<?php echo ($productTypeRec) ? $productTypeRec->seourl : ''; ?>"



                                               data-rule-minlength="3" data-rule-maxlength="200"



                                               data-rule-required="true">



                                    </div>



                                </div>







                                <div class="control-group">



                                    <label class="control-label">Product Description</label>



                                </div>



                                <div class="control-group">



                                    <label class="control-label hide">Product Description</label>







                                    <div class="controls nopadding" style="margin: 0;">



                                        <textarea class="ckeditor span12" name="prtdsc" id="prtdsc"



                                                  style="height: 300px"><?php echo ($productTypeRec) ? $productTypeRec->prtdsc : ''; ?></textarea>



                                    </div>



                                </div>







                                <div class="control-group">



                                    <label class="control-label">Product Specification</label>



                                </div>



                                <div class="control-group">



                                    <label class="control-label hide">Product Specification</label>







                                    <div class="controls nopadding" style="margin: 0;">



                                        <textarea class="ckeditor span12" name="prtspc" id="prtspc"



                                                  style="height: 300px"><?php echo ($productTypeRec) ? $productTypeRec->prtspc : ''; ?></textarea>



                                    </div>



                                </div>











                                <div class="control-group">



                                    <label class="control-label">Product categories



                                        <small>Product tags</small>



                                    </label>







                                    <div class="controls">







                                        <select multiple="multiple" class="input-large" name="prttagselect"



                                                id="prttagselect">







                                            <?php



                                            $tableLength = count($subCategories);



                                            for ($i = 0; $i < $tableLength; ++$i) {







                                                $prdTags = explode(",", $productTypeRec->prttag);







                                                ?>







                                                <option



                                                    value="<?php echo $subCategories[$i]['sub_id'] ?>" <?php echo (isset($productTypeRec) && in_array($subCategories[$i]['sub_id'], $prdTags)) ? 'selected' : ''; ?>><?php echo $subCategories[$i]['subnam'] ?></option>







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



<!--                                               value="--><?php //echo ($productTypeRec) ? $productTypeRec->unipri : '0'; ?><!--">-->



<!--                                    </div>-->



<!--                                </div>-->







                                <div class="control-group">



                                    <label class="control-label">Sale Price



                                        <small>default price charged to customers</small>



                                    </label>







                                    <div class="controls">



                                        <div class="input-append">



                                            <input type="text" class="input-small" name="unipri"



                                                   value="<?php echo ($productTypeRec) ? $productTypeRec->unipri : '0'; ?>">



                                            <button class="btn" type="button" rel="tooltip" title="" data-original-title="Update Products Sale Price" id="updateSalePriceBtn"><i class="icon icon-chevron-right"></i></button>



                                        </div>



                                    </div>



                                </div>











                                <div class="control-group">



                                    <label class="control-label">Pre-Order</label>







                                    <div class="controls">



                                        <input type="checkbox" class="input-block-level customfield"



                                               name="preord"



                                               value="1"



                                            <?php



                                            echo (isset($productTypeRec->prtobj) && $patchworks->getJSONVariable($productTypeRec->prtobj, 'preord', false) == 1) ? 'checked' : '';



                                            ?>>



                                    </div>



                                </div>











                                <div class="control-group">



                                    <label class="control-label">Pre-Order Text</label>



                                    <div class="controls">



                                        <input type="text" class="input-block-level customfield"



                                               name="pretxt"



                                               value="<?php echo (isset($productTypeRec->prtobj)) ? $patchworks->getJSONVariable($productTypeRec->prtobj, 'pretxt', false) : ''; ?>">



                                    </div>



                                </div>







                                <div class="control-group">



                                    <label class="control-label">VAT Rate



                                        <small></small>



                                    </label>







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



                                    <label class="control-label">Purchase Price



                                        <small>default purchase price of product</small>



                                    </label>







                                    <div class="controls">



                                        <input type="text" class="input-block-level" name="buypri"



                                               data-rule-number="true"



                                               value="<?php echo ($productTypeRec) ? $productTypeRec->buypri : '0'; ?>">



                                    </div>



                                </div>







                                <div class="control-group hide">



                                    <label class="control-label">Delivery Price



                                        <small>default delivery price</small>



                                    </label>







                                    <div class="controls">



                                        <input type="text" class="input-block-level" name="delpri"



                                               data-rule-number="true"



                                               value="<?php echo ($productTypeRec) ? $productTypeRec->delpri : '0'; ?>">



                                    </div>



                                </div>



                                <!--</div>-->







                                <div class="control-group">



                                    <label class="control-label">Status



                                        <small>product status</small>



                                    </label>







                                    <div class="controls">



                                        <label class="radio">



                                            <input type="radio" name="sta_id" id="Sta_ID0"



                                                   value="0" <?php echo (!$productTypeRec || $productTypeRec && $productTypeRec->sta_id == 0) ? 'checked' : ''; ?>>



                                            Active</label>



                                        <label class="radio">



                                            <input type="radio" name="sta_id" id="Sta_ID1"



                                                   value="1" <?php echo ($productTypeRec && $productTypeRec->sta_id == 1) ? 'checked' : ''; ?>>



                                            Inactive </label>



                                    </div>



                                </div>







                                <div class="control-group">



                                    <label class="control-label">Home Page



                                        <small></small>



                                    </label>







                                    <div class="controls">



                                        <label class="checkbox">



                                            <input type="checkbox" name="hompag"



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



                                            <input type="text" class="input-block-level" data-rule-required="true"



                                                   data-rule-number="true" name="in_stk" value="0">



                                        </div>



                                    </div>



                                    <div class="control-group">



                                        <label class="control-label">On Order</label>







                                        <div class="controls">



                                            <input type="text" class="input-block-level" data-rule-required="true"



                                                   data-rule-number="true" name="on_ord" value="0">



                                        </div>



                                    </div>



                                    <div class="control-group">



                                        <label class="control-label">On Delivery</label>







                                        <div class="controls">



                                            <input type="text" class="input-block-level" data-rule-required="true"



                                                   data-rule-number="true" name="on_del" value="0">



                                        </div>



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











                            </form>







                        </div>



                    </div>



                </div>







                <div class="span6">







                    <div class="box box-color box-bordered">



                        <div class="box-title">



                            <h3>



                                <i class="icon-cogs"></i>



                                Additional



                            </h3>



                            <ul class="tabs">



                                <li>



                                    <a href="#t10" data-toggle="tab">Images</a>



                                </li>



<!--                                <li>-->



<!--                                    <a href="#t12" data-toggle="tab">Attributes</a>-->



<!--                                </li>-->



                                <li class="active">



                                    <a href="#t7" data-toggle="tab">Variants</a>



                                </li>



                                <li class="">



                                    <a href="#t8" data-toggle="tab">Shop Structure</a>



                                </li>



                                <li class="">



                                    <a href="#t9" data-toggle="tab">Related Products</a>



                                </li>



                                <li class="">



                                    <a href="#t5" data-toggle="tab">Reviews</a>



                                </li>



                                <li class="">



                                    <a href="#t11" data-toggle="tab">Downloads</a>



                                </li>



                            </ul>



                        </div>



                        <div class="box-content nopadding">



                            <div class="tab-content">







                                <div class="tab-pane" id="t12">







                                    <div id="attrLabelTableBox">



                                        <div class="box-title" style="margin-top: 0;">



                                            <div class="actions">







                                                <a href="#" class="btn btn-mini" id="createAttrLabelBtn" rel="tooltip"



                                                   title="New Attribute"><i class="icon-plus-sign"></i></a>







                                            </div>



                                        </div>



                                    </div>







                                    <div id="attrLabelBox" style="display: none;">







                                        <div class="box-title" style="margin-top: 0;">



                                            <div class="actions">







                                                <a href="#" class="btn btn-mini" id="cancelAttrLabelBtn" rel="tooltip"



                                                   title="Cancel"><i class="icon-remove-sign"></i></a>



                                                <a href="#" class="btn btn-mini" id="updateAttrLabelBtn" rel="tooltip"



                                                   title="Update"><i class="icon-save"></i></a>







                                            </div>



                                        </div>







                                        <form class="form-horizontal form-bordered form-validate"



                                              action="<?php echo $patchworks->pwRoot; ?>attributes/attrlabel_script.php"



                                              id="attrLabelForm">



                                            <input type="hidden" name="atr_id" id="atrId"



                                                   value="<?php echo ($productTypeAttr) ? $productTypeAttr->atr_id : 0; ?>"/>







                                            <input type="hidden" name="atl_id" id="id" value="0"/>



                                            <input type="hidden" name="srtord" value=""/>



                                            <input type="hidden" name="atllst" value=""/>







                                            <div class="control-group">



                                                <label class="control-label">Label



                                                    <small>attribute name/label</small>



                                                </label>







                                                <div class="controls">



                                                    <input type="text" name="atllbl" data-rule-required="true"



                                                           data-rule-minlength="2" value="">



                                                </div>



                                            </div>



                                            <div class="control-group">



                                                <label class="control-label">Help



                                                    <small>help text for form</small>



                                                </label>







                                                <div class="controls">



                                                    <textarea class="input-block-level" rows="4"



                                                              name="atldsc"></textarea>



                                                </div>



                                            </div>



                                            <div class="control-group">



                                                <label class="control-label">Input Type



                                                    <small>select input method</small>



                                                </label>



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



                                                    <label class="control-label">Add Entry



                                                        <small>add items to list</small>



                                                    </label>







                                                    <div class="controls">



                                                        <div class="input-append">



                                                            <input type="text" id="AddAltLst" class="input-medium"/>



                                                            <button class="btn" type="button" id="addAltLst"



                                                                    rel="tooltip"



                                                                    title="Add Entry To List"><i



                                                                    class="icon icon-plus"></i>



                                                            </button>



                                                        </div>



                                                    </div>



                                                </div>



                                                <div class="control-group">



                                                    <label class="control-label">Entry List



                                                        <small>items in list</small>



                                                    </label>







                                                    <div class="controls">



                                                        <ul id="AtrLst_UL" style="padding: 0; margin: 0;">



                                                        </ul>



                                                    </div>



                                                </div>



                                            </div>



                                            <textarea id="AtlLst" name="atllst" class="hide"></textarea>







                                            <div class="control-group">



                                                <label class="control-label">Required?



                                                    <small>field is mandatory</small>



                                                </label>







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



                                                <label class="control-label">Search Type



                                                    <small>how to search the data</small>



                                                </label>







                                                <div class="controls">



                                                    <select name="srctyp">



                                                        <option value="text">Text</option>



                                                    </select>



                                                </div>



                                            </div>



                                            <div class="control-group">



                                                <label class="control-label">Specialist Field



                                                    <small>if field is used for functionality</small>



                                                </label>







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







                                    <table class="table table-bordered table-striped table-highlight" id="reviewsTable">



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



                                                    <a href="#" class="btn btn-danger btn-mini deleteReviewBtn"



                                                       data-rev_id="<?php echo $reviews[$i]['rev_id']; ?>" rel="tooltip"



                                                       title="Delete"><i class="icon-trash"></i></a>



                                                </td>



                                            </tr>



                                        <?php } ?>



                                        </tbody>



                                    </table>







                                </div>







                                <div class="tab-pane" id="t10">







                                    <div class="row-fluid">



                                        <div class="span12">



                                            <ul class="gallery gallery-dynamic" id="galleryImages">







                                            </ul>



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







                                <div class="tab-pane active" id="t7">











                                    <div id="variantTableBox">







                                        <div class="box-title" style="margin-top: 0;">



                                            <div class="actions">







                                                <a href="#" class="btn btn-mini" id="createVariantBtn" rel="tooltip"



                                                   title="New Variant"><i class="icon-plus-sign"></i></a>







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







                                                <a href="#" class="btn btn-mini" id="cancelVariantBtn" rel="tooltip"



                                                   title="Cancel"><i class="icon-remove-sign"></i></a>



                                                <a href="#" class="btn btn-mini" id="updateVariantBtn" rel="tooltip"



                                                   title="Update"><i class="icon-save"></i></a>







                                            </div>



                                        </div>







                                        <form id="attlEntryForm" class="form-horizontal form-bordered attributeForm"



                                              action="attributes/attribute-entry_script.php">







                                        </form>







                                        <form id="productForm" class="form-horizontal form-bordered form-validate"



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



                                                <label class="control-label">Name



                                                    <small>individual product name</small>



                                                </label>







                                                <div class="controls">



                                                    <input type="text" class="input-block-level"



                                                           data-rule-required="true" data-rule-minlength="2"



                                                           name="prdnam" value="">



                                                </div>



                                            </div>







                                            <div class="control-group">



                                                <label class="control-label">Reference



                                                    <small></small>



                                                </label>



                                                <div class="controls">



                                                    <input type="text" class="input-block-level" name="altref" value="">



                                                </div>



                                            </div>







                                            <div class="control-group">



                                                <label class="control-label">SEO URL</label>







                                                <div class="controls">



                                                    <input type="text" class="input-block-level" name="seourl"



                                                           value=""



                                                           data-rule-minlength="3" data-rule-maxlength="200"



                                                           data-rule-required="true">



                                                </div>



                                            </div>











                                            <div class="control-group">



                                                <label class="control-label">Sale Price



                                                    <small>individual product price</small>



                                                </label>







                                                <div class="controls">



                                                    <input type="text" class="input-block-level"



                                                           data-rule-required="true" data-rule-number="true"



                                                           name="unipri" value="0">



                                                </div>



                                            </div>







                                            <div class="control-group">



                                                <label class="control-label">Purchase Price



                                                    <small></small>



                                                </label>







                                                <div class="controls">



                                                    <input type="text" class="input-block-level" name="buypri"



                                                           data-rule-number="true"



                                                           value="0">



                                                </div>



                                            </div>







                                            <div class="control-group">



                                                <label class="control-label">Supplier Data



                                                    <small></small>



                                                </label>



                                                <div class="controls">



                                                    <textarea name="supdat" cols="30" rows="3" class="input-block-level customfield"></textarea>



                                                </div>



                                            </div>







                                            <div class="control-group hide">



                                                <label class="control-label">Discount @ Qty Price



                                                    <small>discounted price</small>



                                                </label>







                                                <div class="controls">



                                                    <input type="text" class="input-block-level"



                                                           data-rule-required="true" data-rule-number="true"



                                                           name="delpri" value="0">



                                                </div>



                                            </div>











                                            <div class="control-group hide">



                                                <label class="control-label">Stock



                                                    <small>total stock of this product variant</small>



                                                </label>







                                                <div class="controls">



                                                    <select name="usestk">



                                                        <option value="0" selected>Non Stock</option>



                                                        <option value="1">Use Stock</option>



                                                    </select>



                                                </div>



                                            </div>







                                            <div class="control-group">



                                                <label class="control-label">In Stock



                                                    <small>current stock of this product variant</small>



                                                </label>







                                                <div class="controls">



                                                    <input type="text" class="input-block-level"



                                                           data-rule-required="true" data-rule-number="true"



                                                           name="in_stk" value="0">



                                                </div>



                                            </div>



                                            <div class="control-group hide">



                                                <label class="control-label">On Order



                                                    <small>stock on order of this product variant</small>



                                                </label>







                                                <div class="controls">



                                                    <input type="text" class="input-block-level"



                                                           data-rule-required="true" data-rule-number="true"



                                                           name="on_ord" value="0">



                                                </div>



                                            </div>



                                            <div class="control-group hide">



                                                <label class="control-label">On Delivery



                                                    <small>current stock in transit</small>



                                                </label>







                                                <div class="controls">



                                                    <input type="text" class="input-block-level"



                                                           data-rule-required="true" data-rule-number="true"



                                                           name="on_del" value="0">



                                                </div>



                                            </div>











                                            <div class="control-group">



                                                <label class="control-label">Weight



                                                    <small></small>



                                                </label>







                                                <div class="controls">



                                                    <input type="text" class="input-block-level"



                                                           data-rule-required="true" data-rule-number="true"



                                                           name="weight" value="0">



                                                </div>



                                            </div>







                                        </form>







                                        <div class="row-fluid">



                                            <div class="span12">



                                                <div class="box">



                                                    <div class="box-title">



                                                        <h3>



                                                            <i class="icon-picture"></i> Product Images </h3>



                                                    </div>



                                                    <div class="box-content">







                                                        <ul class="gallery gallery-dynamic" id="productgalleryImages">







                                                        </ul>



                                                    </div>



                                                </div>



                                            </div>



                                        </div>



                                        <div class="row-fluid">



                                            <div class="span12">



                                                <div class="box">



                                                    <div class="box-title">



                                                        <h3><i class="icon-th"></i> Multi Image Upload</h3>



                                                        <div class="actions">



                                                            <a class="btn btn-mini content-slideUp" href="#">



                                                                <i class="icon-angle-down"></i>



                                                            </a>



                                                        </div>



                                                    </div>



                                                    <div class="box-content nopadding">



                                                        <div id="pluploadproducts" data-imgsiz="<?php echo $patchworks->productImageSizes; ?>">



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



                                                    <div class="box-content nopadding">







                                                        <table class="table table-bordered table-striped table-highlight">



                                                            <thead>



                                                            <tr>



                                                                <th>File Name</th>



                                                                <th style="width: 120px;"></th>



                                                            </tr>



                                                            </thead>



                                                            <tbody id="productFileDownloads">







                                                            </tbody>



                                                        </table>







                                                    </div>



                                                </div>



                                            </div>



                                        </div>



                                        <div class="row-fluid">



                                            <div class="span12">



                                                <div class="box">



                                                    <div class="box-title">



                                                        <h3><i class="icon-th"></i> Multi File upload</h3>



                                                        <div class="actions">



                                                            <a class="btn btn-mini content-slideUp" href="#">



                                                                <i class="icon-angle-down"></i>



                                                            </a>



                                                        </div>



                                                    </div>



                                                    <div class="box-content nopadding">







                                                        <table class="table table-bordered table-striped table-highlight"



                                                               id="downloadsTable">



                                                            <thead>



                                                            <tr>



                                                                <th>File Name</th>



                                                                <th style="width: 120px;"></th>



                                                            </tr>



                                                            </thead>



                                                            <tbody id="pluploadproductpdfs">







                                                            </tbody>



                                                        </table>







                                                    </div>



                                                </div>



                                            </div>



                                        </div>











                                    </div>







                                </div>



                                <div class="tab-pane" id="t8">







                                    <div style="display: block; padding-top: 10px;">







                                        <div id="buildStructure">



                                            <?php



                                            $TmpStr->buildStructure(0, NULL, NULL, NULL, true);



                                            ?>



                                        </div>



                                    </div>







                                </div>



                                <div class="tab-pane" id="t9">











                                    <form id="relatedForm" class="form-horizontal form-bordered form-validate"



                                          action="system/related_script.php">



                                        <input type="hidden" name="tblnam" value="PRDTYPE">



                                        <input type="hidden" name="tbl_id"



                                               value="<?php echo (isset($productTypeRec)) ? $productTypeRec->prt_id : 0; ?>">



                                        <input type="hidden" name="ref_id">







                                        <div class="control-group">



                                            <label class="control-label"><i class="icon icon-search"></i> Search



                                                <small>start typing to see results</small>



                                            </label>







                                            <div class="controls">



                                                <input type="text" class="input-block-level autocomplete" name="refnam"



                                                       autocomplete="off">



                                            </div>



                                        </div>







                                        <div class="control-group">



                                            <label class="control-label">Selected Product</label>







                                            <div class="controls">



                                                <input type="text" class="input-block-level autocomplete"



                                                       id="relatedName"



                                                       readonly>



                                            </div>



                                        </div>







                                        <div class="form-actions">



                                            <button type="submit" class="btn btn-primary">Relate</button>



                                        </div>



                                    </form>











                                    <div id="relatedProductList" style="padding: 10px 20px;">







                                    </div>







                                </div>







                            </div>



                        </div>



                    </div>











                    <form class="form-horizontal form-validate" action="#" id="languageForm" style="display: none">



                        <div class="box">



                            <div class="box-title">



                                <h3>



                                    <i class="icon-comments"></i>



                                    Language



                                </h3>



                                <ul class="tabs">



                                    <li class="active">



                                        <a href="#french" class="changelanguagelink" data-toggle="tab">French</a>



                                    </li>



                                    <li>



                                        <a href="#german" class="changelanguagelink" data-toggle="tab">German</a>



                                    </li>



                                    <li class="">



                                        <a href="#spanish" class="changelanguagelink" data-toggle="tab">Spanish</a>



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



                                                       value="<?php echo (isset($productTypeRec->prtobj)) ? $patchworks->getJSONVariable($productTypeRec->prtobj, 'fr_prdnam', false) : ''; ?>">



                                            </div>



                                        </div>







                                        <div class="control-group">



                                            <label class="control-label">SEO Keywords</label>







                                            <div class="controls">







                                                <textarea name="fr_keywrd"



                                                          class="input-block-level customfield"><?php echo (isset($productTypeRec->prtobj)) ? $patchworks->getJSONVariable($productTypeRec->prtobj, 'fr_keywrd', false) : ''; ?></textarea>







                                            </div>



                                        </div>







                                        <div class="control-group">



                                            <label class="control-label">SEO Description</label>







                                            <div class="controls">







                                                <textarea name="fr_seodsc"



                                                          class="input-block-level customfield"><?php echo (isset($productTypeRec->prtobj)) ? $patchworks->getJSONVariable($productTypeRec->prtobj, 'fr_seodsc', false) : ''; ?></textarea>







                                            </div>



                                        </div>







                                        <div class="control-group">



                                            <label class="control-label">Product Description</label>







                                            <div class="controls">







                                                <textarea name="fr_prddsc" class="input-block-level tinymce customfield"



                                                          id="fr_prddsc"><?php echo (isset($productTypeRec->prtobj)) ? $patchworks->getJSONVariable($productTypeRec->prtobj, 'fr_prddsc', false) : ''; ?></textarea>







                                            </div>



                                        </div>







                                        <div class="control-group">



                                            <label class="control-label">Product Specification</label>







                                            <div class="controls">







                                                <textarea name="fr_prdspc" class="input-block-level tinymce customfield"



                                                          id="fr_prdspc"><?php echo (isset($productTypeRec->prtobj)) ? $patchworks->getJSONVariable($productTypeRec->prtobj, 'fr_prdspc', false) : ''; ?></textarea>







                                            </div>



                                        </div>







                                    </div>







                                    <div class="tab-pane" id="german">







                                        <div class="control-group">



                                            <label class="control-label">German Name</label>







                                            <div class="controls">



                                                <input type="text" class="input-block-level customfield"



                                                       name="ge_prdnam"



                                                       value="<?php echo (isset($productTypeRec->prtobj)) ? $patchworks->getJSONVariable($productTypeRec->prtobj, 'ge_prdnam', false) : ''; ?>">



                                            </div>



                                        </div>







                                        <div class="control-group">



                                            <label class="control-label">SEO Keywords</label>







                                            <div class="controls">







                                                <textarea name="ge_keywrd"



                                                          class="input-block-level customfield"><?php echo (isset($productTypeRec->prtobj)) ? $patchworks->getJSONVariable($productTypeRec->prtobj, 'ge_keywrd', false) : ''; ?></textarea>







                                            </div>



                                        </div>







                                        <div class="control-group">



                                            <label class="control-label">SEO Description</label>







                                            <div class="controls">







                                                <textarea name="ge_seodsc"



                                                          class="input-block-level customfield"><?php echo (isset($productTypeRec->prtobj)) ? $patchworks->getJSONVariable($productTypeRec->prtobj, 'ge_seodsc', false) : ''; ?></textarea>







                                            </div>



                                        </div>







                                        <div class="control-group">



                                            <label class="control-label">Product Description</label>







                                            <div class="controls">







                                                <textarea name="ge_prddsc" class="input-block-level tinymce customfield"



                                                          id="ge_prddsc"><?php echo (isset($productTypeRec->prtobj)) ? $patchworks->getJSONVariable($productTypeRec->prtobj, 'ge_prddsc', false) : ''; ?></textarea>







                                            </div>



                                        </div>







                                        <div class="control-group">



                                            <label class="control-label">Product Specification</label>







                                            <div class="controls">







                                                <textarea name="ge_prdspc" class="input-block-level tinymce customfield"



                                                          id="ge_prdspc"><?php echo (isset($productTypeRec->prtobj)) ? $patchworks->getJSONVariable($productTypeRec->prtobj, 'ge_prdspc', false) : ''; ?></textarea>







                                            </div>



                                        </div>







                                    </div>



                                    <div class="tab-pane" id="spanish">







                                        <div class="control-group">



                                            <label class="control-label">Spanish Name</label>







                                            <div class="controls">



                                                <input type="text" class="input-block-level customfield"



                                                       name="sp_prdnam"



                                                       value="<?php echo (isset($productTypeRec->prtobj)) ? $patchworks->getJSONVariable($productTypeRec->prtobj, 'sp_prdnam', false) : ''; ?>">



                                            </div>



                                        </div>







                                        <div class="control-group">



                                            <label class="control-label">SEO Keywords</label>







                                            <div class="controls">







                                                <textarea name="sp_keywrd"



                                                          class="input-block-level customfield"><?php echo (isset($productTypeRec->prtobj)) ? $patchworks->getJSONVariable($productTypeRec->prtobj, 'sp_keywrd', false) : ''; ?></textarea>







                                            </div>



                                        </div>







                                        <div class="control-group">



                                            <label class="control-label">SEO Description</label>







                                            <div class="controls">







                                                <textarea name="sp_seodsc"



                                                          class="input-block-level customfield"><?php echo (isset($productTypeRec->prtobj)) ? $patchworks->getJSONVariable($productTypeRec->prtobj, 'sp_seodsc', false) : ''; ?></textarea>







                                            </div>



                                        </div>







                                        <div class="control-group">



                                            <label class="control-label">Product Description</label>







                                            <div class="controls">







                                                <textarea name="sp_prddsc" class="input-block-level tinymce customfield"



                                                          id="sp_prddsc"><?php echo (isset($productTypeRec->prtobj)) ? $patchworks->getJSONVariable($productTypeRec->prtobj, 'sp_prddsc', false) : ''; ?></textarea>







                                            </div>



                                        </div>







                                        <div class="control-group">



                                            <label class="control-label">Product Specification</label>







                                            <div class="controls">







                                                <textarea name="sp_prdspc" class="input-block-level tinymce customfield"



                                                          id="sp_prdspc"><?php echo (isset($productTypeRec->prtobj)) ? $patchworks->getJSONVariable($productTypeRec->prtobj, 'sp_prdspc', false) : ''; ?></textarea>







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











<div class="modal hide fade" id="imageModal">



    <div class="modal-header">



        <button type="button" class="close" data-dismiss="modal">&times;</button>



        <h3>Image Detail</h3>



    </div>



    <form action="gallery/upload_script.php" id="imageForm" class="form-horizontal" novalidate>



        <input type="hidden" name="upl_id"/>







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



