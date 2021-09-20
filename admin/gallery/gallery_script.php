<?php

require_once("../../config/config.php");
require_once("../patchworks.php");
require_once("classes/gallery.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);

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

function seoUrl($string) {
    //Unwanted:  {UPPERCASE} ; / ? : @ & = + $ , . ! ~ * ' ( )
    $string = strtolower($string);
    //Strip any unwanted characters
    $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
    //Clean multiple dashes or whitespaces
    $string = preg_replace("/[\s-]+/", " ", $string);
    //Convert whitespaces and underscore to dash
    $string = preg_replace("/[\s_]/", "-", $string);
    return $string;
}


$Gal_ID = (isset($_REQUEST['gal_id']) && is_numeric($_REQUEST['gal_id'])) ? $_REQUEST['gal_id'] : NULL;

if (is_null($Gal_ID)) {
	$throwJSON['title'] = 'Invalid Gallery';
	$throwJSON['description'] = 'Gallery not found';
	$throwJSON['type'] = 'error';
}

$GalDao = new GalDAO();

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update' && !is_null($Gal_ID)) {

	$GalObj = $GalDao->select($Gal_ID, NULL, NULL, NULL, true);

	$message = 'updated';

	if (!$GalObj) {

	    $message = 'created';
        $GalObj = new stdClass();

        $GalObj->gal_id = 0;
        $GalObj->tblnam = '';
        $GalObj->tbl_id = 0;
        $GalObj->galnam = '';
        $GalObj->seourl = '';
        $GalObj->keywrd = '';
        $GalObj->keydsc = '';
        $GalObj->sta_id = 0;
        $GalObj->imgsiz = '';

        if (isset($_REQUEST['galnam'])) {
            $GalObj->galnam = $_REQUEST['galnam'];
            $GalObj->tblnam = seoUrl($_REQUEST['galnam']);
            $GalObj->seourl = seoUrl($_REQUEST['galnam']);
            $GalObj->keywrd = $_REQUEST['galnam'];
            $GalObj->keydsc = $_REQUEST['galnam'];
        }

    }
    if (isset($_REQUEST['tblnam'])) $GalObj->tblnam = $_REQUEST['tblnam'];
    if (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) $GalObj->tbl_id = $_REQUEST['tbl_id'];
    if (isset($_REQUEST['seourl'])) $GalObj->seourl = $_REQUEST['seourl'];
    if (isset($_REQUEST['keywrd'])) $GalObj->keywrd = $_REQUEST['keywrd'];
    if (isset($_REQUEST['keydsc'])) $GalObj->keydsc = $_REQUEST['keydsc'];
    if (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) $GalObj->sta_id = $_REQUEST['sta_id'];
    if (isset($_REQUEST['imgsiz'])) $GalObj->imgsiz = $_REQUEST['imgsiz'];
				
		$Gal_ID = $GalDao->update($GalObj);

		$throwJSON['id'] = $Gal_ID;
		$throwJSON['title'] = 'Gallery ' . $message;
		$throwJSON['description'] = 'Gallery ' . $GalObj->galnam . ' '. $message;
		$throwJSON['type'] = 'success';


} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') {
	
	$GalObj = $GalDao->select($Gal_ID, NULL, NULL, NULL, true);
	if ($GalObj) $GalDao->delete($GalObj->gal_id);

	$throwJSON['id'] = $GalObj->gal_id;
	$throwJSON['title'] = 'Gallery Deleted';
	$throwJSON['description'] = 'Gallery '.$GalObj->galnam.' deleted';
	$throwJSON['type'] = 'success';
	
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'select') {

	$GalObj = $GalDao->select($Gal_ID, NULL, NULL, NULL, true);
	$jsonArray = array();
	
	if (is_numeric($Gal_ID) && $GalObj) {
		
		$recordArray = array();
		$recordArray['gal_id'] = $GalObj->gal_id;
		$recordArray['tblnam'] = $GalObj->tblnam;
		$recordArray['tbl_id'] = $GalObj->tbl_id;
		$recordArray['galnam'] = $GalObj->galnam;
		$recordArray['seourl'] = $GalObj->seourl;
		$recordArray['keywrd'] = $GalObj->keywrd;
		$recordArray['keydsc'] = $GalObj->keydsc;
		$recordArray['sta_id'] = $GalObj->sta_id;
        $recordArray['imgsiz'] = $patchworks->galleryImageSizes.','.$GalObj->imgsiz;
		$jsonArray[] = $recordArray;
	}
	die(json_encode($jsonArray));

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'resort') {
    $SrtOrd = (isset($_REQUEST['gal_id'])) ? $_REQUEST['gal_id'] : NULL;

    if (!is_null($SrtOrd)) {
        $SrtOrd = explode(",",$SrtOrd);
        for ($o=0; $o<count($SrtOrd); $o++) {
            $qryArray = array();
            $sql = 'UPDATE gallery SET
				srtord = :srtord
				WHERE gal_id = :gal_id';
            $qryArray["srtord"] = $o;
            $qryArray["gal_id"] = $SrtOrd[$o];

            $recordSet = $patchworks->dbConn->prepare($sql);
            $recordSet->execute($qryArray);
        }

        $throwJSON['id'] = 0;
        $throwJSON['title'] = 'Galleries Resorted';
        $throwJSON['description'] = 'galleries resorted';
        $throwJSON['type'] = 'success';
    }
}

die(json_encode($throwJSON));