<?php

require 'PHPMailer.php';
require 'Exception.php';
require 'SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

define('_HOST', 'smtp.ionos.co.uk');
define('_PORT', 465);
define('_USER', 'website-form-send@tailby.com');
define('_PASS', '6S5%vC39P@xA');
define('_FROM', 'website-form-send@tailby.com');

function email($to, $subject, $message, $args)
{
    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = _HOST;
        $mail->SMTPAuth = true;
        $mail->Username = _USER;
        $mail->Password = _PASS;
        $mail->SMTPSecure = 'ssl';
        $mail->Port = _PORT;

        // from address
        $mail->setFrom(_FROM, _FROM);


        // recipients
        if (is_array($to)) {
            foreach ($to as $address) {
                $mail->addAddress($address);
            }
        }
        else {
            $mail->addAddress($to);
        }

        if ($args['replyTo'] == null) {
            if ($args['email'] == null) {
                $replyTo = 'no-reply@' . $args['domain'];
            } else {
                $replyTo = $args['email'];
            }
        } else {
            $replyTo = $args['replyTo'];
        }

        // reply address
        $mail->addReplyTo($replyTo);
        $mail->addCC($args['email']);

//	$mail->addBCC("dave.rushton@seventy9.co.uk", "Info");

        // attachments
        // $mail->addAttachment('/path/to/file');

        // content
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->AltBody = strip_tags($message);

        return $mail->send();
    }
    catch (Exception $e) {
        return $mail->ErrorInfo;
    }
}

// EOF
