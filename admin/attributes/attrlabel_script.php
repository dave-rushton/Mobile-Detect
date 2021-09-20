<?php

require_once("../../config/config.php");
require_once("../patchworks.php");
require_once("../attributes/classes/attrlabels.cls.php");



$throwJSON = array();
$throwJSON['id'] = '0';
$throwJSON['title'] = 'noaction';
$throwJSON['description'] = 'no action taken';
$throwJSON['type'] = 'warning';

$AtlDao = new AtlDAO();

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'resort') {

	$Atl_ID = (isset($_REQUEST['atl_id'])) ? $_REQUEST['atl_id'] : die('FAIL');
	$AtlDao->resort($Atl_ID);
	
	$throwJSON['id'] = 0;
	$throwJSON['title'] = 'Resort Complete';
	$throwJSON['description'] = 'Resort Complete';
	$throwJSON['type'] = 'success';
	
	die(json_encode($throwJSON));
}

$Atl_ID = (isset($_REQUEST['atl_id']) && is_numeric($_REQUEST['atl_id'])) ? $_REQUEST['atl_id'] : die('FAIL');
$Atr_ID = (isset($_REQUEST['atr_id']) && is_numeric($_REQUEST['atr_id'])) ? $_REQUEST['atr_id'] : NULL;

if (is_null($Atl_ID)) {
	$throwJSON['title'] = 'Invalid Attribute Label';
	$throwJSON['description'] = 'Attribute label not found';
	$throwJSON['type'] = 'error';
	
	die(json_encode($throwJSON));
}



if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'select') {
	
	$AtlObj = $AtlDao->select(NULL, $Atl_ID);
	die(json_encode($AtlObj));
	
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update') {
	
	if (is_null($Atr_ID)) {
		$throwJSON['title'] = 'Invalid Attribute';
		$throwJSON['description'] = 'Attribute not found';
		$throwJSON['type'] = 'error';
		die(json_encode($throwJSON));
	}
	
	$AtlObj = $AtlDao->select(NULL, $Atl_ID, true);
	
	if (!$AtlObj) {
		
		$AtlObj = new stdClass();
		
		$AtlObj->atr_id = 0;
		$AtlObj->atl_id = 0;
		$AtlObj->atllbl = '';
		$AtlObj->atltyp = '';
		$AtlObj->atlreq = 0;
		$AtlObj->atllbl = '';
		$AtlObj->srtord = 0;
		$AtlObj->srtord = 0;
		$AtlObj->srctyp = '';
		$AtlObj->atldsc = '';
		$AtlObj->duplicate_reference = '';
		$AtlObj->colnum = '1';
	}
    if (isset($_REQUEST['atr_id']) && is_numeric($_REQUEST['atr_id'])) $AtlObj->atr_id = $_REQUEST['atr_id'];
    if (isset($_REQUEST['atllbl'])) $AtlObj->atllbl = $_REQUEST['atllbl'];
    if (isset($_REQUEST['atltyp'])) $AtlObj->atltyp = $_REQUEST['atltyp'];
    if (isset($_REQUEST['atllst'])) $AtlObj->atllst = $_REQUEST['atllst'];
    if (isset($_REQUEST['atlreq']) && is_numeric($_REQUEST['atlreq'])) { $AtlObj->atlreq = 1; } else { $AtlObj->atlreq = 0; }
    if (isset($_REQUEST['atlspc']) && is_numeric($_REQUEST['atlspc'])) { $AtlObj->atlspc = 1; } else { $AtlObj->atlspc = 0; }
    if (isset($_REQUEST['srtord']) && is_numeric($_REQUEST['srtord'])) { $AtlObj->srtord = $_REQUEST['srtord']; } else { $AtlObj->srtord = 99; }
    if (isset($_REQUEST['srcabl']) && is_numeric($_REQUEST['srcabl'])) { $AtlObj->srcabl = 1; } else { $AtlObj->srcabl = 0; }
    if (isset($_REQUEST['srctyp'])) $AtlObj->srctyp = $_REQUEST['srctyp'];
    if (isset($_REQUEST['duplicate_reference'])) $AtlObj->duplicate_reference = $_REQUEST['duplicate_reference'];
    if (isset($_REQUEST['atldsc'])) $AtlObj->atldsc = $_REQUEST['atldsc'];
    if (isset($_REQUEST['colnum'])) $AtlObj->colnum = $_REQUEST['colnum'];

    $Atl_ID = $AtlDao->update($AtlObj);

    $throwJSON['id'] = $Atl_ID;
    $throwJSON['title'] = 'Attribute Created';
    $throwJSON['description'] = 'Attribute '.$AtlObj->atllbl.' created';
    $throwJSON['type'] = 'success';

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') {
	
	$AtlObj = $AtlDao->select(NULL, $Atl_ID, true);
	if ($AtlObj) $AtlDao->delete($AtlObj->atl_id);
	$throwJSON['id'] = $AtlObj->atl_id;
	$throwJSON['title'] = 'Attribute Deleted';
	$throwJSON['description'] = 'Attribute '.$AtlObj->atllbl.' deleted';
	$throwJSON['type'] = 'success';
}

die(json_encode($throwJSON));