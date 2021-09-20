<?php

require_once("../../../config/config.php");
require_once("../../patchworks.php");

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
	die(json_encode($throwJSON));

}

$sql = "UPDATE cmsprop
		SET
		cms_id = :cms_id,
		goover = :goover,
		gooweb = :gooweb,
		gooana = :gooana,
		gooapi = :gooapi,
		webusr = :webusr,
		webpwd = :webpwd,
		f_b_id = :f_b_id,
		twi_id = :twi_id,
		l_i_id = :l_i_id,

		fb_app = :fb_app,
		fb_sec = :fb_sec,
		conkey = :conkey,
		consec = :consec,
		acctok = :acctok,
		accsec = :accsec,
		capkey = :capkey,
		capsec = :capsec,
		weboff = :weboff,
		cmsobj = :cmsobj;
		";

$qryArray = array();
$qryArray['cms_id'] = 1;
$qryArray['goover'] = (isset($_POST['goover'])) ? $_POST['goover'] : '';
$qryArray['gooweb'] = (isset($_POST['gooweb'])) ? $_POST['gooweb'] : '';
$qryArray['gooana'] = (isset($_POST['gooana'])) ? htmlspecialchars($_POST['gooana'], ENT_QUOTES) : '';
$qryArray['gooapi'] = (isset($_POST['gooapi'])) ? $_POST['gooapi'] : '';
$qryArray['webusr'] = (isset($_POST['webusr'])) ? $_POST['webusr'] : '';
$qryArray['webpwd'] = (isset($_POST['webpwd'])) ? $_POST['webpwd'] : '';

$qryArray['f_b_id'] = (isset($_POST['f_b_id'])) ? $_POST['f_b_id'] : '';
$qryArray['twi_id'] = (isset($_POST['twi_id'])) ? $_POST['twi_id'] : '';
$qryArray['l_i_id'] = (isset($_POST['l_i_id'])) ? $_POST['l_i_id'] : '';

$qryArray['fb_app'] = (isset($_POST['fb_app'])) ? $_POST['fb_app'] : '';
$qryArray['fb_sec'] = (isset($_POST['fb_sec'])) ? $_POST['fb_sec'] : '';
$qryArray['conkey'] = (isset($_POST['conkey'])) ? $_POST['conkey'] : '';
$qryArray['consec'] = (isset($_POST['consec'])) ? $_POST['consec'] : '';
$qryArray['acctok'] = (isset($_POST['acctok'])) ? $_POST['acctok'] : '';
$qryArray['accsec'] = (isset($_POST['accsec'])) ? $_POST['accsec'] : '';
$qryArray['capkey'] = (isset($_POST['capkey'])) ? $_POST['capkey'] : '';
$qryArray['capsec'] = (isset($_POST['capsec'])) ? $_POST['capsec'] : '';

$qryArray['weboff'] = (isset($_POST['weboff'])) ? $_POST['weboff'] : '0';
$qryArray['cmsobj'] = (isset($_POST['cmsobj'])) ? $_POST['cmsobj'] : '';

$recordSet = $patchworks->dbConn->prepare($sql);
$recordSet->execute($qryArray);

$throwJSON['title'] = 'Properties Updated';
$throwJSON['description'] = 'Success';
$throwJSON['type'] = 'error';

die(json_encode($throwJSON));

?>