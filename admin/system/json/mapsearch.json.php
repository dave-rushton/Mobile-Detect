<?php
require_once('../../../config/config.php');
require_once("../../patchworks.php");
require_once("../classes/places.cls.php");

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


//$Pla_ID = (isset($_REQUEST['pla_id']) && is_numeric($_REQUEST['pla_id'])) ? $_REQUEST['pla_id'] : die('FAIL');
//
//if (is_null($Pla_ID)) {
//	$throwJSON['title'] = 'Invalid Place';
//	$throwJSON['description'] = 'Place not found';
//	$throwJSON['type'] = 'error';
//}

$PlaDao = new PlaDAO();

$GooLat = (isset($_REQUEST['goolat']) && is_numeric($_REQUEST['goolat'])) ? $_REQUEST['goolat'] : 0;
$GooLng = (isset($_REQUEST['goolng']) && is_numeric($_REQUEST['goolng'])) ? $_REQUEST['goolng'] : 0;
$PlaDis = (isset($_REQUEST['pladis']) && is_numeric($_REQUEST['pladis'])) ? $_REQUEST['pladis'] : 999999;
$TblNam = (isset($_REQUEST['tblnam'])) ? $_REQUEST['tblnam'] : 'LOCATION';

//$GooLat = '52.2814646';
//$GooLng = '-0.5933181999999988';

$markers = $PlaDao->findByGoogle($TblNam, NULL, $GooLat, $GooLng, $PlaDis);

die(json_encode($markers));

?>