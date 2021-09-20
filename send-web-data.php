<?php

require_once('config/config.php');
require_once('admin/patchworks.php');

$mailto = $patchworks->adminEmail;
$subject = "Feedback form";
$message = "Values submitted from '.$patchworks->customerName.' website form:";
$header = "From: ".$_POST['email'];

$FwdUrl = '';
if (isset($_POST['fwdurl'])) $FwdUrl = $_POST['fwdurl'];

$MsgTxt = 'Values submitted from '.$patchworks->customerName.' website';
if (isset($_POST['msgtxt'])) $MsgTxt = $_POST['msgtxt'];

foreach ($_POST as $key => $value)
{
   if (!is_array($value)) {
       if ($key != 'fwdurl') $message .= "\n".$key." : ".$value;
   }
   else {
      foreach ($_POST[$key] as $itemvalue) {
         $message .= "\n".$key." : ".$itemvalue;
      }
   }
}

require 'admin/phpmailer/PHPMailerAutoload.php';

$mail = new PHPMailer;

$mail->Host = $patchworks->SmtpServer;
$mail->SMTPAuth = true;
$mail->Username = $patchworks->SmtpUser;
$mail->Password = $patchworks->SmptpPass;
$mail->SMTPSecure = 'tls';
$mail->Port = $patchworks->SmtpPort;

$mail->Sender = $patchworks->adminEmail;
$mail->setFrom($patchworks->adminEmail, $patchworks->customerName);
$mail->addAddress($orderRec->emaadr, $orderRec->cusnam);
$mail->addReplyTo($patchworks->adminEmail, $patchworks->customerName);
$mail->isHTML(true);

$mail->Subject = $MsgTxt;
$mail->Body    = $message;

if(!$mail->send()) {
    //echo 'Message could not be sent.';
    //echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    //echo 'Message has been sent';
}

header('location: '.$FwdUrl);

?>