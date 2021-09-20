<?php

require_once('../../../config/config.php');
require_once('../../../admin/patchworks.php');
require_once("../../../admin/website/classes/pageelements.cls.php");

$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

$title = array("ok" => "Message sent:", "nocaptcha" => "No Captcha: " ,"fail" => "Message failed to send:");
$message = array("ok" => "Thank you for contacting us, one of our staff will be in touch as soon as possible.",
    "nocaptcha" => "Please tick the captcha box to verify you're human" ,
    "fail" => "Something went wrong trying to send your message - please try again later.");

$ClsNam = $EleDao->getVariable($EleObj, 'clsnam', false);
if ($EleDao->getVariable($EleObj, 'nomargin', false) == 1) $ClsNam .= ' nomargin';
if ($EleDao->getVariable($EleObj, 'nopadding', false) == 1) $ClsNam .= ' nopadding';
if ($EleDao->getVariable($EleObj, 'extrapadding', false) == 1) $ClsNam .= ' extrapadding';

if ($EleDao->getVariable($EleObj, 'sucttl', false) != "") $title["ok"] = $EleDao->getVariable($EleObj, 'sucttl', false);
if ($EleDao->getVariable($EleObj, 'errttl', false) != "") $title["fail"] = $EleDao->getVariable($EleObj, 'errttl', false);
if ($EleDao->getVariable($EleObj, 'capttl', false) != "") $title["nocaptcha"] = $EleDao->getVariable($EleObj, 'capttl', false);

if ($EleDao->getVariable($EleObj, 'sucmsg', false) != "") $message["ok"] = $EleDao->getVariable($EleObj, 'sucmsg', false);
if ($EleDao->getVariable($EleObj, 'errmsg', false) != "") $message["fail"] = $EleDao->getVariable($EleObj, 'errmsg', false);
if ($EleDao->getVariable($EleObj, 'capmsg', false) != "") $message["nocaptcha"] = $EleDao->getVariable($EleObj, 'capmsg', false);

if (isset($_GET['result'])) {

    switch ($_GET['result']) {
        case "ok":
            $alertCls = "alert-info";
            break;
        case "fail":
            $alertCls = "alert-danger";
            break;
        case "nocaptcha";
            $alertCls = "alert-warning";
            break;
    }

    ?>
    <section <?php echo $ClsNam; ?>>
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="alert <?php echo $alertCls; ?>">
                        <p>
                            <strong><?php echo $title[$_GET["result"]]; ?></strong>
                        </p>
                        <p>
                            <?php echo $message[$_GET["result"]]; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php } ?>