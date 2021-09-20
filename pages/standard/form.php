<?php

require_once("../../config/config.php");
require_once("../../admin/patchworks.php");
require_once("../../admin/attributes/classes/attrgroups.cls.php");
require_once("../../admin/attributes/classes/attrlabels.cls.php");
require_once("../../admin/website/classes/pageelements.cls.php");

$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : null;
$EleObj = $EleDao->select($Pel_ID, null, null, true);
if (!$EleObj) die();

$eleVarArr = json_decode($EleObj->elevar, true);

if (is_null($eleVarArr[0])) die();


$ClsNam = $EleDao->getVariable($EleObj, 'clsnam', false);
if ($EleDao->getVariable($EleObj, 'nomargin', false) == 1) $ClsNam .= ' nomargin';
if ($EleDao->getVariable($EleObj, 'nopadding', false) == 1) $ClsNam .= ' nopadding';

$Atr_ID = null;
$Atr_ID = $EleDao->getVariable($EleObj, 'atr_id', false);
$FwdUrl = null;
$FwdUrl = $EleDao->getVariable($EleObj, 'fwdurl', false);
$AltUrl = null;
$AltUrl = $EleDao->getVariable($EleObj, 'alturl', false);

$ShwTtl = false;
$ShwTtl = $EleDao->getVariable($EleObj, 'shwttl', false);
$ShwDsc = false;
$ShwDsc = $EleDao->getVariable($EleObj, 'shwdsc', false);
$ShwLbl = true;
$ShwLbl = $EleDao->getVariable($EleObj, 'shwlbl', false);

if (is_null($Atr_ID)) die();

$TmpAtr = new AtrDAO();
$TmpAtl = new AtlDAO();

$editAttrGroup = $Atr_ID;
$attrGroupRec = null;
$attrLabelRec = null;

if (!is_null($editAttrGroup)) {
    $attrGroupRec = $TmpAtr->select($editAttrGroup, null, null, null, true, null, null, 0);

    if (!isset($attrGroupRec->tblnam)) die();

    $editReferenceTable = $attrGroupRec->tblnam;
    $attrLabelRec = $TmpAtl->select($editAttrGroup);

    $numOfCols = $attrGroupRec->numcol;
    $colWidth = 12 / $numOfCols;
}

$FldNum = array();
$FldTyp = array();
$FldLbl = array();
$FldLst = array();
$FldReq = array();
$ColNum = array();

$i = 0;

$tableLength = count($attrLabelRec);

for ($i = 0; $i < $tableLength; ++$i) {

    $FldNum[$i] = $attrLabelRec[$i]['atl_id'];
    $FldTyp[$i] = $attrLabelRec[$i]['atltyp'];
    $FldLbl[$i] = $attrLabelRec[$i]['atllbl'];
    $FldLst[$i] = $attrLabelRec[$i]['atllst'];
    $FldReq[$i] = ($attrLabelRec[$i]['atlreq'] == 1) ? true : false;
    $FldSpc[$i] = ($attrLabelRec[$i]['atlspc'] == 1) ? true : false;
    $ColNum[$i] = $attrLabelRec[$i]["colnum"];
}

$qryArray = array();
$sql = "SELECT * FROM cmsprop WHERE cms_id = 1";
$cmsProp = $patchworks->run($sql, array(), true);
$showCaptcha = true;

if (empty($cmsProp->capkey) || empty($cmsProp->capsec)) {
    $showCaptcha = false;
}

//DO NOT ADD IN HERE

$spacing = $EleDao->getVariable($EleObj, 'spacing', false);
$nopadding = $EleDao->getVariable($EleObj, 'nopadding', false);
$nomargin = $EleDao->getVariable($EleObj, 'nomargin', false);
$extrapadding = $EleDao->getVariable($EleObj, 'extrapadding', false);
$extramargin = $EleDao->getVariable($EleObj, 'extramargin', false);
$theme = $EleDao->getVariable($EleObj, 'theme', false);
$animation = $EleDao->getVariable($EleObj, 'animation', false);
$indent = $EleDao->getVariable($EleObj, 'indent', false);
$negmargin = $EleDao->getVariable($EleObj, 'negmargin', false);
$class = $spacing . " " . $nopadding . " " . $nomargin . " " . $extrapadding . " " . $extramargin . " " . $theme . " " . $negmargin;
//DO NOT ADD IN HERE

?>


<div class="section form-wrapper <?php echo $class; ?>">
    <div class="container">
        <?php
        if (!empty($indent))
            echo '<div class="' . $indent . '">'
        ?>
        <div class="row">
            <div class="col-sm-12">

                <?php
                if (isset($_GET['result'])) {

                    if ($_GET['result'] == 'ok') {
                        ?>
                        <div class="alert alert-info">
                            <p>
                                <strong>Message sent: </strong>
                            </p>
                            <p>
                                Thank you for contacting us, one of our staff will be in touch as soon as possible.
                            </p>
                        </div>
                    <?php } else if ($_GET['result'] == 'fail') { ?>

                        <div class="alert alert-danger">
                            <p>
                                <strong>Message failed to send: </strong>
                            </p>

                            <p>
                                Something went wrong trying to send your message - please try again later.
                            </p>
                        </div>
                        <?php
                    } else if ($_GET["result"] == 'nocaptcha') { ?>
                        <div class="alert alert-warning">
                            <p>
                                <strong>No Captcha: </strong>
                            </p>

                            <p>
                                Please tick the captcha box to verify you're human
                            </p>
                        </div>
                        <?php
                    }
                }
                ?>


                <form class="form-vertical attributeForm"
                      action="<?php echo $patchworks->webRoot; ?>pages/webformsubmit.php" data-parsley-validate
                      enctype="multipart/form-data" method="post">
                    <div class="pw-form">

                        <?php if ($ShwTtl) { ?>
                            <div class="pw-form-header">
                                <h2>
                                    <?php echo ($attrGroupRec) ? $attrGroupRec->atrnam : ''; ?></h2>
                            </div>
                        <?php } ?>

                        <?php if ($ShwDsc) { ?>
                            <div class="pw-form-header">
                                <p>
                                    <?php echo ($attrGroupRec) ? nl2br($attrGroupRec->atrdsc) : ''; ?></p>
                            </div>
                        <?php } ?>


                        <div class="row">
                            <div class="pw-form-content">
                                <fieldset>
                                    <div class="col-sm-12">
                                        <input type="hidden" name="atvtblnam"
                                               value="<?php echo ($attrGroupRec) ? $attrGroupRec->tblnam : ''; ?>"/>
                                        <!--<input type="hidden" name="atvtbl_id" value="<?php echo (!is_null($editReferenceID)) ? $editReferenceID : ''; ?>" />-->
                                        <input type="hidden" name="atr_id"
                                               value="<?php echo ($attrGroupRec) ? $attrGroupRec->atr_id : 0; ?>"/>
                                        <input type="hidden" name="httpChk" value="http://"/>
                                        <input type="hidden" name="ema_to"
                                               value="<?php echo ($attrGroupRec) ? $attrGroupRec->ema_to : ''; ?>"/>
                                        <input type="hidden" name="fwdurl"
                                               value="<?php echo (empty($FwdUrl)) ? $attrGroupRec->fwdurl : $FwdUrl; ?>"/>
                                        <input type="hidden" name="alturl"
                                               value="<?php echo (empty($AltUrl)) ? $attrGroupRec->alturl : $AltUrl; ?>"/>
                                        <div class="row">
                                            <?php for ($col = 1;
                                            $col <= $numOfCols;
                                            $col++) { ?>
                                            <div class="col-sm-<?php echo $colWidth; ?>">
                                                <?php for ($i = 0;
                                                           $i < sizeof($FldNum);
                                                           $i++) {
                                                if ($FldTyp[$i] == 'hidden') {
                                                    continue; //Ignore hidden elements for placement
                                                } else {
                                                //Check modulus then check type
                                                //                                                            $fieldMod = $i % $numOfCols;
                                                //                                                            $colMod = $fieldMod+1;
                                                //                                                            if ($colMod == $col) {
                                                if ($ColNum[$i] == $col) {
                                                if ($FldTyp[$i] == 'text')
                                                {
                                                    ?>
                                                    <div class="control-group form-group">
                                                        <div class="controls">
                                                            <?php
                                                            if ($ShwLbl) {
                                                                echo '<label>' . $FldLbl[$i] . '</label>';
                                                            }

                                                            if ($FldReq[$i] == true) echo '<div class="required-star"></div>';
                                                            ?>

                                                            <input type="text" name="fld[]"
                                                                   placeholder="<?php echo $FldLbl[$i]; ?>"
                                                                   class="form-control fldval"
                                                                   id="Fld-<?php echo $FldNum[$i]; ?>" <?php if ($FldReq[$i] == true) echo 'required'; ?>
                                                                   data-validation-required-message="Please enter your name.">
                                                            <p class="help-block"></p>
                                                        </div>
                                                    </div>
                                                    <?php
                                                } elseif ($FldTyp[$i] == 'upload') {
                                                    ?>
                                                    <div class="form-group">
                                                        <?php
                                                        if ($ShwLbl) {
                                                            echo '<label for="exampleInputFile">' . $FldLbl[$i] . '</label>';
                                                        }
                                                        ?>
                                                        <input type="file" name="uploadfile"
                                                               id="Fld-<?php echo $FldNum[$i]; ?>">
                                                        <input type="hidden" name="fld[]"
                                                               id="Fld-<?php echo $FldNum[$i]; ?>" value="uploadfile">
                                                        <p class="help-block">Example block-level help text here.</p>
                                                    </div>
                                                    <?php
                                                } elseif ($FldTyp[$i] == 'date') {
                                                    ?>
                                                    <div class="control-group form-group">
                                                        <div class="controls">
                                                            <?php
                                                            if ($ShwLbl) {
                                                                echo '<label>' . $FldLbl[$i] . '<small><em>(DD-MM-YYYY)</em></small>:</label>';
                                                            }
                                                            if ($FldReq[$i] == true) echo '<div class="required-star"></div>';
                                                            ?>

                                                            <input type="text" name="fld[]" class="form-control fldval"
                                                                   id="Fld-<?php echo $FldNum[$i]; ?>" <?php if ($FldReq[$i] == true) echo 'required'; ?>
                                                                   data-validation-required-message="Please enter a date."
                                                                   data-parsley-pattern="/[0-9][0-9]-[0-9][0-9]-[0-9][0-9][0-9][0-9]/i"
                                                                   placeholder="DD-MM-YYYY">
                                                            <p class="help-block"></p>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                                else if ($FldTyp[$i] == 'singleCheckbox')
                                                {
                                                    ?>
                                                    <div class="control-group">
                                                        <?php
                                                        if ($ShwLbl) {
                                                            echo ' <label class="control-label">' . $FldLbl[$i] . '</label>';
                                                        }
                                                        ?>
                                                        <div class="controls">
                                                            <label class="checkbox">
                                                                <input type="checkbox"
                                                                       id="cbfor-<?php echo $FldNum[$i]; ?>">
                                                                Website - Templates </label>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="fld[]" class="fldval"
                                                           id="cbact-<?php echo $FldNum[$i]; ?>" value=""/>
                                                    <?php
                                                }
                                                else if ($FldTyp[$i] == 'select')
                                                {
                                                    ?>
                                                    <div class="control-group">
                                                        <?php
                                                        if ($ShwLbl) {
                                                            echo ' <label class="control-label">' . $FldLbl[$i] . '</label>';
                                                        }
                                                        ?>

                                                        <div class="controls">
                                                            <?php
                                                            if ($FldReq[$i] == true) echo '<div class="required-star"></div>';
                                                            ?>
                                                            <select name="fld[]" id="Fld-<?php echo $FldNum[$i]; ?>"
                                                                    class="fldval select form-control" <?php if ($FldReq[$i] == true) echo 'required'; ?>>
                                                                <option value="">Select Option...</option>
                                                                <?php
                                                                $SelOpt = explode(",", $FldLst[$i]);
                                                                for ($s = 0; $s < sizeof($SelOpt); $s++) { ?>
                                                                    <option value="<?php echo $SelOpt[$s]; ?>"><?php echo $SelOpt[$s]; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                                else if ($FldTyp[$i] == 'textarea')
                                                {
                                                    ?>
                                                    <div class="control-group form-group">
                                                        <div class="controls">
                                                            <?php
                                                            if ($ShwLbl) {
                                                                echo ' <label class="control-label">' . $FldLbl[$i] . '</label>';
                                                            }
                                                            if ($FldReq[$i] == true) echo '<div class="required-star"></div>';
                                                            ?>
                                                            <textarea name="fld[]"
                                                                      id="FldNum-<?php echo $FldNum[$i]; ?>"
                                                                      placeholder="<?php echo $FldLbl[$i]; ?>" rows="10"
                                                                      cols="100"
                                                                      class="form-control fldval <?php echo ($FldReq[$i]) ? 'required' : ''; ?>"
                                                                      id="message" required
                                                                      data-validation-required-message="Please enter your message"
                                                                      maxlength="999" style="resize:none"></textarea>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                                else if ($FldTyp[$i] == 'radio')
                                                {
                                                    ?>
                                                    <div class="control-group">
                                                        <label class="control-label"><?php echo $FldLbl[$i]; ?></label>
                                                        <div class="controls">
                                                            <?php
                                                            $SelOpt = explode(",", $FldLst[$i]);
                                                            for ($s = 0; $s < sizeof($SelOpt); $s++) {
                                                                ?>
                                                                <label class="radio">
                                                                    <input type="radio"
                                                                           name="rbact-<?php echo $FldNum[$i]; ?>"
                                                                           value="<?php echo $SelOpt[$s]; ?>">
                                                                    <?php echo $SelOpt[$s]; ?></label>
                                                            <?php } ?>
                                                            <input type="hidden" name="fld[]"/>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                                else if ($FldTyp[$i] == 'checkbox')
                                                {
                                                $SelOpt = explode(",", $FldLst[$i]);
                                                for ($s = 0;
                                                     $s < sizeof($SelOpt);
                                                     $s++)
                                                {
                                                ?>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="cbact-<?php echo $FldNum[$i]; ?>"
                                                               value="<?php echo $SelOpt[$s]; ?>">
                                                        <?php echo $FldLbl[$i]; ?>
                                                    </label>
                                                    <?php
                                                    }

                                                    echo '<input type="hidden" name="fld[]" />';
                                                    echo '</div>';
                                                    } ?>
                                                    <input type="hidden" name="fldnum[]" class="fldnum"
                                                           value="<?php echo $FldNum[$i]; ?>"/>
                                                    <input type="hidden" name="lbl[]" class="fldlbl"
                                                           value="<?php echo $FldLbl[$i]; ?>"/>
                                                    <?php
                                                    }
                                                    }
                                                    } ?>
                                                </div>
                                                <?php } ?>

                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">

                                                    <?php
                                                    if (!empty($attrGroupRec->gdpr_text)) {
                                                        ?>
                                                        <div class="alert alert-info">
                                                            <p>
                                                                <?php
                                                                if (!empty($attrGroupRec->gdpr_title)) {
                                                                    ?>
                                                                    <strong>
                                                                        <?php
                                                                        echo $attrGroupRec->gdpr_title;
                                                                        ?>
                                                                    </strong>
                                                                    <br>
                                                                    <?php
                                                                }
                                                                ?>
                                                                <?php
                                                                echo nl2br($attrGroupRec->gdpr_text);
                                                                ?>
                                                            </p>

                                                            <div class="radio">
                                                                <label>
                                                                    <input type="radio" required="required"
                                                                           name="nomail"
                                                                           value="<?php echo(!empty($attrGroupRec->gdpr_yes) ? $attrGroupRec->gdpr_yes : "Yes please, I'd like to hear about offers and services"); ?>"
                                                                           data-parsley-multiple="nomail"
                                                                           data-parsley-id="0680">
                                                                    <?php
                                                                    echo(!empty($attrGroupRec->gdpr_yes) ? $attrGroupRec->gdpr_yes : "Yes please, I'd like to hear about offers and services");
                                                                    ?>
                                                                </label>
                                                                <ul class="parsley-errors-list"
                                                                    id="parsley-id-multiple-nomail"></ul>
                                                            </div>

                                                            <div class="radio">
                                                                <label>
                                                                    <input type="radio" required="required"
                                                                           name="nomail"
                                                                           value="<?php echo(!empty($attrGroupRec->gdpr_no) ? $attrGroupRec->gdpr_no : " No thanks, I don't want to hear about offers and services"); ?>"
                                                                           data-parsley-multiple="nomail"
                                                                           data-parsley-id="0680">

                                                                    <?php
                                                                    echo(!empty($attrGroupRec->gdpr_no) ? $attrGroupRec->gdpr_no : " No thanks, I don't want to hear about offers and
                                                                    services");
                                                                    ?>
                                                                </label>
                                                            </div>

                                                        </div>
                                                        <?php
                                                    }


                                                    ?>

                                                    <?php
                                                    if ($showCaptcha) {
                                                        ?>
                                                        <input type="hidden"
                                                               name="g-recaptcha-response"
                                                               id="g-recaptcha-response">
                                                        <div class="captchabox">
                                                            <img src="https://www.gstatic.com/recaptcha/api2/logo_48.png"
                                                                 alt="">
                                                            <div class="rc-anchor-invisible-text">
                                                                <span>protected by <strong>reCAPTCHA</strong></span>
                                                                <div class="rc-anchor-pt"><a
                                                                            href="https://www.google.com/intl/en/policies/privacy/"
                                                                            target="_blank">Privacy</a><span
                                                                            aria-hidden="true"
                                                                            role="presentation"> - </span><a
                                                                            href="https://www.google.com/intl/en/policies/terms/"
                                                                            target="_blank">Terms</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php

                                                    }
                                                    ?>

                                                    <input type="hidden" value="Create Attribute Set" name="formSubmit"
                                                           id="atvsetSubmit"/>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-actions">
                                                        <button type="submit"
                                                                class="btn btn-primary"><?php echo ($attrGroupRec) ? $attrGroupRec->btntxt : 'Submit'; ?>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php
        if (!empty($indent))
            echo '</div>'
        ?>
    </div>
</div>

<?php
function displayFields($i)
{

}

?>


<link rel="stylesheet" type="text/css" href="admin/css/plugins/datepicker/datepicker.css">
<script src="pages/js/bootstrap-datepicker.min.js"></script>
<script>

    $(function () {
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            startDate: '+1d',
            autoclose: true
        });
    })

</script>
<?php
$qryArray = array();
$sql = "SELECT * FROM cmsprop WHERE cms_id = 1";
$cmsProp = $patchworks->run($sql, array(), true);


if (!empty($cmsProp->capkey) && !empty($cmsProp->capsec)) {
    ?>

    <script src="https://www.google.com/recaptcha/api.js?render=<?php echo $cmsProp->capkey;?>"></script>
    <script>
        grecaptcha.ready(function () {
            grecaptcha.execute(
                '<?php echo $cmsProp->capkey;?>',
                {
                    action: 'homepage'
                }
            ).then(function (token) {
                $('input[name="g-recaptcha-response"]').val(token);
            });
        });
    </script>
    <?php
}
?>