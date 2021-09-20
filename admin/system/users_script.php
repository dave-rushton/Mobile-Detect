<?php

require_once("../../config/config.php");
require_once("../patchworks.php");
require_once("classes/users.cls.php");

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


$Usr_ID = (isset($_REQUEST['usr_id']) && is_numeric($_REQUEST['usr_id'])) ? $_REQUEST['usr_id'] : NULL;

if (is_null($Usr_ID)) {
	$throwJSON['title'] = 'Invalid User';
	$throwJSON['description'] = 'User not found';
	$throwJSON['type'] = 'error';
}

$UsrDao = new UsrDAO();

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update') {

	$UsrObj = $UsrDao->select($Usr_ID, NULL, true);
	
	if (!$UsrObj) {
		
		$UsrObj = new stdClass();
		$UsrObj->usr_id = 0;
		$UsrObj->usrnam = (isset($_REQUEST['usrnam'])) ? $_REQUEST['usrnam'] : $UsrObj->usrnam;
		if (isset($_REQUEST['paswrd'])) $UsrObj->paswrd = $_REQUEST['paswrd'];
		$UsrObj->usrema = (isset($_REQUEST['usrema'])) ? $_REQUEST['usrema'] : $UsrObj->usrema;
		$UsrObj->usracc = (isset($_REQUEST['usracc'])) ? $_REQUEST['usracc'] : $UsrObj->usracc;
		$UsrObj->sta_id = (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) ? $_REQUEST['sta_id'] : $UsrObj->sta_id;
		
		$Usr_ID = $UsrDao->update($UsrObj);
		
		$throwJSON['id'] = $Usr_ID;
		$throwJSON['title'] = 'User Created';
		$throwJSON['description'] = 'User '.$UsrObj->usrnam.' created';
		$throwJSON['type'] = 'success';

		
	} else {
		
		if (isset($_REQUEST['usrnam'])) $UsrObj->usrnam = $_REQUEST['usrnam'];
		if (isset($_REQUEST['paswrd'])) $UsrObj->paswrd = $_REQUEST['paswrd'];
		if (isset($_REQUEST['usrema'])) $UsrObj->usrema = $_REQUEST['usrema'];
		if (isset($_REQUEST['usracc'])) $UsrObj->usracc = $_REQUEST['usracc'];
		if (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) $UsrObj->sta_id = $_REQUEST['sta_id'];
		
		$Usr_ID = $UsrDao->update($UsrObj);
		
		$throwJSON['id'] = $Usr_ID;
		$throwJSON['title'] = 'User Updated';
		$throwJSON['description'] = 'User '.$UsrObj->usrnam.' updated';
		$throwJSON['type'] = 'success';
		
	}

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') {
	
	$UsrObj = $UsrDao->select($Usr_ID, NULL, true);
	if ($UsrObj) $UsrDao->delete($UsrObj->usr_id);
		
	$throwJSON['id'] = $UsrObj->usr_id;
	$throwJSON['title'] = 'User Delete';
	$throwJSON['description'] = 'User '.$UsrObj->usrnam.' deleted';
	$throwJSON['type'] = 'success';
	
}

die(json_encode($throwJSON));

//header('location: users.php');

?>