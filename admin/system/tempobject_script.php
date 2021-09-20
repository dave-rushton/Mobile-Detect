<?php

require_once("../../config/config.php");
require_once("../patchworks.php");
require_once("../system/classes/tempobject.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
//if ($loggedIn == 0) header('location: ../login.php');

$TmpDao = new TmpDAO();

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


$Tmp_ID = (isset($_REQUEST['tmp_id']) && is_numeric($_REQUEST['tmp_id'])) ? $_REQUEST['tmp_id'] : NULL;

if (is_null($Tmp_ID)) {
	$throwJSON['title'] = 'Invalid Object';
	$throwJSON['description'] = 'Object not found';
	$throwJSON['type'] = 'error';
}


if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update') {

    $TmpObj = $TmpDao->select($Tmp_ID, NULL, NULL, NULL, true);

    if (!$TmpObj) {

        $TmpObj = new stdClass();
        $TmpObj->tmp_id = 0;
        $TmpObj->tblnam = $_REQUEST['tblnam'];
        $TmpObj->tbl_id = $_REQUEST['tbl_id'];
        $TmpObj->tmpobj = $_REQUEST['tmpobj'];

        $Tmp_ID = $TmpDao->update($TmpObj);

        $throwJSON['id'] = $Tmp_ID;
        $throwJSON['title'] = 'Object Created';
        $throwJSON['description'] = 'Object ' . $TmpObj->tblnam . ' created';
        $throwJSON['type'] = 'success';


    } else {

        $TmpObj->tmp_id = $_REQUEST['tmp_id'];
        $TmpObj->tblnam = $_REQUEST['tblnam'];
        $TmpObj->tbl_id = $_REQUEST['tbl_id'];
        $TmpObj->tmpobj = $_REQUEST['tmpobj'];

        $Tmp_ID = $TmpDao->update($TmpObj);

        $throwJSON['id'] = $Tmp_ID;
        $throwJSON['title'] = 'Object Updated';
        $throwJSON['description'] = 'Object updated';
        $throwJSON['type'] = 'success';

    }

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') {
	
	$TmpObj = $TmpDao->select($Tmp_ID, NULL, NULL, NULL, true);
	if ($TmpObj) $TmpDao->delete($TmpObj->tmp_id);
	

	$throwJSON['id'] = $TmpObj->tmp_id;
	$throwJSON['title'] = 'Object Deleted';
	$throwJSON['description'] = 'Object deleted';
	$throwJSON['type'] = 'success';


}

die(json_encode($throwJSON));

?>