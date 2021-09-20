<?php

require_once("../../config/config.php");
require_once("../patchworks.php");

require_once("classes/statuscodes.cls.php");

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


$Sta_ID = (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) ? $_REQUEST['sta_id'] : die('FAIL');

if (is_null($Sta_ID)) {
	$throwJSON['title'] = 'Invalid Status';
	$throwJSON['description'] = 'Status not found';
	$throwJSON['type'] = 'error';
}

$StaDao = new StaDAO();

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'select') {
	
	$TblNam = NULL;
	if (isset($_REQUEST['tblnam'])) $TblNam = $_REQUEST['tblnam'];
	
	$statusCodes = $StaDao->select(NULL, $TblNam, false);
	die(json_encode($statusCodes));
	
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update') {

	$StaObj = $StaDao->select($Sta_ID, NULL, true);
	
	if (!$StaObj) {
		
		$StaObj = new stdClass();
		$StaObj->sta_id = 0;
		$StaObj->tblnam = '';
		$StaObj->stanam = '';
		
		if (isset($_REQUEST['tblnam'])) $StaObj->tblnam = $_REQUEST['tblnam'];
		if (isset($_REQUEST['stanam'])) $StaObj->stanam = $_REQUEST['stanam'];
		
		$Sta_ID = $StaDao->update($StaObj);
		
		$throwJSON['id'] = $Sta_ID;
		$throwJSON['title'] = 'Status Created';
		$throwJSON['description'] = 'Status '.$StaObj->stanam.' created';
		$throwJSON['type'] = 'success';

		
	} else {
		
		if (isset($_REQUEST['tblnam'])) $StaObj->tblnam = $_REQUEST['tblnam'];
		if (isset($_REQUEST['stanam'])) $StaObj->stanam = $_REQUEST['stanam'];
		
		$Sta_ID = $StaDao->update($StaObj);
		
		$throwJSON['id'] = $Sta_ID;
		$throwJSON['title'] = 'Place Updated';
		$throwJSON['description'] = 'Place '.$StaObj->stanam.' updated';
		$throwJSON['type'] = 'success';
		
	}

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') {
	
	$StaObj = $StaDao->select($Sta_ID, NULL, true);
	if ($StaObj) $StaDao->delete($StaObj->sta_id);
	
	$throwJSON['id'] = $StaObj->sta_id;
	$throwJSON['title'] = 'Place Deleted';
	$throwJSON['description'] = 'Place '.$StaObj->stanam.' deleted';
	$throwJSON['type'] = 'success';
	
}

die(json_encode($throwJSON));

?>