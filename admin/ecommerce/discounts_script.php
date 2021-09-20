<?php

require_once("../../config/config.php");
require_once("../patchworks.php");
require_once("classes/discounts.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);

$throwJSON = array();
$throwJSON['id'] = '0';
$throwJSON['title'] = 'noaction';
$throwJSON['description'] = 'no action taken';
$throwJSON['type'] = 'warning';

if ($loggedIn == 0) {
	$throwJSON['title'] = 'Authorisation';
	$throwJSON['description'] = 'You are not authorised for this action';
	$throwJSON['type'] = 'error';
}


$Dis_ID = (isset($_REQUEST['dis_id']) && is_numeric($_REQUEST['dis_id'])) ? $_REQUEST['dis_id'] : NULL;

if (is_null($Dis_ID) ) {
	$throwJSON['title'] = 'Invalid Discount';
	$throwJSON['description'] = 'Discount not found';
	$throwJSON['type'] = 'error';
	
	die(json_encode($throwJSON));

}

$TmpDis = new DisDAO();

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update' && !is_null($Dis_ID)) {

	$DisObj = $TmpDis->select($Dis_ID, NULL, NULL, NULL, NULL, NULL, NULL, true);
	
	if (!$DisObj) {
		
		$DisObj = new stdClass();
		
		$DisObj->dis_id = 0;
		$DisObj->disnam = '';
		$DisObj->discod = '';
		$DisObj->sub_id = 0;
		$DisObj->atr_id = 0;
		$DisObj->prt_id = 0;
		$DisObj->prd_id = '';
		$DisObj->pctamt = 'P';
		$DisObj->disamt = 0;
		$DisObj->begdat = '';
		$DisObj->enddat = '';
		$DisObj->totuse = -1;
		$DisObj->minamt = 0;
		
		
		if (isset($_REQUEST['disnam'])) $DisObj->disnam = $_REQUEST['disnam'];
		if (isset($_REQUEST['discod'])) $DisObj->discod = $_REQUEST['discod'];
		if (isset($_REQUEST['sub_id']) && is_numeric($_REQUEST['sub_id'])) $DisObj->sub_id = $_REQUEST['sub_id'];
		if (isset($_REQUEST['atr_id']) && is_numeric($_REQUEST['atr_id'])) $DisObj->atr_id = $_REQUEST['atr_id'];
		if (isset($_REQUEST['prt_id']) && is_numeric($_REQUEST['prt_id'])) $DisObj->prt_id = $_REQUEST['prt_id'];
		if (isset($_REQUEST['prd_id'])) $DisObj->prd_id = $_REQUEST['prd_id'];
		if (isset($_REQUEST['pctamt'])) $DisObj->pctamt = $_REQUEST['pctamt'];
		if (isset($_REQUEST['disamt']) && is_numeric($_REQUEST['disamt'])) $DisObj->disamt = $_REQUEST['disamt'];
				
		if (isset($_REQUEST['begdat'])) $DisObj->begdat = $_REQUEST['begdat'];
		if (isset($_REQUEST['enddat'])) $DisObj->enddat = $_REQUEST['enddat'];
		if (isset($_REQUEST['totuse']) && is_numeric($_REQUEST['totuse'])) $DisObj->totuse = $_REQUEST['totuse'];
		if (isset($_REQUEST['minamt']) && is_numeric($_REQUEST['minamt'])) $DisObj->minamt = $_REQUEST['minamt'];
				
		$Dis_ID = $TmpDis->update($DisObj);
		
		$throwJSON['id'] = $Dis_ID;
		$throwJSON['title'] = 'Discount Created';
		$throwJSON['description'] = 'Discount '.$DisObj->disnam.' created';
		$throwJSON['type'] = 'success';

		
	} else {
		
		if (isset($_REQUEST['disnam'])) $DisObj->disnam = $_REQUEST['disnam'];
		if (isset($_REQUEST['discod'])) $DisObj->discod = $_REQUEST['discod'];
		if (isset($_REQUEST['sub_id']) && is_numeric($_REQUEST['sub_id'])) $DisObj->sub_id = $_REQUEST['sub_id'];
		if (isset($_REQUEST['atr_id']) && is_numeric($_REQUEST['atr_id'])) $DisObj->atr_id = $_REQUEST['atr_id'];
		if (isset($_REQUEST['prt_id']) && is_numeric($_REQUEST['prt_id'])) $DisObj->prt_id = $_REQUEST['prt_id'];
		if (isset($_REQUEST['prd_id'])) $DisObj->prd_id = $_REQUEST['prd_id'];
		if (isset($_REQUEST['pctamt'])) $DisObj->pctamt = $_REQUEST['pctamt'];
		if (isset($_REQUEST['disamt']) && is_numeric($_REQUEST['disamt'])) $DisObj->disamt = $_REQUEST['disamt'];
				
		if (isset($_REQUEST['begdat'])) $DisObj->begdat = $_REQUEST['begdat'];
		if (isset($_REQUEST['enddat'])) $DisObj->enddat = $_REQUEST['enddat'];
		if (isset($_REQUEST['totuse']) && is_numeric($_REQUEST['totuse'])) $DisObj->totuse = $_REQUEST['totuse'];
		if (isset($_REQUEST['minamt']) && is_numeric($_REQUEST['minamt'])) $DisObj->minamt = $_REQUEST['minamt'];
		
		$Dis_ID = $TmpDis->update($DisObj);
		
		$throwJSON['id'] = $Dis_ID;
		$throwJSON['title'] = 'Discount Updated';
		$throwJSON['description'] = 'Discount '.$DisObj->disnam.' updated';
		$throwJSON['type'] = 'success';
		
	}

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') {
	
	$DisObj = $TmpDis->select($Dis_ID, NULL, NULL, NULL, NULL, NULL, NULL, true);
	if ($DisObj) $TmpDis->delete($DisObj->dis_id);
	
	$throwJSON['id'] = $DisObj->sub_id;
	$throwJSON['title'] = 'Discount Deleted';
	$throwJSON['description'] = 'Discount '.$DisObj->subnam.' deleted';
	$throwJSON['type'] = 'success';
	
}

die(json_encode($throwJSON));

?>