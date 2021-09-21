<?php

require_once("../../config/config.php");
require_once("../patchworks.php");
require_once("classes/product_types.cls.php");

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


$Prt_ID = (isset($_REQUEST['prt_id']) && is_numeric($_REQUEST['prt_id'])) ? $_REQUEST['prt_id'] : die('FAIL');

if (is_null($Prt_ID)) {
	$throwJSON['title'] = 'Invalid Product Type';
	$throwJSON['description'] = 'Product not found';
	$throwJSON['type'] = 'error';
}

$PrtDao = new PrtDAO();

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update') {

	$PrtObj = $PrtDao->select($Prt_ID, NULL, NULL, NULL, NULL, NULL, NULL, NULL, true);
	
	if (!$PrtObj) {
		
		$PrtObj = new stdClass();
		
		$PrtObj->prt_id = 0;
		$PrtObj->tblnam = '';
		$PrtObj->tbl_id = 0;
		$PrtObj->prtnam = '';
		$PrtObj->prtdsc = '';
		$PrtObj->unipri = 0;
		$PrtObj->buypri = 0;
		$PrtObj->delpri = 0;
		$PrtObj->atr_id = 0;
		$PrtObj->sta_id = 0;
		
		$PrtObj->seourl = '';
		$PrtObj->seokey = '';
		$PrtObj->seodsc = '';
		
		$PrtObj->usestk = 0;
		
		if (isset($_REQUEST['tblnam'])) $PrtObj->tblnam = $_REQUEST['tblnam'];
		if (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) $PrtObj->tbl_id = $_REQUEST['tbl_id'];
		if (isset($_REQUEST['prt_id'])) $PrtObj->prt_id = $_REQUEST['prt_id'];
		if (isset($_REQUEST['prtnam'])) $PrtObj->prtnam = $_REQUEST['prtnam'];
		if (isset($_REQUEST['prtdsc'])) $PrtObj->prtdsc = $_REQUEST['prtdsc'];
		if (isset($_REQUEST['unipri'])) $PrtObj->unipri = $_REQUEST['unipri'];
		if (isset($_REQUEST['buypri'])) $PrtObj->buypri = $_REQUEST['buypri'];
		if (isset($_REQUEST['delpri'])) $PrtObj->delpri = $_REQUEST['delpri'];
		if (isset($_REQUEST['atr_id'])) $PrtObj->atr_id = $_REQUEST['atr_id'];
		if (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) $PrtObj->sta_id = $_REQUEST['sta_id'];
		
		if (isset($_REQUEST['seourl'])) $PrtObj->seourl = $_REQUEST['seourl'];
		if (isset($_REQUEST['seokey'])) $PrtObj->seokey = $_REQUEST['seokey'];
		if (isset($_REQUEST['seodsc'])) $PrtObj->seodsc = $_REQUEST['seodsc'];
		
		if (isset($_REQUEST['usestk']) && is_numeric($_REQUEST['usestk'])) $PrtObj->usestk = $_REQUEST['usestk'];
				
		$Prt_ID = $PrtDao->update($PrtObj);
		
		$throwJSON['id'] = $Prt_ID;
		$throwJSON['title'] = 'Product Type Created';
		$throwJSON['description'] = 'Product Type '.$PrtObj->prtnam.' created';
		$throwJSON['type'] = 'success';

		
	} else {
		
		if (isset($_REQUEST['tblnam'])) $PrtObj->tblnam = $_REQUEST['tblnam'];
		if (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) $PrtObj->tbl_id = $_REQUEST['tbl_id'];
		if (isset($_REQUEST['prtnam'])) $PrtObj->prtnam = $_REQUEST['prtnam'];
		if (isset($_REQUEST['prtdsc'])) $PrtObj->prtdsc = $_REQUEST['prtdsc'];
		if (isset($_REQUEST['unipri'])) $PrtObj->unipri = $_REQUEST['unipri'];
		if (isset($_REQUEST['buypri'])) $PrtObj->buypri = $_REQUEST['buypri'];
		if (isset($_REQUEST['delpri'])) $PrtObj->delpri = $_REQUEST['delpri'];
		if (isset($_REQUEST['atr_id'])) $PrtObj->atr_id = $_REQUEST['atr_id'];
		if (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) $PrtObj->sta_id = $_REQUEST['sta_id'];
		
		if (isset($_REQUEST['seourl'])) $PrtObj->seourl = $_REQUEST['seourl'];
		if (isset($_REQUEST['seokey'])) $PrtObj->seokey = $_REQUEST['seokey'];
		if (isset($_REQUEST['seodsc'])) $PrtObj->seodsc = $_REQUEST['seodsc'];
		
		if (isset($_REQUEST['usestk']) && is_numeric($_REQUEST['usestk'])) $PrtObj->usestk = $_REQUEST['usestk'];
		
		$Prt_ID = $PrtDao->update($PrtObj);
		
		$throwJSON['id'] = $Prt_ID;
		$throwJSON['title'] = 'Product Type Updated';
		$throwJSON['description'] = 'Product Type '.$PrtObj->prtnam.' updated';
		$throwJSON['type'] = 'success';
		
	}

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') {
	
	$PrtObj = $PrtDao->select($Prt_ID, NULL, NULL, NULL, NULL, NULL, NULL, NULL, true);
	if ($PrtObj) {
		$PrtDao->delete($PrtObj->prt_id);
	
		$throwJSON['id'] = $PrtObj->prt_id;
		$throwJSON['title'] = 'Product Type Deleted';
		$throwJSON['description'] = 'Product Type '.$PrtObj->prtnam.' deleted';
		$throwJSON['type'] = 'success';
	} else {
		
		$throwJSON['id'] = $Prt_ID;
		$throwJSON['title'] = 'Product Type No Found';
		$throwJSON['description'] = 'Product Type not found';
		$throwJSON['type'] = 'error';

			
	}
	
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'select') {
	
	$products = $PrtDao->select($Prt_ID, NULL, NULL, NULL, NULL, NULL, NULL, NULL, false);
	die(json_encode($products));
}

die(json_encode($throwJSON));

?>