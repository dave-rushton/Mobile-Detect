<?php

require_once("../../config/config.php");
require_once("../patchworks.php");
require_once("classes/people.cls.php");

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


$Ppl_ID = (isset($_REQUEST['ppl_id']) && is_numeric($_REQUEST['ppl_id'])) ? $_REQUEST['ppl_id'] : NULL;

if (is_null($Ppl_ID)) {
	$throwJSON['title'] = 'Invalid Person';
	$throwJSON['description'] = 'Person not found';
	$throwJSON['type'] = 'error';
}

$PplDao = new PplDAO();

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update') {

	$PplObj = $PplDao->select($Ppl_ID, NULL, NULL, NULL, true);
	
	if (!$PplObj) {
		
		$PplObj = new stdClass();
		
		$PplObj->ppl_id = 0;
		$PplObj->tblnam = '';
		$PplObj->tbl_id = 0;
		$PplObj->pplttl = '';
		$PplObj->pplfna = '';
		$PplObj->pplsna = '';
		$PplObj->pplnam = '';
		$PplObj->adr1 = '';
		$PplObj->adr2 = '';
		$PplObj->adr3 = '';
		$PplObj->adr4 = '';
		$PplObj->pstcod = '';
		$PplObj->ctynam = '';
		$PplObj->goolat = 0;
		$PplObj->goolng = 0;
		$PplObj->pplema = '';
		$PplObj->ppltel = '';
		$PplObj->pplmob = '';
		$PplObj->pplref = '';
		$PplObj->usrnam = '';
		$PplObj->paswrd = 'password';
		$PplObj->sta_id = 0;
		$PplObj->credat = date("Y-m-d H:i:s");
		$PplObj->amndat = date("Y-m-d H:i:s");
		$PplObj->pplimg = '';
		$PplObj->ppltxt = '';

		if (isset($_REQUEST['tblnam'])) $PplObj->tblnam = $_REQUEST['tblnam'];
		if (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) $PplObj->tbl_id = $_REQUEST['tbl_id'];
		if (isset($_REQUEST['pplttl'])) $PplObj->pplttl = $_REQUEST['pplttl'];
		if (isset($_REQUEST['pplfna'])) $PplObj->pplfna = $_REQUEST['pplfna'];
		if (isset($_REQUEST['pplsna'])) $PplObj->pplsna = $_REQUEST['pplsna'];
		if (isset($_REQUEST['pplnam'])) $PplObj->pplnam = $_REQUEST['pplnam'];
		if (isset($_REQUEST['adr1'])) $PplObj->adr1 = $_REQUEST['adr1'];
		if (isset($_REQUEST['adr2'])) $PplObj->adr2 = $_REQUEST['adr2'];
		if (isset($_REQUEST['adr3'])) $PplObj->adr3 = $_REQUEST['adr3'];
		if (isset($_REQUEST['adr4'])) $PplObj->adr4 = $_REQUEST['adr4'];
		if (isset($_REQUEST['pstcod'])) $PplObj->pstcod = $_REQUEST['pstcod'];
		if (isset($_REQUEST['ctynam'])) $PplObj->ctynam = $_REQUEST['ctynam'];
		if (isset($_REQUEST['goolat'])) $PplObj->goolat = $_REQUEST['goolat'];
		if (isset($_REQUEST['goolng'])) $PplObj->goolng = $_REQUEST['goolng'];
		if (isset($_REQUEST['pplema'])) $PplObj->pplema = $_REQUEST['pplema'];
		if (isset($_REQUEST['ppltel'])) $PplObj->ppltel = $_REQUEST['ppltel'];
		if (isset($_REQUEST['pplmob'])) $PplObj->pplmob = $_REQUEST['pplmob'];
		if (isset($_REQUEST['pplref'])) $PplObj->pplref = $_REQUEST['pplref'];
		if (isset($_REQUEST['usrnam'])) $PplObj->usrnam = $_REQUEST['usrnam'];
		if (isset($_REQUEST['paswrd'])) $PplObj->paswrd = $_REQUEST['paswrd'];
		if (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) $PplObj->sta_id = $_REQUEST['sta_id'];
		if (isset($_REQUEST['credat'])) $PplObj->credat = $_REQUEST['credat'];
		if (isset($_REQUEST['amndat'])) $PplObj->amndat = $_REQUEST['amndat'];
		if (isset($_REQUEST['pplimg'])) $PplObj->pplimg = $_REQUEST['pplimg'];
        if (isset($_REQUEST['ppltxt'])) $PplObj->ppltxt = $_REQUEST['ppltxt'];

		$Ppl_ID = $PplDao->update($PplObj);
		
		$throwJSON['id'] = $Ppl_ID;
		$throwJSON['title'] = 'Person Created';
		$throwJSON['description'] = 'Person '.$PplObj->pplnam.' created';
		$throwJSON['type'] = 'success';

		
	} else {
		
		if (isset($_REQUEST['tblnam'])) $PplObj->tblnam = $_REQUEST['tblnam'];
		if (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) $PplObj->tbl_id = $_REQUEST['tbl_id'];
		if (isset($_REQUEST['pplttl'])) $PplObj->pplttl = $_REQUEST['pplttl'];
		if (isset($_REQUEST['pplfna'])) $PplObj->pplfna = $_REQUEST['pplfna'];
		if (isset($_REQUEST['pplsna'])) $PplObj->pplsna = $_REQUEST['pplsna'];
		if (isset($_REQUEST['pplnam'])) $PplObj->pplnam = $_REQUEST['pplnam'];
		if (isset($_REQUEST['adr1'])) $PplObj->adr1 = $_REQUEST['adr1'];
		if (isset($_REQUEST['adr2'])) $PplObj->adr2 = $_REQUEST['adr2'];
		if (isset($_REQUEST['adr3'])) $PplObj->adr3 = $_REQUEST['adr3'];
		if (isset($_REQUEST['adr4'])) $PplObj->adr4 = $_REQUEST['adr4'];
		if (isset($_REQUEST['pstcod'])) $PplObj->pstcod = $_REQUEST['pstcod'];
		if (isset($_REQUEST['ctynam'])) $PplObj->ctynam = $_REQUEST['ctynam'];
		if (isset($_REQUEST['goolat'])) $PplObj->goolat = $_REQUEST['goolat'];
		if (isset($_REQUEST['goolng'])) $PplObj->goolng = $_REQUEST['goolng'];
		if (isset($_REQUEST['pplema'])) $PplObj->pplema = $_REQUEST['pplema'];
		if (isset($_REQUEST['ppltel'])) $PplObj->ppltel = $_REQUEST['ppltel'];
		if (isset($_REQUEST['pplmob'])) $PplObj->pplmob = $_REQUEST['pplmob'];
		if (isset($_REQUEST['pplref'])) $PplObj->pplref = $_REQUEST['pplref'];
		if (isset($_REQUEST['usrnam'])) $PplObj->usrnam = $_REQUEST['usrnam'];
		if (isset($_REQUEST['paswrd'])) $PplObj->paswrd = $_REQUEST['paswrd'];
		if (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) $PplObj->sta_id = $_REQUEST['sta_id'];
		if (isset($_REQUEST['credat'])) $PplObj->credat = $_REQUEST['credat'];
		if (isset($_REQUEST['amndat'])) $PplObj->amndat = $_REQUEST['amndat'];
		if (isset($_REQUEST['pplimg'])) $PplObj->pplimg = $_REQUEST['pplimg'];
        if (isset($_REQUEST['ppltxt'])) $PplObj->ppltxt = $_REQUEST['ppltxt'];
		
		$Ppl_ID = $PplDao->update($PplObj);
		
		$throwJSON['id'] = $Ppl_ID;
		$throwJSON['title'] = 'Person Updated';
		$throwJSON['description'] = 'Person '.$PplObj->pplnam.' updated';
		$throwJSON['type'] = 'success';
		
	}

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') {
	
	$PplObj = $PplDao->select($Ppl_ID, NULL, NULL, NULL, true);
	if ($PplObj) $PplDao->delete($PplObj->ppl_id);
	

	$throwJSON['id'] = $PplObj->ppl_id;
	$throwJSON['title'] = 'Person Deleted';
	$throwJSON['description'] = 'Person '.$PplObj->pplnam.' deleted';
	$throwJSON['type'] = 'success';
	
}

die(json_encode($throwJSON));

?>