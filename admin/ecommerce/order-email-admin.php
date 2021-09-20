<?php

require_once('../../config/config.php');
require_once('../../admin/patchworks.php');
require_once("../../admin/ecommerce/classes/order.cls.php");

$TmpOrd = new OrdDAO();

$Ord_ID = (isset($_REQUEST['ord_id']) && is_numeric($_REQUEST['ord_id'])) ? $_REQUEST['ord_id'] : NULL;


if (!is_numeric($Ord_ID)) {
    $throwJSON = array();
    $throwJSON['id'] = '0';
    $throwJSON['title'] = 'Email Error';
    $throwJSON['description'] = 'No Order Found';
    $throwJSON['type'] = 'error';
    die(json_encode($throwJSON));
} else {

    $orderRec = $TmpOrd->select($Ord_ID, NULL, NULL, NULL, true);

    $mailto = (isset($_REQUEST['emaadr'])) ? $_REQUEST['emaadr'] : $orderRec->emaadr;

    $emailBody = $TmpOrd->emailConfirmation($Ord_ID, $mailto, NULL);

    require '../phpmailer/PHPMailerAutoload.php';

    $mail = new PHPMailer;
    $mail->Host = $patchworks->SmtpServer;
    $mail->SMTPAuth = true;
    $mail->Username = $patchworks->SmtpUser;
    $mail->Password = $patchworks->SmptpPass;
    $mail->SMTPSecure = 'tls';
    $mail->Port = $patchworks->SmtpPort;

    $mail->Sender = $patchworks->adminEmail;
    $mail->setFrom($patchworks->adminEmail, $patchworks->customerName);
    $mail->addAddress($patchworks->adminEmail, $patchworks->customerName);
    $mail->addReplyTo($patchworks->adminEmail, $patchworks->customerName);
    $mail->isHTML(true);

    $mail->Subject = 'Order Confirmation (ADMIN - RE-SENT)';
    $mail->Body = $emailBody;

    if ( $mail->send() ) {
        $throwJSON = array();
        $throwJSON['id'] = '0';
        $throwJSON['title'] = 'Email OK';
        $throwJSON['description'] = 'Email sent ok';
        $throwJSON['type'] = 'success';
        die(json_encode($throwJSON));
    } else {
        $throwJSON = array();
        $throwJSON['id'] = '0';
        $throwJSON['title'] = 'Email Error';
        $throwJSON['description'] = 'Email Failed To Send';
        $throwJSON['type'] = 'error';
        die(json_encode($throwJSON));
    }


}

?>