<?php
require_once("../../../config/config.php");
require_once("../../patchworks.php");
require_once("../../attributes/classes/attrgroups.cls.php");
require_once("../../attributes/classes/attrlabels.cls.php");
require_once("../../attributes/classes/attrvalues.cls.php");

//$userAuth = new AuthDAO();
//$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
//if ($loggedIn == 0) header('location: ../login.php');

$TmpAtr = new AtrDAO();
$TmpAtl = new AtlDAO();

$editAttrGroup = (isset($_GET['atr_id']) && is_numeric($_GET['atr_id'])) ? $_GET['atr_id'] : NULL;
$attrGroupRec = NULL;
$attrLabelRec = NULL;

$editMode = isset($_GET['edit']);

$searchMode = isset($_GET['search']) && is_numeric($_GET['search']);

$editReferenceTable = (isset($_GET['atvtblnam'])) ? $_GET['atvtblnam'] : 'UNKNOWN';
$editReferenceID = (isset($_GET['atvtbl_id']) && is_numeric($_GET['atvtbl_id'])) ? $_GET['atvtbl_id'] : '';

$attrGroupRec = $TmpAtr->select($editAttrGroup, NULL, NULL, NULL, true, NULL, NULL, NULL);

if (!isset($attrGroupRec->tblnam)) die();

$editReferenceTable = $attrGroupRec->tblnam;

$TmpAtv = new AtvDAO();
$attrLabelRec = $TmpAtv->selectValueSet($attrGroupRec->atr_id, $editReferenceTable, $editReferenceID, 'products', 'prd_id', NULL);

$FldNum = array();
$FldTyp = array();
$FldLbl = array();
$FldLst = array();
$FldReq = array();
$FldVal = array();
$FldDsc = array();

$i = 0;

$tableLength = count($attrLabelRec);
for ($i = 0; $i < $tableLength; ++$i) {

    $FldNum[$i] = $attrLabelRec[$i]['atl_id'];
    $FldTyp[$i] = $attrLabelRec[$i]['atltyp'];
    $FldLbl[$i] = $attrLabelRec[$i]['atllbl'];
    $FldLst[$i] = $attrLabelRec[$i]['atllst'];
    $FldDsc[$i] = $attrLabelRec[$i]['atldsc'];
    $FldReq[$i] = $attrLabelRec[$i]['atlreq'] == 1;
    $FldVal[$i] = $attrLabelRec[$i]['atvval'];

}
?>

<div id="attrForm_<?php echo $editReferenceTable; ?>">

    <div
        id="atrTableDetails" <?php if (!is_null($editReferenceTable) || !is_null($editReferenceID)) echo 'class="hide"' ?>>
        <input type="<?php echo (!is_null($editReferenceTable)) ? 'hidden' : 'text'; ?>" name="atvtblnam"
               value="<?php echo (!is_null($editReferenceTable)) ? $editReferenceTable : 'UNKNOWN'; ?>"/>
        <input type="<?php echo (!is_null($editReferenceID)) ? 'hidden' : 'text'; ?>" name="atvtbl_id" id="AtvTbl_ID"
               value="<?php echo (!is_null($editReferenceID)) ? $editReferenceID : ''; ?>"/>
    </div>
    <input type="hidden" name="atr_id" value="<?php echo ($attrGroupRec) ? $attrGroupRec->atr_id : 0; ?>"/>
    <input type="hidden" name="httpChk" value="http://"/>
    <input type="hidden" name="ema_to" value="<?php echo ($attrGroupRec) ? $attrGroupRec->ema_to : ''; ?>"/>
    <input type="hidden" name="fwdurl" value="<?php echo ($attrGroupRec) ? $attrGroupRec->fwdurl : ''; ?>"/>
    <?php for ($i = 0; $i < sizeof($FldNum); $i++) { ?>
        <input type="hidden" name="fldnum[]" class="fldnum" value="<?php echo $FldNum[$i]; ?>"/>
        <input type="hidden" name="lbl[]" class="fldlbl" value="<?php echo $FldLbl[$i]; ?>"/>
        <?php if ($FldTyp[$i] == 'text') { ?>
            <div class="control-group">
                <label class="control-label"><?php echo $FldLbl[$i]; ?>
                    <small><?php echo $FldDsc[$i]; ?></small>
                </label>

                <div class="controls">
                    <input type="text" name="fld[]" id="Fld-<?php echo $FldNum[$i]; ?>"
                           class="input-large fldval <?php echo ($FldReq[$i] && $editMode) ? 'required' : ''; ?>"
                           value="<?php echo $FldVal[$i]; ?>"/>
                </div>
            </div>
        <?php } else if ($FldTyp[$i] == 'singleCheckbox') { ?>
            <div class="control-group">
                <label class="control-label"><?php echo $FldLbl[$i]; ?>
                    <small><?php echo $FldDsc[$i]; ?></small>
                </label>

                <div class="controls">
                    <label class="checkbox">
                        <input type="checkbox" id="cbfor-<?php echo $FldNum[$i]; ?>">
                        Website - Templates </label>
                </div>
            </div>
            <input type="hidden" name="fld[]" class="fldval" id="cbact-<?php echo $FldNum[$i]; ?>" value=""/>
        <?php } else if ($FldTyp[$i] == 'select') { ?>
            <div class="control-group">
                <label class="control-label"><?php echo $FldLbl[$i]; ?>
                    <small><?php echo $FldDsc[$i]; ?></small>
                </label>

                <div class="controls">
                    <select name="fld[]" id="Fld-<?php echo $FldNum[$i]; ?>" class="fldval select">
                        <option value="">Select Option...</option>
                        <?php
                        $SelOpt = explode(",", $FldLst[$i]);
                        for ($s = 0; $s < sizeof($SelOpt); $s++) { ?>
                            <option
                                value="<?php echo $SelOpt[$s]; ?>" <?php echo ($SelOpt[$s] == $FldVal[$i]) ? 'selected' : ''; ?>><?php echo $SelOpt[$s]; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        <?php } else if ($FldTyp[$i] == 'textarea') { ?>
            <div class="control-group">
                <label class="control-label"><?php echo $FldLbl[$i]; ?>
                    <small><?php echo $FldDsc[$i]; ?></small>
                </label>

                <div class="controls">
                    <textarea name="fld[]" id="FldNum-<?php echo $FldNum[$i]; ?>"
                              class="fldval <?php echo ($FldReq[$i] && $editMode) ? 'required' : ''; ?>"><?php echo $FldVal[$i]; ?></textarea>
                </div>
            </div>
        <?php } else if ($FldTyp[$i] == 'radio') { ?>
            <div class="control-group">
                <label class="control-label"><?php echo $FldLbl[$i]; ?>
                    <small><?php echo $FldDsc[$i]; ?></small>
                </label>

                <div class="controls">
                    <?php
                    $SelOpt = explode(",", $FldLst[$i]);
                    for ($s = 0; $s < sizeof($SelOpt); $s++) {
                        ?>
                        <label class="radio">
                            <input type="radio" name="rbact-<?php echo $FldNum[$i]; ?>"
                                   value="<?php echo $SelOpt[$s]; ?>" <?php echo ($SelOpt[$s] == $FldVal[$i]) ? 'checked' : ''; ?>>
                            <?php echo $SelOpt[$s]; ?></label>
                    <?php } ?>
                    <input type="hidden" name="fld[]"/>
                </div>
            </div>
        <?php } else if ($FldTyp[$i] == 'checkbox') { ?>
            <div class="control-group">
                <label class="control-label"><?php echo $FldLbl[$i]; ?>
                    <small><?php echo $FldDsc[$i]; ?></small>
                </label>

                <div class="controls">

                    <?php if (!$searchMode) { ?>


                        <?php
                        $SelOpt = explode(",", $FldLst[$i]);
                        for ($s = 0; $s < sizeof($SelOpt); $s++) {
                            ?>
                            <label class="checkbox">
                                <input type="checkbox" name="cbact-<?php echo $FldNum[$i]; ?>"
                                       value="<?php echo $SelOpt[$s]; ?>" <?php echo ($FldVal[$i] == 1) ? 'checked' : ''; ?>>
                                <?php echo $SelOpt[$s]; ?>
                            </label>
                        <?php } ?>
                        <input type="hidden" name="fld[]"/>

                    <?php } else { ?>

                        <select name="fld[]" id="Fld-<?php echo $FldNum[$i]; ?>" class="fldval select">
                            <option value="">Select Option...</option>
                            <option value="1">Include <?php echo $FldDsc[$i]; ?></option>
                            <option value="0">Exclude <?php echo $FldDsc[$i]; ?></option>
                        </select>

                    <?php } ?>

                </div>
            </div>
            <?php
        }
    }
    ?>
    <input type="hidden" value="submit" name="formSubmit" id="atvsetSubmit"/>

</div>