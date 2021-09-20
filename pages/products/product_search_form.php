<?php

//require_once("../../../config/config.php");
//require_once("../../patchworks.php");
//require_once("../../attributes/classes/attrgroups.cls.php");
//require_once("../../attributes/classes/attrlabels.cls.php");
//require_once("../../attributes/classes/attrvalues.cls.php");

$editMode = false;
$searchMode = true;

$attrGroupRec = NULL;
$attrLabelRec = NULL;

$TmpAtr = new AtrDAO();
$TmpAtl = new AtlDAO();



//$editAttrGroup = (isset($_GET['atr_id']) && is_numeric($_GET['atr_id'])) ? $_GET['atr_id'] : NULL;
//$editReferenceTable = (isset($_GET['atvtblnam'])) ? $_GET['atvtblnam'] : 'UNKNOWN';
//$editReferenceID = (isset($_GET['atvtbl_id']) && is_numeric($_GET['atvtbl_id'])) ? $_GET['atvtbl_id'] : '';



$attrGroupRec = $TmpAtr->select($editAttrGroup, NULL, NULL, NULL, true, NULL, NULL, NULL);

if (isset($attrGroupRec->tblnam)) {

//$editReferenceTable = $attrGroupRec->tblnam;
    $attrLabelRec = $TmpAtl->select($attrGroupRec->atr_id);

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
        $FldReq[$i] = ($attrLabelRec[$i]['atlreq'] == 1) ? true : false;
        $FldSrc[$i] = ($attrLabelRec[$i]['srcabl'] == 1) ? true : false;
        $FldSty[$i] = $attrLabelRec[$i]['srctyp'];
        $FldVal[$i] = ($attrLabelRec[$i]['atvval']) ? $attrLabelRec[$i]['atvval'] : '';

    }

    if (!isset($_REQUEST['srtord'])) $SrtOrd = 'p.unipri DESC';

    ?>

    <form id="attrForm_<?php echo $editReferenceTable; ?>" class="form-vertical attributeForm">


        <div
            id="atrTableDetails" <?php if (!is_null($editReferenceTable) || !is_null($editReferenceID)) echo 'class="hide"' ?>>
            <input type="<?php echo (!is_null($editReferenceTable)) ? 'hidden' : 'text'; ?>" name="atvtblnam"
                   value="<?php echo (!is_null($editReferenceTable)) ? $editReferenceTable : 'UNKNOWN'; ?>"/>
            <input type="<?php echo (!is_null($editReferenceID)) ? 'hidden' : 'text'; ?>" name="atvtbl_id"
                   id="AtvTbl_ID"
                   value="<?php echo (!is_null($editReferenceID)) ? $editReferenceID : ''; ?>"/>
        </div>

        <input type="hidden" name="atr_id" value="<?php echo ($attrGroupRec) ? $attrGroupRec->atr_id : 0; ?>"/>

        <div class="form-group">
            <label class="control-label">Sort Order</label>

            <div class="controls">
                <select name="srtord" class="form-control">
                    <option value="p.unipri DESC" <?php if ($SrtOrd == 'p.unipri DESC') echo 'selected'; ?>>Price (High
                        to Low)
                    </option>
                    <option value="p.unipri ASC" <?php if ($SrtOrd == 'p.unipri ASC') echo 'selected'; ?>>Price (Low to
                        High)
                    </option>
                    <option value="p.prdnam ASC" <?php if ($SrtOrd == 'p.prdnam ASC') echo 'selected'; ?>>Name (A - Z)
                    </option>
                    <option value="p.prdnam DESC" <?php if ($SrtOrd == 'p.prdnam DESC') echo 'selected'; ?>>Name (Z -
                        A)
                    </option>
                </select>
            </div>
        </div>


        <?php
        for ($i = 0; $i < sizeof($FldNum); $i++) {

            if ($FldSrc[$i] != 1) continue;

            if ($FldSty[$i] == 'list') {

                $atvLst = $TmpAtv->selectDistinctValues('PRODUCTGROUP', NULL, $attrGroupRec->atr_id, $FldNum[$i], true);

                $valArray = explode(",", $atvLst->atvlst);
                asort($valArray);
                $atvLst->atvlst = implode(",", $valArray);

                // Switch type to select
                $FldTyp[$i] = 'select';
                $FldLst[$i] = $atvLst->atvlst;

            }

            ?>
            <input type="hidden" name="fldnum[]" class="fldnum" value="<?php echo $FldNum[$i]; ?>"/>
            <input type="hidden" name="lbl[]" class="fldlbl" value="<?php echo $FldLbl[$i]; ?>"/>

            <?php if ($FldTyp[$i] == 'text') { ?>
                <div class="form-group">
                    <label class="control-label"><?php echo $FldLbl[$i]; ?>
                        <small><?php echo $FldDsc[$i]; ?></small>
                    </label>

                    <div class="controls">

                        <?php
                        $selectedValue = '';
                        if (isset($_GET['fldnum']) && in_array($FldNum[$i], $_GET['fldnum'])) {
                            $selectedValue = $_GET['fld'][array_search($FldNum[$i], $_GET['fldnum'])];
                        }
                        ?>

                        <input type="text" name="fld[]" id="Fld-<?php echo $FldNum[$i]; ?>"
                               class="form-control fldval <?php echo ($FldReq[$i] && $editMode) ? 'required' : ''; ?>"
                               value="<?php echo $selectedValue; ?>"/>
                    </div>
                </div>

            <?php } else if ($FldTyp[$i] == 'singleCheckbox') { ?>
                <div class="form-group">
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
                <div class="form-group">
                    <label class="control-label"><?php echo $FldLbl[$i]; ?>
                        <small><?php echo $FldDsc[$i]; ?></small>
                    </label>

                    <div class="controls">

                        <?php
                        $selectedValue = '';
                        if (isset($_GET['fldnum']) && in_array($FldNum[$i], $_GET['fldnum'])) {
                            $selectedValue = $_GET['fld'][array_search($FldNum[$i], $_GET['fldnum'])];
                        }
                        ?>


                        <select name="fld[]" id="Fld-<?php echo $FldNum[$i]; ?>" class="fldval select form-control">
                            <option value="">Select Option...</option>
                            <?php
                            $SelOpt = explode(",", $FldLst[$i]);
                            for ($s = 0; $s < sizeof($SelOpt); $s++) {
                                ?>
                                <option
                                    value="<?php echo $SelOpt[$s]; ?>" <?php echo ($SelOpt[$s] == $selectedValue) ? 'selected' : ''; ?>><?php echo $SelOpt[$s]; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

            <?php } else if ($FldTyp[$i] == 'textarea') { ?>
                <div class="form-group">
                    <label class="control-label"><?php echo $FldLbl[$i]; ?>
                        <small><?php echo $FldDsc[$i]; ?></small>
                    </label>

                    <div class="controls">

                        <!--                    --><?php
                        //                    $selectedValue = '';
                        //                    if ( in_array($FldNum[$i], $_GET['fldnum']) ) {
                        //                        $selectedValue = $_GET['fld'][ array_search($FldNum[$i], $_GET['fldnum']) ];
                        //                    }
                        //                    ?>

                        <textarea name="fld[]" id="FldNum-<?php echo $FldNum[$i]; ?>"
                                  class="fldval form-control <?php echo ($FldReq[$i] && $editMode) ? 'required' : ''; ?>"><?php echo $selectedValue; ?></textarea>
                    </div>
                </div>

            <?php } else if ($FldTyp[$i] == 'radio') { ?>
                <div class="form-group">
                    <label class="control-label"><?php echo $FldLbl[$i]; ?>
                        <small><?php echo $FldDsc[$i]; ?></small>
                    </label>

                    <div class="controls">

                        <?php
                        $selectedValue = '';
                        if (in_array($FldNum[$i], $_GET['fldnum'])) {
                            $selectedValue = $_GET['fld'][array_search($FldNum[$i], $_GET['fldnum'])];
                        }
                        ?>

                        <?php
                        $SelOpt = explode(",", $FldLst[$i]);
                        for ($s = 0; $s < sizeof($SelOpt); $s++) {
                            ?>
                            <label class="radio">
                                <input type="radio" name="rbact-<?php echo $FldNum[$i]; ?>"
                                       value="<?php echo $SelOpt[$s]; ?>" <?php echo ($SelOpt[$s] == $selectedValue) ? 'checked' : ''; ?>>
                                <?php echo $SelOpt[$s]; ?></label>
                        <?php } ?>
                        <input type="hidden" name="fld[]"/>
                    </div>
                </div>

            <?php } else if ($FldTyp[$i] == 'checkbox') { ?>
                <div class="form-group">
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

                            <?php
                        } else {
                            $selectedValue = '';
                            if (isset($_GET['fldnum']) && in_array($FldNum[$i], $_GET['fldnum'])) {
                                $selectedValue = $_GET['fld'][array_search($FldNum[$i], $_GET['fldnum'])];
                            }
                            ?>

                            <select name="fld[]" id="Fld-<?php echo $FldNum[$i]; ?>" class="fldval select form-control">
                                <option value="">Select Option...</option>
                                <option value="1" <?php if ($selectedValue == 1) {
                                    echo 'selected';
                                } ?> >Include <?php echo $FldDsc[$i]; ?></option>
                                <option value="0" <?php if ($selectedValue == 0) {
                                    echo 'selected';
                                } ?>>Exclude <?php echo $FldDsc[$i]; ?></option>
                            </select>

                        <?php } ?>

                    </div>
                </div>
                <?php
            }
        }
        ?>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary" name="formaction" id="atvsetSubmit" value="SEARCH">SUBMIT
            </button>
        </div>


    </form>

    <?php
}
?>