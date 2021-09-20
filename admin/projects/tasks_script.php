<?php

require_once("../../config/config.php");
require_once("../patchworks.php");
require_once("../projects/classes/tasks.cls.php");
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


$Btk_ID = (isset($_REQUEST['btk_id']) && !is_null($_REQUEST['btk_id'])) ? $_REQUEST['btk_id'] : die('FAIL');

if (is_null($Btk_ID)) {
	$throwJSON['title'] = 'Invalid Task';
	$throwJSON['description'] = 'Task not found';
	$throwJSON['type'] = 'error';
}

$BtkDao = new BtkDAO();

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update') {

	$BtkObj = $BtkDao->select($Btk_ID, NULL, NULL, NULL, true);
	
	if (!$BtkObj) {
		
		$BtkObj = new stdClass();
		
		$BtkObj->btk_id = 0;
		$BtkObj->tblnam = '';
		$BtkObj->tbl_id = 0;
		$BtkObj->btkttl = '';
		$BtkObj->btkdsc = '';
		$BtkObj->btkdur = '';
		$BtkObj->sta_id = 0;
		
		if (isset($_REQUEST['tblnam'])) $BtkObj->tblnam = $_REQUEST['tblnam'];
		if (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) $BtkObj->tbl_id = $_REQUEST['tbl_id'];
		if (isset($_REQUEST['btkttl'])) $BtkObj->btkttl = $_REQUEST['btkttl'];
		if (isset($_REQUEST['btkdsc'])) $BtkObj->btkdsc = $_REQUEST['btkdsc'];
		if (isset($_REQUEST['btkdur']) && is_numeric($_REQUEST['btkdur'])) $BtkObj->btkdur = $_REQUEST['btkdur'];
		if (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) $BtkObj->sta_id = $_REQUEST['sta_id'];
				
		if (isset($_REQUEST['reftbl'])) $BtkObj->reftbl = $_REQUEST['reftbl'];
		if (isset($_REQUEST['ref_id']) && is_numeric($_REQUEST['ref_id'])) $BtkObj->ref_id = $_REQUEST['ref_id'];
		
		$Btk_ID = $BtkDao->update($BtkObj);
		
		$throwJSON['id'] = $Btk_ID;
		$throwJSON['title'] = 'Task Created';
		$throwJSON['description'] = 'Task '.$BtkObj->btkdsc.' created';
		$throwJSON['type'] = 'success';

		
	} else {
		
		if (isset($_REQUEST['tblnam'])) $BtkObj->tblnam = $_REQUEST['tblnam'];
		if (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) $BtkObj->tbl_id = $_REQUEST['tbl_id'];
		if (isset($_REQUEST['btkttl'])) $BtkObj->btkttl = $_REQUEST['btkttl'];
		if (isset($_REQUEST['btkdsc'])) $BtkObj->btkdsc = $_REQUEST['btkdsc'];
		if (isset($_REQUEST['btkdur'])) $BtkObj->btkdur = $_REQUEST['btkdur'];
		if (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) $BtkObj->sta_id = $_REQUEST['sta_id'];
		
		if (isset($_REQUEST['reftbl'])) $BtkObj->reftbl = $_REQUEST['reftbl'];
		if (isset($_REQUEST['ref_id']) && is_numeric($_REQUEST['ref_id'])) $BtkObj->ref_id = $_REQUEST['ref_id'];
		
		$Btk_ID = $BtkDao->update($BtkObj);
		
		$throwJSON['id'] = $Btk_ID;
		$throwJSON['title'] = 'Task Updated';
		$throwJSON['description'] = 'Task '.$BtkObj->btkdsc.' updated';
		$throwJSON['type'] = 'success';
		
	}

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') {
	
	$BtkDao->delete($Btk_ID);

	$throwJSON['id'] = $Btn_ID;
	$throwJSON['title'] = 'Task Deleted';
	$throwJSON['description'] = 'Task '.$BtkObj->btkdsc.' deleted';
	$throwJSON['type'] = 'success';
	
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'select') {
	
	$task = $BtkDao->select($Btk_ID, NULL, NULL, NULL, true);
	die(json_encode($task));

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'updatestatus') {
	
	$Sta_ID = (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) ? $_REQUEST['sta_id'] : NULL;

	$BtkDao->updateStatus($Btk_ID, $Sta_ID);
	$throwJSON['id'] = '0';
	$throwJSON['title'] = 'Task Updated';
	$throwJSON['description'] = 'Tasks updated';
	$throwJSON['type'] = 'success';

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'updateimportant') {
	
	$ImpFlg = (isset($_REQUEST['impflg']) && is_numeric($_REQUEST['impflg'])) ? $_REQUEST['impflg'] : NULL;

	$task = $BtkDao->updateHighlight($Btk_ID, $ImpFlg);
	$throwJSON['id'] = '0';
	$throwJSON['title'] = 'Tasks Marked';
	$throwJSON['description'] = 'Tasks marked';
	$throwJSON['type'] = 'success';

}

die(json_encode($throwJSON));

?>