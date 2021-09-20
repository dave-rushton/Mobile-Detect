<?php


//die($_GET['tmp_id']);

require_once('../../config/config.php');
require_once('../../admin/patchworks.php');

require_once("../../admin/attributes/classes/attrgroups.cls.php");
require_once("../../admin/attributes/classes/attrlabels.cls.php");
require_once("../../admin/attributes/classes/attrvalues.cls.php");

require_once("../../admin/ecommerce/classes/product_types.cls.php");
require_once("../../admin/ecommerce/classes/products.cls.php");

require_once("../../admin/ecommerce/classes/order.cls.php");
require_once("../../admin/ecommerce/classes/orderline.cls.php");

require_once("../../admin/products/classes/discounts.cls.php");

require_once("../../admin/ecommerce/classes/delivery.cls.php");

require_once("../../admin/system/classes/messages.cls.php");

require_once("../../admin/ecommerce/classes/vat.cls.php");
require_once("../../admin/system/classes/tempobject.cls.php");



function withoutVAT($amount = 0) {
    $vatCalc = ((100 + 20) / 100);
    return number_format(($amount / $vatCalc),2);
}

function calcVAT($amount = 0) {
    $vatCalc = ((100 + 20) / 100);
    return number_format(round($amount - ($amount / $vatCalc),2, PHP_ROUND_HALF_DOWN),2);
}

function addVAT($amount = 0) {
    $amount = $amount + (($amount / 100) * 20);
    return number_format($amount,2);
}



function correctDiscount($Ord_ID=NULL) {

    if (!is_numeric($Ord_ID)) return false;

    $TmpOrd = new OrdDAO();
    $TmpDis = new DisDAO();
    $OlnDao = new OlnDAO();

    $orders = $TmpOrd->select($Ord_ID , NULL, NULL, NULL, false);

    $tableLength = count($orders);
    for ($i=0;$i<$tableLength;++$i) {

        if (!empty($orders[$i]['discod'])) {

            $orderlines = $OlnDao->select($orders[$i]['ord_id'], NULL, false);

            $discountRec = $TmpDis->selectByCode($orders[$i]['discod'], true);

        } else {
            continue;
        }

        // determine if line or basket based

        $basketAmount = 0;

        for ($l=0;$l<count($orderlines);$l++) {

            $basketAmount = $basketAmount + ($orderlines[$l]['numuni'] * $orderlines[$l]['unipri']);

        }

        if (!$discountRec) continue;

        if (
            $discountRec->sub_id > 0 ||
            $discountRec->atr_id > 0 ||
            $discountRec->prt_id > 0 ||
            $discountRec->prd_id != ''
        ) {

            // BASKET ITEM DISCOUNT

            for ($l=0;$l<count($orderlines);$l++) {

                $applyLineDiscount = false;

                if ($discountRec->sub_id > 0 && $discountRec->sub_id == $orderlines[$l]['sub_id']) {
                    $applyLineDiscount = true;
                }
                if ($discountRec->atr_id > 0 && $discountRec->atr_id == $orderlines[$l]['atr_id']) {
                    $applyLineDiscount = true;
                }
                if ($discountRec->prt_id > 0 && $discountRec->prt_id == $orderlines[$l]['prt_id']) {
                    $applyLineDiscount = true;
                }

                $prdArr = explode(",",$discountRec->prd_id);
                if (in_array($orderlines[$l]['prd_id'], $prdArr)) {
                    $applyLineDiscount = true;
                }

                //
                // DISCOUNT MATCHES PRODUCT
                //

                if ($applyLineDiscount == true) {

                    // GET ORIGINAL PRICE FROM PRODUCT
                    // APPLY DISCOUNT TO ORDER LINE

                    echo 'ORDER: '.$orders[$i]['ord_id'].' ORDER LINE ID: '.$orderlines[$l]['oln_id'].' DISCOUNT: '.$discountRec->disnam.'<br>';

                }

            }

        }
        else {

            // BASKET DISCOUNT

            //
            // CREATE NEW DISCOUNT LINE WITH NO PRODUCT
            //

            echo 'ORDER: '.$orders[$i]['ord_id'].' TOTAL: '.$basketAmount.' DISCOUNT: '.$discountRec->disnam.'<br>';

            if ($discountRec->pctamt == 'A') {

                $discountAmount = number_format($discountRec->disamt, 2, '.', '');

                echo '-&pound;' . number_format($discountRec->disamt, 2, '.', '').'<br>';
            } else {

                $discountAmount =number_format((($basketAmount / 100) * $discountRec->disamt), 2, '.', '');

                echo '-&pound;' . number_format((($basketAmount / 100) * $discountRec->disamt), 2, '.', '').'<br>';
            }

            $discountAmount = $discountAmount * -1;

            $OlnObj = new stdClass();
            $OlnObj->oln_id = 0;
            $OlnObj->ord_id = $orders[$i]['ord_id'];
            $OlnObj->prd_id = 0;
            $OlnObj->numuni = 1;
            $OlnObj->unipri = $discountAmount;
            $OlnObj->vatrat = 20;
            $OlnObj->olndsc = $discountRec->disnam;
            $OlnObj->tblnam = 'DISCOUNT';
            $OlnObj->tbl_id = 0;
            $OlnObj->sta_id = 0;

            $OlnDao->update($OlnObj);


        }

    }

}




$TmpVat = new VatDAO();
$vatRecord = $TmpVat->select(NULL, date("Y-m-d"), NULL, true);

$TmpObj = new TmpDAO();
$object = $TmpObj->select($_GET['tmp_id'], NULL, NULL, true);
$shoppingCart = json_decode($object->tmpobj, true);

die(var_dump($shoppingCart));

$totalAmount = 0;

//
// Create Order
//

$OrdDao = new OrdDAO();
$OlnDao = new OlnDAO();

$OrdObj = new stdClass();

$OrdObj->ord_id = 0;
$OrdObj->ordtyp = 0;
$OrdObj->invdat = date('Y-m-d H:i:s');
$OrdObj->duedat = date('Y-m-d H:i:s');
$OrdObj->paydat = date('Y-m-d H:i:s');
$OrdObj->cusnam = $shoppingCart['customer']['cusfna'].' '.$shoppingCart['customer']['cussna'];
$OrdObj->adr1 = $shoppingCart['customer']['adr1'];
$OrdObj->adr2 = $shoppingCart['customer']['adr2'];
$OrdObj->adr3 = $shoppingCart['customer']['adr3'];
$OrdObj->adr4 = $shoppingCart['customer']['adr4'];
$OrdObj->pstcod = $shoppingCart['customer']['pstcod'];
$OrdObj->payadr1 = $shoppingCart['customer']['payadr1'];
$OrdObj->payadr2 = $shoppingCart['customer']['payadr2'];
$OrdObj->payadr3 = $shoppingCart['customer']['payadr3'];
$OrdObj->payadr4 = $shoppingCart['customer']['payadr4'];
$OrdObj->paypstcod = $shoppingCart['customer']['paypstcod'];
$OrdObj->paytrm = 'TEL: '.$shoppingCart['customer']['custel'].' MOB: '.$shoppingCart['customer']['cusmob'];
$OrdObj->vatrat = 0;
$OrdObj->tblnam = 'CUS';
$OrdObj->tbl_id = 0;
$OrdObj->sta_id = 20; // PAID STATUS
$OrdObj->altref = '';
$OrdObj->altnam = '';
$OrdObj->del_id = $shoppingCart['del_id'];
$OrdObj->discod = (isset($shoppingCart['discod'])) ? $shoppingCart['discod'] : '';
$OrdObj->emaadr = $shoppingCart['customer']['cusema'];

$Ord_ID = $OrdDao->update($OrdObj);

$_SESSION['confirmationOrderID'] = $Ord_ID;

unset($_SESSION['ordertoken']);

if (is_array($shoppingCart['items'])) {
    for ($i=0;$i<count($shoppingCart['items']);$i++) {

        //
        // Create Order Lines
        //

        $lineDescription = $shoppingCart['items'][$i]['prdnam'];
        $lineAmount = $shoppingCart['items'][$i]['unipri'];
        $discountSign = '�';

        if (isset($shoppingCart['items'][$i]['discod'])) {

            if ( $shoppingCart['items'][$i]['pctamt'] == 'A' ) {

                $lineAmount -= $shoppingCart['items'][$i]['disamt'];

                $lineDescription .= ' (�'.$shoppingCart['items'][$i]['disamt'].' discount)';

            } else {

                $lineAmount -= ($lineAmount / 100) * $shoppingCart['items'][$i]['disamt'];

                $lineDescription .= ' ('.$shoppingCart['items'][$i]['disamt'].'% discount)';

            }

        }


        $OlnObj = new stdClass();
        $OlnObj->oln_id = 0;
        $OlnObj->ord_id = $Ord_ID;
        $OlnObj->prd_id = $shoppingCart['items'][$i]['prd_id'];
        $OlnObj->numuni = $shoppingCart['items'][$i]['qty'];
        $OlnObj->unipri = $lineAmount; // $shoppingCart['items'][$i]['totamt'];

        //
        // FIND VAT RATE
        //

        $vatRat = 0;
        if (isset($vatRecord->vatrat)) { $vatRat = $vatRecord->vatrat; }

        $OlnObj->vatrat = $vatRat;
        $OlnObj->olndsc = $lineDescription;
        $OlnObj->tblnam = 'SALE';
        $OlnObj->tbl_id = 0;
        $OlnObj->sta_id = 0;

        $OlnDao->update($OlnObj);


        //$emailProducts .= '<tr><td></td><td></td></tr>';


    }
}


//
// CORRECT DISCOUNT
//


correctDiscount($Ord_ID);


//
// SEND CONFIRMATION EMAIL
//

$TmpOrd = new OrdDAO();
$TmpOln = new OlnDAO();
$MsgDao = new MsgDAO();

$order = $TmpOrd->select($Ord_ID, NULL, NULL, NULL, true);

if (isset($order) && 1 == 2) {

    $orderlines = $TmpOln->select($Ord_ID, NULL, false);

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

    $body .= '<h4>Outlandish Items Ltd</h4>';
    $body .= '<p>Carlton Grange Farm<br>';
    $body .= 'Three Gates Road<br>';
    $body .= 'Carlton Curlieu<br>';
    $body .= 'Leicestershire<br>';
    $body .= 'LE8 0PQ</p>';

//        $body .= '<h4>Outlandish Items Ltd</h4>';
//        $body .= '<p>PO Box 9173<br>';
//        $body .= 'Carlton Curlieu<br>';
//        $body .= 'Leicestershire<br>';
//        $body .= 'LE8 0SW</p>';

    $body .= '<p>Tel: 0116 279 6900<br>';
    $body .= 'E-mail: info@masterclip.co.uk</p>';

    $body .= '</td>';
    $body .= '<td align="left" width="50%" style="text-align: right">';

    $body .= '<img src="' . $patchworks->webRoot . '/pages/images/logo.png" width="260" height="60">';
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

    $body .= '<table cellspacing="0" cellpadding="10" border="0" align="center" style="font-family: arial, helvetica, sans-serif; font-size: 14px; width: 900px; padding-top: 50px;">';
    $body .= '<tr>';
    $body .= '<td align="center">';
    $body .= '<h3>FOR ALL YOUR FUTURE CLIPPING NEEDS PLEASE<br>VISIT US AT MASTERCLIP.CO.UK</h3>';
    $body .= '</td>';
    $body .= '</tr>';
    $body .= '</table>';

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
    $body .= '<td align="right"> <b>&pound;' . number_format((($orderTotal / 100) * $order->vatrat) + $orderTotal, 2) . '</b></td>';
    $body .= '</tr>';
    $body .= '</table>';

    $_SESSION['confirmationAmount'] = number_format((($orderTotal / 100) * $order->vatrat) + $orderTotal, 2);


    $body .= '<table cellspacing="0" cellpadding="3" border="0" align="center" style="font-family: arial, helvetica, sans-serif; font-size: 14px; width: 900px; padding-top: 50px;">';
    $body .= '<tr>';
    $body .= '<td align="left" width="50%">';
    $body .= 'VAT no 866 2965 75';
    $body .= '</td>';
    $body .= '<td align="right" width="50%">';
    $body .= 'Company Number: 5465712';
    $body .= '</td>';
    $body .= '</tr>';
    $body .= '</table>';

    //die($body);

    $headers = "From: " . $FrmEma . "\r\n";
    $headers .= "Reply-To: " . $FrmEma . "\r\n";

    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    $message = '<html><body>';

    $message .= $body;

    $message .= "</body></html>";

    $sendOK = mail($mailto, 'MASTERCLIP WEBSITE ORDER', $message, $headers);

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

    $sendOK = mail('anna@masterclip.co.uk', 'MASTERCLIP WEBSITE ORDER', $message, $headers);

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

}

header('location: ../ecommerce/order-edit.php?ord_id='.$Ord_ID);
exit();

?>