<?php

require_once("../../config/config.php");
require_once("../patchworks.php");
require_once("classes/uploads.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);

$throwJSON = array();
$throwJSON['id'] = '0';
$throwJSON['title'] = 'noaction';
$throwJSON['description'] = 'no action taken';
$throwJSON['type'] = 'warning';

if ($loggedIn == 0) {
	
	//header('louplion: ../login.php');
		
	$throwJSON['title'] = 'Authorisation';
	$throwJSON['description'] = 'You are not authorised for this action';
	$throwJSON['type'] = 'error';
}


$Upl_ID = (isset($_REQUEST['upl_id']) && is_numeric($_REQUEST['upl_id'])) ? $_REQUEST['upl_id'] : NULL;

if (is_null($Upl_ID)) {
	$throwJSON['title'] = 'Invalid Upload';
	$throwJSON['description'] = 'Upload not found';
	$throwJSON['type'] = 'error';
}

$UplDao = new UplDAO();

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update' && !is_null($Upl_ID)) {

	$UplObj = $UplDao->select($Upl_ID, NULL, NULL, NULL, true);
	
	if (!$UplObj) {
		
		$UplObj = new stdClass();
		
		$UplObj->upl_id = 0;
		$UplObj->tblnam = '';
		$UplObj->tbl_id = 0;
		$UplObj->uplttl = '';
		$UplObj->upldsc = '';
		$UplObj->urllnk = '';
		$UplObj->srtord = 99;
		$UplObj->uplobj = '';

		if (isset($_REQUEST['tblnam'])) $UplObj->tblnam = $_REQUEST['tblnam'];
		if (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) $UplObj->tbl_id = $_REQUEST['tbl_id'];
		
		if (isset($_REQUEST['upldsc'])) $UplObj->upldsc = $_REQUEST['upldsc'];
		if (isset($_REQUEST['urllnk'])) $UplObj->urllnk = $_REQUEST['urllnk'];
        if (isset($_REQUEST['alttxt'])) $UplObj->alttxt = $_REQUEST['alttxt'];
		if (isset($_REQUEST['srtord']) && is_numeric($_REQUEST['srtord'])) $UplObj->srtord = $_REQUEST['srtord'];
		if (isset($_REQUEST['uplobj'])) $UplObj->uplobj = $_REQUEST['uplobj'];

		$Upl_ID = $UplDao->update($UplObj);
		
		$throwJSON['id'] = $Upl_ID;
		$throwJSON['title'] = 'Upload Created';
		$throwJSON['description'] = 'Upload '.$UplObj->uplttl.' created';
		$throwJSON['type'] = 'success';

		
	} else {
		
		if (isset($_REQUEST['tblnam'])) $UplObj->tblnam = $_REQUEST['tblnam'];
		if (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) $UplObj->tbl_id = $_REQUEST['tbl_id'];
		if (isset($_REQUEST['uplttl'])) $UplObj->uplttl = $_REQUEST['uplttl'];
		if (isset($_REQUEST['upldsc'])) $UplObj->upldsc = $_REQUEST['upldsc'];
		if (isset($_REQUEST['urllnk'])) $UplObj->urllnk = $_REQUEST['urllnk'];
		if (isset($_REQUEST['alttxt'])) $UplObj->alttxt = $_REQUEST['alttxt'];
		if (isset($_REQUEST['srtord']) && is_numeric($_REQUEST['srtord'])) $UplObj->srtord = $_REQUEST['srtord'];
		if (isset($_REQUEST['uplobj'])) $UplObj->uplobj = $_REQUEST['uplobj'];

		$Upl_ID = $UplDao->update($UplObj);
		
		$throwJSON['id'] = $Upl_ID;
		$throwJSON['title'] = 'Upload Updated';
		$throwJSON['description'] = 'Upload '.$UplObj->uplttl.' updated';
		$throwJSON['type'] = 'success';
		
	}

}
if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'updatejson') {
	$jsonstr = !empty($_REQUEST['json'])?$_REQUEST['json']:"";
	if(!empty($jsonstr)){
		$jsons = json_decode($jsonstr);

		foreach ($jsons as $json){
			$UplObj = $UplDao->select($json->upl_id, NULL, NULL, NULL, true);
			$UplObj->upl_id = !empty($json->upl_id)?$json->upl_id:0;
			$UplObj->uplttl = !empty($json->uplttl)?$json->uplttl:"";
			$UplObj->upldsc = !empty($json->upldsc)?$json->upldsc:"";
			$UplObj->alttxt = !empty($json->alttxt)?$json->alttxt:"";
			$UplObj->urllnk = !empty($json->urllnk)?$json->urllnk:"";
			$UplDao->update($UplObj);
		}

		$throwJSON['id'] = "Updated";
		$throwJSON['title'] = 'Upload Updated';
		$throwJSON['description'] = 'Upload ';
		$throwJSON['type'] = 'success';
	}else{
		$throwJSON['id'] = "Empty";
		$throwJSON['title'] = 'Nothing Changed';
		$throwJSON['description'] = 'Please make sure you have images in the gallery.';
		$throwJSON['type'] = 'warning';
	}


}else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'transfere') {
	$Tbl_id="";

	if(isset($_REQUEST['upl_id'])){
		if(is_numeric($_REQUEST['upl_id'])){
			$Upl_ID=$_REQUEST['upl_id'];
		}
	}

	if(isset($_REQUEST['gal_id'])){
		$Tbl_id =$_REQUEST['gal_id'];
	}

	$UplObj = $UplDao->select($Upl_ID, NULL, NULL, NULL, true);

	if ($UplObj) {

		if(is_numeric($_REQUEST['gal_id'])){
			$UplObj->tbl_id=$Tbl_id;
		}

		$UplDao->update($UplObj);
	}

	$throwJSON['id'] = $UplObj->upl_id;
	$throwJSON['title'] = 'Upload Transfered';
	$throwJSON['description'] = 'Upload '.$UplObj->uplttl.' Transfered';
	$throwJSON['type'] = 'success';

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') {
	
	$UplObj = $UplDao->select($Upl_ID, NULL, NULL, NULL, true);
	if ($UplObj) {

        $UplDao->delete($UplObj->upl_id);

        $globalImage = $UplDao->select(NULL, NULL, NULL, $UplObj->filnam, false);

        if (!isset($globalImage) || count($globalImage) == 0) {
            $UplDao->deleteFile($patchworks->docRoot, $UplObj->filnam);
        }

        if (isset($_REQUEST['masterimage']) && $_REQUEST['masterimage'] == true) {
            $UplDao->deleteFile($patchworks->docRoot, $UplObj->filnam);
        }
    }

	$throwJSON['id'] = $UplObj->upl_id;
	$throwJSON['title'] = 'Upload Deleted';
	$throwJSON['description'] = 'Upload '.$UplObj->uplttl.' deleted';
	$throwJSON['type'] = 'success';
	
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'select') {

	$UplObj = $UplDao->select($Upl_ID, NULL, NULL, NULL, true);
	$jsonArray = array();

	if (is_numeric($Upl_ID) && $UplObj) {

		$recordArray = array();
		$recordArray['upl_id'] = $UplObj->upl_id;
		$recordArray['tblnam'] = $UplObj->tblnam;
		$recordArray['tbl_id'] = $UplObj->tbl_id;
		$recordArray['uplttl'] = $UplObj->uplttl;
		$recordArray['upldsc'] = $UplObj->upldsc;
		$recordArray['urllnk'] = $UplObj->urllnk;
		$recordArray['srtord'] = $UplObj->srtord;
		$recordArray['alttxt'] = $UplObj->alttxt;
		$recordArray['uplobj'] = $UplObj->uplobj;
		$jsonArray[] = $recordArray;
	}
	
	die(json_encode($jsonArray));	

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'resort') {
	$SrtOrd = (isset($_GET['srtord'])) ? $_GET['srtord'] : NULL;
	if (!is_null($SrtOrd)) {
		$SrtOrd = explode(",",$SrtOrd);
		for ($o=0; $o<count($SrtOrd); $o++) {
			$qryArray = array();
			$sql = 'UPDATE uploads SET
					srtord = '.$o.'
					WHERE upl_id = '.$SrtOrd[$o];
			$upload = $patchworks->run($sql, $qryArray, false);
		}
	}
}
die(json_encode($throwJSON));


