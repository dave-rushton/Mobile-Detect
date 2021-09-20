<?php

function randomString($length = 6) {
    $str = "";
    //$characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
    $characters = array_merge(range('A','Z'), range('0','9'));
    $max = count($characters) - 1;
    for ($i = 0; $i < $length; $i++) {
        $rand = mt_rand(0, $max);
        $str .= $characters[$rand];
    }
    return $str;
}


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

require_once("../../../config/config.php");
require_once("../../../admin/patchworks.php");
//require_once("../../../admin/ecommerce/classes/order.cls.php");
//require_once("../../../admin/ecommerce/classes/orderline.cls.php");
//require_once("../../../admin/ecommerce/classes/vat.cls.php");
require_once("../../../admin/events/classes/bookings.cls.php");
require_once("../../../admin/system/classes/places.cls.php");
require_once("../../../admin/system/classes/messages.cls.php");
//require_once("../../../admin/products/classes/products.cls.php");

$TmpBoo = new BooDAO();
$TmpPla = new PlaDAO();
$TmpBoo = new BooDAO();
//$TmpPrd = new PrdDAO();
//$OrdDao = new OrdDAO();
//$OlnDao = new OlnDAO();
$MsgDao = new MsgDAO();

$bookingDetail = (isset($_SESSION['bookingdetail'])) ? json_decode($_SESSION['bookingdetail'], true) : array();


$stepNo = (isset($_REQUEST['stepno']) && is_numeric($_REQUEST['stepno'])) ? $_REQUEST['stepno'] : 1;



if ($stepNo == 1) {

    $billingAddress = array();
    $billingAddress['adr_id'] = 0;
    $billingAddress['title'] = $_POST['custtl'];
    $billingAddress['firstname'] = $_POST['cusfna'];
    $billingAddress['surname'] = $_POST['cussna'];
    $billingAddress['adr1'] = $_POST['adr1'];
    $billingAddress['adr2'] = $_POST['adr2'];
    $billingAddress['adr3'] = $_POST['adr3'];
    $billingAddress['adr4'] = $_POST['adr4'];
    $billingAddress['pstcod'] = $_POST['pstcod'];

    $billingAddress['cusema'] = $_POST['emailaddress'];
    $billingAddress['custel'] = $_POST['telephone'];

    $billingAddress['boo_id'] = $_POST['boo_id'];
    //$billingAddress['prd_id'] = $_POST['prd_id'];
    //$billingAddress['numuni'] = $_POST['numuni'];
    $billingAddress['fwdurl'] = $_POST['fwdurl'];

    $bookingDetail['billingaddress'] = $billingAddress;

    $_SESSION['bookingdetail'] = json_encode($bookingDetail);



    //
    // SEND EMAIL
    //


    $mailto = $patchworks->adminEmail;

    $subject = "Booking Form Submission";
    $message = "Values submitted from web site form:";

    if (isset($_POST['contactEmail'])) {
        $FrmEma = $_POST['contactEmail'];
    } else {
        $FrmEma = $mailto;
    }

    $body = '';

    foreach ($_POST as $key => $value)
    {
        if (!is_array($value))
        {
            $message .= "\n".$key." : ".$value;

            $body .= "<tr><td><strong>".$key."</strong> </td><td>" . $value . "</td></tr>";

        }
        else
        {
            foreach ($_POST[$key] as $itemvalue)
            {
                $message .= "\n".$key." : ".$itemvalue;

                $body .= "<tr><td><strong>".$key."</strong> </td><td>" . $value . "</td></tr>";
            }
        }
    }



    $headers = "From: " . $FrmEma . "\r\n";
    $headers .= "Reply-To: " . $FrmEma . "\r\n";
    //$headers .= "CC: iainkdoughty@googlemail.com\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    $message = '<html><body>';
    $message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
    $message .= $body;
    $message .= "</table>";
    $message .= "</body></html>";


    if (!@mail($mailto, 'Website Contact', $message, $headers)) {

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

    //header('location: '.$patchworks->webRoot.'/'.$_POST['fwdurl'].'/eventbooking/summary/'.$_POST['boo_id']);
    header('location: '.$patchworks->webRoot.'/'.$_POST['fwdurl'].'/eventbooking/success/'.$_POST['boo_id']);
    die();

}


if ($stepNo == 2) {

    //
    // CREATE ORDER FROM SESSION
    //


    // FIND BOOKING

    $bookingDate = $TmpBoo->select($bookingDetail['billingaddress']['boo_id'], NULL, NULL, NULL, NULL, NULL, NULL, NULL, true, NULL, NULL, 'begdat desc');
    $venueRec = $TmpPla->select($bookingDate->ref_id, NULL, NULL, NULL, NULL, true);
    //$productRec = $TmpPrd->select($bookingDate->prd_id, NULL, NULL, NULL, NULL, true, NULL, true, NULL, NULL);
    $eventRec = $TmpPla->select($bookingDate->tbl_id, NULL, NULL, NULL, NULL, true);

    // CREATE ORDER

    //die($bookingDetail['billingaddress']['numuni'].' '.$bookingDetail['billingaddress']['firstname'].' '.$bookingDetail['billingaddress']['surname']);

    $OrdObj = new stdClass();
    $OrdObj->ord_id = 0;
    $OrdObj->ordtyp = 'SALE';
    $OrdObj->invdat = date('Y-m-d');
    $OrdObj->duedat = date('Y-m-d');
    $OrdObj->paydat = date('Y-m-d');
    $OrdObj->cusnam = $bookingDetail['billingaddress']['title'].' '.$bookingDetail['billingaddress']['firstname'].' '.$bookingDetail['billingaddress']['surname'];
    $OrdObj->adr1 = $bookingDetail['billingaddress']['adr1'];
    $OrdObj->adr2 = $bookingDetail['billingaddress']['adr2'];
    $OrdObj->adr3 = $bookingDetail['billingaddress']['adr3'];
    $OrdObj->adr4 = $bookingDetail['billingaddress']['adr4'];
    $OrdObj->pstcod = $bookingDetail['billingaddress']['pstcod'];
    $OrdObj->payadr1 = $bookingDetail['billingaddress']['adr1'];
    $OrdObj->payadr2 = $bookingDetail['billingaddress']['adr2'];
    $OrdObj->payadr3 = $bookingDetail['billingaddress']['adr3'];
    $OrdObj->payadr4 = $bookingDetail['billingaddress']['adr4'];
    $OrdObj->paypstcod = $bookingDetail['billingaddress']['pstcod'];
    $OrdObj->paytrm = $bookingDetail['billingaddress']['custel'];
    $OrdObj->vatrat = 20;
    $OrdObj->tblnam = 'EVENT';
    $OrdObj->tbl_id = $bookingDetail['billingaddress']['boo_id'];
    $OrdObj->sta_id = 10;
    $OrdObj->altref = '';
    $OrdObj->altnam = '';
    $OrdObj->del_id = 0;
    $OrdObj->discod = '';
    $OrdObj->emaadr = $bookingDetail['billingaddress']['cusema'];

    $Ord_ID = $OrdDao->update($OrdObj);

    $OlnObj = new stdClass();
    $OlnObj->oln_id = 0;
    $OlnObj->ord_id = $Ord_ID;
    $OlnObj->prd_id = $productRec->prd_id;
    $OlnObj->numuni = $bookingDetail['billingaddress']['numuni'];
    $OlnObj->unipri = $productRec->unipri;
    $OlnObj->vatrat = 20;
    $OlnObj->olndsc = $productRec->prdnam;
    $OlnObj->tblnam = 'EVENT';
    $OlnObj->tbl_id = $bookingDetail['billingaddress']['boo_id'];
    $OlnObj->sta_id = 0;

    $OlnDao->update($OlnObj);

}


if ($stepNo == 3) {


    echo $bookingDetail['AEDReference'].' '.$bookingDetail['BarclaysReference'];

    //
    // Update Order
    //

    $qryArray = array();
    $sql = "UPDATE orders SET sta_id = 20, altref = :altref, altnam = :altnam WHERE ord_id = :ord_id";
    $qryArray['altref'] = $bookingDetail['BarclaysReference'];
    $qryArray['altnam'] = $bookingDetail['BarclaysReference'];
    $qryArray['ord_id'] = $bookingDetail['AEDReference'];

    try {
        $returnRec = $patchworks->run($sql, $qryArray, false);
    } catch (Exception $e) {}


    // Clear Session



    // Send Email


    $mailto = $bookingDetail['billingaddress']['cusema'];
    $subject = "ORDER CONFIRMATION";
    $message = "ORDER DETAILS:";

    $FrmEma = $patchworks->adminEmail;

    $body = '';

    $body .= '<table cellspacing="0" cellpadding="0" border="0" width="100%"  align="center" style="font-family: arial, helvetica, sans-serif;">';
    $body .= '<tr>';
    $body .= '<td align="center" width="100%">';
    $body .= '<img src="'.$patchworks->webRoot.'/pages/img/logo.png" width="212" height="111">';
    $body .= '</td>';
    $body .= '</tr>';
    $body .= '<tr>';
    $body .= '<td align="center" width="100%">';
    $body .= '<p style="font-size:14px; padding: 20px 0;">AE DRIVER<br>';
    $body .= 'Liliput Rd, Brackmills Industrial Estate, Northampton, Northamptonshire, England, NN4 7DT<br>';
    $body .= 'TEL: 01604 700400</p>';
    $body .= '</td>';
    $body .= '</tr>';
    $body .= '<tr>';
    $body .= '<td align="center" width="100%">';
    $body .= '<h1>BOOKING CONFIRMATION</h1>';
    $body .= '</td>';
    $body .= '</tr>';
    $body .= '</table>';

    $body .= '<table cellspacing="0" cellpadding="10" border="0" width="100%"  align="center">';
    $body .= '<tr>';
    $body .= '<td align="left">';

    $body .= '<h3>Billing Address</h3>';
    $body .= '<p>'.$bookingDetail['billingaddress']['title'].' '.$bookingDetail['billingaddress']['firstname'].' '.$bookingDetail['billingaddress']['surname'].'</p>';
    $body .= '<p>'.$bookingDetail['billingaddress']['adr1'].'<br>'.$bookingDetail['billingaddress']['adr2'].'<br>'.$bookingDetail['billingaddress']['adr3'].'<br>'.$bookingDetail['billingaddress']['adr4'].'<br>'.$bookingDetail['billingaddress']['pstcod'].'</p>';

    $body .= '<h3>Items</h3>';

    for ($i = 0; $i < count($bookingDetail['deliveryitems']); $i++) {

        $body .= '<p><strong>PARCEL: '.($i+1).'</strong></p>';
        $body .= '<p>'.$bookingDetail['deliveryitems'][$i]['weight'].'kg ('.$bookingDetail['deliveryitems'][$i]['dim1'].'x'.$bookingDetail['deliveryitems'][$i]['dim2'].'x'.$bookingDetail['deliveryitems'][$i]['dim3'].')</p>';
    }

    $PaymentAmount = number_format($PaymentAmount,2);


    $vatAmount = (($PaymentAmount / 100) * 20);
    $body .= 'VAT: '.number_format($vatAmount, 2).'<br>';

    $body .= 'TOTAL COST: '.number_format($PaymentAmount + $vatAmount, 2).'<br>';


    $body .= '<h3>Reference</h3>';

    //$body .= '<p>Payment Ref: <strong>'.$bookingDetail['barclays_ref'].'</strong><br>';
    //$body .= '<p>Consignment: <strong>'.$bookingDetail['nd_consignment'].'</strong>';

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

    try {

        $sendOK = mail($mailto, 'ACTION EXPRESS ORDER CONFIRMATION', $message, $headers);

    } catch (Exception $ex) {

    }


    header('location:'.$patchworks->webRoot.$bookingDetail['billingaddress']['fwdurl'].'/eventbooking/success/'.$bookingDetail['billingaddress']['boo_id']);

}
?>