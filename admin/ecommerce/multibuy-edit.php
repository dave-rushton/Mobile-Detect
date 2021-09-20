<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("classes/multibuy.cls.php");
require_once("../products/classes/products.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpMul = new MulDAO();
$editMB_ID = (isset($_GET['mul_id']) && is_numeric($_GET['mul_id'])) ? $_GET['mul_id'] : NULL;
$multibuyRec = NULL;
if (!is_null($editMB_ID)) $multibuyRec = $TmpMul->select($editMB_ID, NULL, NULL, true);

$TmpPrd = new PrdDAO();
$products = $TmpPrd->select(NULL, NULL, NULL, NULL, NULL, NULL, 'prdnam', false);

?>
<!doctype html>
<html>
<head>
    <title><?php echo ($multibuyRec) ? $multibuyRec->multtl : 'New Multibuy Item'; ?> : PatchWorks Multibuy
        Maintenance </title>
    <?php include('../webparts/headdata.php'); ?>

    <link rel="stylesheet" href="css/plugins/datepicker/datepicker.css">

    <script src="js/plugins/datepicker/bootstrap-datepicker.js"></script>
    <script src="js/system.date.js"></script>

    <script src="ecommerce/js/multibuy-edit.js"></script>
</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
    <?php include('../webparts/website-left.php'); ?>
    <div id="main">
        <div class="container-fluid">
            <div class="page-header">
                <div class="pull-left">
                    <h1>Multibuy Item : <?php echo ($multibuyRec) ? $multibuyRec->multtl : 'New Multibuy Item'; ?></h1>
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
                        <a href="ecommerce/dashboard.php">eCommerce</a>
                        <i class="icon-angle-right"></i>
                    </li>
                    <li>
                        <a href="ecommerce/multibuy.php">Multibuy</a>
                        <i class="icon-angle-right"></i>
                    </li>
                    <li>
                        <a><?php echo ($multibuyRec) ? $multibuyRec->multtl : 'New Multibuy item'; ?></a>
                    </li>
                </ul>
            </div>
            <div class="row-fluid">
                <div class="span4">
                    <form action="ecommerce/multibuy_script.php" id="multibuyForm" class="form-horizontal form-bordered"
                          data-returnurl="ecommerce/multibuy.php">
                        <div class="box box-color box-bordered">
                            <div class="box-title">
                                <h3>
                                    <i class="icon-bolt"></i> Multibuy Item</h3>

                                <div class="actions">
                                    <a href="#" id="updateMultibuyBtn" class="btn btn-mini" rel="tooltip"
                                       title="Update"><i class="icon-save"></i></a>
                                    <a href="#" id="deleteMultibuyBtn" class="btn btn-mini" rel="tooltip"
                                       title="Delete"><i class="icon-trash"></i></a>
                                </div>
                            </div>
                            <div class="box-content nopadding">

                                <input type="hidden" name="mul_id" id="id"
                                       value="<?php echo ($multibuyRec) ? $multibuyRec->mul_id : '0'; ?>">
                                <input type="hidden" name="tblnam"
                                       value="<?php echo ($multibuyRec) ? $multibuyRec->tblnam : ''; ?>">
                                <input type="hidden" name="tbl_id"
                                       value="<?php echo ($multibuyRec) ? $multibuyRec->tbl_id : '0'; ?>">

                                <div class="control-group">
                                    <label class="control-label">Multibuy Title
                                        <small>identifying name</small>
                                    </label>

                                    <div class="controls">
                                        <input type="text" class="input-block-level" name="multtl"
                                               value="<?php echo ($multibuyRec) ? $multibuyRec->multtl : ''; ?>">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">Multibuy Type
                                        <small></small>
                                    </label>

                                    <div class="controls">

                                        <select name="multyp" class="input-block-level">
                                            <!--                                            <option value="1" -->
                                            <?php //echo (isset($multibuyRec->multyp) && $multibuyRec->multyp == 1) ? 'selected' : ''; ?><!-->
                                            Cheapest Free</option>-->
                                            <!--                                            <option value="2" -->
                                            <?php //echo (isset($multibuyRec->multyp) && $multibuyRec->multyp == 2) ? 'selected' : ''; ?><!-->
                                            Buy 1 get 1 Free</option>-->
                                            <!--                                            <option value="3" -->
                                            <?php //echo (isset($multibuyRec->multyp) && $multibuyRec->multyp == 3) ? 'selected' : ''; ?><!-->
                                            Buy 1 get 1 half Price</option>-->
                                            <option
                                                value="4" <?php echo (isset($multibuyRec->multyp) && $multibuyRec->multyp == 4) ? 'selected' : 'selected'; ?>>
                                                Discount
                                            </option>
                                        </select>

                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">Discount Type</label>

                                    <div class="controls">
                                        <select name="pctamt">
                                            <option
                                                value="A" <?php if (isset($multibuyRec) && $multibuyRec->pctamt == 'A') echo 'selected'; ?>>
                                                Amount
                                            </option>
                                            <option
                                                value="P" <?php if (isset($multibuyRec) && $multibuyRec->pctamt == 'P') echo 'selected'; ?>>
                                                Percentage
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Discount Amount</label>

                                    <div class="controls">
                                        <input type="text" class="input-block-level" name="disamt"
                                               data-rule-required="true" data-rule-number="true"
                                               value="<?php echo (isset($multibuyRec)) ? $multibuyRec->disamt : ''; ?>">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">Minimum Items
                                        <small>minimum amount of items in basket</small>
                                    </label>

                                    <div class="controls">
                                        <input type="text" class="input-block-level" name="minbuy"
                                               value="<?php echo ($multibuyRec) ? $multibuyRec->minbuy : ''; ?>">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">Minimum Price
                                        <small>minimum amount of items in basket</small>
                                    </label>

                                    <div class="controls">
                                        <input type="text" class="input-block-level" name="minpri"
                                               value="<?php echo ($multibuyRec) ? $multibuyRec->minpri : ''; ?>">
                                    </div>
                                </div>

                                <div class="control-group hide">
                                    <label class="control-label">Products
                                        <small>select products</small>
                                    </label>

                                    <div class="controls">

                                        <select multiple="multiple" class="input-block-level" name="prd_idselect"
                                                id="prd_idselect">

                                            <?php
                                            $tableLength = count($products);
                                            $tableLength = 0;
                                            for ($i = 0; $i < $tableLength; ++$i) {
                                                ?>

                                                <option
                                                    value="<?php echo $products[$i]['prd_id'] ?>" <?php echo (isset($multibuyRec) && is_numeric(strpos($multibuyRec->prd_id, $products[$i]['prd_id']))) ? 'selected' : ''; ?>><?php echo $products[$i]['prdnam'] ?></option>

                                            <?php } ?>

                                        </select>

                                        <input type="hidden" name="prd_id">

                                    </div>
                                </div>


                                <div class="control-group">
                                    <label class="control-label">Start Date</label>

                                    <div class="controls">
                                        <div class="input-append">
                                            <input type="text" name="begdat"
                                                   value="<?php echo (isset($multibuyRec) && !empty($multibuyRec->begdat)) ? date("Y-m-d", strtotime($multibuyRec->begdat)) : ''; ?>">
                                            <a href="#" id="clearBegDateBtn" class="btn" rel="tooltip"
                                               data-placement="top" data-original-title="Clear Start Date"><i
                                                    class="icon-remove"></i></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">End Date</label>

                                    <div class="controls">
                                        <div class="input-append">
                                            <input type="text" name="enddat"
                                                   value="<?php echo (isset($multibuyRec) && !empty($multibuyRec->enddat)) ? date("Y-m-d", strtotime($multibuyRec->enddat)) : ''; ?>">
                                            <a href="#" id="clearEndDateBtn" class="btn" rel="tooltip"
                                               data-placement="top" data-original-title="Clear End Date"><i
                                                    class="icon-remove"></i></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">Status
                                        <small>multibuy status</small>
                                    </label>

                                    <div class="controls">
                                        <label class="radio">
                                            <input type="radio" name="sta_id" id="Sta_ID0"
                                                   value="0" <?php echo (!isset($multibuyRec) || isset($multibuyRec) && $multibuyRec->sta_id == 0) ? 'checked' : ''; ?>>
                                            Active</label>
                                        <label class="radio">
                                            <input type="radio" name="sta_id" id="Sta_ID1"
                                                   value="1" <?php echo (isset($multibuyRec) && $multibuyRec->sta_id == 1) ? 'checked' : ''; ?>>
                                            Inactive </label>
                                    </div>
                                </div>


                            </div>
                        </div>


                    </form>
                </div>
                <div class="span4">

                    <div class="box box-color box-bordered" id="relatedProductTypeBox">
                        <div class="box-title">
                            <h3>
                                <i class="icon-shopping-cart"></i> Related Product Types</h3>
                        </div>
                        <div class="box-content nopadding">

                            <form id="relatedProductTypeForm" class="form-horizontal form-bordered form-validate"
                                  action="system/related_script.php">
                                <input type="hidden" name="tblnam" value="PRDTYPE">
                                <input type="hidden" name="tbl_id"
                                       value="<?php echo (isset($multibuyRec)) ? $multibuyRec->mul_id : 0; ?>">
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
                                    <label class="control-label">Selected Product Type</label>

                                    <div class="controls">
                                        <input type="text" class="input-block-level autocomplete"
                                               id="relatedProductTypeName"
                                               readonly>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">Relate</button>
                                </div>
                            </form>
                        </div>
                        <div class="box-content">

                            <ul id="relatedProductTypeList" class="relatedList">

                            </ul>

                        </div>
                    </div>





                </div>

                <div class="span4">

                    <div class="box box-color box-bordered" id="relatedProductBox">
                        <div class="box-title">
                            <h3>
                                <i class="icon-shopping-cart"></i> Related Products</h3>
                        </div>
                        <div class="box-content nopadding">

                            <form id="relatedProductForm" class="form-horizontal form-bordered form-validate"
                                  action="system/related_script.php">
                                <input type="hidden" name="tblnam" value="PRODUCT">
                                <input type="hidden" name="tbl_id"
                                       value="<?php echo (isset($multibuyRec)) ? $multibuyRec->mul_id : 0; ?>">
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
                                        <input type="text" class="input-block-level autocomplete" id="relatedName"
                                               readonly>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">Relate</button>
                                </div>
                            </form>
                        </div>
                        <div class="box-content">

                            <ul id="relatedProductList" class="relatedList">

                            </ul>

                        </div>
                    </div>

                </div>


            </div>

        </div>
    </div>
</div>
</body>
</html>
