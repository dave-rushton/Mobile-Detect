<?php


require_once('../../../config/config.php');
require_once('../../patchworks.php');
require_once("../../system/classes/places.cls.php");
require_once("../../ecommerce/classes/products.cls.php");
require_once("../../ecommerce/classes/order.cls.php");
require_once("../../ecommerce/classes/orderline.cls.php");
require_once("../../ecommerce/classes/delivery.cls.php");


$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
//if ($loggedIn == 0) die();


$TmpPla = new PlaDAO();
$customers = $TmpPla->select(NULL, 'CUS', NULL, NULL);

$TmpPrd = new PrdDAO();
$products = $TmpPrd->select(NULL, NULL, NULL, NULL, false);

$Ord_ID = (isset($_GET['ord_id']) && is_numeric($_GET['ord_id'])) ? $_GET['ord_id'] : 10;

$TmpOrd = new OrdDAO();
$TmpOln = new OlnDAO();

if (is_numeric($Ord_ID)) {
    $order = $TmpOrd->select($Ord_ID, NULL, NULL, NULL, true);
    $orderlines = $TmpOln->select($Ord_ID, NULL, false);

    $TmpDel = new DelDAO();
    $delivery = $TmpDel->select($order->del_id, NULL, NULL, true);

}


$mailto = $patchworks->adminEmail;
$subject = "ORDER CONFIRMATION";
$message = "ORDER DETAILS:";



if (isset($_REQUEST['contactEmail'])) {
    $FrmEma = $_REQUEST['contactEmail'];
} else {
    $FrmEma = $mailto;
}

$body = '';

$body .= '<table cellspacing="0" cellpadding="0" border="0" width="100%"  align="center" style="font-family: arial, helvetica, sans-serif;">';
$body .= '<tr>';
$body .= '<td align="center" width="100%">';
$body .= '<img src="'.$patchworks->webRoot.'/pages/img/logo.jpg" width="491" height="51">';
$body .= '</td>';
$body .= '</tr>';
$body .= '<tr>';
$body .= '<td align="center" width="100%">';
$body .= '<p style="font-size:14px; padding: 20px 0;">Fastline Group Ltd.<br>';
$body .= '8 Riley Road Henson Way Telford Way Industrial Estate Kettering Northamptonshire NN16 8NN<br>';
$body .= 'TEL: 01604 755321</p>';
$body .= '</td>';
$body .= '</tr>';
$body .= '<tr>';
$body .= '<td align="center" width="100%">';
$body .= '<h1>INVOICE</h1>';
$body .= '</td>';
$body .= '</tr>';
$body .= '</table>';
$body .= '<table cellspacing="0" cellpadding="10" border="0" width="100%"  align="center">';
$body .= '<tr>';
$body .= '<td align="left" width="50%">';
$body .= '<p>';
$body .= '<b>'.$order->planam.'</b>';
$body .= '</p>';
$body .= '<p>'.$order->adr1.'<br />';
$body .= $order->adr2.'<br />';
$body .= $order->adr3.'<br />';
$body .= $order->adr4.'<br />';
$body .= $order->pstcod.'</p>';
$body .= '</td>';
$body .= '<td align="right" width="50%">';
$body .= '<p> Invoice No: '.str_pad($order->ord_id, 8, '0', STR_PAD_LEFT);
$body .= '<br> Invoice Date: '.date("jS M Y", strtotime($order->invdat));
$body .= '<br> Due Date: '.date("jS M Y", strtotime($order->duedat)).'</p>';
$body .= '</td>';
$body .= '</tr>';
$body .= '</table>';
$body .= '<table cellspacing="0" cellpadding="10" border="0" width="100%"  align="center">';
$body .= '<tr>';
$body .= '<td colspan="5" height="20"></td>';
$body .= '</tr>';
$body .= '<tr>';
$body .= '<td align="left" style="border-bottom: solid 1px #666;"> <b>Product</b> </td>';
$body .= '<td align="left" style="border-bottom: solid 1px #666;"> <b>Description</b> </td>';
$body .= '<td align="left" style="border-bottom: solid 1px #666;"> <b>Price</b> </td>';
$body .= '<td align="left" style="border-bottom: solid 1px #666;"> <b>Qty</b> </td>';
$body .= '<td align="right" style="border-bottom: solid 1px #666;"> <b>Total</b> </td>';
$body .= '</tr>';

$orderTotal = 0;

$tableLength = count($orderlines);
for ($i=0;$i<$tableLength;++$i) {

    $orderTotal = $orderTotal + $orderlines[$i]['unipri'] * $orderlines[$i]['numuni'];

    $clscol = '';
    if ($i % 2 == 0) {
        $clscol = '#ccc';
    }


    $body .= '<tr style="background: '.$clscol.'">';
    $body .= '<td>'.$orderlines[$i]['prdnam'].'</td>';
    $body .= '<td>'.$orderlines[$i]['olndsc'].'</td>';
    $body .= '<td width="80"> &pound;'.$orderlines[$i]['unipri'].'</td>';
    $body .= '<td width="50"> '.$orderlines[$i]['numuni'].'</td>';
    $body .= '<td width="80" align="right"> &pound;'.number_format($orderlines[$i]['unipri'] * $orderlines[$i]['numuni'],2).'</td>';
    $body .= '</tr>';

}

$body .= '<tr class="orderTotals">';
$body .= '<td colspan="2" style="border-top: solid 1px #666;">';
$body .= '<td colspan="2" style="border-top: solid 1px #666;"> SUB TOTAL </td>';
$body .= '<td align="right" style="border-top: solid 1px #666;"> &pound;'.number_format($orderTotal, 2).'</td>';
$body .= '</tr>';

$body .= '<tr>';
$body .= '<td colspan="2" style="border-top: solid 1px #666;">';
$body .= '<td colspan="2" style="border-top: solid 1px #666;"> '.$delivery->delnam.' </td>';
$body .= '<td align="right" style="border-top: solid 1px #666;"> &pound;'.number_format($delivery->delpri, 2).'</td>';
$body .= '</tr>';

$orderTotal += $delivery->delpri;

$body .= '<tr>';
$body .= '<td colspan="2">';
$body .= '<td colspan="2"> <b>TOTAL</b> </td>';
$body .= '<td align="right"> <b>&pound;'.number_format( (($orderTotal / 100) * $order->vatrat) + $orderTotal, 2).'</b></td>';
$body .= '</tr>';
$body .= '</table>';


//die($body);


$headers = "From: " . $FrmEma . "\r\n";
$headers .= "Reply-To: " . $FrmEma . "\r\n";
//$headers .= "CC: iainkdoughty@googlemail.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

$message = '<html><body>';
//$message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
$message .= $body;
//$message .= "</table>";
$message .= "</body></html>";

$sendOK = mail($mailto, 'WEBSITE ORDER', $message, $headers);

if (!$sendOK) {

    $MsgObj = new stdClass();
    $MsgObj->msg_id = 0;
    $MsgObj->sta_id = 0;
    $MsgObj->tblnam = 'WARNING';
    $MsgObj->tbl_id = 0;
    $MsgObj->atr_id = 0;
    $MsgObj->msgttl = 'Website Contact Email Error';
    $MsgObj->msgtxt = date("jS M Y H:i");
    $Msg_ID = $MsgDao->update($MsgObj);

}



//mail($mailto, $subject, stripslashes($message), $header);

//header('location: thank-you');

?>

