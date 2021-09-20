<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("../system/classes/places.cls.php");
require_once("../products/classes/products.cls.php");
require_once("../ecommerce/classes/order.cls.php");
require_once("../ecommerce/classes/orderline.cls.php");
require_once("../ecommerce/classes/delivery.cls.php");
require_once("../ecommerce/classes/vat.cls.php");
require_once("../products/classes/discounts.cls.php");


$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$Ord_ID = (isset($_GET['ord_id']) && is_numeric($_GET['ord_id'])) ? $_GET['ord_id'] : NULL;

$TmpOrd = new OrdDAO();
$TmpOln = new OlnDAO();
$TmpDis = new DisDAO();

if (is_numeric($Ord_ID)) {
    $order = $TmpOrd->select($Ord_ID, NULL, NULL, NULL, true);
    $orderlines = $TmpOln->select($Ord_ID, NULL, false);

    $discountRec = $TmpDis->selectByCode($order->discod, true);

}

$TmpVat = new VatDAO();
$vatRecs = $TmpVat->select();

?>
<!doctype html>
<html>
<head>
    <title>Enquiries</title>
    <?php include('../webparts/headdata.php'); ?>

    <link rel="stylesheet" type="text/css" href="css/plugins/datatable/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="css/plugins/datepicker/datepicker.css">

    <style>

        .table tr td {
            vertical-align: top;
        }

        .invoice-infos table tr th {
            padding: 0 5px;
        }

        .invoice-infos table tr td {
            padding: 0 5px;
        }

    </style>

    <script src="js/plugins/datepicker/bootstrap-datepicker.js"></script>
    <script src="js/system.date.js"></script>

    <script src="ecommerce/js/order-edit.js"></script>

</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid nav-hidden" id="content">
    <?php //include('../webparts/index-left.php'); ?>
    <div id="main">
        <div class="container-fluid">
            <div class="page-header">
                <div class="pull-left">
                    <h1>Enquiry Maintenance</h1>
                </div>
                <div class="pull-right">
                    <div id="left">

                    </div>
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
                        <a href="ecommerce/orders.php">Enquiry</a>
                        <i class="icon-angle-right"></i>
                    </li>
                    <li>
                        <a><?php echo (isset($order)) ? 'Enquiry #' . str_pad($order->ord_id, 8, "0", STR_PAD_LEFT) : 'New Order'; ?></a>
                    </li>
                </ul>
            </div>

            <div class="row-fluid">
                <div class="span12">

                    <div class="box">
                        <div class="box-title">
                            <h3>
                                <i class="icon-money"></i>
                                Enquiry
                            </h3>
                        </div>
                        <div class="box-content">
                            <div class="invoice-info">
                                <div class="invoice-name">

                                    <?php echo (isset($order)) ? 'Enquiry #' . str_pad($order->ord_id, 8, "0", STR_PAD_LEFT) : 'New Order'; ?>

                                </div>
                                <div class="invoice-from">
                                    <span>From</span>

                                    <strong><?php echo (isset($order)) ? $order->paycus : ''; ?></strong><br>
                                    <i><?php echo (isset($order)) ? $order->payfao : ''; ?></i>
                                    <hr>
                                    <address>
                                        <?php echo (isset($order)) ? $order->payadr1 : ''; ?><br>
                                        <?php echo (isset($order)) ? $order->payadr2 : ''; ?><br>
                                        <?php echo (isset($order)) ? $order->payadr3 : ''; ?><br>
                                        <?php echo (isset($order)) ? $order->payadr4 : ''; ?><br>
                                        <?php echo (isset($order)) ? $order->paypstcod : ''; ?><br>
                                        <?php echo (isset($order)) ? $order->paycoucod : ''; ?><br><br>

                                        <abbr
                                                title="Email">Email:</abbr> <?php echo (isset($order)) ? '<a href="mailto:' . $order->emaadr . '">' . $order->emaadr . '</a>' : ''; ?>
                                        <br>
                                        <abbr
                                                title="Phone">Contact:</abbr> <?php echo (isset($order)) ? $order->paytrm : ''; ?>

                                    </address>

                                </div>
                                <div class="invoice-to">
                                    <span>Deliver To</span>

                                    <strong><?php echo (isset($order)) ? $order->cusnam : ''; ?></strong><br>
                                    <i><?php echo (isset($order)) ? $order->fao : ''; ?></i>
                                    <hr>
                                    <address>
                                        <?php echo (isset($order)) ? $order->adr1 : ''; ?><br>
                                        <?php echo (isset($order)) ? $order->adr2 : ''; ?><br>
                                        <?php echo (isset($order)) ? $order->adr3 : ''; ?><br>
                                        <?php echo (isset($order)) ? $order->adr4 : ''; ?><br>
                                        <?php echo (isset($order)) ? $order->pstcod : ''; ?><br>
                                        <?php echo (isset($order)) ? $order->coucod : ''; ?>
                                    </address>

                                </div>
                                <div class="invoice-infos">
                                    <table>
                                        <tbody>
                                        <tr>
                                            <th align="right">Date:</th>
                                            <td><?php echo (isset($order)) ? date("M d, Y H:i", strtotime($order->invdat)) : date('M d, Y'); ?> </td>
                                        </tr>
                                        <tr>
                                            <th align="right">Enquiry #:</th>
                                            <td><?php echo (isset($order)) ? '#' . str_pad($order->ord_id, 8, "0", STR_PAD_LEFT) : 'New Order'; ?></td>
                                        </tr>
                                        <tr>
                                            <th align="right">Method:</th>
                                            <td><?php echo (isset($order)) ? $order->altref : ''; ?></td>
                                        </tr>

                                        <!--                                        <tr>-->
                                        <!--                                            <th align="right">Payment Ref:</th>-->
                                        <!--                                            <td>-->
                                        <?php //echo(isset($order)) ? $order->altref : ''; ?><!--</td>-->
                                        <!--                                        </tr>-->
                                        <!--                                        <tr>-->
                                        <!--                                            <th align="right">Reference Name:</th>-->
                                        <!--                                            <td>-->
                                        <?php //echo(isset($order)) ? $order->altnam : ''; ?><!--</td>-->
                                        <!--                                        </tr>-->

                                        <tr>
                                            <th align="right">Actions:</th>
                                            <td>
                                                <a href="#" id="emailOrderBtn" class="btn btn-mini" rel="tooltip"
                                                   title="Email"><i class="icon-envelope"></i></a>

                                                <?php if (1 == 2) { ?>

                                                    <a href="#" id="emailDespatchBtn" class="btn btn-mini" rel="tooltip"
                                                       title="Email Despatch note"><i class="icon-truck"></i></a>

                                                    <a href="#" id="printOrderBtn" class="btn btn-mini" rel="tooltip"
                                                       title="Print Receipt"><i class="icon-print"></i></a>
                                                <?php } ?>
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <table class="table table-striped table-invoice">
                                <thead>
                                <tr>
                                    <th>Item</th>
<!--                                    <th>Price</th>-->
                                    <th>Qty</th>
<!--                                    <th class="tr">Total</th>-->
                                </tr>
                                </thead>
                                <tbody>

                                <?php

                                $orderTotal = 0;

                                for ($l = 0; $l < count($orderlines); $l++) {

                                    $orderTotal += number_format($orderlines[$l]['unipri'] * $orderlines[$l]['numuni'], 2);

                                    ?>

                                    <tr>
                                        <td class="name"><?php echo $orderlines[$l]['olndsc']; ?></td>
<!--                                        <td class="price">--><?php //echo $orderlines[$l]['unipri']; ?><!--</td>-->
                                        <td class="qty"><?php echo $orderlines[$l]['numuni']; ?></td>
<!--                                        <td class="total">--><?php //echo number_format($orderlines[$l]['unipri'] * $orderlines[$l]['numuni'], 2); ?><!--</td>-->
                                    </tr>

                                    <?php
                                }
                                ?>

                                <!--                                <tr>-->
                                <!--                                    <td class="name">-->
                                <!--                                        Discount-->
                                <!--                                    </td>-->
                                <!--                                    <td class="price">0.00</td>-->
                                <!--                                    <td class="qty">1</td>-->
                                <!--                                    <td class="total">0.00</td>-->
                                <!--                                </tr>-->

<!--                                <tr>-->
<!--                                    <td colspan="3"></td>-->
<!--                                    <td class="taxes">-->
<!--                                        <p>-->
<!--                                            <span class="light">Total</span>-->
<!--                                            <span>--><?php //echo number_format($orderTotal, 2, ".", ""); ?><!--</span>-->
<!--                                        </p>-->
<!--                                    </td>-->
<!--                                </tr>-->
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>

            <div class="row-fluid hide">
                <div class="span12">
                    <div class="box box-color box-bordered">
                        <div class="box-title">
                            <h3>
                                <i class="icon-reorder"></i>
                                Object
                            </h3>

                            <div class="actions">
                                <a href="#" class="btn btn-mini content-slideUp"><i class="icon-angle-down"></i></a>
                            </div>
                        </div>
                        <div class="box-content" style="display: none">
                            <pre><?php echo (isset($order)) ? json_encode(json_decode($order->ordobj), JSON_PRETTY_PRINT) : ''; ?></pre>

                            <a href="ecommerce/converttosessionorder.php?ord_id=<?php echo $order->ord_id; ?>"
                               target="_blank">Convert To Session Order</a>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div id="returnHTML"></div>


<div class="modal hide fade" id="emailModal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h3>Email Order</h3>
    </div>
    <form action="ecommerce/sendemail_script.php" id="emailForm" class="form-horizontal" novalidate>
        <div class="modal-body">
            <fieldset>

                <input type="hidden" name="ord_id" value="<?php echo $Ord_ID; ?>">

                <div class="control-group">
                    <label class="control-label">Email Address</label>
                    <div class="controls">
                        <input type="text" class="input-block-level" name="emaadr"
                               value="<?php echo $order->emaadr; ?>">
                    </div>
                </div>

            </fieldset>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn" data-dismiss="modal"><i class="icon-remove"></i> Cancel</a>
            <button type="submit" class="btn btn-primary" name="action" value="send" id="sendOrderEmailBtn"><i
                        class="icon-save"></i> Send
            </button>
        </div>
    </form>
</div>


<div class="modal hide fade" id="emailDespatchModal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h3>Email Despatch</h3>
    </div>
    <form action="ecommerce/senddespatch_script.php" id="emailDespatchForm" class="form-horizontal" novalidate>
        <div class="modal-body">
            <fieldset>

                <input type="hidden" name="ord_id" value="<?php echo $Ord_ID; ?>">

                <div class="control-group">
                    <label class="control-label">Email Address</label>
                    <div class="controls">
                        <input type="text" class="input-block-level" name="emaadr">
                    </div>
                </div>

            </fieldset>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn" data-dismiss="modal"><i class="icon-remove"></i> Cancel</a>
            <button type="submit" class="btn btn-primary" name="action" value="send" id="sendDespatchEmailBtn"><i
                        class="icon-save"></i> Send
            </button>
        </div>
    </form>
</div>


</body>
</html>