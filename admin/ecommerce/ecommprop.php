<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("classes/ecommprop.cls.php");
require_once("../attributes/classes/attrgroups.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$qryArray = array();
$sql = "SELECT * FROM ecommprop WHERE eco_id = 1";
$ecoProp = $patchworks->run($sql, array(), true);

$TmpAtr = new AtrDAO();
$attrGroups = $TmpAtr->select(NULL, 'FORM');

?>
<!doctype html>
<html>
<head>

    <title>eCommerce Properties</title>
    <?php include('../webparts/headdata.php'); ?>

    <script src="js/plugins/tinymce/tinymce.min.js"></script>

    <script src="ecommerce/js/ecommprop.js"></script>

</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
    <?php include('../webparts/website-left.php'); ?>
    <div id="main">
        <div class="container-fluid">
            <div class="page-header">
                <div class="pull-left">
                    <h1>Sitemap</h1>
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
                        <a>eCommerce Properties</a>
                    </li>
                </ul>
            </div>


            <div class="row-fluid">

                <form action="<?php echo $patchworks->pwRoot; ?>ecommerce/ecommpropupdate.php" id="ecommPropForm"
                      class="form-horizontal form-bordered" novalidate>

                    <div class="span6">


                        <div class="box box-color box-bordered">
                            <div class="box-title">
                                <h3>
                                    <i class="icon-cogs"></i> eCommerce Properties</h3>

                                <div class="actions">
                                    <a href="#" class="btn btn-mini submit-form" role="button" rel="tooltip"
                                       title="Update Properties"><i class="icon-save"></i></a>
                                </div>
                            </div>
                            <div class="box-content nopadding">

                                <div class="control-group">
                                    <label class="control-label">Company Name
                                        <small>Enter the name on the email invoice</small>
                                    </label>

                                    <div class="controls">
                                        <input type="text" class="input-block-level" name="comnam"
                                               value="<?php echo $ecoProp->comnam; ?>">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">Address
                                        <small>The company address to appear on the invoice</small>
                                    </label>

                                    <div class="controls">
                                        <input type="text" class="input-block-level input-margin-bottom" name="adr1"
                                               value="<?php echo $ecoProp->adr1; ?>">
                                        <input type="text" class="input-block-level input-margin-bottom" name="adr2"
                                               value="<?php echo $ecoProp->adr2; ?>">
                                        <input type="text" class="input-block-level input-margin-bottom" name="adr3"
                                               value="<?php echo $ecoProp->adr3; ?>">
                                        <input type="text" class="input-block-level input-margin-bottom" name="adr4"
                                               value="<?php echo $ecoProp->adr4; ?>">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Post Code
                                        <small></small>
                                    </label>

                                    <div class="controls">
                                        <input type="text" class="input-block-level" name="pstcod"
                                               value="<?php echo $ecoProp->pstcod; ?>">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">Email Address
                                        <small>Enter the email address for order correspondance</small>
                                    </label>

                                    <div class="controls">
                                        <input type="text" class="input-block-level" name="emaadr"
                                               value="<?php echo $ecoProp->emaadr; ?>">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">Company Telephone
                                        <small>Enter the telephone no. for order correspondance</small>
                                    </label>

                                    <div class="controls">
                                        <input type="text" class="input-block-level" name="comtel"
                                               value="<?php echo $ecoProp->comtel; ?>">
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="box box-color box-bordered">
                            <div class="box-title">
                                <h3>
                                    <i class="icon-shopping-cart"></i> Checkout Properties</h3>

                                <div class="actions">
                                    <a href="#" class="btn btn-mini submit-form" role="button" rel="tooltip"
                                       title="Update Properties"><i class="icon-save"></i></a>
                                </div>
                            </div>
                            <div class="box-content nopadding">


                                <div class="control-group">
                                    <label class="control-label">EU Text
                                        <small></small>
                                    </label>

                                    <div class="controls">

                                        <textarea class="input-block-level customfield" id="euText" name="eutext" rows="5"><?php echo $patchworks->getJSONVariable($ecoProp->ecoobj, 'eutext', false); ?></textarea>

                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">Non EU Text
                                        <small></small>
                                    </label>

                                    <div class="controls">

                                        <textarea class="input-block-level customfield" id="nonEuText" name="noneutext" rows="5"><?php echo $patchworks->getJSONVariable($ecoProp->ecoobj, 'noneutext', false); ?></textarea>

                                    </div>
                                </div>


                                <div class="control-group">
                                    <label class="control-label">Order Success
                                        <small></small>
                                    </label>

                                    <div class="controls">

                                        <textarea class="input-block-level customfield" id="orderSuccessText" name="ordersuccesstext" rows="5"><?php echo $patchworks->getJSONVariable($ecoProp->ecoobj, 'ordersuccesstext', false); ?></textarea>

                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">Order Fail
                                        <small></small>
                                    </label>

                                    <div class="controls">

                                        <textarea class="input-block-level customfield" id="orderFailText" name="orderfailtext" rows="5"><?php echo $patchworks->getJSONVariable($ecoProp->ecoobj, 'orderfailtext', false); ?></textarea>

                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">Checkout Form
                                        <small></small>
                                    </label>

                                    <div class="controls">

                                        <select name="atr_id" class="input-block-level customfield">

                                            <option value="0">N/A</option>

                                            <?php

                                            $Atr_ID = $patchworks->getJSONVariable($ecoProp->ecoobj, 'atr_id', false);

                                            $tableLength = count($attrGroups);
                                            for ($i = 0; $i < $tableLength; ++$i) {
                                                ?>
                                                <option
                                                    value="<?php echo $attrGroups[$i]['atr_id']; ?>" <?php if ($Atr_ID == $attrGroups[$i]['atr_id']) echo 'selected'; ?>><?php echo $attrGroups[$i]['atrnam']; ?></option>
                                            <?php } ?>
                                        </select>

                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="box box-color box-bordered">
                            <div class="box-title">
                                <h3>
                                    <i class="icon-cogs"></i> Email Properties</h3>

                                <div class="actions">
                                    <a href="#" class="btn btn-mini submit-form" role="button" rel="tooltip"
                                       title="Update Properties"><i class="icon-save"></i></a>
                                </div>
                            </div>
                            <div class="box-content nopadding">

                                <div class="control-group">
                                    <label class="control-label">Despatch Email Text
                                        <small></small>
                                    </label>

                                    <div class="controls">

                                        <textarea class="input-block-level customfield" id="despatchEmailText" name="despatchemailtext" rows="5"><?php echo $patchworks->getJSONVariable($ecoProp->ecoobj, 'despatchemailtext', false); ?></textarea>

                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="span6">

                        <div class="box box-color box-bordered">
                            <div class="box-title">
                                <h3>
                                    <i class="icon-tasks"></i> Product Properties</h3>

                                <div class="actions">
                                    <a href="#" class="btn btn-mini submit-form" role="button" rel="tooltip"
                                       title="Update Properties"><i class="icon-save"></i></a>
                                </div>
                            </div>
                            <div class="box-content nopadding">

                                <div class="control-group">
                                    <label class="control-label">Prices Include VAT
                                        <small></small>
                                    </label>

                                    <div class="controls">
                                        <label class="checkbox">
                                            <input type="checkbox" name="incvat" value="1" <?php echo ($ecoProp && $ecoProp->incvat == 1) ? 'checked' : ''; ?>>
                                            prices entered in administration include VAT? </label>
                                    </div>
                                </div>


                                <div class="control-group">
                                    <label class="control-label">Show out of stock products?
                                        <small></small>
                                    </label>

                                    <div class="controls">
                                        <label class="checkbox">
                                            <input type="checkbox" name="outstk" value="1" <?php echo ($ecoProp && $ecoProp->outstk == 1) ? 'checked' : ''; ?>>
                                            0 stock products shown on website </label>
                                    </div>
                                </div>


                                <div class="control-group">
                                    <label class="control-label">Product Display Format
                                        <small></small>
                                    </label>

                                    <div class="controls">
                                        <label class="radio">
                                            <input type="radio" name="prddsp" id="PrdDsp1" value="1" <?php echo ($ecoProp->prddsp == 1) ? 'checked' : ''; ?>>
                                            Product Type With Listing</label>
                                        <label class="radio">
                                            <input type="radio" name="prddsp" id="PrdDsp2" value="2" <?php echo ($ecoProp->prddsp == 2) ? 'checked' : ''; ?>>
                                            Individual Product</label>
                                        <label class="radio">
                                            <input type="radio" name="prddsp" id="PrdDsp3" value="3" <?php echo ($ecoProp->prddsp == 3) ? 'checked' : ''; ?>>
                                            Product Type With Selection </label>
                                    </div>
                                </div>


                            </div>
                        </div>

                        <div class="box box-color box-bordered">
                            <div class="box-title">
                                <h3>
                                    <i class="icon-tasks"></i> Cusmtomer Account Properties</h3>

                                <div class="actions">
                                    <a href="#" class="btn btn-mini submit-form" role="button" rel="tooltip"
                                       title="Update Properties"><i class="icon-save"></i></a>
                                </div>
                            </div>
                            <div class="box-content nopadding">

                                <div class="control-group">
                                    <label class="control-label">Account Details
                                        <small></small>
                                    </label>

                                    <div class="controls">

                                        <label class="radio">
                                            <input type="radio" name="acctyp" id="PrdDsp0" value="0" <?php echo ($ecoProp->acctyp == 0) ? 'checked' : ''; ?>>
                                            No Account Register/Login</label>
                                        <label class="radio">
                                            <input type="radio" name="acctyp" id="PrdDsp1" value="1" <?php echo ($ecoProp->acctyp == 1) ? 'checked' : ''; ?>>
                                            Public Only Register/Login</label>
                                        <label class="radio">
                                            <input type="radio" name="acctyp" id="PrdDsp2" value="2" <?php echo ($ecoProp->acctyp == 2) ? 'checked' : ''; ?>>
                                            Public & Trade Register/Login</label>
                                        <label class="radio">
                                            <input type="radio" name="acctyp" id="PrdDsp3" value="3" <?php echo ($ecoProp->acctyp == 3) ? 'checked' : ''; ?>>
                                            Trade Only Register/Login</label>

                                    </div>
                                </div>


                            </div>

                            <div class="box-content nopadding">

                                <div class="control-group">
                                    <label class="control-label">Trade Account Details
                                        <small></small>
                                    </label>

                                    <div class="controls">

                                        <label class="radio">
                                            <input type="radio" name="trdtyp" id="TrdTyp0" value="0" <?php echo ($ecoProp->acctyp == 0) ? 'checked' : ''; ?>>
                                            N/A</label>
                                        <label class="radio">
                                            <input type="radio" name="trdtyp" id="TrdTyp1" value="1" <?php echo ($ecoProp->acctyp == 1) ? 'checked' : ''; ?>>
                                            Trade Registration (No Authorisation)</label>
                                        <label class="radio">
                                            <input type="radio" name="trdtyp" id="TrdTyp2" value="2" <?php echo ($ecoProp->acctyp == 2) ? 'checked' : ''; ?>>
                                            Trade Registration (With Authorisation)</label>
                                        <label class="radio">
                                            <input type="radio" name="trdtyp" id="TrdTyp3" value="3" <?php echo ($ecoProp->acctyp == 3) ? 'checked' : ''; ?>>
                                            Trade Registration (Admin Registration)</label>

                                    </div>
                                </div>


                            </div>
                        </div>

                        <div class="box box-color box-bordered">
                            <div class="box-title">
                                <h3>
                                    <i class="icon-credit-card"></i> Payment Gateway Properties</h3>

                                <div class="actions">
                                    <a href="#" class="btn btn-mini submit-form" role="button" rel="tooltip"
                                       title="Update Properties"><i class="icon-save"></i></a>
                                </div>
                            </div>
                            <div class="box-content nopadding">


                                <div class="control-group">
                                    <label class="control-label">Allow Collect and Pay In Store
                                        <small></small>
                                    </label>

                                    <div class="controls">
                                        <label class="checkbox">
                                            <input type="checkbox" name="colect" value="1" <?php echo ($ecoProp && $ecoProp->colect == 1) ? 'checked' : ''; ?>>
                                            Creation of orders on front end to be paid in store </label>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">SagePay Status
                                        <small></small>
                                    </label>

                                    <div class="controls">

                                        <select name="sp_sta" class="input-block-level">

                                            <option value="NA" <?php echo ($ecoProp->sp_sta == 'NA') ? 'selected' : ''; ?> >N/A</option>
                                            <option value="TEST" <?php echo ($ecoProp->sp_sta == 'TEST') ? 'selected' : ''; ?> >TEST</option>
                                            <option value="LIVE" <?php echo ($ecoProp->sp_sta == 'LIVE') ? 'selected' : ''; ?> >LIVE</option>
                                            <option value="SIMULATION" <?php echo ($ecoProp->sp_sta == 'SIMULATION') ? 'selected' : ''; ?> >SIMULATION</option>

                                        </select>

                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">SagePay Vendor
                                        <small>LIVE</small>
                                    </label>

                                    <div class="controls">

                                        <input type="text" class="input-block-level" name="sp_ven" value="<?php echo $ecoProp->sp_ven; ?>">

                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">SagePay Encryption
                                        <small>LIVE</small>
                                    </label>

                                    <div class="controls">

                                        <input type="text" class="input-block-level" name="sp_enc" value="<?php echo $ecoProp->sp_enc; ?>">

                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">SagePay Vendor
                                        <small>TEST</small>
                                    </label>

                                    <div class="controls">

                                        <input type="text" class="input-block-level" name="sptven" value="<?php echo $ecoProp->sptven; ?>">

                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">SagePay Encryption
                                        <small>TEST</small>
                                    </label>

                                    <div class="controls">

                                        <input type="text" class="input-block-level" name="sptenc" value="<?php echo $ecoProp->sptenc; ?>">

                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">PayPal Status
                                        <small></small>
                                    </label>

                                    <div class="controls">

                                        <select name="pp_sta" class="input-block-level">

                                            <option value="NA" <?php echo ($ecoProp->pp_sta == 'NA') ? 'selected' : ''; ?> >N/A</option>
                                            <option value="TEST" <?php echo ($ecoProp->pp_sta == 'TEST') ? 'selected' : ''; ?> >TEST</option>
                                            <option value="LIVE" <?php echo ($ecoProp->pp_sta == 'LIVE') ? 'selected' : ''; ?> >LIVE</option>

                                        </select>

                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">PayPal Email
                                        <small></small>
                                    </label>

                                    <div class="controls">

                                        <input type="text" class="input-block-level" name="pp_ema" value="<?php echo $ecoProp->pp_ema; ?>">

                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">WorldPay Status
                                        <small></small>
                                    </label>

                                    <div class="controls">

                                        <select name="wp_sta" class="input-block-level">

                                            <option value="NA" <?php echo ($ecoProp->wp_sta == 'NA') ? 'selected' : ''; ?> >N/A</option>
                                            <option value="TEST" <?php echo ($ecoProp->wp_sta == 'TEST') ? 'selected' : ''; ?> >TEST</option>
                                            <option value="LIVE" <?php echo ($ecoProp->wp_sta == 'LIVE') ? 'selected' : ''; ?> >LIVE</option>

                                        </select>

                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">WorldPay Instance ID
                                        <small></small>
                                    </label>

                                    <div class="controls">

                                        <input type="text" class="input-block-level" name="wpinst" value="<?php echo $ecoProp->wpinst; ?>">

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

</body>
</html>
