<?php

require_once("../../config/config.php");
require_once("../patchworks.php");
require_once("classes/multibuy.cls.php");

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


$Mul_ID = (isset($_REQUEST['mul_id']) && is_numeric($_REQUEST['mul_id'])) ? $_REQUEST['mul_id'] : die('FAIL');

if (is_null($Mul_ID)) {
	$throwJSON['title'] = 'Invalid Multibuy';
	$throwJSON['description'] = 'Multibuy not found';
	$throwJSON['type'] = 'error';
}

$MulDao = new MulDAO();

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update') {

	$MulObj = $MulDao->select($Mul_ID, NULL, NULL, true);

	if (!$MulObj) {
		
		$MulObj = new stdClass();
		
		$MulObj->mul_id = 0;
		$MulObj->tblnam = '';
		$MulObj->tbl_id = 0;
		$MulObj->multtl = 'NO TITLE';
        $MulObj->multyp = 0;
        $MulObj->minbuy = 0;
        $MulObj->minpri = 0;
        $MulObj->prd_id = '';
		$MulObj->begdat = date("Y-m-d");
		$MulObj->enddat = date("Y-m-d");
        $MulObj->sta_id = 1;

		if (isset($_REQUEST['tblnam'])) $MulObj->tblnam = $_REQUEST['tblnam'];
		if (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) $MulObj->tbl_id = $_REQUEST['tbl_id'];

        if (isset($_REQUEST['multtl'])) $MulObj->multtl = $_REQUEST['multtl'];
        if (isset($_REQUEST['multyp'])) $MulObj->multyp = $_REQUEST['multyp'];
        if (isset($_REQUEST['minbuy']) && is_numeric($_REQUEST['minbuy'])) $MulObj->minbuy = $_REQUEST['minbuy'];
        if (isset($_REQUEST['minpri']) && is_numeric($_REQUEST['minpri'])) $MulObj->minpri = $_REQUEST['minpri'];
        if (isset($_REQUEST['prd_id'])) $MulObj->prd_id = $_REQUEST['prd_id'];

        if (isset($_REQUEST['begdat'])) $MulObj->begdat = $_REQUEST['begdat'];
        if (isset($_REQUEST['enddat'])) $MulObj->enddat = $_REQUEST['enddat'];

        if (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) $MulObj->sta_id = $_REQUEST['sta_id'];

        if (isset($_REQUEST['pctamt'])) $MulObj->pctamt = $_REQUEST['pctamt'];
        if (isset($_REQUEST['disamt']) && is_numeric($_REQUEST['disamt'])) $MulObj->disamt = $_REQUEST['disamt'];

		$Mul_ID = $MulDao->update($MulObj);
		
		$throwJSON['id'] = $Mul_ID;
		$throwJSON['title'] = 'Multibuy Created';
		$throwJSON['description'] = 'Multibuy '.$MulObj->tblnam.' created';
		$throwJSON['type'] = 'success';

		
	} else {

        if (isset($_REQUEST['tblnam'])) $MulObj->tblnam = $_REQUEST['tblnam'];
        if (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) $MulObj->tbl_id = $_REQUEST['tbl_id'];

        if (isset($_REQUEST['multtl'])) $MulObj->multtl = $_REQUEST['multtl'];
        if (isset($_REQUEST['multyp'])) $MulObj->multyp = $_REQUEST['multyp'];
        if (isset($_REQUEST['minbuy']) && is_numeric($_REQUEST['minbuy'])) $MulObj->minbuy = $_REQUEST['minbuy'];
        if (isset($_REQUEST['minpri']) && is_numeric($_REQUEST['minpri'])) $MulObj->minpri = $_REQUEST['minpri'];
        if (isset($_REQUEST['prd_id'])) $MulObj->prd_id = $_REQUEST['prd_id'];

        if (isset($_REQUEST['begdat'])) $MulObj->begdat = $_REQUEST['begdat'];
        if (isset($_REQUEST['enddat'])) $MulObj->enddat = $_REQUEST['enddat'];

        if (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) $MulObj->sta_id = $_REQUEST['sta_id'];

        if (isset($_REQUEST['pctamt'])) $MulObj->pctamt = $_REQUEST['pctamt'];
        if (isset($_REQUEST['disamt']) && is_numeric($_REQUEST['disamt'])) $MulObj->disamt = $_REQUEST['disamt'];
		
		$Mul_ID = $MulDao->update($MulObj);
		
		$throwJSON['id'] = $Mul_ID;
		$throwJSON['title'] = 'Multibuy Updated';
		$throwJSON['description'] = 'Multibuy '.$MulObj->tblnam.' updated';
		$throwJSON['type'] = 'success';
		
	}

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') {
	
	$MulObj = $MulDao->select($Mul_ID, NULL, NULL, true);
	if ($MulObj) {
		$MulDao->delete($MulObj->mul_id);
	
		$throwJSON['id'] = $MulObj->mul_id;
		$throwJSON['title'] = 'Multibuy Muleted';
		$throwJSON['description'] = 'Multibuy '.$MulObj->tblnam.' deleted';
		$throwJSON['type'] = 'success';
	} else {
		
		$throwJSON['id'] = $Mul_ID;
		$throwJSON['title'] = 'Multibuy No Found';
		$throwJSON['description'] = 'Multibuy not found';
		$throwJSON['type'] = 'error';

			
	}
	
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'select') {
	
	$muliveries = $MulDao->select($Mul_ID, NULL, NULL, false);
	die(json_encode($muliveries));
}

die(json_encode($throwJSON));

?>