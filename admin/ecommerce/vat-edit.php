<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("classes/vat.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpVat = new VatDAO();
$editVatID = (isset($_GET['vat_id']) && is_numeric($_GET['vat_id'])) ? $_GET['vat_id'] : NULL;
$vatRec = NULL;
if (!is_null($editVatID)) $vatRec = $TmpVat->select($editVatID, NULL, NULL, true);

?>
<!doctype html>
<html>
<head>
    <title><?php echo ($vatRec) ? $vatRec->vatnam : 'New Vat Item'; ?> : PatchWorks Vat Item
        Maintenance </title>
    <?php include('../webparts/headdata.php'); ?>
    <script src="ecommerce/js/vat-edit.js"></script>
</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
    <?php include('../webparts/website-left.php'); ?>
    <div id="main">
        <div class="container-fluid">
            <div class="page-header">
                <div class="pull-left">
                    <h1>Vat Item : <?php echo ($vatRec) ? $vatRec->vatnam : 'New Vat Item'; ?></h1>
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
                        <a href="ecommerce/vat.php">Vat</a>
                        <i class="icon-angle-right"></i>
                    </li>
                    <li>
                        <a><?php echo ($vatRec) ? $vatRec->vatnam : 'New Vat item'; ?></a>
                    </li>
                </ul>
            </div>
            <div class="row-fluid">
                <form action="ecommerce/vat_script.php" id="vatForm" class="form-horizontal form-bordered"
                      data-returnurl="ecommerce/vat.php">
                    <div class="span6">
                        <div class="box box-color box-bordered">
                            <div class="box-title">
                                <h3>
                                    <i class="icon-legal"></i> Vat Item</h3>

                                <div class="actions">
                                    <a href="#" id="updateVatBtn" class="btn btn-mini" rel="tooltip"
                                       title="Update"><i class="icon-save"></i></a>
                                    <a href="#" id="deleteVatBtn" class="btn btn-mini" rel="tooltip"
                                       title="Delete"><i class="icon-trash"></i></a>
                                </div>
                            </div>
                            <div class="box-content nopadding">
                                <input type="hidden" name="vat_id" id="id"
                                       value="<?php echo ($vatRec) ? $vatRec->vat_id : '0'; ?>">

                                <div class="control-group">
                                    <label class="control-label">Vat Name
                                        <small>identifying name</small>
                                    </label>

                                    <div class="controls">
                                        <input type="text" class="input-large" name="vatnam"
                                               value="<?php echo ($vatRec) ? $vatRec->vatnam : ''; ?>">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Rate
                                        <small>vat rate</small>
                                    </label>

                                    <div class="controls">
                                        <input type="text" class="input-large" name="vatrat"
                                               value="<?php echo ($vatRec) ? $vatRec->vatrat : '0.00'; ?>">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Start Date
                                        <small></small>
                                    </label>

                                    <div class="controls">
                                        <input type="text" class="input-large" name="begdat"
                                               value="<?php echo ($vatRec) ? date("Y-m-d", strtotime($vatRec->begdat)) : date("Y-m-d"); ?>">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">Default VAT?</label>
                                    <div class="controls">
                                        <label class="checkbox">
                                            <input type="checkbox" name="defvat" value="1" <?php echo (isset($vatRec) && $vatRec->defvat == 1) ? 'checked' : ''; ?>> Default VAT
                                        </label>
                                    </div>
                                </div>

                            </div>


                        </div>
                </form>
            </div>

        </div>
    </div>
</div>
</body>
</html>
