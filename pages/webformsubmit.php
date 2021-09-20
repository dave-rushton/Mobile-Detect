<?php

require_once("../config/config.php");
require_once("../admin/patchworks.php");
require_once("../admin/attributes/classes/attrgroups.cls.php");
require_once("../admin/attributes/classes/attrlabels.cls.php");
require_once("../admin/attributes/classes/attrvalues.cls.php");
require_once("../admin/system/classes/messages.cls.php");
require_once("recaptchalib.php");
require_once('email/email.php');

$sql = "SELECT capkey, capsec FROM cmsprop WHERE cms_id = 1 LIMIT 1";
$qryArray = [];
$captchaRec = $patchworks->run($sql, $qryArray, true);
$showCaptcha = true;

if (empty($captchaRec->capkey) || empty($captchaRec->capsec)) {
    $showCaptcha = false;
}

$FwdUrl = (isset($_POST['fwdurl']))
    ? $_POST['fwdurl']
    : '';

$Atr_ID = (isset($_POST['atr_id']))
    ? $_POST['atr_id']
    : 0;

$TblNam = (isset($_POST['atvtblnam']))
    ? $_POST['atvtblnam']
    : 'UNKNOWN';

$Tbl_ID = (isset($_POST['atvtbl_id']))
    ? $_POST['atvtbl_id']
    : generateRandomString(20);

$body = '';

$AtrDao = new AtrDAO();
$AtlDao = new AtlDAO();
$AtvDao = new AtvDAO();
$MsgDao = new MsgDAO();

$attrGroup = $AtrDao->select($Atr_ID, null, null, null, true);

function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $randomString;
}

$arrayLabel = $_POST['lbl'];
$arrayValue = $_POST['fld'];
$arrayNumber = $_POST['fldnum'];

$domain = 'tailby.com';
$secret = "";
$sendmail = true;

if ($showCaptcha) {
    $sendmail = false;
    $secret = $captchaRec->capsec;

    if (isset($_REQUEST['g-recaptcha-response'])) {
        $Tbl_ID = (isset($_POST['atvtbl_id']))
            ? $_POST['atvtbl_id']
            : generateRandomString(20);

        // Build POST request:
        $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptcha_secret = $secret;
        $recaptcha_response = $_REQUEST['g-recaptcha-response'];

        // Make and decode POST request:
        $recaptcha = file_get_contents(
            $recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response
        );
        $recaptcha = json_decode($recaptcha);

        // Take action based on the score returned:
        if ($recaptcha->score >= 0.5) {
            $sendmail = true;
        } else {
            header('location: /fail#1');
            die();
        }
    } else {
        header('location: /fail#2');
        die();
    }
}

if ($sendmail) {
    $MsgObj = new stdClass();
    $MsgObj->msg_id = 0;
    $MsgObj->sta_id = 0;
    $MsgObj->tblnam = $TblNam;
    $MsgObj->tbl_id = $Tbl_ID;
    $MsgObj->atr_id = $Atr_ID;
    $MsgObj->msgttl = 'Website Contact';
    $MsgObj->msgtxt = "" . ' : ' . date("jS M Y H:i");
    $Msg_ID = $MsgDao->update($MsgObj);

    $TblNam = 'FORM';
    $mailto = $attrGroup->atrema;
    $subject = $attrGroup->atrnam;

    $header = "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $from_email = "website-form-send@tailby.com";

    for ($a = 0; $a < sizeof($arrayLabel); $a++) {
        $AtlObj = $AtlDao->select(null, $arrayNumber[$a], true);

        //
        // check if upload
        //
        // if upload save file
        if ($AtlObj->atltyp == 'upload') {
            if (file_exists($_FILES['uploadfile']['tmp_name']) || is_uploaded_file($_FILES['uploadfile']['tmp_name'])) {
                if ($_FILES["uploadfile"]["error"] > 0) {

                } else {
                    // upload validation from pvc
                    // create and name file using $Tbl_ID
                    $path_info = pathinfo($_FILES['uploadfile']['name']);
                    $extension = strtolower($path_info['extension']);

                    $file1 = $patchworks->docRoot . "uploads/files/formuploads/" . $_POST['atr_id'] . '-' . $arrayNumber[$a] . '-' . $Tbl_ID . "." . $extension;
                    $weblnk = $patchworks->webRoot . "uploads/files/formuploads/" . $_POST['atr_id'] . '-' . $arrayNumber[$a] . '-' . $Tbl_ID . "." . $extension;

                    $fp = fopen($file1, "w") or die("Unable to open file1!");
                    fwrite($fp, file_get_contents($_FILES['uploadfile']['tmp_name']));

                    // // modify email code below for attachment
                    // create link variable for email and atvval
                    $body .= '<tr><td style="font-family: Arial"><strong>' . $arrayLabel[$a] . '</strong> </td><td style="font-family: Arial"><a href="' . $weblnk . '">view</a></td></tr>';

                    $AtvObj = new stdClass();
                    $AtvObj->atv_id = 0;
                    $AtvObj->atl_id = $arrayNumber[$a];
                    $AtvObj->atr_id = $_POST['atr_id'];
                    $AtvObj->tblnam = $TblNam;
                    $AtvObj->tbl_id = $Tbl_ID;
                    $AtvObj->atvval = $_FILES["uploadfile"]["name"];
                    $Atv_ID = $AtvDao->update($AtvObj);
                }
            }
        } else {
            if ($arrayLabel == "email" || $arrayLabel == "Email") {
                $header .= "From: " . $arrayValue[$a] . "\r\n";
                $from_email = $arrayValue[$a];
            }

            if ($AtlObj->atlspc == 1) {
                $FrmEma = $arrayValue[$a];
            }

            $body .= "<tr><td style='font-family: Arial'><strong>" . $arrayLabel[$a] . "</strong> </td><td style='font-family: Arial'>" . strip_tags(
                    $arrayValue[$a]
                ) . "</td></tr>";

            $AtvObj = new stdClass();

            $AtvObj->atv_id = 0;
            $AtvObj->atl_id = $arrayNumber[$a];
            $AtvObj->atr_id = $_POST['atr_id'];
            $AtvObj->tblnam = $TblNam;
            $AtvObj->tbl_id = $Tbl_ID;
            $AtvObj->atvval = $arrayValue[$a];

            $Atv_ID = $AtvDao->update($AtvObj);
        }
    }

    $message = '<html><body>';
    $message .= "<h2 style='font-size:16px; font-family: arial;'>Values submitted from the contact form</h2> ";
    $message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
    $message .= $body;
    $message .= "</table>";
    $message .= "</body></html>";

    $sendOK = email($mailto, $subject, $message, [
        'domain' => $domain,
        'email' => $from_email
    ]);

    if (!$sendOK) {
        $MsgObj = new stdClass();
        $MsgObj->msg_id = 0;
        $MsgObj->sta_id = 0;
        $MsgObj->tblnam = 'WARNING';
        $MsgObj->tbl_id = 0;
        $MsgObj->atr_id = 0;
        $MsgObj->msgttl = 'Website Contact Email Error';
        $MsgObj->msgtxt = $attrGroup->atrnam . ' : ' . date("jS M Y H:i");
        $Msg_ID = $MsgDao->update($MsgObj);
    }

    header('location: /thank-you');
}
