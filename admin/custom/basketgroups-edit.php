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

$Bpg_ID = (isset($_GET['bpg_id']) && is_numeric($_GET['bpg_id'])) ? $_GET['bpg_id'] : NULL;
$TmpBpg = new BpgDAO();
$basketGroupRec = NULL;

if (!is_null($Bpg_ID)) {
    $basketGroupRec = $TmpBpg->select($Bpg_ID, 0, NULL, true);
}

?>
<!doctype html>
<html>
<head>
    <title>Basket Maintenance</title>
    <?php include('../webparts/headdata.php'); ?>

    <script src="js/plugins/fileupload/bootstrap-fileupload.min.js"></script>

    <script src="custom/js/basketgroups-edit.js"></script>


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
                    <h1>Basket Group Maintenance</h1>
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
                        <a href="custom/basketgroups.php">Basket Groups</a>
                        <i class="icon-angle-right"></i>
                    </li>
                    <li>
                        <a>Basket Group Edit</a>
                    </li>
                </ul>
            </div>
            <div class="row-fluid">

                <div class="span12">

                    <form action="custom/baskets_script.php" id="basketForm" class="form-horizontal form-bordered"
                        data-returnurl="custom/baskets.php" enctype="multipart/form-data" style="display: none">

                        <div class="box box-color box-bordered">
                            <div class="box-title">
                                <h3>
                                    <i class="icon-gift"></i> Basket Builder</h3>

                                <div class="actions">
                                    <a href="#" id="deleteBasketBtn" class="btn btn-mini" rel="tooltip" title="Delete"
                                       style="display: none;"><i class="icon-trash"></i></a>
                                    <a href="#" id="updateBasketBtn" class="btn btn-mini" rel="tooltip"
                                       title="Update"><i class="icon-save"></i></a>
                                </div>
                            </div>
                            <div class="box-content nopadding">

                                <div class="row-fluid">

                                    <div class="span6">

                                        <input type="hidden" name="bsk_id" value="0">
                                        <input type="hidden" name="bpg_id" id="id" value="<?php echo ($basketGroupRec) ? $basketGroupRec->bpg_id : 0; ?>">

                                        <div class="control-group">
                                            <label class="control-label">Basket Group Title
                                                <small>identifying name</small>
                                            </label>

                                            <div class="controls">
                                                <input type="text" class="input-block-level" name="bskttl"
                                                       value="<?php echo ($basketGroupRec) ? $basketGroupRec->bpgttl : ''; ?>" required>
                                            </div>
                                        </div>



                                    </div>
                                    <div class="span6">

                                    </div>

                                </div>

                            </div>




                        </div>

                    </form>

                </div>

            </div>

            <div class="row-fluid" id="addGroupsDiv" style="display: none;">
                <div class="span12">

                    <div class="box box-color blue box-bordered">
                        <div class="box-title">
                            <h3>
                                <i class="icon-plus"></i> Add Products To Group</h3>

                            <div class="actions">
<!--                                <a href="#" id="addProductBtn" class="btn btn-mini" rel="tooltip" title="Add Product"><i class="icon-plus"></i></a>-->
                            </div>

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



<!--            <div class="control-group">-->
<!--                <label class="control-label">Search-->
<!--                    <small>start typing to see results</small>-->
<!--                </label>-->
<!---->
<!--                <div class="controls">-->
<!--                    <input type="text" class="input-block-level autocomplete" name="prdnam"-->
<!--                           autocomplete="off">-->
<!--                </div>-->
<!--            </div>-->

        </div>
        <div class="modal-footer">
            <a href="#" class="btn" data-dismiss="modal"><i class="icon-remove"></i> Cancel</a>
            <button type="submit" class="btn btn-primary" name="action" value="update" id="updateImageBtn"><i
                    class="icon-save"></i> Update
            </button>
        </div>

    </form>


</div>

</body>
</html>
