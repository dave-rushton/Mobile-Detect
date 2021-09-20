<?php


require_once('../../config/config.php');
require_once('../../admin/patchworks.php');
require_once('../../pages/shoppingcart/classes/shoppingcart.cls.php');
require_once('payments.cls.php');
$TmpPay = new PayDAO();

require_once("../../admin/ecommerce/classes/ecommprop.cls.php");
$TmpEco = new EcoDAO();
$ecoProp = $TmpEco->select(true);

require_once("../../admin/attributes/classes/attrgroups.cls.php");
require_once("../../admin/attributes/classes/attrlabels.cls.php");
require_once("../../admin/attributes/classes/attrvalues.cls.php");

require_once("../../admin/products/classes/product_types.cls.php");
require_once("../../admin/products/classes/products.cls.php");
require_once("../../admin/gallery/classes/uploads.cls.php");

require_once("../../admin/ecommerce/classes/order.cls.php");
require_once("../../admin/ecommerce/classes/orderline.cls.php");

require_once("../../admin/products/classes/discounts.cls.php");

require_once("../../admin/ecommerce/classes/delivery.cls.php");

require_once("../../admin/system/classes/messages.cls.php");

require_once("../../admin/ecommerce/classes/vat.cls.php");
$TmpVat = new VatDAO();
$vatRecord = $TmpVat->select(NULL, date("Y-m-d"), NULL, true);

require_once("../../admin/system/classes/tempobject.cls.php");


$shoppingcart = new shoppingCart();

$ppconfig = new config();

// Database variables
$host = $ppconfig->host; //"localhost"; //database location
$user = $ppconfig->user; //database username
$pass = $ppconfig->password; //database password
$db_name = $ppconfig->dbname; //database name

// PayPal settings
//$paypal_email = 'outlandish.items@btinternet.com';
//$paypal_email = 'info-facilitator@masterclip.co.uk';

$paypal_email = $ecoProp->pp_ema;
$return_url = $patchworks->webRoot.'checkout/complete';
$cancel_url = $patchworks->webRoot.'checkout/fail';
$notify_url = $patchworks->webRoot.'payment/paypal/payments.php';

//
// Paypal Code
//

//$payPalURL = 'www.sandbox.paypal.com';
$payPalURL = 'www.paypal.com';


$item_name = $ecoProp->comnam.' PayPal Online Order';
$item_amount = $shoppingcart->calcCartPrice();
$item_amount -= $shoppingcart->calcDiscount();

include("functions.php");

if (!isset($_POST["txn_id"]) && !isset($_POST["txn_type"])) {

    //$file = 'paypal.txt';
    //$current = file_get_contents($file);
    //$current .= "RUNNING PAYPAL SCRIPT\n";

    $querystring = '';

    // Firstly Append paypal account to querystring
    $querystring .= "?business=" . urlencode($paypal_email) . "&";

    // Append amount& currency (£) to quersytring so it cannot be edited in html

    //The item name and amount can be brought in dynamically by querying the $_POST['item_number'] variable.
    $querystring .= "item_name=" . urlencode($item_name) . "&";
    $querystring .= "amount=" . urlencode($item_amount) . "&";

    //loop for posted values and append to querystring
    foreach ($_POST as $key => $value) {
        $value = urlencode(stripslashes($value));
        $querystring .= "$key=$value&";
    }

    // Append paypal return addresses
    $querystring .= "return=" . urlencode(stripslashes($return_url)) . "&";
    $querystring .= "cancel_return=" . urlencode(stripslashes($cancel_url)) . "&";
    $querystring .= "notify_url=" . urlencode($notify_url);

    //echo urlencode(stripslashes($querystring)).'<br>';

    //
    // CREATE ORDER
    //

    if (!isset($shoppingcart->shoppingCart['orderToken']) || !is_numeric($shoppingcart->shoppingCart['orderToken'])) {

        $Ord_ID = $shoppingcart->convertSessionToOrder(NULL, $loggedIn, 'PayPal');
        $orderToken = $Ord_ID;

    } else {

        $Ord_ID = $shoppingcart->convertSessionToOrder( $shoppingcart->shoppingCart['orderToken'], $loggedIn, 'PayPal');
        $orderToken = $shoppingcart->shoppingCart['orderToken'];

    }

    $querystring .= "&custom=".$orderToken;


    // Redirect to paypal IPN
    header('location:https://'.$payPalURL.'/cgi-bin/webscr' . $querystring);
    exit();

} else {

    error_reporting(0);

    $req = 'cmd=_notify-validate';

    foreach ($_POST as $key => $value) {
        $value = urlencode(stripslashes($value));
        $value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i', '${1}%0D%0A${3}', $value);// IPN fix
        $req .= "&$key=$value";
    }


    $orderToken = $_POST['custom'];

    $paymentRec = new stdClass();
    $paymentRec->id = 0;
    $paymentRec->txnid = $_POST['txn_id'];
    $paymentRec->payment_amount = $_POST['mc_gross'];
    $paymentRec->payment_status = $_POST['payment_status'];
    $paymentRec->itemid = 0;
    $paymentRec->createdtime = date("Y-m-d H:i:s");

//    $file = 'paypal.txt';
//    $current = file_get_contents($file);
//    $current .= "POST BACK TO PAYPAL : " . $_POST['txn_id'] .' - ALTREF : '.$_POST['custom'] . "\n";
//    file_put_contents($file, $current);

    $header .= "POST /cgi-bin/webscr HTTP/1.1\r\n";
    $header .= "Host: ".$payPalURL."\r\n";
    $header .= "Connection: close\r\n";
    $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
    $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";

    $fp = fsockopen('ssl://'.$payPalURL, 443, $errno, $errstr, 30);

    if (!$fp) {
        // HTTP ERROR

//        $file = 'paypal.txt';
//        $current = file_get_contents($file);
//        $current .= "HTTP ERROR\n";
//        file_put_contents($file, $current);

    } else {

//        $file = 'paypal.txt';
//        $current = file_get_contents($file);
//        $current .= "HTTP OK\n";
//        $current .= "\n\n\n";
//        //$current .= $fp."\n\n\n";
//        file_put_contents($file, $current);

        fputs($fp, $header . $req);
        while (!feof($fp)) {
            $res = fgets($fp, 1024);

//            $file = 'paypal.txt';
//            $current = file_get_contents($file);
//            $current .= $res . "\n";
//            file_put_contents($file, $current);

            if (strpos($res, 'VERIFIED') !== false) {
                //if (strcmp($res, "VERIFIED") == 0) {

                $file = 'paypal.txt';
                $current = file_get_contents($file);
                $current .= "PAYMENT VERIFIED\n\n";
                $current .= print_r($_POST).'\n\n';
                $current .= var_dump($_POST).'\n\n';
                $current .= $_POST['payer_email'];
                $current .= "COMPLETED\n";
                file_put_contents($file, $current);

                $req_dump = print_r($_REQUEST, TRUE);
                $fp = fopen('paypalreturn.txt', 'a');
                fwrite($fp, $req_dump);
                fclose($fp);


                // Used for debugging
                mail('projects@seventy9.co.uk', 'PAYPAL POST - VERIFIED RESPONSE', $current);

                // Validate payment (Check unique txnid & correct price)

                $recordCreated = false;

                $valid_txnid = $TmpPay->check_txnid($_POST['txn_id']);
                if (isset($valid_txnid) && isset($valid_txnid->id)) {

                    $recordCreated = true;

                    $valid_txnid = false;
                } else {
                    $valid_txnid = true;
                }

                $valid_price = true; //check_price($data['payment_amount'], $data['item_number']);

                // PAYMENT VALIDATED & VERIFIED!
                if ($valid_txnid && $valid_price) {

                    require '../../admin/phpmailer/PHPMailerAutoload.php';

                    $OrdDao = new OrdDAO();
                    $orderRec = $OrdDao->select($_POST['custom'], NULL, NULL, NULL, true);


                    // Check Status

                    if (is_numeric($orderRec->sta_id) && $orderRec->sta_id != 20 && $orderRec->sta_id != 30) {

                        // NOT PAID-CONFIRMED YET

                        $OrdDao->confirmOrderPayment($_POST['custom']);

                        unset($shoppingCart);

                        $shoppingCart = array();
                        $_SESSION['cart'] = json_encode($shoppingCart);

                        $emailBody = $OrdDao->emailOrder($_POST['custom'], NULL, NULL);
                        $mail = new PHPMailer;
                        $mail->Host = $patchworks->SmtpServer;
                        $mail->SMTPAuth = true;
                        $mail->Username = $patchworks->SmtpUser;
                        $mail->Password = $patchworks->SmtpPass;
                        $mail->SMTPSecure = 'tls';
                        $mail->Port = $patchworks->SmtpPort;
                        $mail->Sender = $patchworks->adminEmail;

                        $mail->setFrom($patchworks->adminEmail, $patchworks->customerName);
                        $mail->addAddress($orderRec->emaadr, $orderRec->cusnam);
                        $mail->addBCC($patchworks->adminEmail, $patchworks->customerName);
                        $mail->addReplyTo($patchworks->adminEmail, $patchworks->customerName);

                        $mail->isHTML(true);

                        $mail->Subject = $orderRec->cusnam.' '.$orderRec->ord_id.' Order Confirmation';
                        $mail->Body    = $emailBody;
                        if ($mail->send()) {
                            mail('projects@seventy9.co.uk', 'PAYPAL PAYMENT - EMAIL SUCCESS', $_POST['custom']);
                        } else {
                            mail('projects@seventy9.co.uk', 'PAYPAL PAYMENT - EMAIL FAIL', $_POST['custom']);
                        }


                        //
                        // ADMIN CUSTOMER CONFIRMATION
                        //

                        $emailBody = $OrdDao->emailOrder($_REQUEST['prd_id'], NULL, NULL);
                        $mail3 = new PHPMailer;
                        $mail3->Host = $patchworks->SmtpServer;
                        $mail3->SMTPAuth = true;
                        $mail3->Username = $patchworks->SmtpUser;
                        $mail3->Password = $patchworks->SmtpPass;
                        $mail3->SMTPSecure = 'tls';
                        $mail3->Port = $patchworks->SmtpPort;
                        $mail3->Sender = $patchworks->adminEmail;
                        $mail3->setFrom($patchworks->adminEmail, $patchworks->customerName);
                        $mail3->addAddress($patchworks->adminEmail, $patchworks->customerName);
                        $mail3->addReplyTo($patchworks->adminEmail, $patchworks->customerName);
                        $mail3->isHTML(true);
                        $mail3->Subject = $orderRec->cusnam.' '.$orderRec->ord_id.' Order Confirmation (ADMIN)';
                        $mail3->Body    = $emailBody;
                        $mail3->send();



                        $emailBody = $OrdDao->emailConfirmation($_REQUEST['prd_id'], NULL, NULL);
                        $mail2 = new PHPMailer;

                        // MANUAL SEND
                        $mail2->Host = "mail.seventy9.co.uk";
                        $mail2->SMTPAuth = true;
                        $mail2->Username = "forms@seventy9.co.uk";
                        $mail2->Password = "PWHGr0up!";
                        $mail2->SMTPSecure = 'tls';
                        $mail2->Port = "25";

                        $mail2->Sender = $orderRec->emaadr;
                        $mail2->setFrom($orderRec->emaadr, $orderRec->cusnam);
                        $mail2->addAddress('yy@seventy9.co.uk', 'yy@seventy9.co.uk');
                        $mail2->addAddress($patchworks->adminEmail, $patchworks->customerName);
                        $mail2->addReplyTo($orderRec->emaadr, $orderRec->cusnam);

                        $mail2->isHTML(true);

                        $mail2->Subject = $orderRec->cusnam.' '.$orderRec->ord_id.' Order Confirmation (ADMIN)';
                        $mail2->Body = $emailBody;
                        $mail2->send();



                    } else {

                    }


                } else {

                    mail('projects@seventy9.co.uk', 'PAYPAL POST - PAYMENT MADE WRONG DATA', $current);

                }

            } else if (strcmp($res, "INVALID") == 0) {

                @mail("projects@seventy9.co.uk", "PAYPAL DEBUGGING", $current);

            }
        }
        fclose($fp);
    }
}
?>