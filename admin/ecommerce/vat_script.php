<?php

require_once("../../config/config.php");
require_once("../patchworks.php");
require_once("classes/vat.cls.php");

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


$Vat_ID = (isset($_REQUEST['vat_id']) && is_numeric($_REQUEST['vat_id'])) ? $_REQUEST['vat_id'] : die('FAIL');

if (is_null($Vat_ID)) {
	$throwJSON['title'] = 'Invalid VAT';
	$throwJSON['description'] = 'VAT not found';
	$throwJSON['type'] = 'error';
}

$VatDao = new VatDAO();

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update') {

	$VatObj = $VatDao->select($Vat_ID, NULL, NULL, true);
	

	if (!$VatObj) {
		
		$VatObj = new stdClass();
		
		$VatObj->vat_id = 0;
		$VatObj->vatnam = '';
		$VatObj->vatrat = 0;
		$VatObj->begdat = date("Y-m-d");
        $VatObj->defvat = 0;
		
		if (isset($_REQUEST['vatnam'])) $VatObj->vatnam = $_REQUEST['vatnam'];
		if (isset($_REQUEST['vatrat']) && is_numeric($_REQUEST['vatrat'])) $VatObj->vatrat = $_REQUEST['vatrat'];
        if (isset($_REQUEST['begdat'])) $VatObj->begdat = $_REQUEST['begdat'];
        if (isset($_REQUEST['defvat']) && is_numeric($_REQUEST['defvat'])) $VatObj->defvat = $_REQUEST['defvat'];
				
		$Vat_ID = $VatDao->update($VatObj);
		
		$throwJSON['id'] = $Vat_ID;
		$throwJSON['title'] = 'VAT Created';
		$throwJSON['description'] = 'VAT '.$VatObj->vatnam.' created';
		$throwJSON['type'] = 'success';

		
	} else {

        if (isset($_REQUEST['vatnam'])) $VatObj->vatnam = $_REQUEST['vatnam'];
        if (isset($_REQUEST['vatrat']) && is_numeric($_REQUEST['vatrat'])) $VatObj->vatrat = $_REQUEST['vatrat'];
        if (isset($_REQUEST['begdat'])) $VatObj->begdat = $_REQUEST['begdat'];
        if (isset($_REQUEST['defvat']) && is_numeric($_REQUEST['defvat'])) $VatObj->defvat = $_REQUEST['defvat'];
		
		$Vat_ID = $VatDao->update($VatObj);
		
		$throwJSON['id'] = $Vat_ID;
		$throwJSON['title'] = 'VAT Updated';
		$throwJSON['description'] = 'VAT '.$VatObj->vatnam.' updated';
		$throwJSON['type'] = 'success';
		
	}

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') {
	
	$VatObj = $VatDao->select($Vat_ID, NULL, NULL, true);
	if ($VatObj) {
		$VatDao->delete($VatObj->vat_id);
	
		$throwJSON['id'] = $VatObj->vat_id;
		$throwJSON['title'] = 'VAT Vateted';
		$throwJSON['description'] = 'VAT '.$VatObj->vatnam.' deleted';
		$throwJSON['type'] = 'success';
	} else {
		
		$throwJSON['id'] = $Vat_ID;
		$throwJSON['title'] = 'VAT No Found';
		$throwJSON['description'] = 'VAT not found';
		$throwJSON['type'] = 'error';

			
	}
	
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'select') {
	
	$VatRecs = $VatDao->select($Vat_ID, NULL, NULL, false);
	die(json_encode($VatRecs));
}

die(json_encode($throwJSON));

?>