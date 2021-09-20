<?php

require_once('../../config/config.php');
require_once('../../admin/patchworks.php');
require_once("../../admin/ecommerce/classes/order.cls.php");
require_once("../../admin/ecommerce/classes/orderline.cls.php");
require_once("../../admin/ecommerce/classes/ecommprop.cls.php");
require_once("../../pages/shoppingcart/classes/shoppingcart.cls.php");
require_once("../../admin/products/classes/product_types.cls.php");
require_once("../../admin/products/classes/products.cls.php");
require_once("../../admin/system/classes/places.cls.php");

if (isset($_SESSION['loginToken'])) {
    $PlaDao = new PlaDAO();
    $loggedIn = $PlaDao->loggedIn($_SESSION['loginToken']);
} else {
    $loggedIn = NULL;
}

$shoppingcart = new shoppingCart();

//echo '<pre>'.print_r($shoppingcart->shoppingCart).'</pre>';

//$shoppingcart->setDeliveryInstructions( $_REQUEST['delins'] );
//$shoppingcart->setSpecialInstructions( $_REQUEST['spcins'] );


$TmpEco = new EcoDAO();
$ecoProp = $TmpEco->select(true);

$TmpPrd = new PrdDAO();

function randomString($length = 6)
{
    $str = "";
    $characters = array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9'));
    $max = count($characters) - 1;
    for ($i = 0; $i < $length; $i++) {
        $rand = mt_rand(0, $max);
        $str .= $characters[$rand];
    }
    return $str;
}

require_once('../../config/config.php');
require_once('../../admin/patchworks.php');

$orderToken = '';
$totalOrderPrice = 0;

if (!isset($shoppingcart->shoppingCart['orderToken']) || !is_numeric($shoppingcart->shoppingCart['orderToken'])) {

    $Ord_ID = $shoppingcart->convertSessionToOrder(NULL, $loggedIn, 'SAGEPAY');
    $orderToken = $Ord_ID;

} else {

    $Ord_ID = $shoppingcart->convertSessionToOrder($shoppingcart->shoppingCart['orderToken'], $loggedIn, 'SAGEPAY');
    $orderToken = $shoppingcart->shoppingCart['orderToken'];

}

//** Sage Pay Start **/

$strConnectTo = $ecoProp->sp_sta; //"TEST";    //Set to SIMULATOR for the Simulator expert system, TEST for the Test Server and LIVE in the live environment
$strVirtualDir = "SagePayFormKit";    //The URL path to this web application
$strYourSiteFQDN = $patchworks->webRoot;

//$strVendorName="";    /** Set this value to the Vendor Name assigned to you by Sage Pay or chosen when you applied **/
//$strVendorName="gforce";

if ($ecoProp->sp_sta == 'TEST' || $ecoProp->sp_sta == 'SIMULATION') {
    $strVendorName = $ecoProp->sptven;
    $strEncryptionPassword = $ecoProp->sptenc;
} else if ($ecoProp->sp_sta == 'LIVE') {
    $strVendorName = $ecoProp->sp_ven;
    $strEncryptionPassword = $ecoProp->sp_enc;
}

$strCurrency = "GBP";
/** Set this to indicate the currency in which you wish to trade. You will need a merchant number in this currency **/
$strTransactionType = "DEFERRED";
/** This can be PAYMENT, DEFERRED or AUTHENTICATE if your Sage Pay account supports those payment types **/
$strPartnerID = "";
/** Optional setting. If you are a Sage Pay Partner and wish to flag the transactions with your unique partner id set it here. **/
$bSendEMail = 2; //** 0 = Do not send either customer or vendor e-mails, ** 1 = Send customer and vendor e-mails if address(es) are provided(DEFAULT). ** 2 = Send Vendor Email but not Customer Email. If you do not supply this field, 1 is assumed and e-mails are sent if addresses are provided. **/
$strVendorEMail = $patchworks->adminEmail; //$shoppingcart->shoppingCart['customer']['cusema']; //"iainkdoughty@live.co.uk";  // Optional setting. Set this to the mail address which will receive order confirmations and failures
$strEncryptionType = "AES"; // Encryption type should be left set to AES unless you are experiencing problems and have been told by SagePay support to change it - XOR is the only other acceptable value

//$strProtocol = "2.23";
$strProtocol = "3.00";
if ($strConnectTo == "LIVE")
    $strPurchaseURL = "https://live.sagepay.com/gateway/service/vspform-register.vsp";
elseif ($strConnectTo == "TEST")
    $strPurchaseURL = "https://test.sagepay.com/gateway/service/vspform-register.vsp";
else
    $strPurchaseURL = "https://test.sagepay.com/simulator/vspformgateway.asp";

//** Sage Pay End **/

include 'sagepay/sagepay.php';


//$orderToken = randomString(10);


// if card payment forward to sagepay
$strVendorTxCode = $strVendorName . "-" . $orderToken;

$successURL = $patchworks->webRoot . 'ordergateway/success';
$failURL = $patchworks->webRoot . 'checkout/options';

// HARD CODED TO SHOOPING CONTROL -- CONSIDER HOLDING CRYPT KEY ON ORDER RECORD FOR  CROSS REFERENCE
$successURL = $patchworks->webRoot . 'shoppingcart/confirmorder/' . $orderToken;

unset($_SESSION['ordertoken']);
$_SESSION['ordertoken'] = $orderToken;

//
// Build Basket
//tfgg

$totalWeight = 0;
$strBasket = '';

$basketItemCount = 0;

if (isset($shoppingcart->shoppingCart['items']) && is_array($shoppingcart->shoppingCart['items'])) {

    $basketItemCount = count($shoppingcart->shoppingCart['items']);

    for ($i = 0; $i < count($shoppingcart->shoppingCart['items']); $i++) {

        $productRec = $TmpPrd->select($shoppingcart->shoppingCart['items'][$i]['prd_id'], NULL, NULL, NULL, NULL, NULL, NULL, true, NULL, NULL);

        $totalWeight += ($productRec->weight * $shoppingcart->shoppingCart['items'][$i]['qty']);

        $UniPri = 0;
        if (isset($productRec->unipri)) {
            $UniPri = $productRec->unipri;
        }

        $UniPri = $shoppingcart->priceProduct($shoppingcart->shoppingCart['items'][$i]['prd_id'], $shoppingcart->shoppingCart['items'][$i]['qty']);

        $multiPrice = 0; //$shoppingcart->multiBuyPrice($shoppingcart->shoppingCart['items'][$i]['prt_id']);

        if (is_numeric($multiPrice) && $multiPrice > 0) $UniPri = $multiPrice;

        $lineAmount = number_format($UniPri * $shoppingcart->shoppingCart['items'][$i]['qty'], 2, '.', '');

        $unitPrice = $UniPri;

        $totalOrderPrice += number_format($lineAmount, 2);

        $vatAmount = ($unitPrice / 100) * 20;

        $strbasketItem = ':' . $shoppingcart->shoppingCart['items'][$i]['prdnam'];
        $strbasketItem .= ':' . $shoppingcart->shoppingCart['items'][$i]['qty'];

        if ($shoppingcart->shoppingCart['customer']['coucod'] == 'GB') {
            $strbasketItem .= ':' . $shoppingcart->withoutVAT($unitPrice);
            $strbasketItem .= ':' . $shoppingcart->calcVAT($unitPrice);
        } else {
            $strbasketItem .= ':' . $unitPrice;
            $strbasketItem .= ':' . 0;
        }

        $strbasketItem .= ':' . number_format($unitPrice, 2); //number_format($shoppingcart->shoppingCart['items'][$i]['unipri'],2);
        $strbasketItem .= ':' . number_format($unitPrice * $shoppingcart->shoppingCart['items'][$i]['qty'], 2);//number_format($shoppingcart->shoppingCart['items'][$i]['unipri'] * $shoppingcart->shoppingCart['items'][$i]['qty'],2);

        $strBasket .= $strbasketItem;

    }
}

//
// DISCOUNT
//

if (isset($shoppingcart->shoppingCart['discount']['discod'])) {

    $basketItemCount++;

    if ($shoppingcart->shoppingCart['discount']['pctamt'] == 'A') {
        $discountAmount = number_format($shoppingcart->shoppingCart['discount']['disamt'], 2);
    } else {
        $discountAmount = number_format((($totalOrderPrice / 100) * $shoppingcart->shoppingCart['discount']['disamt']), 2);
    }


    $totalOrderPrice -= $discountAmount;

    $strbasketItem = ':' . $shoppingcart->shoppingCart['discount']['disnam'];
    $strbasketItem .= ':' . '1';
    $strbasketItem .= ':' . $discountAmount * -1;
    $strbasketItem .= ':' . '0';
    $strbasketItem .= ':' . $discountAmount * -1;
    $strbasketItem .= ':' . $discountAmount * -1;

    $strBasket .= $strbasketItem;

}

//
// DELIVERY
//

if (isset($shoppingcart->shoppingCart['delivery']['del_id'])) {

    //
    // FIND DELIVERY RECORD
    //

    require_once("../../admin/ecommerce/classes/delivery.cls.php");
    $TmpDel = new DelDAO();
    $delivery = $TmpDel->select($shoppingcart->shoppingCart['delivery']['del_id'], NULL, NULL, 0, true);

    if (isset($delivery->delpri)) {

        $basketItemCount++;

        $unitPrice = $delivery->delpri;
        $vatAmount = ($unitPrice / 100) * 20;

        $totalOrderPrice += number_format($unitPrice, 2);

        $strbasketItem = ':DELIVERY - ' . $delivery->delnam;
        $strbasketItem .= ':1';

        if ($shoppingcart->shoppingCart['customer']['coucod'] == 'GB') {
            $strbasketItem .= ':' . number_format($unitPrice - $vatAmount, 2);
            $strbasketItem .= ':' . number_format($vatAmount, 2);
        } else {
            $strbasketItem .= ':' . number_format($unitPrice, 2);
            $strbasketItem .= ':' . '0';
        }

        $strbasketItem .= ':' . number_format($unitPrice, 2);
        $strbasketItem .= ':' . number_format($unitPrice * 1, 2);
        $strBasket .= $strbasketItem;
    }

}

$TmpOrd = new OrdDAO();
//echo $totalOrderPrice.' '.$TmpOrd->calcVAT($totalOrderPrice);

if (!$TmpDel->inEurope($shoppingcart->shoppingCart['customer']['paycoucod'])) {

    $basketItemCount++;

    $unitPrice = number_format($TmpOrd->calcVAT($totalOrderPrice), 2, '.', '');
    $unitPrice = $unitPrice * -1;

    $totalOrderPrice += number_format($unitPrice, 2);

    $strbasketItem = ':OUTSIDE OF UK';
    $strbasketItem .= ':1';

    $strbasketItem .= ':' . number_format($unitPrice, 2);
    $strbasketItem .= ':' . '0';

    $strbasketItem .= ':' . number_format($unitPrice, 2);
    $strbasketItem .= ':' . number_format($unitPrice * 1, 2);
    $strBasket .= $strbasketItem;

}

$strBasket = $basketItemCount . $strBasket;

$TaxAtm = number_format(($totalOrderPrice / 100) * 20, 2, '.', '');

// Now to build the Form crypt field.  For more details see the Form Protocol 2.23 	
$strPost = "Description=Website Order.";

$strPost .= "&VendorTxCode=" . $strVendorTxCode;
/** As generated above **/

// Optional: If you are a Sage Pay Partner and wish to flag the transactions with your unique partner id, it should be passed here
if (strlen($strPartnerID) > 0)
    $strPost = $strPost . "&ReferrerID=" . $strPartnerID;

$strPost = $strPost . "&Amount=" . number_format(($totalOrderPrice), 2, '.', ''); // Formatted to 2 decimal places with leading digit
$strPost = $strPost . "&Currency=" . $strCurrency;

//$requestID = 9013;

/* The SuccessURL is the page to which Form returns the customer if the transaction is successful */
$strPost = $strPost . "&SuccessURL=" . $successURL;

/* The FailureURL is the page to which Form returns the customer if the transaction is unsuccessful */
$strPost = $strPost . '&FailureURL=' . $failURL;


if ($bSendEMail == 0)
    $strPost = $strPost . "&SendEMail=0";
else {

    if ($bSendEMail == 1) {
        $strPost = $strPost . "&SendEMail=1";
    } else {
        $strPost = $strPost . "&SendEMail=2";
    }

    //$con = $TmpCon->getByLog_ID($Log_ID);
    //if( is_array($con) ) {

    //if (strlen($con[0]->getConEma()) > 0)
    $strPost = $strPost . "&CustomerEMail=" . $shoppingcart->shoppingCart['customer']['cusema'];// . //$con[0]->getConEma();  // This is an Optional setting

    //}

//	if (($strVendorEMail <> "[your e-mail address]") && ($strVendorEMail <> ""))
//		$strPost=$strPost . "&VendorEMail=" . $strVendorEMail;  // This is an Optional setting

    // You can specify any custom message to send to your customers in their confirmation e-mail here. The field can contain HTML if you wish, and be different for each order.  This field is optional
    $strPost = $strPost . "&eMailMessage=Thank you for your order.";
}

// Billing Details:

$full = $shoppingcart->shoppingCart['customer']['ordfao'];
$full1 = explode(' ', $full);
$first = $full1[0];
$rest = ltrim($full, $first . ' ');

$strPost = $strPost . "&BillingFirstnames=" . $first; //$shoppingcart->shoppingCart['customer']['cusfna'];
$strPost = $strPost . "&BillingSurname=" . $rest; //$shoppingcart->shoppingCart['customer']['cussna'];
$strPost = $strPost . "&BillingAddress1=" . $shoppingcart->shoppingCart['customer']['payadr1'];
$strPost = $strPost . "&BillingAddress2=" . $shoppingcart->shoppingCart['customer']['payadr2'];
$strPost = $strPost . "&BillingCity=" . $shoppingcart->shoppingCart['customer']['payadr3'];
$strPost = $strPost . "&BillingPostCode=" . $shoppingcart->shoppingCart['customer']['paypstcod'];
$strPost = $strPost . "&BillingCountry=" . $shoppingcart->shoppingCart['customer']['paycoucod'];

if ($shoppingcart->shoppingCart['customer']['paycoucod'] == 'US') {
    $strPost = $strPost . "&BillingState=" . $shoppingcart->shoppingCart['customer']['payadr4'];
}

$full = $shoppingcart->shoppingCart['customer']['ordfao'];

if (empty($full)) $full = $shoppingcart->shoppingCart['customer']['cusnam'];

$full1 = explode(' ', $full);
$first = $full1[0];
$rest = ltrim($full, $first . ' ');

// Delivery Details:
$strPost = $strPost . "&DeliveryFirstnames=" . $first; //$shoppingcart->shoppingCart['customer']['cusfna'];
$strPost = $strPost . "&DeliverySurname=" . $rest; //$shoppingcart->shoppingCart['customer']['cussna'];
$strPost = $strPost . "&DeliveryAddress1=" . $shoppingcart->shoppingCart['customer']['adr1'];
$strPost = $strPost . "&DeliveryAddress2=" . $shoppingcart->shoppingCart['customer']['adr2'];
$strPost = $strPost . "&DeliveryCity=" . $shoppingcart->shoppingCart['customer']['adr3'];
$strPost = $strPost . "&DeliveryPostCode=" . $shoppingcart->shoppingCart['customer']['pstcod'];
$strPost = $strPost . "&DeliveryCountry=" . $shoppingcart->shoppingCart['customer']['coucod'];

if ($shoppingcart->shoppingCart['customer']['coucod'] == 'US') {
    $strPost = $strPost . "&DeliveryState=" . $shoppingcart->shoppingCart['customer']['adr4'];
}

$strPost = $strPost . "&Basket=" . $strBasket; // As created above

// For charities registered for Gift Aid, set to 1 to display the Gift Aid check box on the payment pages
$strPost = $strPost . "&AllowGiftAid=0";

/* Allow fine control over AVS/CV2 checks and rules by changing this value. 0 is Default It can be changed dynamically, per transaction, if you wish.  See the Server Protocol document */
if ($strTransactionType !== "AUTHENTICATE")
    $strPost = $strPost . "&ApplyAVSCV2=0";

/* Allow fine control over 3D-Secure checks and rules by changing this value. 0 is Default It can be changed dynamically, per transaction, if you wish.  See the Form Protocol document */
$strPost = $strPost . "&Apply3DSecure=1";

// Encrypt the plaintext string for inclusion in the hidden field
$strCrypt = encryptAndEncode($strPost);


if ($ecoProp->sp_sta == 'TEST') {

    echo 'TYPE : ' . $ecoProp->sp_sta . ' VENDOR : ' . $strVendorName . ' ENCRYPTION : ' . $strEncryptionPassword . '<br>';
    echo '<p>' . $strPost . '</p>';

} else {

    ?>

    <script src="../../pages/js/jquery.js"></script>
    <script>

        $(function () {
            $('#SagePayForm').submit();
        });

    </script>

    <?php
}
?>
<form action="<?php echo $strPurchaseURL ?>" method="POST" id="SagePayForm" name="SagePayForm">
    <input type="hidden" name="navigate" value="<?php echo $strProtocol ?>"/>
    <input type="hidden" name="VPSProtocol" value="<?php echo $strProtocol ?>">
    <input type="hidden" name="TxType" value="<?php echo $strTransactionType ?>">
    <input type="hidden" name="Vendor" value="<?php echo $strVendorName ?>">
    <input type="hidden" name="Crypt" value="<?php echo $strCrypt ?>">
    <button type="submit">CLICK TO MAKE PAYMENT!</button>
</form>