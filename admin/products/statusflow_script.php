<?php

require_once("../../config/config.php");
require_once("../patchworks.php");

require_once("classes/statusflow.cls.php");

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


$Flo_ID = (isset($_REQUEST['flo_id']) && is_numeric($_REQUEST['flo_id'])) ? $_REQUEST['flo_id'] : die('fail');

//if (is_null($Sta_ID)) {
//	$throwJSON['title'] = 'Invalid Status';
//	$throwJSON['description'] = 'Status not found';
//	$throwJSON['type'] = 'error';
//}

$FloDao = new FloDAO();

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'select') {
	
	$Frm_ID = (isset($_REQUEST['frm_id']) && is_numeric($_REQUEST['frm_id'])) ? $_REQUEST['frm_id'] : NULL;
	$Sta_ID = (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) ? $_REQUEST['sta_id'] : NULL;
	
	$statusCodes = $FloDao->select(NULL, $Frm_ID, NULL, false);
	die(json_encode($statusCodes));
	
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update') {
	
	
	$FloObj = $FloDao->select($Flo_ID, NULL, NULL, true);
	
	if (!$FloObj) {
		
		$FloObj = new stdClass();
		$FloObj->flo_id = 0;
		$FloObj->frm_id = 0;
		$FloObj->to_id  = 0;
		$FloObj->flonam = '';
		
		if (isset($_REQUEST['frm_id']) && is_numeric($_REQUEST['frm_id'])) $FloObj->frm_id = $_REQUEST['frm_id'];
		if (isset($_REQUEST['to_id']) && is_numeric($_REQUEST['to_id'])) $FloObj->to_id = $_REQUEST['to_id'];
		if (isset($_REQUEST['flonam'])) $FloObj->flonam = $_REQUEST['flonam'];
		
		$Flo_ID = $FloDao->update($FloObj);

		$throwJSON['id'] = $Sta_ID;
		$throwJSON['title'] = 'Flow Created';
		$throwJSON['description'] = 'Flow '.$FloObj->flonam.' created';
		$throwJSON['type'] = 'success';

		
	} else {
		
		if (isset($_REQUEST['frm_id']) && is_numeric($_REQUEST['frm_id'])) $FloObj->frm_id = $_REQUEST['frm_id'];
		if (isset($_REQUEST['to_id']) && is_numeric($_REQUEST['to_id'])) $FloObj->to_id = $_REQUEST['to_id'];
		if (isset($_REQUEST['flonam'])) $FloObj->flonam = $_REQUEST['flonam'];
		
		$Sta_ID = $FloDao->update($FloObj);
		
		$throwJSON['id'] = $Sta_ID;
		$throwJSON['title'] = 'Flow Updated';
		$throwJSON['description'] = 'Flow '.$FloObj->flonam.' updated';
		$throwJSON['type'] = 'success';
		
	}

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') {
	
	$FloObj = $FloDao->select($Flo_ID, NULL, NULL, true);
	if ($FloObj) $FloDao->delete($FloObj->flo_id);
	
	$throwJSON['id'] = $FloObj->flo_id;
	$throwJSON['title'] = 'Flow Deleted';
	$throwJSON['description'] = 'Flow '.$FloObj->flonam.' deleted';
	$throwJSON['type'] = 'success';
	
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'remove') {
	
	$Frm_ID = (isset($_REQUEST['frm_id']) && is_numeric($_REQUEST['frm_id'])) ? $_REQUEST['frm_id'] : NULL;
	$To_ID  = (isset($_REQUEST['to_id'])  && is_numeric($_REQUEST['to_id']))  ? $_REQUEST['to_id']  : NULL;
	
	if (!is_null($Frm_ID) && !is_null($To_ID)) {
		$FloDao->removeFlow($Frm_ID, $To_ID);
		
		$throwJSON['id'] = $FloObj->flo_id;
		$throwJSON['title'] = 'Flow Deleted';
		$throwJSON['description'] = 'Flow '.$FloObj->flonam.' deleted';
		$throwJSON['type'] = 'success';
	} else {
		$throwJSON['id'] = 0;
		$throwJSON['title'] = 'Invalid Flow';
		$throwJSON['description'] = 'Flow record not found';
		$throwJSON['type'] = 'error';
	}
	
}

die(json_encode($throwJSON));

?>