<?php

require_once("../../config/config.php");
require_once("../../admin/patchworks.php");
require_once("../../admin/attributes/classes/attrgroups.cls.php");
require_once("../../admin/attributes/classes/attrlabels.cls.php");

require_once("../../admin/website/classes/pageelements.cls.php");

//echo '#'.$_GET['pel_id'].'#';

$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

$eleVarArr = json_decode($EleObj->elevar, true);

if (is_null($eleVarArr[0])) die('<p>invalid form</p>');

$Atr_ID = NULL;
foreach($eleVarArr[0] as $key => $item) {	
	if ($item == 'atr_id') $Atr_ID = $eleVarArr[0]['value'];
}

if (is_null($Atr_ID)) die();

$TmpAtr = new AtrDAO();
$TmpAtl = new AtlDAO();

$editAttrGroup =  $Atr_ID;
$attrGroupRec = NULL;
$attrLabelRec = NULL;

if (!is_null($editAttrGroup)) {
	$attrGroupRec = $TmpAtr->select($editAttrGroup, NULL, NULL, NULL, true, NULL, NULL, 0);
	$editReferenceTable = $attrGroupRec->tblnam;
	$attrLabelRec = $TmpAtl->select($editAttrGroup); 
}

$FldNum = array();
$FldTyp = array();
$FldLbl = array();
$FldLst = array();
$FldReq = array();

$i = 0;

$tableLength = count($attrLabelRec);
for ($i=0;$i<$tableLength;++$i) {
								
	$FldNum[$i] = $attrLabelRec[$i]['atl_id'];
	$FldTyp[$i] = $attrLabelRec[$i]['atltyp'];
	$FldLbl[$i] = $attrLabelRec[$i]['atllbl'];
	$FldLst[$i] = $attrLabelRec[$i]['atllst'];
	$FldReq[$i] = ($attrLabelRec[$i]['atlreq'] == 1) ? true : false;

}

?>

<form class="form-vertical attributeForm" action="<?php echo $patchworks->webRoot; ?>pages/webformsubmit.php" data-parsley-validate  enctype="multipart/form-data" method="post">
	<div class="pw-form">
		<div class="pw-form-header">
			<h3>
				<?php echo ($attrGroupRec) ? $attrGroupRec->atrnam : 'Form Not Found'; ?></h3>
		</div>
		<?php echo ($attrGroupRec) ? '<div class="pw-form-description"><p>'.nl2br($attrGroupRec->atrdsc).'</p></div>' : 'Form Not Found'; ?></p>
		<div class="pw-form-content">
			<fieldset>
				<input type="hidden" name="atvtblnam" value="<?php echo ($attrGroupRec) ? $attrGroupRec->tblnam : ''; ?>" />
				<!--<input type="hidden" name="atvtbl_id" value="<?php echo (!is_null($editReferenceID)) ? $editReferenceID : ''; ?>" />-->
				<input type="hidden" name="atr_id" value="<?php echo ($attrGroupRec) ? $attrGroupRec->atr_id : 0;  ?>" />
				<input type="hidden" name="httpChk" value="http://" />
				<input type="hidden" name="ema_to" value="<?php echo ($attrGroupRec) ? $attrGroupRec->ema_to : '';  ?>" />
				<input type="hidden" name="fwdurl" value="<?php echo ($attrGroupRec) ? $attrGroupRec->fwdurl : '';  ?>" />
				
				<?php for ($i = 0; $i < sizeof($FldNum); $i++) { ?>
				<input type="hidden" name="fldnum[]" class="fldnum" value="<?php echo $FldNum[$i]; ?>" />
				<input type="hidden" name="lbl[]" class="fldlbl" value="<?php echo $FldLbl[$i]; ?>" />
				
				<?php if ( $FldTyp[$i] == 'text' ) {?>
				
				<div class="control-group form-group">
					<div class="controls">
						<label><?php echo $FldLbl[$i]; ?>:</label>
						<input type="text" name="fld[]" class="form-control fldval" id="Fld-<?php echo $FldNum[$i]; ?>" <?php if ($FldReq[$i] == true) echo 'required'; ?> data-validation-required-message="Please enter your name.">
						<p class="help-block"></p>
					</div>
				</div>

                <?php } elseif ($FldTyp[$i] == 'upload') { ?>

                    <div class="form-group">
                        <label for="exampleInputFile"><?php echo $FldLbl[$i]; ?></label>
                        <input type="file" name="uploadfile" id="Fld-<?php echo $FldNum[$i]; ?>">
                        <input type="hidden" name="fld[]" id="Fld-<?php echo $FldNum[$i]; ?>" value="uploadfile">
                        <p class="help-block">Example block-level help text here.</p>
                    </div>

                <?php } elseif ($FldTyp[$i] == 'date') { ?>

                    <div class="control-group form-group">
                        <div class="controls">
                            <label><?php echo $FldLbl[$i]; ?> <small><em>(DD-MM-YYYY)</em></small>:</label>
                            <input type="text" name="fld[]" class="form-control fldval" id="Fld-<?php echo $FldNum[$i]; ?>" <?php if ($FldReq[$i] == true) echo 'required'; ?> data-validation-required-message="Please enter a date."  data-parsley-pattern="/[0-9][0-9]-[0-9][0-9]-[0-9][0-9][0-9][0-9]/i" placeholder="DD-MM-YYYY">
                            <p class="help-block"></p>
                        </div>
                    </div>

				<?php } else if ( $FldTyp[$i] == 'singleCheckbox' ) { ?>
				<div class="control-group">
					<label class="control-label"><?php echo $FldLbl[$i]; ?></label>
					<div class="controls">
						<label class="checkbox">
							<input type="checkbox" id="cbfor-<?php echo $FldNum[$i]; ?>" >
							Website - Templates </label>
					</div>
				</div>
				<input type="hidden" name="fld[]" class="fldval" id="cbact-<?php echo $FldNum[$i]; ?>" value="" />
				<?php } else if ( $FldTyp[$i] == 'select' ) { ?>
				<div class="control-group">
					<label class="control-label"><?php echo $FldLbl[$i]; ?></label>
					<div class="controls">
						<select name="fld[]" id="Fld-<?php echo $FldNum[$i]; ?>" class="fldval select form-control" <?php if ($FldReq[$i] == true) echo 'required'; ?>>
							<option value="">Select Option...</option>
							<?php 
										$SelOpt = explode(",",$FldLst[$i]);
										for ($s = 0; $s < sizeof($SelOpt); $s++) { ?>
							<option value="<?php echo $SelOpt[$s]; ?>"><?php echo $SelOpt[$s]; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<?php } else if ( $FldTyp[$i] == 'textarea' ) { ?>
				
				
				<div class="control-group form-group">
					<div class="controls">
						<label><?php echo $FldLbl[$i]; ?>:</label>
						<textarea name="fld[]" id="FldNum-<?php echo $FldNum[$i]; ?>" rows="10" cols="100" class="form-control fldval <?php echo ($FldReq[$i]) ? 'required' : ''; ?>" id="message" required data-validation-required-message="Please enter your message" maxlength="999" style="resize:none"></textarea>
					</div>
				</div>
				
				<?php } else if ( $FldTyp[$i] == 'radio' ) { ?>
				<div class="control-group">
					<label class="control-label"><?php echo $FldLbl[$i]; ?></label>
					<div class="controls">
						<?php
									$SelOpt = explode(",",$FldLst[$i]);
									for ($s = 0; $s < sizeof($SelOpt); $s++) {
									?>
						<label class="radio">
							<input type="radio" name="rbact-<?php echo $FldNum[$i]; ?>" value="<?php echo $SelOpt[$s]; ?>" >
							<?php echo $SelOpt[$s]; ?></label>
						<?php } ?>
						<input type="hidden" name="fld[]" />
					</div>
				</div>
				<?php } else if ( $FldTyp[$i] == 'checkbox' ) { ?>

						<?php 
									$SelOpt = explode(",",$FldLst[$i]);
									for ($s = 0; $s < sizeof($SelOpt); $s++) {
									?>
                    <div class="checkbox">
						<label>
                            <input type="checkbox" name="cbact-<?php echo $FldNum[$i]; ?>" value="<?php echo $SelOpt[$s]; ?>">
                            <?php echo $FldLbl[$i]; ?>
						</label>
						<?php } ?>
						<input type="hidden" name="fld[]" />
                        </div>
				<?php
							}
						}
						?>
				<input type="hidden" value="Create Attribute Set" name="formSubmit" id="atvsetSubmit" />
			</fieldset>
		</div>
		
		
		<div class="form-actions">
			<button type="submit" class="btn btn-primary"><?php echo ($attrGroupRec) ? $attrGroupRec->btntxt : 'Submit';  ?></button>
		</div>
	</div>
</form>
