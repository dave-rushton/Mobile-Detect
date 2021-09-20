<?php

require_once("../../config/config.php");
require_once("../patchworks.php");
require_once("../website/classes/template.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);

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


$Tpl_ID = (isset($_REQUEST['tpl_id']) && is_numeric($_REQUEST['tpl_id'])) ? $_REQUEST['tpl_id'] : die('FAIL');

if (is_null($Tpl_ID)) {
	$throwJSON['title'] = 'Invalid Template';
	$throwJSON['description'] = 'Template not found';
	$throwJSON['type'] = 'error';
}

$TmpTpl = new TplDAO();

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update') {

	$TplObj = $TmpTpl->select($Tpl_ID, true);
	
	if (!$TplObj) {
		
		$TplObj = new stdClass();
		
		$TplObj->tpl_id = 0;
		$TplObj->tplnam = '';
		$TplObj->tplfil = '';
		$TplObj->tpldef = 0;
		$TplObj->tplobj = '';

		if (isset($_REQUEST['tplnam'])) $TplObj->tplnam = $_REQUEST['tplnam'];
		if (isset($_REQUEST['tplfil'])) $TplObj->tplfil = $_REQUEST['tplfil'];
		if (isset($_REQUEST['tpldef'])) $TplObj->tpldef = $_REQUEST['tpldef'];
		if (isset($_REQUEST['tplobj'])) $TplObj->tplobj = $_REQUEST['tplobj'];

		$Tpl_ID = $TmpTpl->update($TplObj);
		
		$throwJSON['id'] = $Tpl_ID;
		$throwJSON['title'] = 'Template Created';
		$throwJSON['description'] = 'Template '.$TplObj->tplfil.' created';
		$throwJSON['type'] = 'success';

		
	} else {

		if (isset($_REQUEST['tplnam'])) $TplObj->tplnam = $_REQUEST['tplnam'];
		if (isset($_REQUEST['tplfil'])) $TplObj->tplfil = $_REQUEST['tplfil'];
		if (isset($_REQUEST['tpldef'])) $TplObj->tpldef = $_REQUEST['tpldef'];
        if (isset($_REQUEST['tplobj'])) $TplObj->tplobj = $_REQUEST['tplobj'];
		
		$Tpl_ID = $TmpTpl->update($TplObj);
		
		$throwJSON['id'] = $Tpl_ID;
		$throwJSON['title'] = 'Template Updated';
		$throwJSON['description'] = 'Template '.$TplObj->tplfil.' updated';
		$throwJSON['type'] = 'success';
		
	}

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') {
	
	$TplObj = $TmpTpl->select($Tpl_ID, NULL, NULL, NULL, true);
	if ($TplObj) $TmpTpl->delete($TplObj->tpl_id);
	

	$throwJSON['id'] = $TplObj->tpl_id;
	$throwJSON['title'] = 'Template Deleted';
	$throwJSON['description'] = 'Template '.$TplObj->tplfil.' deleted';
	$throwJSON['type'] = 'success';
	
}

die(json_encode($throwJSON));

?>