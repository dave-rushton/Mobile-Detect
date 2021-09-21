<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("../attributes/classes/attrgroups.cls.php");
require_once("../custom/classes/baskets.cls.php");
require_once("../products/classes/products.cls.php");
require_once("../attributes/classes/attrgroups.cls.php");
require_once("../system/classes/subcategories.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpAtr = new AtrDAO();
$attrGroups = $TmpAtr->select(NULL, 'PRODUCTGROUP', NULL, NULL, false, NULL, NULL, 0);


$TmpBsk = new BskDAO();
$Bsk_ID = (isset($_GET['bsk_id']) && is_numeric($_GET['bsk_id'])) ? $_GET['bsk_id'] : NULL;
$basketRec = NULL;
$basketProducts = NULL;
if (!is_null($Bsk_ID)) {
    $basketRec = $TmpBsk->select($Bsk_ID, NULL, NULL, NULL, true);
    $basketProducts = $TmpBsk->selectProducts($Bsk_ID);
    $basketExtras = $TmpBsk->selectExtras($Bsk_ID);
}

$TmpAtr = new AtrDAO();
$customForms = $TmpAtr->select(NULL, 'CUSTOM');

$TmpSub = new SubDAO();
$subCategories = $TmpSub->selectByTableName('basket-tags', NULL);

?>
<!doctype html>
<html>
<head>
    <title>Basket Maintenance</title>
    <?php include('../webparts/headdata.php'); ?>

    <script src="js/plugins/fileupload/bootstrap-fileupload.min.js"></script>


    <!-- Plupload -->
    <link rel="stylesheet" href="css/plugins/plupload/jquery.plupload.queue.css">
    <!-- PLUpload -->
    <script src="js/plugins/plupload/plupload.full.js"></script>
    <script src="js/plugins/plupload/jquery.plupload.queue.js"></script>

    <script src="custom/js/baskets-edit.js"></script>


    <style>

        #productListBody input[name="bprman"] {
            margin-bottom: 0px;
            width: 40px;
            text-align: right;
        }

        #productListBody input[name="exttxt"] {
            margin-bottom: 0px;
            width: 95%;
        }

        #extrasListBody input[name="bexpri"] {
            margin-bottom: 0px;
            width: 40px;
            text-align: right;
        }

        #extrasListBody input[name="bexttl"] {
            margin-bottom: 0px;
            width: 95%;
        }

        #extrasListBody input[name="bextxt"] {
            margin-bottom: 0px;
            width: 95%;
        }

        #extrasListFoot td {
            background: #f8a31f;
        }

        #extrasListFoot input[name="bexpri"] {
            margin-bottom: 0px;
            width: 40px;
            text-align: right;
        }

        #extrasListFoot input[name="bexttl"] {
            margin-bottom: 0px;
            width: 95%;
        }

        #extrasListFoot input[name="bextxt"] {
            margin-bottom: 0px;
            width: 95%;
        }

        .typeahead.dropdown-menu {
            height: 210px;
            overflow-y: scroll;
            overflow-x: hidden;
        }

        #addProductsDiv {
            display: none;
        }

        #productListTable input {
            margin-bottom: 0;
        }

    </style>

</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-orange">
<div class="container-fluid" id="content">
    <?php include('../webparts/website-left.php'); ?>
    <div id="main">
        <div class="container-fluid">
            <div class="page-header">
                <div class="pull-left">
                    <h1>Basket Maintenance</h1>
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
                        <a href="custom/baskets.php">Baskets</a>
                        <i class="icon-angle-right"></i>
                    </li>
                    <li>
                        <a>Basket Edit</a>
                    </li>
                </ul>
            </div>
            <div class="row-fluid">

                <div class="span12">

                    <form action="custom/baskets_script.php" id="basketForm" class="form-horizontal form-bordered"
                          data-returnurl="custom/baskets.php" enctype="multipart/form-data">

                        <div class="box box-color box-bordered">
                            <div class="box-title">
                                <h3>
                                    <i class="icon-gift"></i> Basket Builder</h3>

                                <div class="actions">
                                    <a href="#" id="deleteBasketBtn" class="btn btn-mini" rel="tooltip" title="Delete"
                                       style="display: none;"><i class="icon-trash"></i></a>
                                    <a data-root="<?php echo $patchworks->webRoot; ?>" href="<?php echo $patchworks->webRoot."baskets/basket/".$basketRec->bsk_id."/".$basketRec->seourl;?>" target="_blank" id="previewBtn" style="margin-left: 20px;" class="btn btn-mini" rel="tooltip"
                                       title="Preview"><i class="icon-eye-open"></i></a>
                                    <a href="#" id="updateBasketBtn" class="btn btn-mini" rel="tooltip"
                                       title="Update"><i class="icon-save"></i></a>

                                </div>
                            </div>
                            <div class="box-content nopadding">

                                <div class="row-fluid">

                                    <div class="span6">

                                        <input type="hidden" name="bsk_id" id="id"
                                               value="<?php echo ($basketRec) ? $basketRec->bsk_id : '0'; ?>">

                                        <div class="control-group">
                                            <label class="control-label">Basket Title
                                                <small>identifying name</small>
                                            </label>

                                            <div class="controls">
                                                <input type="text" class="input-block-level" name="bskttl"
                                                       value="<?php echo ($basketRec) ? $basketRec->bskttl : ''; ?>" required>
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label">Basket Description
                                                <small>basic summary</small>
                                            </label>

                                            <div class="controls">
                                        <textarea class="input-block-level" name="bskdsc"
                                                  rows="10"><?php echo ($basketRec) ? $basketRec->bskdsc : ''; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <dl class="dl-horizontal">
                                                <dt>Base Price</dt>
                                                <dd>
                                                    &pound;<span id="basketBasePrice">144.50</span>
                                                </dd>
                                            </dl>
                                        </div>

                                        <div class="control-group">
                                            <label for="textfield" class="control-label">Mark Up</label>
                                            <div class="controls">
                                                <div class="input-append">
                                                    <input type="text" class="input-large" name="mrk_up" value="<?php echo ($basketRec) ? $basketRec->mrk_up : '0'; ?>">
                                                    <button class="btn" type="button" id="calcBasePriceBtn"><i class="icon icon-download"></i></button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <dl class="dl-horizontal">
                                                <dt>Recommended Price</dt>
                                                <dd>
                                                    &pound;<span id="recommendPrice">0.00</span>
                                                </dd>
                                            </dl>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label">Basket Base Price
                                                <small>display price before customisation</small>
                                            </label>
                                            <div class="controls">
                                                <div class="input-append">
                                                    <input type="text" class="input-large" name="unipri" value="<?php echo ($basketRec) ? $basketRec->unipri : '0'; ?>">
                                                    <button class="btn" type="button" id="calcMarkupBtn"><i class="icon icon-upload"></i></button>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="control-group">
                                            <label class="control-label">Minimum Order Qty
                                                <small></small>
                                            </label>
                                            <div class="controls">
                                                <input type="text" class="input-large" name="minord" value="<?php echo ($basketRec) ? $basketRec->minord : '1'; ?>">
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label">Basket Tags
                                                <small>basket tags</small>
                                            </label>

                                            <div class="controls">

                                                <select multiple="multiple" class="input-large" name="bsktagselect"
                                                        id="bsktagselect">

                                                    <?php
                                                    $tableLength = count($subCategories);
                                                    for ($i = 0; $i < $tableLength; ++$i) {

                                                        $bskTags = explode(",", $basketRec->bsktag);

                                                        ?>

                                                        <option
                                                            value="<?php echo $subCategories[$i]['sub_id'] ?>" <?php echo (isset($basketRec) && in_array($subCategories[$i]['sub_id'], $bskTags)) ? 'selected' : ''; ?>><?php echo $subCategories[$i]['subnam'] ?></option>

                                                    <?php } ?>

                                                </select>

                                                <input type="hidden" name="bsktag">

                                            </div>
                                        </div>


                                        <div class="control-group">
                                            <label class="control-label">Basket Weight
                                                <small>weight for delivery info</small>
                                            </label>
                                            <div class="controls">
                                                    <input type="text" class="input-large" name="weight" value="<?php echo ($basketRec) ? $basketRec->weight : '0'; ?>">
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label">VAT Rate %
                                                <small>tax rate of basket</small>
                                            </label>
                                            <div class="controls">
                                                <input type="text" class="input-large" name="vatrat" value="<?php echo ($basketRec) ? $basketRec->vatrat : '0'; ?>">
                                            </div>
                                        </div>

                                    </div>
                                    <div class="span6">

                                        <div class="control-group">
                                            <label for="textfield" class="control-label">Image</label>

                                            <div class="controls">
                                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                                    <div class="fileupload-new thumbnail"
                                                         style="max-width: 200px; max-height: 150px;">

                                                        <?php

                                                        if (
                                                            isset($basketRec->bskimg) &&
                                                            file_exists($patchworks->docRoot . 'uploads/images/basket/' . $basketRec->bskimg) &&
                                                            !is_dir($patchworks->docRoot . 'uploads/images/basket/' . $basketRec->bskimg)
                                                        ) {
                                                            echo '<img src="../uploads/images/basket/' . $basketRec->bskimg . '" class="img-responsive productImage" />';
                                                        } else {
                                                            echo '<img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image" />';
                                                        }
                                                        ?>

                                                    </div>

                                                    <div class="fileupload-preview fileupload-exists thumbnail"
                                                         style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                                    <div>
                                                <span class="btn btn-file"><span
                                                        class="fileupload-new">Select image</span><span
                                                        class="fileupload-exists">Change</span><input type="file"
                                                                                                      name='logofile'
                                                                                                      id="logofile"/></span>
                                                        <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                                                    </div>

                                                    <input type="hidden" name="bskimg"
                                                           value="<?php echo ($basketRec) ? $basketRec->bskimg : ''; ?>">

                                                </div>
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label">Customise Text
                                                <small>Custom text on the customise page</small>
                                            </label>

                                            <div class="controls">
                                        <textarea class="input-block-level" name="customtext"
                                                  rows="10"><?php echo ($basketRec) ? $basketRec->customtext : ''; ?></textarea>
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label">Bypass Minimum Order
                                                <small>checkbox to bypass minimum order if one is set</small>
                                            </label>

                                            <div class="controls">
                                                <label class="checkbox">
                                                    <input type="checkbox" name="bypass_min_order"
                                                           value="1" <?php echo ($basketRec && $basketRec->bypass_min_order == 1) ? 'checked' : ''; ?>>
                                                    Bypass Minimum Order? </label>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">Customise
                                                <small>checkbox to allow customer edit</small>
                                            </label>

                                            <div class="controls">
                                                <label class="checkbox">
                                                    <input type="checkbox" name="custom"
                                                           value="1" <?php echo ($basketRec && $basketRec->custom == 1) ? 'checked' : ''; ?>>
                                                    Allow customisation? </label>
                                            </div>
                                        </div>







                                        <div class="control-group">
                                            <label class="control-label">Cutomisation Form
                                                <small>select associated question form</small>
                                            </label>

                                            <div class="controls">
                                                <?php
                                                $tableLength = count($customForms);
                                                $atr_id = explode(",",$basketRec->atr_id);

                                                for ($i = 0; $i < $tableLength; ++$i) {
                                                    ?>
                                                    <label class="checkbox">
                                                        <input type="checkbox" class="art_id" value="<?php echo $customForms[$i]['atr_id']; ?>"  <?php echo ($basketRec && in_array($customForms[$i]['atr_id'],$atr_id)) ? 'checked' : ''; ?>>
                                                        <?php echo $customForms[$i]['atrnam']; ?></label>

                                                <?php } ?>

                                            </div>
                                        </div>


                                        <div class="control-group">
                                            <label class="control-label">SEO URL
                                                <small></small>
                                            </label>

                                            <div class="controls">
                                                <input type="text" class="input-block-level" name="seourl"
                                                       value="<?php echo ($basketRec) ? $basketRec->seourl : ''; ?>" required>
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label">SEO Keywords
                                                <small></small>
                                            </label>

                                            <div class="controls">
                                        <textarea class="input-block-level" name="keywrd"
                                                  rows="3"><?php echo ($basketRec) ? $basketRec->keywrd : ''; ?></textarea>
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label">SEO Description
                                                <small></small>
                                            </label>

                                            <div class="controls">
                                            <textarea class="input-block-level" name="keydsc"
                                                  rows="5"><?php echo ($basketRec) ? $basketRec->keydsc : ''; ?></textarea>
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label">Ribbon Label
                                                <small></small>
                                            </label>

                                            <div class="controls">
                                                <input type="text" class="input-block-level" name="riblbl"
                                                       value="<?php echo ($basketRec) ? $basketRec->riblbl : ''; ?>">
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label">Ribbon Label
                                                <small></small>
                                            </label>

                                            <div class="controls">

                                                <select name="ribcol" class="input-block-level">

                                                    <option value="#00bbdf" <?php echo ($basketRec && $basketRec->ribcol == '#00bbdf') ? 'selected' : ''; ?>>Blue</option>
                                                    <option value="#61d914" <?php echo ($basketRec && $basketRec->ribcol == '#61d914') ? 'selected' : ''; ?>>Green</option>
                                                    <option value="#ef5656" <?php echo ($basketRec && $basketRec->ribcol == '#ef5656') ? 'selected' : ''; ?>>Red</option>
                                                    <option value="#FF8A00" <?php echo ($basketRec && $basketRec->ribcol == '#FF8A00') ? 'selected' : ''; ?>>Orange</option>

                                                </select>

                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>


                            <div class="box-title" style="display: none; margin-top: 0;">
                                <h3>
                                    <i class="icon-warning-sign"></i> Basket Limits</h3>
                            </div>

                            <div class="box-content nopadding" style="display: none">


                                <table class="table table-bordered table-striped table-highlight"
                                       id="productGroupTable">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Min</th>
                                        <th>Max</th>
                                    </tr>
                                    </thead>
                                    <tbody id="productGroupBody">
                                    <?php

                                    $limits = NULL;
                                    if (isset($basketRec->bsktxt)) $limits = json_decode($basketRec->bsktxt, true);

                                    $tableLength = count($attrGroups);
                                    for ($i = 0; $i < $tableLength; ++$i) {

                                        $limitArr = NULL;
                                        $minUni = 1;
                                        $maxUni = 1;
                                        for ($l = 0; $l < count($limits); $l++) {

                                            if ($limits[$l]['atr_id'] == $attrGroups[$i]['atr_id']) {
                                                $limitArr = $l;
                                                $minUni = $limits[$l]['minuni'];
                                                $maxUni = $limits[$l]['maxuni'];
                                                break;
                                            }
                                        }

                                        ?>
                                        <tr class="atr_id" data-atr_id="<?php echo $attrGroups[$i]['atr_id']; ?>">
                                            <td>
                                                <?php echo $attrGroups[$i]['atrnam']; ?><br>
                                                <small><?php echo $attrGroups[$i]['subnam']; ?></small>
                                            </td>
                                            <td width="50">

                                                <select name="minuni" class="input-block-level minuni"
                                                        data-atr_id="<?php echo $attrGroups[$i]['atr_id']; ?>">
                                                    <?php
                                                    for ($j = 0; $j <= 20; $j++) {
                                                        ?>
                                                        <option
                                                            value="<?php echo $j; ?>" <?php if ($j == $minUni) echo 'selected'; ?>><?php echo $j; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>

                                            </td>

                                            <td width="50">

                                                <select name="maxuni" class="input-block-level maxuni"
                                                        data-atr_id="<?php echo $attrGroups[$i]['atr_id']; ?>">
                                                    <?php
                                                    for ($j = 0; $j <= 20; $j++) {
                                                        ?>
                                                        <option
                                                            value="<?php echo $j; ?>" <?php if ($j == $maxUni) echo 'selected'; ?>><?php echo $j; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>

                                            </td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>

                            </div>
                            <div class="box-content nopadding">
                                <div class="form-actions" style="margin-top: 0">
                                    <button type="submit" class="btn btn-primary"><i class="icon-save"></i> Update
                                    </button>
                                </div>
                            </div>

                        </div>

                    </form>

                </div>

            </div>




            <div class="row-fluid">
                <div class="span12">
                    <!-- INSERT GALLERY HERE -->

                    <div id="galleryImagesDiv">

                        <div class="box">
                            <div class="box-title">
                                <h3>
                                    <i class="icon-picture"></i> Gallery Images </h3>
                                <div class="actions">
                                    <a href="#" id="addImageBtn" class="btn btn-mini" rel="tooltip"
                                       title="Add Images"><i class="icon-plus"></i></a>
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
                                    <a href="#" id="updateGalleryImagesBtn" class="btn btn-mini" rel="tooltip"
                                       title="Update"><i class="icon-save"></i></a>
                                    <a href="#" id="cancelGalleryImagesBtn" class="btn btn-mini" rel="tooltip"
                                       title="Cancel"><i class="icon-remove"></i></a>
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
                                <div id="plupload" data-resize="<?php echo $patchworks->galleryImageSizes; ?>">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>



                    <div class="row-fluid" id="addGroupsDiv" style="display: none;">
                <div class="span12">

                    <div class="box box-color orange box-bordered" style="display: none;">

                        <div class="box-title" style="margin-top: 0;">
                            <h3>
                                <i class="icon-tags"></i> Basket Extras</h3>
                        </div>
                        <div class="box-content nopadding">

                            <table class="table table-bordered table-striped table-highlight" id="extrasListTable">
                                <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Ext. Description</th>
                                    <th width="30" style="text-align: center">Def.</th>
                                    <th width="30" style="text-align: center">Man.</th>
                                    <th width="50" style="text-align: right">Ext. &pound;</th>
                                    <th width="20"></th>
                                    <th width="20"></th>
                                </tr>
                                </thead>
                                <tbody id="extrasListBody">


                                <?php

                                if (isset($basketExtras) && is_array($basketExtras)) {
                                    for ($i = 99; $i < count($basketExtras); $i++) {
                                        ?>

                                        <tr>
                                            <td><input type="text" name="bexttl"
                                                       value="<?php echo $basketExtras[$i]['bexttl']; ?>"></td>
                                            <td><input type="text" name="bextxt"
                                                       value="<?php echo $basketExtras[$i]['bextxt']; ?>"></td>
                                            <td style="text-align: center;"><input type="checkbox" name="bexdef"
                                                                                   value="1" <?php if ($basketExtras[$i]['bexdef'] == 1) echo 'checked'; ?>>
                                            </td>
                                            <td style="text-align: center;"><input type="checkbox" name="bexman"
                                                                                   value="1" <?php if ($basketExtras[$i]['bexman'] == 1) echo 'checked'; ?>>
                                            </td>
                                            <td><input type="text" name="bexpri"
                                                       value="<?php echo $basketExtras[$i]['bexpri']; ?>"></td>
                                            <td><a href="#" class="btn btn-danger deleteExtraBtn"
                                                   data-bex_id="<?php echo $basketExtras[$i]['bex_id']; ?>"><i
                                                        class="icon icon-trash"></i></a></td>
                                            <td><a href="#" class="btn btn-success sortExtraBtn""><i
                                                    class="icon icon-sort"></i></a></td>
                                        </tr>

                                        <?php
                                    }
                                }
                                ?>

                                </tbody>
                                <tfoot id="extrasListFoot">
                                <tr>
                                    <td><input type="text" name="bexttl" value=""></td>
                                    <td><input type="text" name="bextxt" value=""></td>
                                    <td style="text-align: center;"><input type="checkbox" name="bexdef" value="1"></td>
                                    <td style="text-align: center;"><input type="checkbox" name="bexman" value="1"></td>
                                    <td><input type="text" name="bexpri" value="0.00"></td>
                                    <td colspan="2" style="text-align: center"><a href="#" class="btn btn-success"
                                                                                  id="addExtraBtn"><i
                                                class="icon icon-save"></i></a></td>
                                </tr>
                                </tfoot>
                            </table>

                        </div>

                    </div>





                    <div class="box box-color blue box-bordered">
                        <div class="box-title">
                            <h3>
                                <i class="icon-plus"></i> Add Group</h3>


                            <div class="actions">
                                <a href="#" id="addBatchBtn" class="btn btn-mini" rel="tooltip" title="Add Batch of Products"><i class="icon-plus"></i></a>
                            </div>


                        </div>
                        <div class="box-content nopadding">

                            <form id="addGroupForm" class="form-horizontal form-bordered form-validate">
                                <input type="hidden" name="bsk_id" value="<?php echo ($basketRec) ? $basketRec->bsk_id : '0'; ?>">

                                <div class="control-group">
                                    <label for="textfield" class="control-label">Add Product to Group</label>

                                    <div class="controls">
                                        <div class="input-append">
                                            <input type="text" class="input-large" placeholder="Enter Group Name" name="bpgttl">
                                            <button class="btn" type="submit" id="addGroupBtn"><span class="icon icon-plus"></span></button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                        <div class="box-content nopadding">

                            <div id="productListTable">

                            </div>

                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal hide fade" id="productModal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h3>Product Search</h3>
    </div>

    <form id="modalProductSearchForm" class="form-horizontal form-bordered form-validate">

        <input type="hidden" name="bpg_id" value="">

        <div class="modal-body" style="overflow-y: initial; padding: 0;">

            <div class="control-group">
                <label for="textfield" class="control-label">Search
                    <small>start typing to see results</small></label>
                <div class="controls">
                    <div class="input-append">
                        <input type="text" class="input-block-level autocomplete" name="prdnam" autocomplete="off">
                        <button class="btn" type="button" id="addBatchProduct">Add To List</button>
                    </div>
                </div>
            </div>

            <div class="control-group">
                <label for="textfield" class="control-label">Selected Products</label>
                <div class="controls" style="max-height: 200px; overflow-y: scroll;">

                    <ul id="batchProducts">

                    </ul>

                </div>
            </div>

        </div>
        <div class="modal-footer">
            <a href="#" class="btn" data-dismiss="modal"><i class="icon-remove"></i> Cancel</a>
            <button type="submit" class="btn btn-primary" name="action" value="update" id="updateImageBtn"><i
                    class="icon-save"></i> Update
            </button>
        </div>

    </form>


</div>



<div class="modal hide fade" id="batchModal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h3>Basket Product Groups</h3>
    </div>

    <div class="modal-body" style="max-height: 250px; overflow-y: scroll; padding: 0;">

        <?php
        $TmpBpg = new BpgDAO();
        $basketProductGroups = $TmpBpg->select(NULL, 0, NULL, false);
        ?>

        <?php
        for ($i=0;$i<count($basketProductGroups);$i++) {
            ?>

            <ul>
                <li><a href="#" class="selectGroup" data-bpg_id="<?php echo $basketProductGroups[$i]['bpg_id']; ?>"><?php echo $basketProductGroups[$i]['bpgttl']; ?></a></li>
            </ul>

            <?php
        }
        ?>

    </div>
    <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal"><i class="icon-remove"></i> Cancel</a>
        <button type="submit" class="btn btn-primary" name="action" value="update" id="addBatchBtn"><i
                class="icon-save"></i> Update
        </button>
    </div>

</div>

</body>
</html>

