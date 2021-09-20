<?php

require_once("../../../config/config.php");
require_once("../../patchworks.php");
require_once("../../website/classes/pages.cls.php");

//$userAuth = new AuthDAO();
//$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
//if ($loggedIn == 0) header('location: ../login.php');

$Pag_ID = (isset($_GET['pag_id']) && is_numeric($_GET['pag_id'])) ? $_GET['pag_id'] : die('FAIL');

$PagDao = new PagDAO();

$throwJSON = array();
$throwJSON['id'] = '0';
$throwJSON['title'] = 'noaction';
$throwJSON['description'] = 'no action taken';
$throwJSON['type'] = 'warning';

$jsonArray = array();

if ($Pag_ID) {

	$PagDao->delete($Pag_ID);
		
	$throwJSON = array();
	$throwJSON['id'] = $Pag_ID;
	$throwJSON['title'] = 'Page Updated';
	$throwJSON['description'] ='Page clean up';
	$throwJSON['type'] = 'success';

} else {

		
	$throwJSON = array();
	$throwJSON['id'] = '0';
	$throwJSON['title'] = 'Page Not Found';
	$throwJSON['description'] = 'the page you were looking for could not be found';
	$throwJSON['type'] = 'error';
	
}

die(json_encode($throwJSON));

?>