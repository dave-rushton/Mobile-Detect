<?php

require_once("../../config/config.php");
require_once("../patchworks.php");
require_once("../reviews/classes/reviews.cls.php");

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


$Rev_ID = (isset($_REQUEST['rev_id']) && is_numeric($_REQUEST['rev_id'])) ? $_REQUEST['rev_id'] : NULL;

if (is_null($Rev_ID)) {
	$throwJSON['title'] = 'Invalid Review';
	$throwJSON['description'] = 'Review not found';
	$throwJSON['type'] = 'error';
}

$RevDao = new RevDAO();

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update') {

	$RevObj = $RevDao->select($Rev_ID, NULL, NULL, true);
	
	if (!$RevObj) {
		
		$RevObj = new stdClass();
		
		$RevObj->rev_id = 0;
		$RevObj->tblnam = '';
		$RevObj->tbl_id = 0;
		$RevObj->refnam = '';
		$RevObj->ref_id = 0;
		$RevObj->revttl = '';
		$RevObj->revdsc = '';
		$RevObj->rating = 0;
		$RevObj->sta_id = 0;
		
		if (isset($_REQUEST['tblnam'])) $RevObj->tblnam = $_REQUEST['tblnam'];
		if (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) $RevObj->tbl_id = $_REQUEST['tbl_id'];
		if (isset($_REQUEST['refnam'])) $RevObj->refnam = $_REQUEST['refnam'];
        if (isset($_REQUEST['ref_id']) && is_numeric($_REQUEST['ref_id'])) $RevObj->ref_id = $_REQUEST['ref_id'];
		if (isset($_REQUEST['revttl'])) $RevObj->revttl = $_REQUEST['revttl'];
		if (isset($_REQUEST['revdsc'])) $RevObj->revdsc = $_REQUEST['revdsc'];
        if (isset($_REQUEST['rating']) && is_numeric($_REQUEST['rating'])) $RevObj->rating = $_REQUEST['rating'];
		if (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) $RevObj->sta_id = $_REQUEST['sta_id'];

		$Rev_ID = $RevDao->update($RevObj);
		
		$throwJSON['id'] = $Rev_ID;
		$throwJSON['title'] = 'Review Created';
		$throwJSON['description'] = 'Review '.$RevObj->revdsc.' created';
		$throwJSON['type'] = 'success';

		
	} else {

        if (isset($_REQUEST['tblnam'])) $RevObj->tblnam = $_REQUEST['tblnam'];
        if (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) $RevObj->tbl_id = $_REQUEST['tbl_id'];
        if (isset($_REQUEST['refnam'])) $RevObj->refnam = $_REQUEST['refnam'];
        if (isset($_REQUEST['ref_id']) && is_numeric($_REQUEST['ref_id'])) $RevObj->ref_id = $_REQUEST['ref_id'];
        if (isset($_REQUEST['revttl'])) $RevObj->revttl = $_REQUEST['revttl'];
        if (isset($_REQUEST['revdsc'])) $RevObj->revdsc = $_REQUEST['revdsc'];
        if (isset($_REQUEST['rating']) && is_numeric($_REQUEST['rating'])) $RevObj->rating = $_REQUEST['rating'];
        if (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) $RevObj->sta_id = $_REQUEST['sta_id'];
		$Rev_ID = $RevDao->update($RevObj);
		
		$throwJSON['id'] = $Rev_ID;
		$throwJSON['title'] = 'Review Updated';
		$throwJSON['description'] = 'Review '.$RevObj->revdsc.' updated';
		$throwJSON['type'] = 'success';
		
	}

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') {
	
	$RevObj = $RevDao->select($Rev_ID, NULL, NULL, true);
	if ($RevObj) $RevDao->delete($RevObj->rev_id);
	

	$throwJSON['id'] = $RevObj->rev_id;
	$throwJSON['title'] = 'Review Deleted';
	$throwJSON['description'] = 'Review '.$RevObj->revdsc.' deleted';
	$throwJSON['type'] = 'success';
	
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'confirm') {

    $RevObj = $RevDao->select($Rev_ID, NULL, NULL, true);
    $RevObj->sta_id = 0;
    $RevDao->update($RevObj);

    $throwJSON['id'] = $RevObj->rev_id;
    $throwJSON['title'] = 'Review Confirmed';
    $throwJSON['description'] = 'Review '.$RevObj->revttl.' confirmed';
    $throwJSON['type'] = 'success';

}

die(json_encode($throwJSON));

?>