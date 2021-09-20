<?php

require_once("../../config/config.php");
require_once("../patchworks.php");
require_once("../attributes/classes/attrgroups.cls.php");

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


$Atr_ID = (isset($_REQUEST['atr_id']) && is_numeric($_REQUEST['atr_id'])) ? $_REQUEST['atr_id'] : die('FAIL');

if (is_null($Atr_ID)) {
    $throwJSON['title'] = 'Invalid Attribute';
    $throwJSON['description'] = 'Attribute not found';
    $throwJSON['type'] = 'error';
}

$AtrDao = new AtrDAO();

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update') {

    $AtrObj = $AtrDao->select($Atr_ID, NULL, NULL, NULL, true);

    if (!$AtrObj) {

        $AtrObj = new stdClass();
        $AtrObj->atr_id = 0;
        $AtrObj->tblnam = '';
        $AtrObj->tbl_id = '';
        $AtrObj->atrnam = '';
        $AtrObj->atrdsc = '';
        $AtrObj->atrema = '';
        $AtrObj->seourl = '';
        $AtrObj->fwdurl = '';
        $AtrObj->alturl = '';
        $AtrObj->btntxt = '';
        $AtrObj->seokey = '';
        $AtrObj->seodsc = '';
        $AtrObj->gdpr_title = '';
        $AtrObj->gdpr_text = '';
        $AtrObj->gdpr_yes = '';
        $AtrObj->gdpr_no = '';
        $AtrObj->sta_id = 0;
        $AtrObj->atrtag = '';
        $AtrObj->numcol = '1';
        $AtrObj->coldir = '0';
    }

    if (isset($_REQUEST['tblnam'])) $AtrObj->tblnam = $_REQUEST['tblnam'];
    if (isset($_REQUEST['atrnam'])) $AtrObj->atrnam = $_REQUEST['atrnam'];
    if (isset($_REQUEST['atrdsc'])) $AtrObj->atrdsc = $_REQUEST['atrdsc'];
    if (isset($_REQUEST['atrema'])) $AtrObj->atrema = $_REQUEST['atrema'];
    if (isset($_REQUEST['tbl_id'])) $AtrObj->tbl_id = $_REQUEST['tbl_id'];
    if (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) $AtrObj->sta_id = $_REQUEST['sta_id'];
    if (isset($_REQUEST['seourl'])) $AtrObj->seourl = $_REQUEST['seourl'];
    if (isset($_REQUEST['seokey'])) $AtrObj->seokey = $_REQUEST['seokey'];
    if (isset($_REQUEST['seodsc'])) $AtrObj->seodsc = $_REQUEST['seodsc'];

    if (isset($_REQUEST['fwdurl'])) $AtrObj->fwdurl = $_REQUEST['fwdurl'];
    if (isset($_REQUEST['alturl'])) $AtrObj->alturl = $_REQUEST['alturl'];
    if (isset($_REQUEST['btntxt'])) $AtrObj->btntxt = $_REQUEST['btntxt'];

    if (isset($_REQUEST['atrtag'])) $AtrObj->atrtag = $_REQUEST['atrtag'];
    if (isset($_REQUEST['numcol'])) $AtrObj->numcol = $_REQUEST['numcol'];
    if (isset($_REQUEST['gdpr_title'])) $AtrObj->gdpr_title = $_REQUEST['gdpr_title'];
    if (isset($_REQUEST['gdpr_text'])) $AtrObj->gdpr_text = $_REQUEST['gdpr_text'];
    if (isset($_REQUEST['gdpr_yes'])) $AtrObj->gdpr_yes = $_REQUEST['gdpr_yes'];
    if (isset($_REQUEST['gdpr_no'])) $AtrObj->gdpr_no = $_REQUEST['gdpr_no'];

    $Atr_ID = $AtrDao->update($AtrObj);

    $throwJSON['id'] = $Atr_ID;
    $throwJSON['title'] = 'Attribute Created';
    $throwJSON['description'] = 'Attribute '.$AtrObj->atrnam.' created';
    $throwJSON['type'] = 'success';

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') {

    $AtrObj = $AtrDao->select($Atr_ID, NULL, NULL, NULL, true);
    if ($AtrObj) $AtrDao->delete($AtrObj->atr_id);


    $throwJSON['id'] = $AtrObj->atr_id;
    $throwJSON['title'] = 'Attribute Deleted';
    $throwJSON['description'] = 'Attribute '.$AtrObj->atrnam.' deleted';
    $throwJSON['type'] = 'success';
}

die(json_encode($throwJSON));