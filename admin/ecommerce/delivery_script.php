<?php

require_once("../../config/config.php");
require_once("../patchworks.php");
require_once("classes/delivery.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
//if ($loggedIn == 0) header('location: ../login.php');

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


$Del_ID = (isset($_REQUEST['del_id']) && is_numeric($_REQUEST['del_id'])) ? $_REQUEST['del_id'] : die('FAIL');

if (is_null($Del_ID)) {
	$throwJSON['title'] = 'Invalid Delivery';
	$throwJSON['description'] = 'Delivery not found';
	$throwJSON['type'] = 'error';
}

$DelDao = new DelDAO();

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update') {

	$DelObj = $DelDao->select($Del_ID, NULL, NULL, NULL, true);
	

	if (!$DelObj) {
		
		$DelObj = new stdClass();
		
		$DelObj->del_id = 0;
		$DelObj->delnam = '';
		$DelObj->delpri = 0;
		$DelObj->delcod = '';
		$DelObj->deltyp = 'PRICE';
		$DelObj->deldis = 0;
		$DelObj->maxdis = 0;
		$DelObj->sta_id = 0;
		
		if (isset($_REQUEST['delnam'])) $DelObj->delnam = $_REQUEST['delnam'];
		if (isset($_REQUEST['delpri']) && is_numeric($_REQUEST['delpri'])) $DelObj->delpri = $_REQUEST['delpri'];
        if (isset($_REQUEST['delcod'])) $DelObj->delcod = $_REQUEST['delcod'];
        if (isset($_REQUEST['deltyp'])) $DelObj->deltyp = $_REQUEST['deltyp'];
        if (isset($_REQUEST['deldis']) && is_numeric($_REQUEST['deldis'])) $DelObj->deldis = $_REQUEST['deldis'];
        if (isset($_REQUEST['maxdis']) && is_numeric($_REQUEST['maxdis'])) $DelObj->maxdis = $_REQUEST['maxdis'];
        if (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) $DelObj->sta_id = $_REQUEST['sta_id'];
				
		$Del_ID = $DelDao->update($DelObj);
		
		$throwJSON['id'] = $Del_ID;
		$throwJSON['title'] = 'Delivery Created';
		$throwJSON['description'] = 'Delivery '.$DelObj->delnam.' created';
		$throwJSON['type'] = 'success';

		
	} else {

        if (isset($_REQUEST['delnam'])) $DelObj->delnam = $_REQUEST['delnam'];
        if (isset($_REQUEST['delpri']) && is_numeric($_REQUEST['delpri'])) $DelObj->delpri = $_REQUEST['delpri'];
        if (isset($_REQUEST['delcod'])) $DelObj->delcod = $_REQUEST['delcod'];
        if (isset($_REQUEST['deltyp'])) $DelObj->deltyp = $_REQUEST['deltyp'];
        if (isset($_REQUEST['deldis']) && is_numeric($_REQUEST['deldis'])) $DelObj->deldis = $_REQUEST['deldis'];
        if (isset($_REQUEST['maxdis']) && is_numeric($_REQUEST['maxdis'])) $DelObj->maxdis = $_REQUEST['maxdis'];
        if (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) $DelObj->sta_id = $_REQUEST['sta_id'];
		
		$Del_ID = $DelDao->update($DelObj);
		
		$throwJSON['id'] = $Del_ID;
		$throwJSON['title'] = 'Delivery Updated';
		$throwJSON['description'] = 'Delivery '.$DelObj->delnam.' updated';
		$throwJSON['type'] = 'success';
		
	}

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') {
	
	$DelObj = $DelDao->select($Del_ID, NULL,NULL, NULL, true);
	if ($DelObj) {
		$DelDao->delete($DelObj->del_id);
	
		$throwJSON['id'] = $DelObj->del_id;
		$throwJSON['title'] = 'Delivery Deleted';
		$throwJSON['description'] = 'Delivery '.$DelObj->delnam.' deleted';
		$throwJSON['type'] = 'success';
	} else {
		
		$throwJSON['id'] = $Del_ID;
		$throwJSON['title'] = 'Delivery No Found';
		$throwJSON['description'] = 'Delivery not found';
		$throwJSON['type'] = 'error';

			
	}
	
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'select') {
	
	$deliveries = $DelDao->select($Del_ID, NULL,NULL, NULL, false);
	die(json_encode($deliveries));
}

die(json_encode($throwJSON));

?>