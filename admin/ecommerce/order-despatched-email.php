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

$Ord_ID = (isset($_POST['ord_id']) && is_numeric($_POST['ord_id'])) ? $_POST['ord_id'] : NULL;

$order = $TmpOrd->select($Ord_ID, NULL, NULL, NULL, true);

if (isset($order)) {

    $order->sta_id = 20;
    $TmpOrd->update($order);

    $orderlines = $TmpOln->select($order->ord_id, NULL, false);

    $TmpDel = new DelDAO();
    $delivery = $TmpDel->select($order->del_id, NULL, NULL, true);

    $mailto = $order->emaadr;

    if (isset($_POST['emaadr'])) $mailto = $_POST['emaadr'];

    $subject = "YOUR ORDER HAS BEEN DESPATCHED";
    $message = "ORDER DETAILS:";

    $FrmEma = $patchworks->adminEmail;

    $body = '';

    $body .= '<table cellspacing="0" cellpadding="10" border="0" align="center" style="font-family: arial, helvetica, sans-serif; font-size: 14px; width: 900px;">';
    $body .= '<tr>';
    $body .= '<td align="left" width="100%">';

    $body .= '<h2>Order Despatched</h2><hr>';
    
    $body .= '<h4>Dear '.$order->cusnam.'</h4>';
    $body .= '<p>This is an email to confirm that your order <strong>'.$order->ord_id.'</strong> has been despatched</p>';
    $body .= '<p>Thank you for shopping with Hamilton Turnberry.</p>';

    $body .= '<p>Please enter your tracking code: <strong>'.$_POST['trkcod'].'</strong> on the following website: <a href="'.$_POST['trkurl'].'">'.$_POST['trkurl'].'</a></p>';

    $body .= '<p>If you have any questions regarding your order, delivery details or items purchased, please contact our customer service team directly via details on our website <a href="http://www.hamiltonturnberry.com">Hamilton Turnberry</a>.</p>';

    $body .= '</td>';
    $body .= '</tr>';

    $body .= '</table>';





    $headers = "From: " . $FrmEma . "\r\n";
    $headers .= "Reply-To: " . $FrmEma . "\r\n";

    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    $message = '<html><body>';

    $message .= $body;

    $message .= "</body></html>";

    $sendOK = mail($mailto, 'WEBSITE ORDER DESPATCHED', $message, $headers);

    if (!$sendOK) {


    }

}


?>