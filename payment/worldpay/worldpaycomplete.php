<?php


function withoutVAT($amount = 0)
{
    $vatCalc = ((100 + 20) / 100);
    return number_format(($amount / $vatCalc), 2);
}

function calcVAT($amount = 0)
{
    $vatCalc = ((100 + 20) / 100);
    return number_format(round($amount - ($amount / $vatCalc), 2, PHP_ROUND_HALF_DOWN), 2);
}

function addVAT($amount = 0)
{
    $amount = $amount + (($amount / 100) * 20);
    return number_format($amount, 2);
}

require_once('../../config/config.php');
require_once('../../admin/patchworks.php');
require_once("../../admin/system/classes/places.cls.php");
require_once("../../admin/products/classes/product_types.cls.php");
require_once("../../admin/products/classes/products.cls.php");
require_once("../../admin/gallery/classes/uploads.cls.php");
require_once("../../admin/system/classes/related.cls.php");
require_once("../../admin/ecommerce/classes/delivery.cls.php");
//require_once("../admin/ecommerce/classes/deliveryextra.cls.php");
require_once("../../admin/ecommerce/classes/discounts.cls.php");
require_once("../../admin/ecommerce/classes/order.cls.php");
require_once("../../admin/ecommerce/classes/orderline.cls.php");

require_once("../../admin/ecommerce/classes/ecommprop.cls.php");

$TmpEco = new EcoDAO();
$ecoProp = $TmpEco->select(true);

//
// SEND CONFIRMATION EMAIL
//

$TmpOrd = new OrdDAO();
$TmpOln = new OlnDAO();
//$MsgDao = new MsgDAO();

$order = $TmpOrd->selectByAltRef($_POST['MC_altref'], true);

if (isset($order) && $_POST['authMode'] == 'A') {

    $order->sta_id = 20;
    $TmpOrd->update($order);

    $orderlines = $TmpOln->select($order->ord_id, NULL, false);

    $TmpDel = new DelDAO();
    $delivery = $TmpDel->select($order->del_id, NULL, NULL, true);

    $mailto = $order->emaadr;

    if (isset($_POST['emaadr'])) $mailto = $_POST['emaadr'];

    $subject = "ORDER CONFIRMATION";
    $message = "ORDER DETAILS:";

    $FrmEma = $patchworks->adminEmail;

    $body = '';

    $body .= '<table cellspacing="0" cellpadding="10" border="0" align="center" style="font-family: arial, helvetica, sans-serif; font-size: 14px; width: 900px;">';
    $body .= '<tr>';
    $body .= '<td align="left" width="50%">';

    $body .= '<h4>'.$ecoProp->comnam.'</h4>';
    $body .= '<p>'.$ecoProp->adr1.'<br>';
    $body .= ''.$ecoProp->adr2.'<br>';
    $body .= ''.$ecoProp->adr3.'<br>';
    $body .= ''.$ecoProp->adr4.'<br>';
    $body .= ''.$ecoProp->pstcod.'</p>';

    $body .= '<p>'.$ecoProp->comtel.'<br>';
    $body .= 'E-mail: '.$ecoProp->emaadr.'</p>';

    $body .= '</td>';
    $body .= '<td align="left" width="50%" style="text-align: right">';

    $body .= '<img src="' . $patchworks->webRoot . '/pages/img/logo-small.jpg" width="260">';
    $body .= '<h2>RECEIPT</h2>';
    $body .= '<p> Invoice No: ' . str_pad($order->ord_id, 8, '0', STR_PAD_LEFT);
    $body .= '<br> Date: ' . date("jS M Y", strtotime($order->invdat));

    $body .= '</td>';
    $body .= '</tr>';


    $body .= '<tr>';
    $body .= '<td width="50%" valign="top">';

    $body .= '<h4>TO:</h4>';
    $body .= '<p>';
    $body .= '<b>' . $order->cusnam . '</b>';
    $body .= '</p>';
    $body .= '<p>' . $order->payadr1 . '<br />';
    $body .= (!empty($order->payadr2)) ? $order->payadr2 . '<br />' : '';
    $body .= (!empty($order->payadr3)) ? $order->payadr3 . '<br />' : '';
    $body .= (!empty($order->payadr4)) ? $order->payadr4 . '<br />' : '';
    $body .= $order->paypstcod;
    $body .= '</p>';

    //$body .= '<p>'.$order->paytrm.'</p>';

    $body .= '</td>';
    $body .= '<td width="50%" valign="top">';

    $body .= '<h4>SHIP TO:</h4>';
    $body .= '<p>';
    $body .= '<b>' . $order->cusnam . '</b>';
    $body .= '</p>';
    $body .= '<p>' . $order->adr1 . '<br />';
    $body .= (!empty($order->adr2)) ? $order->adr2 . '<br />' : '';
    $body .= (!empty($order->adr3)) ? $order->adr3 . '<br />' : '';
    $body .= (!empty($order->adr4)) ? $order->adr4 . '<br />' : '';
    $body .= $order->pstcod;
    $body .= '</p>';

    $body .= '</td>';
    $body .= '</tr>';
    $body .= '</table>';

//    $body .= '<table cellspacing="0" cellpadding="10" border="0" align="center" style="font-family: arial, helvetica, sans-serif; font-size: 14px; width: 900px; padding-top: 50px;">';
//    $body .= '<tr>';
//    $body .= '<td align="center">';
//    $body .= '<h3>FOR ALL YOUR FUTURE CLIPPING NEEDS PLEASE<br>VISIT US AT MASTERCLIP.CO.UK</h3>';
//    $body .= '</td>';
//    $body .= '</tr>';
//    $body .= '</table>';

    $body .= '<table cellspacing="0" cellpadding="3" border="0" align="center" style="font-family: arial, helvetica, sans-serif; font-size: 14px; width: 900px; padding-top: 50px;">';

    $body .= '<tr>';
    $body .= '<td align="left" style="border-bottom: solid 1px #666;"> <b>Qty</b> </td>';
    $body .= '<td align="left" style="border-bottom: solid 1px #666;"> <b>Description</b> </td>';
    $body .= '<td align="left" style="border-bottom: solid 1px #666; text-align: right;"> <b>Price</b> </td>';
    $body .= '<td align="left" style="border-bottom: solid 1px #666; text-align: right;"> <b>VAT</b> </td>';
    $body .= '<td align="right" style="border-bottom: solid 1px #666; text-align: right;"> <b>Total</b> </td>';
    $body .= '</tr>';

    $orderTotal = 0;

    $tableLength = count($orderlines);
    for ($i = 0; $i < $tableLength; ++$i) {

        $orderTotal = $orderTotal + $orderlines[$i]['unipri'] * $orderlines[$i]['numuni'];

        $clscol = '';
        if ($i % 2 == 0) {
            $clscol = '#ffffff';
        }

        $body .= '<tr style="background: ' . $clscol . '">';
        $body .= '<td width="50"> ' . $orderlines[$i]['numuni'] . '</td>';
        $body .= '<td>' . $orderlines[$i]['olndsc'] . '</td>';
        $body .= '<td width="80" align="right"> &pound;' . withoutVAT($orderlines[$i]['unipri']) . '</td>';
        $body .= '<td width="80" align="right"> &pound;' . calcVAT($orderlines[$i]['unipri']) . '</td>';
        $body .= '<td width="80" align="right"> &pound;' . number_format($orderlines[$i]['unipri'] * $orderlines[$i]['numuni'], 2) . '</td>';
        $body .= '</tr>';

    }

    if (isset($delivery->delnam)) {

        $body .= '<tr>';
        $body .= '<td colspan="1" >';
        $body .= '<td colspan="1"> ' . $delivery->delnam . ' </td>';
        $body .= '<td align="right"> &pound;' . withoutVAT($delivery->delpri) . '</td>';
        $body .= '<td align="right"> &pound;' . calcVAT($delivery->delpri) . '</td>';
        $body .= '<td align="right"> &pound;' . number_format($delivery->delpri, 2) . '</td>';
        $body .= '</tr>';

        $orderTotal += $delivery->delpri;

    }

    $body .= '<tr class="orderTotals">';
    $body .= '<td colspan="1" style="border-top: solid 1px #666;">';
    $body .= '<td colspan="3" style="border-top: solid 1px #666;" align="right"> SUB TOTAL </td>';
    $body .= '<td align="right" style="border-top: solid 1px #666;"> &pound;' . withoutVAT($orderTotal) . '</td>';
    $body .= '</tr>';


    $body .= '<tr>';
    $body .= '<td colspan="3">';
    $body .= '<td colspan="1" align="right"> VAT </td>';
    //$body .= '<td align="right"> &pound;' . number_format((($orderTotal / 100) * $order->vatrat) + $orderTotal, 2) . '</td>';
    $body .= '<td align="right"> &pound;' . calcVAT($orderTotal) . '</td>';
    $body .= '</tr>';

    $body .= '<tr>';
    $body .= '<td colspan="3">';
    $body .= '<td colspan="1" align="right"> <b>TOTAL</b> </td>';
    $body .= '<td align="right"> <b>&pound;' . number_format($orderTotal, 2) . '</b></td>';
    $body .= '</tr>';
    $body .= '</table>';

    $_SESSION['confirmationAmount'] = number_format($orderTotal, 2);

//        $body .= '<table cellspacing="0" cellpadding="3" border="0" align="center" style="font-family: arial, helvetica, sans-serif; font-size: 14px; width: 900px; padding-top: 50px;">';
//        $body .= '<tr>';
//        $body .= '<td align="left" width="50%">';
//        $body .= 'VAT no 866 2965 75';
//        $body .= '</td>';
//        $body .= '<td align="right" width="50%">';
//        $body .= 'Company Number: 5465712';
//        $body .= '</td>';
//        $body .= '</tr>';
//        $body .= '</table>';

    //die($body);

    $headers = "From: " . $FrmEma . "\r\n";
    $headers .= "Reply-To: " . $FrmEma . "\r\n";

    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    $message = '<html><body>';

    $message .= $body;

    $message .= "</body></html>";

    $sendOK = mail($mailto, 'WEBSITE ORDER', $message, $headers);

    if (!$sendOK) {

//        $MsgObj = new stdClass();
//        $MsgObj->msg_id = 0;
//        $MsgObj->sta_id = 0;
//        $MsgObj->tblnam = 'WARNING';
//        $MsgObj->tbl_id = 0;
//        $MsgObj->atr_id = 0;
//        $MsgObj->msgttl = 'Website Contact Email Error';
//        $MsgObj->msgtxt = date("jS M Y H:i");
//        $Msg_ID = $MsgDao->update($MsgObj);

    }

}

if (isset($order) && $_POST['authMode'] == 'A') {

}

?>



<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="favicon.ico" type="image/x-icon"/>

    <title>Shopping Basket</title>

    <base href="<?php echo $patchworks->webRoot; ?>"/>

    <link href='http://fonts.googleapis.com/css?family=Lato:400,300,100,700,900' rel='stylesheet' type='text/css'>

    <link rel="stylesheet" type="text/css" href="pages/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="pages/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="pages/css/styles.css">

</head>

<body>


<div class="section" id="header">

    <a href="#" id="mobilemenubtn"></a>

    <div class="container">
        <div class="row">
            <div class="col-sm-12">

                <a href="<?php echo $patchworks->webRoot; ?>" id="logo"></a>

            </div>
        </div>
    </div>
</div>


<div class="section" id="mainmenu">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">

            </div>
        </div>
    </div>
</div>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">

                <h1>Order Complete</h1>
                <p>Thank you for your order.</p>

                <p>Click <a href="<?php echo $patchworks->webRoot; ?>">here</a> to return to <?php echo $ecoProp->comnam; ?></p>

                <p><em><strong>INFO: </strong>Purchased items may remain in your basket on the <?php echo $ecoProp->comnam; ?> website..</em></p>

            </div>
        </div>
    </div>
</div>

<div class="section" id="footer">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">

                <p>&copy; <?php echo $ecoProp->comnam; ?> Ltd <?php echo date("Y"); ?></p>

            </div>
            <div class="col-sm-6 text-right">

                <p>Website Design by <a href="http://seventy9.co.uk" target="_blank">Seventy9</a></p>

            </div>
        </div>
    </div>
</div>
</body>

</html>
