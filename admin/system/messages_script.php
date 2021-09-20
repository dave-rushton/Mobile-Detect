<?php

require_once("../../config/config.php");
require_once("../patchworks.php");
require_once("classes/messages.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
//if ($loggedIn == 0) header('location: ../login.php');

$throwJSON = array();
$throwJSON['id'] = '0';
$throwJSON['title'] = 'noaction';
$throwJSON['description'] = 'no action taken';
$throwJSON['type'] = 'warning';

if ($loggedIn == 0) {
	
	//header('location: ../login.php');
		
	$throwJSON['title'] = 'Authorisation';
	$throwJSON['description'] = 'You are not authorised for this action';
	$throwJSON['type'] = 'error';
}


$Msg_ID = (isset($_REQUEST['msg_id']) && is_numeric($_REQUEST['msg_id'])) ? $_REQUEST['msg_id'] : NULL;

if (is_null($Msg_ID && is_null($Msg_ID))) {
	$throwJSON['title'] = 'Invalid Message';
	$throwJSON['description'] = 'Message not found';
	$throwJSON['type'] = 'error';
	
	die(json_encode($throwJSON));

}

$MsgDao = new MsgDAO();

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update' && !is_null($Msg_ID)) {

	$MsgObj = $MsgDao->select($Msg_ID, NULL, NULL, NULL, NULL, true);
	
	if (!$MsgObj) {
		
		$MsgObj = new stdClass();
		$MsgObj->msg_id = 0;
		$MsgObj->sta_id = 0;
		$MsgObj->tblnam = '';
		$MsgObj->tbl_id = '';
		$MsgObj->atr_id = 0;
		$MsgObj->msgttl = '';
		$MsgObj->msgtxt = '';
		
		if (isset($_REQUEST['msg_id']) && is_numeric($_REQUEST['msg_id'])) $MsgObj->msg_id = $_REQUEST['msg_id'];
		if (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) $MsgObj->sta_id = $_REQUEST['sta_id'];
		
		if (isset($_REQUEST['tblnam'])) $MsgObj->tblnam = $_REQUEST['tblnam'];
		if (isset($_REQUEST['tbl_id'])) $MsgObj->tbl_id = $_REQUEST['tbl_id'];
		if (isset($_REQUEST['atr_id']) && is_numeric($_REQUEST['atr_id'])) $MsgObj->atr_id = $_REQUEST['atr_id'];
		
		if (isset($_REQUEST['msgttl'])) $MsgObj->msgttl = $_REQUEST['msgttl'];
		if (isset($_REQUEST['msgttl'])) $MsgObj->msgtxt = $_REQUEST['msgtxt'];
		
		$Msg_ID = $MsgDao->update($MsgObj);
		
		$throwJSON['id'] = $Msg_ID;
		$throwJSON['title'] = 'Message Created';
		$throwJSON['description'] = 'Message '.$MsgObj->msgttl.' created';
		$throwJSON['type'] = 'success';

		
	} else {
		
		if (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) $MsgObj->sta_id = $_REQUEST['sta_id'];
		
		if (isset($_REQUEST['tblnam'])) $MsgObj->tblnam = $_REQUEST['tblnam'];
		if (isset($_REQUEST['tbl_id'])) $MsgObj->tbl_id = $_REQUEST['tbl_id'];
		if (isset($_REQUEST['atr_id']) && is_numeric($_REQUEST['atr_id'])) $MsgObj->atr_id = $_REQUEST['atr_id'];
		
		if (isset($_REQUEST['msgttl'])) $MsgObj->msgttl = $_REQUEST['msgttl'];
		if (isset($_REQUEST['msgttl'])) $MsgObj->msgtxt = $_REQUEST['msgtxt'];
		
		$Msg_ID = $MsgDao->update($MsgObj);
		
		$throwJSON['id'] = $Msg_ID;
		$throwJSON['title'] = 'Message Updated';
		$throwJSON['description'] = 'Message '.$MsgObj->msgttl.' updated';
		$throwJSON['type'] = 'success';
		
	}

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') {
	
	$MsgObj = $MsgDao->select($Msg_ID, NULL, NULL, NULL, NULL, true);
	if ($MsgObj) $MsgDao->delete($MsgObj->msg_id);
	
	$throwJSON['id'] = $MsgObj->msg_id;
	$throwJSON['title'] = 'MsgMessage Deleted';
	$throwJSON['description'] = 'MsgMessage '.$MsgObj->msgttl.' deleted';
	$throwJSON['type'] = 'success';
	
}

die(json_encode($throwJSON));

?>