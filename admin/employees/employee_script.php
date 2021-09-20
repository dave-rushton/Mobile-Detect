<?php

require_once("../../config/config.php");
require_once("../patchworks.php");
require_once("../system/classes/people.cls.php");

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


$Ppl_ID = (isset($_REQUEST['ppl_id']) && is_numeric($_REQUEST['ppl_id'])) ? $_REQUEST['ppl_id'] : NULL;

if (is_null($Ppl_ID)) {
	$throwJSON['title'] = 'Invalid Employee';
	$throwJSON['description'] = 'Employee not found';
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

        $PplObj->ppldob = date("Y-m-d");
        $PplObj->gender = 'M';
        $PplObj->ppltxt = '';
        $PplObj->srtord = 99;
    }

    if (isset($_REQUEST['tblnam'])) $PplObj->tblnam = $_REQUEST['tblnam'];
    if (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) $PplObj->tbl_id = $_REQUEST['tbl_id'];
    if (isset($_REQUEST['pplnam'])) $PplObj->pplnam = $_REQUEST['pplnam'];
    if (isset($_REQUEST['pplref'])) $PplObj->pplref = $_REQUEST['pplref'];
    if (isset($_REQUEST['pplimg'])) $PplObj->pplimg = $_REQUEST['pplimg'];
    if (isset($_REQUEST['ppltxt'])) $PplObj->ppltxt = $_REQUEST['ppltxt'];
    if (isset($_REQUEST['srtord']) && is_numeric($_REQUEST['srtord'])) $PplObj->srtord = $_REQUEST['srtord'];


    $Ppl_ID = $PplDao->update($PplObj);

    $throwJSON['id'] = $Ppl_ID;
    $throwJSON['title'] = 'Employee Updated';
    $throwJSON['description'] = 'Employee '.$PplObj->pplnam.' created';
    $throwJSON['type'] = 'success';

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') {
	
	$PplObj = $PplDao->select($Ppl_ID, NULL, NULL, NULL, true);
	if ($PplObj) $PplDao->delete($PplObj->ppl_id);
	

	$throwJSON['id'] = $PplObj->ppl_id;
	$throwJSON['title'] = 'Employee Deleted';
	$throwJSON['description'] = 'Employee '.$PplObj->pplnam.' deleted';
	$throwJSON['type'] = 'success';
	
}

else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'resort') {

	$SrtOrd = (isset($_REQUEST['ppl_id'])) ? $_REQUEST['ppl_id'] : NULL;
	if (!is_null($SrtOrd)) {

		$SrtOrd = explode(",",$SrtOrd);

		for ($o=0; $o<count($SrtOrd); $o++) {

			$qryArray = array();
			$sql = 'UPDATE people SET
				srtord = :srtord
				WHERE ppl_id = :ppl_id';
			$qryArray["srtord"] = $o;
			$qryArray["ppl_id"] = $SrtOrd[$o];

			$recordSet = $patchworks->dbConn->prepare($sql);
			$recordSet->execute($qryArray);
		}

		$throwJSON['id'] = 0;
		$throwJSON['title'] = 'Resorted';
		$throwJSON['description'] = 'resorted';
		$throwJSON['type'] = 'success';
	}
}

die(json_encode($throwJSON));