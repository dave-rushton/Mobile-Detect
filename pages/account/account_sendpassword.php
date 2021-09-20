<?php

require_once("../../config/config.php");
require_once("../../admin/patchworks.php");
require_once("../../admin/system/classes/places.cls.php");
require_once("../../admin/phpmailer/PHPMailerAutoload.php");

//
// ADMIN CONFIRMATION
//

//echo $_POST['accountemail'];

$PlaDao = new PlaDAO();

$LogEma =  (isset($_REQUEST['accountemail'])) ? $_REQUEST['accountemail'] : '';

$PwdTok = $PlaDao->forgotPassword($LogEma, 'CUS');

//echo $PwdTok;

if ($PwdTok != '0') {

    // Send Email

    //header('location: '.$patchworks->webRoot.'useraccount/forgotpassword?result=emailsent');

    $body = '';
    $body .= '<table cellspacing="0" cellpadding="0" border="0" width="100%"  align="center" style="font-family: arial, helvetica, sans-serif;">';
    $body .= '<tr>';
    $body .= '<td align="center" width="100%">';
    $body .= '<img src="'.$patchworks->webRoot.'/pages/img/logo.png" width="173 height="100">';
    $body .= '</td>';
    $body .= '</tr>';
    $body .= '<tr>';
    $body .= '<td align="center" width="100%">';
    $body .= '<h1>PASSWORD RESET</h1>';

    $body .= '<p>Click <a href="'.$patchworks->webRoot.'useraccount/resetpassword?pwdtok='.$PwdTok.'">here</a> to reset your password.</p>';

    $body .= '</td>';
    $body .= '</tr>';
    $body .= '</table>';

    $message = '<html><body>';
    $message .= $body;
    $message .= "</body></html>";

    $mail = new PHPMailer;
    $mail->Host = $patchworks->SmtpServer;
    $mail->SMTPAuth = true;
    $mail->Username = $patchworks->SmtpUser;
    $mail->Password = $patchworks->SmtpPass;
    $mail->SMTPSecure = 'tls';
    $mail->Port = $patchworks->SmtpPort;
    $mail->Sender = $patchworks->adminEmail;
    $mail->setFrom($patchworks->adminEmail, $patchworks->adminEmail);
    $mail->addAddress($_REQUEST['accountemail'], $_REQUEST['accountemail']);
    $mail->isHTML(true);
    $mail->Subject = $patchworks->customerName.' '.' Password Reset Request';
    $mail->Body = $message;


    if ($mail->send()) {

        header('location: '.$patchworks->webRoot.'useraccount/forgotpassword?result=emailsent');
        exit();

    } else {

        $MsgDao = new MsgDAO();
        $MsgObj = new stdClass();
        $MsgObj->msg_id = 0;
        $MsgObj->sta_id = 0;
        $MsgObj->tblnam = 'WARNING';
        $MsgObj->tbl_id = 0;
        $MsgObj->atr_id = 0;
        $MsgObj->msgttl = 'Password Reset Email Error';
        $MsgObj->msgtxt = date("jS M Y H:i");
        $Msg_ID = $MsgDao->update($MsgObj);

        header('location: '.$patchworks->webRoot.'useraccount/forgotpassword?result=error');
        exit();

    }


} else {

    header('location: '.$patchworks->webRoot.'useraccount/forgotpassword?result=noemail');
    exit();

}

?>