<?php

require_once("../../config/config.php");
require_once("../patchworks.php");
require_once("../hotspots/classes/hotspots.cls.php");
require_once("../hotspots/classes/hotspotsdetail.cls.php");

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


$Hot_ID = (isset($_REQUEST['hot_id']) && is_numeric($_REQUEST['hot_id'])) ? $_REQUEST['hot_id'] : NULL;

if (is_null($Hot_ID)) {
	$throwJSON['title'] = 'Invalid Hot Spot';
	$throwJSON['description'] = 'Hot Spot not found';
	$throwJSON['type'] = 'error';
}

$HotDao = new HotDAO();

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update') {

	$HotObj = $HotDao->select($Hot_ID, NULL, NULL, true);
	
	if (!$HotObj) {
		
		$HotObj = new stdClass();
		
		$HotObj->hot_id = 0;
		$HotObj->tblnam = '';
		$HotObj->tbl_id = 0;
		$HotObj->hotnam = '';
		
		if (isset($_REQUEST['tblnam'])) $HotObj->tblnam = $_REQUEST['tblnam'];
		if (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) $HotObj->tbl_id = $_REQUEST['tbl_id'];
		if (isset($_REQUEST['hotnam'])) $HotObj->hotnam = $_REQUEST['hotnam'];
		if (isset($_REQUEST['hotimg'])) $HotObj->hotimg = $_REQUEST['hotimg'];

		$Hot_ID = $HotDao->update($HotObj);
		
		$throwJSON['id'] = $Hot_ID;
		$throwJSON['title'] = 'Hot Spot Created';
		$throwJSON['description'] = 'Hot Spot '.$HotObj->hotnam.' created';
		$throwJSON['type'] = 'success';

		
	} else {

        if (isset($_REQUEST['tblnam'])) $HotObj->tblnam = $_REQUEST['tblnam'];
        if (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) $HotObj->tbl_id = $_REQUEST['tbl_id'];
        if (isset($_REQUEST['hotnam'])) $HotObj->hotnam = $_REQUEST['hotnam'];
        if (isset($_REQUEST['hotimg'])) $HotObj->hotimg = $_REQUEST['hotimg'];
		$Hot_ID = $HotDao->update($HotObj);
		
		$throwJSON['id'] = $Hot_ID;
		$throwJSON['title'] = 'Hot Spot Updated';
		$throwJSON['description'] = 'Hot Spot '.$HotObj->hotnam.' updated';
		$throwJSON['type'] = 'success';
		
	}

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') {
	
	$HotObj = $HotDao->select($Hot_ID, NULL, NULL, true);
	if ($HotObj) $HotDao->delete($HotObj->hot_id);
	

	$throwJSON['id'] = $HotObj->hot_id;
	$throwJSON['title'] = 'Hot Spot Deleted';
	$throwJSON['description'] = 'Hot Spot '.$HotObj->hotnam.' deleted';
	$throwJSON['type'] = 'success';
	
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'createhotspot') {

    $TmpHsp = new HspDAO();

    $HspObj = new stdClass();

    $HspObj->hsp_id = 0;
    $HspObj->hot_id = 0;
    $HspObj->hottop = 0;
    $HspObj->hotlft = 0;
    $HspObj->hspttl = 'HOTSPOT';
    $HspObj->hsptxt = '';

    if (isset($_REQUEST['hot_id']) && is_numeric($_REQUEST['hot_id'])) $HspObj->hot_id = $_REQUEST['hot_id'];
    if (isset($_REQUEST['hottop']) && is_numeric($_REQUEST['hottop'])) $HspObj->hottop = $_REQUEST['hottop'];
    if (isset($_REQUEST['hotlft']) && is_numeric($_REQUEST['hotlft'])) $HspObj->hotlft = $_REQUEST['hotlft'];
    if (isset($_REQUEST['hspttl'])) $HspObj->hspttl = $_REQUEST['hspttl'];
    if (isset($_REQUEST['hsptxt'])) $HspObj->hsptxt = $_REQUEST['hsptxt'];

    $Hsp_ID = $TmpHsp->update($HspObj);

    $throwJSON['id'] = $Hsp_ID;
    $throwJSON['title'] = 'Hot Spot Created';
    $throwJSON['description'] = 'Hot Spot '.$HspObj->hsp_id.' created';
    $throwJSON['type'] = 'success';

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'selecthotspots') {

    $TmpHsp = new HspDAO();
    $hotSpots = $TmpHsp->select(NULL, $_REQUEST['hot_id'], false);

    die(json_encode($hotSpots));

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'selecthotspot') {

    $TmpHsp = new HspDAO();
    $hotSpots = $TmpHsp->select($_REQUEST['hsp_id'], NULL, true);

    die(json_encode($hotSpots));

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'updatehotspot') {

    $TmpHsp = new HspDAO();
    $hotSpotRec = $TmpHsp->select($_REQUEST['hsp_id'], NULL, true);

    if (isset($_REQUEST['hottop']) && is_numeric($_REQUEST['hottop'])) $hotSpotRec->hottop = $_REQUEST['hottop'];
    if (isset($_REQUEST['hotlft']) && is_numeric($_REQUEST['hotlft'])) $hotSpotRec->hotlft = $_REQUEST['hotlft'];

    $Hsp_ID = $TmpHsp->update($hotSpotRec);

    $throwJSON['id'] = $hotSpotRec->hsp_id;
    $throwJSON['title'] = 'Hot Spot Updated';
    $throwJSON['description'] = 'Hot Spot '.$hotSpotRec->hsp_id.' updated';
    $throwJSON['type'] = 'success';

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'updatehotspotdetails') {

    $TmpHsp = new HspDAO();
    $hotSpotRec = $TmpHsp->select($_REQUEST['hsp_id'], NULL, true);

    if (isset($_REQUEST['hspttl'])) $hotSpotRec->hspttl = $_REQUEST['hspttl'];
    if (isset($_REQUEST['hsptxt'])) $hotSpotRec->hsptxt = $_REQUEST['hsptxt'];

    $Hsp_ID = $TmpHsp->update($hotSpotRec);

    $throwJSON['id'] = $hotSpotRec->hsp_id;
    $throwJSON['title'] = 'Hot Spot Updated';
    $throwJSON['description'] = 'Hot Spot '.$hotSpotRec->hsp_id.' updated';
    $throwJSON['type'] = 'success';

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'deletespotdetails') {

    $TmpHsp = new HspDAO();
    $hotSpotRec = $TmpHsp->select($_REQUEST['hsp_id'], NULL, true);
    $TmpHsp->delete($hotSpotRec->hsp_id);

    $throwJSON['id'] = $hotSpotRec->hsp_id;
    $throwJSON['title'] = 'Hot Spot Deleted';
    $throwJSON['description'] = 'Hot Spot '.$hotSpotRec->hsp_id.' deleted';
    $throwJSON['type'] = 'success';

}

die(json_encode($throwJSON));

?>