<?php

require_once("../../config/config.php");
require_once("../patchworks.php");
require_once("../attributes/classes/attrvalues.cls.php");

function generateRandomString($length = 10): string
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

$TblNam = ($_REQUEST['atvtblnam']) ? $_REQUEST['atvtblnam'] : 'UNKNOWN';
$Tbl_ID = ($_REQUEST['atvtbl_id']) ? $_REQUEST['atvtbl_id'] : generateRandomString(30);

$body = '';

$AtvDao = new AtvDAO();

if ( isset($_REQUEST['formSubmit']) && $_REQUEST['httpChk'] == 'http://' ) {
	
	$arrayLabel = $_REQUEST['lbl'];
	$arrayValue = $_REQUEST['fld'];
	$arrayNumber = $_REQUEST['fldnum'];
	
	$AtvDao->clear($TblNam, $Tbl_ID);
    $AtvObj = new stdClass();
	if (sizeof($arrayLabel) > 0) {
	
		for ($a = 0; $a < sizeof($arrayLabel); $a++) {
			
			$body .= sprintf("%20s: %s\n",$arrayLabel[$a],$arrayValue[$a]); 

			$AtvObj->atv_id = 0;
			$AtvObj->atl_id = $arrayNumber[$a];
			$AtvObj->atr_id = $_REQUEST['atr_id'];
			$AtvObj->tblnam = $TblNam;
			$AtvObj->tbl_id = $Tbl_ID;
			$AtvObj->atvval = $arrayValue[$a];
			$Atv_ID = $AtvDao->update($AtvObj);
			
			$throwJSON['id'] = $Atv_ID;
			$throwJSON['title'] = 'Attribute Created';
			$throwJSON['description'] = 'Attribute value set created';
			$throwJSON['type'] = 'success';
		} 
	} else {
		$throwJSON['id'] = 0;
		$throwJSON['title'] = 'No Attribute Update';
		$throwJSON['description'] = 'Attribute value set not created';
		$throwJSON['type'] = 'warning';
	}
} else {
	$throwJSON['id'] = 0;
	$throwJSON['title'] = 'No Attribute Update';
	$throwJSON['description'] = 'Attribute value set not created';
	$throwJSON['type'] = 'warning';
}

die(json_encode($throwJSON));