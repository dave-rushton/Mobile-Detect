<?php

require_once('../../config/config.php');
require_once('../../admin/patchworks.php');
require_once('../../pages/shoppingcart/classes/shoppingcart.cls.php');
require_once("../../admin/products/classes/product_types.cls.php");
require_once("../../admin/products/classes/products.cls.php");
require_once("../../admin/ecommerce/classes/order.cls.php");
require_once("../../admin/ecommerce/classes/orderline.cls.php");
require_once("../../admin/products/classes/discounts.cls.php");
require_once("../../admin/ecommerce/classes/delivery.cls.php");
require_once("../../admin/system/classes/messages.cls.php");
require_once("../../admin/ecommerce/classes/ecommprop.cls.php");
require_once("../../admin/system/classes/places.cls.php");

$shoppingcart = new shoppingCart();

$PlaDao = new PlaDAO();
$loggedIn = $PlaDao->loggedIn( (isset($_SESSION['loginToken'])) ? $_SESSION['loginToken'] : NULL);

if (!isset($_REQUEST['qty']) || !is_numeric($_REQUEST['qty'])) $_REQUEST['qty'] = 1;

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'add') {

    $shoppingcart->addProduct($_REQUEST['prd_id'], $_REQUEST['qty']);

    if (isset($_REQUEST['ajax']) == true) {

        die();

    }

}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'remove') {

    $shoppingcart->removeProduct($_REQUEST['prd_id']);

}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'updateqty') {

    $shoppingcart->updateQty($_REQUEST['prd_id'],$_REQUEST['qty']);

    //die();

    header('location: ' . $patchworks->webRoot . 'checkout/shoppingcart');

}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'discount') {

    $shoppingcart->setDiscount($_REQUEST['discod']);

    header('location: '.$patchworks->webRoot.'checkout/options');
    die();

}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'clear') {

    $shoppingcart->clearCart();

}


if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delivery') {

    $shoppingcart->setDelivery($_REQUEST['del_id']);

    header('location: '.$patchworks->webRoot.'checkout/options');
    die();

}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'updatedetails') {

    $shoppingcart->shoppingCart['customer'] = array();



    $shoppingcart->shoppingCart['customer']['custtl']    = $_REQUEST['custtl'];
    $shoppingcart->shoppingCart['customer']['cusfna']    = $_REQUEST['cusfna'];
    $shoppingcart->shoppingCart['customer']['cussna']    = $_REQUEST['cussna'];

    $shoppingcart->shoppingCart['customer']['paycus']    = (!empty($_REQUEST['paycus'])) ? $_REQUEST['paycus'] : '';

    $shoppingcart->shoppingCart['customer']['custtl']    = (!empty($_REQUEST['custtl'])) ? $_REQUEST['custtl'] : '';
    $shoppingcart->shoppingCart['customer']['cusfna']    = (!empty($_REQUEST['cusfna'])) ? $_REQUEST['cusfna'] : '';
    $shoppingcart->shoppingCart['customer']['cussna']    = (!empty($_REQUEST['cussna'])) ? $_REQUEST['cussna'] : '';

    $shoppingcart->shoppingCart['customer']['payadr1']   = $_REQUEST['payadr1'];
    $shoppingcart->shoppingCart['customer']['payadr2']   = $_REQUEST['payadr2'];
    $shoppingcart->shoppingCart['customer']['payadr3']   = $_REQUEST['payadr3'];
    $shoppingcart->shoppingCart['customer']['payadr4']   = $_REQUEST['payadr4'];
    $shoppingcart->shoppingCart['customer']['paypstcod'] = $_REQUEST['paypstcod'];
    $shoppingcart->shoppingCart['customer']['paycoucod'] = $_REQUEST['paycountry'];

    $shoppingcart->shoppingCart['customer']['cusema']    = $_REQUEST['cusema'];
    $shoppingcart->shoppingCart['customer']['cusmob']    = $_REQUEST['cusmob'];
    $shoppingcart->shoppingCart['customer']['custel']    = $_REQUEST['custel'];


    if (isset($_REQUEST['delisbill']) && $_REQUEST['delisbill'] == 1) {

        $shoppingcart->shoppingCart['customer']['delisbill'] = 1;
        $shoppingcart->shoppingCart['customer']['cusnam']    = (!empty($_REQUEST['paycus'])) ? $_REQUEST['paycus'] : '';
        $shoppingcart->shoppingCart['customer']['ordfao']    = $_REQUEST['custtl'].' '.$_REQUEST['cusfna'].' '.$_REQUEST['cussna'];
        $shoppingcart->shoppingCart['customer']['adr1']      = $_REQUEST['payadr1'];
        $shoppingcart->shoppingCart['customer']['adr2']      = $_REQUEST['payadr2'];
        $shoppingcart->shoppingCart['customer']['adr3']      = $_REQUEST['payadr3'];
        $shoppingcart->shoppingCart['customer']['adr4']      = $_REQUEST['payadr4'];
        $shoppingcart->shoppingCart['customer']['pstcod']    = $_REQUEST['paypstcod'];
        $shoppingcart->shoppingCart['customer']['coucod']    = $_REQUEST['paycountry'];

    } else {

        $shoppingcart->shoppingCart['customer']['delisbill'] = 0;
        $shoppingcart->shoppingCart['customer']['cusnam']    = (!empty($_REQUEST['cusnam'])) ? $_REQUEST['cusnam'] : $_REQUEST['cusnam'];
        $shoppingcart->shoppingCart['customer']['ordfao']    = $_REQUEST['fao'];
        $shoppingcart->shoppingCart['customer']['adr1']      = $_REQUEST['adr1'];
        $shoppingcart->shoppingCart['customer']['adr2']      = $_REQUEST['adr2'];
        $shoppingcart->shoppingCart['customer']['adr3']      = $_REQUEST['adr3'];
        $shoppingcart->shoppingCart['customer']['adr4']      = $_REQUEST['adr4'];
        $shoppingcart->shoppingCart['customer']['pstcod']    = $_REQUEST['pstcod'];
        $shoppingcart->shoppingCart['customer']['coucod']    = $_REQUEST['country'];

    }

    $shoppingcart->shoppingCart['customer']['tandcs']   = (isset($_REQUEST['tandcs'])) ? 1 : 0;
    $shoppingcart->shoppingCart['customer']['marketing']   = (isset($_REQUEST['marketing'])) ? 1 : 0;


    $shoppingcart->updateCartSession();

    header('location: ' . $patchworks->webRoot . 'checkout/options');
    die();

}




if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'payment') {

    if (is_array($shoppingCart['items'])) {
        $shoppingCart['items'] = array_values($shoppingCart['items']);
    }

    $_SESSION['cart'] = json_encode($shoppingCart);

    $shoppingCart['paygateway']['paymenttype'] = 'WORLDPAY';

    //
    // SET GATEWAY TO WORLDPAY ALWAYS
    //

    if (!isset($shoppingCart['paygateway']['paymenttype']) || empty($shoppingCart['paygateway']['paymenttype']) || is_null($shoppingCart['paygateway']['paymenttype']) || $shoppingCart['paygateway']['paymenttype'] == 'NONE') {

        header('location: ' . $patchworks->webRoot . 'shoppingcart/confirmorder/0');

    } else if ($shoppingCart['paygateway']['paymenttype'] == 'SAGEPAY') {

        header('location: ' . $patchworks->webRoot . 'pages/payment/sagepaycheckout.php');

    } else if ($shoppingCart['paygateway']['paymenttype'] == 'WORLDPAY') {

        header('location: ' . $patchworks->webRoot . 'pages/payment/worldpaycheckout.php');

    }

    die();

}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'confirmorder') {

    $OrdDao = new OrdDAO();
    $OrdDao->confirmOrderPayment($_REQUEST['prd_id']);
    $orderRec = $OrdDao->select($_REQUEST['prd_id'], NULL, NULL, NULL, true);

    require '../../admin/phpmailer/PHPMailerAutoload.php';

    //
    // ADMIN CONFIRMATION
    //

    $emailBody = $OrdDao->emailConfirmation($_REQUEST['prd_id'], NULL, NULL);
    $mail2 = new PHPMailer;
    $mail2->Host = $patchworks->SmtpServer;
    $mail2->SMTPAuth = true;
    $mail2->Username = $patchworks->SmtpUser;
    $mail2->Password = $patchworks->SmtpPass;
    $mail2->SMTPSecure = 'tls';
    $mail2->Port = $patchworks->SmtpPort;
    $mail2->Sender = $patchworks->adminEmail;
    $mail2->setFrom($patchworks->adminEmail, $patchworks->adminEmail);
    $mail2->addAddress($patchworks->adminEmail, $patchworks->customerName);

    $mail2->addReplyTo($orderRec->emaadr, $orderRec->cusnam);
    $mail2->isHTML(true);
    $mail2->Subject = $orderRec->custtl.' '.$orderRec->cusfna.' '.$orderRec->cussna.' '.' '.$orderRec->ord_id.' Order Confirmation (ADMIN)';
    $mail2->Body = $emailBody;
    $mail2->send();

    //
    // CUSTOMER CONFIRMATION
    //

    $emailBody = $OrdDao->emailOrder($_REQUEST['prd_id'], NULL, NULL);
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
    $mail->addReplyTo($patchworks->adminEmail, $patchworks->customerName);
    $mail->isHTML(true);
    $mail->Subject = $orderRec->custtl.' '.$orderRec->cusfna.' '.$orderRec->cussna.' '.$orderRec->ord_id.' Order Confirmation';
    $mail->Body    = $emailBody;
    $mail->send();


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
    $mail3->Subject = $orderRec->custtl.' '.$orderRec->cusfna.' '.$orderRec->cussna.' '.' '.$orderRec->ord_id.' Order Confirmation (ADMIN)';
    $mail3->Body    = $emailBody;
    $mail3->send();



    header('location: '.$patchworks->webRoot.'checkout/complete');
    die();

}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'collectorder') {

    unset($shoppingcart->shoppingCart['delivery']);

    if (!isset($shoppingcart->shoppingCart['orderToken']) || !is_numeric($shoppingcart->shoppingCart['orderToken'])) {

        $Ord_ID = $shoppingcart->convertSessionToOrder(NULL, $loggedIn, 'Collect', 1);
        $orderToken = $Ord_ID;

    } else {

        $Ord_ID = $shoppingcart->convertSessionToOrder( $shoppingcart->shoppingCart['orderToken'], $loggedIn, 'Collect', 1 );
        $orderToken = $shoppingcart->shoppingCart['orderToken'];

    }

    //$Ord_ID = $shoppingcart->convertSessionToOrder();
    header('location: '.$patchworks->webRoot.'checkout/complete');
    die();

}

//if (is_array($shoppingcart->shoppingCart['items'])) $shoppingcart->shoppingCart['items'] = array_values($shoppingcart->shoppingCart['items']);
//$_SESSION['cart'] = json_encode($shoppingCart);
//$_SESSION['cart'] = json_encode(checkMultibuy($_SESSION['cart']));

header('location: '.$patchworks->webRoot.'checkout/shoppingcart');
die();

?>
