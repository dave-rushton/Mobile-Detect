<?php

require_once("../../config/config.php");
require_once("../patchworks.php");
require_once("classes/categories.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
//if ($loggedIn == 0) header('location: ../login.php');


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


$Cat_ID = (isset($_REQUEST['cat_id']) && is_numeric($_REQUEST['cat_id'])) ? $_REQUEST['cat_id'] : NULL;

if (is_null($Cat_ID)) {
	$throwJSON['title'] = 'Invalid Category';
	$throwJSON['description'] = 'Category not found';
	$throwJSON['type'] = 'error';
}

$CatDao = new CatDAO();

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update' && !is_null($Cat_ID)) {

	$CatObj = $CatDao->select($Cat_ID, NULL, NULL, NULL, true);
	
	if (!$CatObj) {
		
		$CatObj = new stdClass();
		
		$CatObj->cat_id = 0;
		$CatObj->tblnam = '';
		$CatObj->tbl_id = 0;
		$CatObj->catnam = '';
		$CatObj->seourl = '';
		$CatObj->keywrd = '';
		$CatObj->keydsc = '';
		$CatObj->sta_id = 0;
		
		
		if (isset($_REQUEST['catnam'])) {
			$CatObj->catnam = $_REQUEST['catnam'];
			$CatObj->tblnam = seoUrl($_REQUEST['catnam']);
			$CatObj->seourl = seoUrl($_REQUEST['catnam']);
			$CatObj->keywrd = $_REQUEST['catnam'];
			$CatObj->keydsc = $_REQUEST['catnam'];
		}
		
		if (isset($_REQUEST['tblnam'])) $CatObj->tblnam = $_REQUEST['tblnam'];
		if (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) $CatObj->tbl_id = $_REQUEST['tbl_id'];
		
		if (isset($_REQUEST['seourl'])) $CatObj->seourl = $_REQUEST['seourl'];
		if (isset($_REQUEST['keywrd'])) $CatObj->keywrd = $_REQUEST['keywrd'];
		if (isset($_REQUEST['keydsc'])) $CatObj->keydsc = $_REQUEST['keydsc'];
		if (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) $CatObj->sta_id = $_REQUEST['sta_id'];
				
		$Cat_ID = $CatDao->update($CatObj);
		
		$throwJSON['id'] = $Cat_ID;
		$throwJSON['title'] = 'Category Created';
		$throwJSON['description'] = 'Category '.$CatObj->catnam.' created';
		$throwJSON['type'] = 'success';

		
	} else {
		
		if (isset($_REQUEST['tblnam'])) $CatObj->tblnam = $_REQUEST['tblnam'];
		if (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) $CatObj->tbl_id = $_REQUEST['tbl_id'];
		if (isset($_REQUEST['catnam'])) $CatObj->catnam = $_REQUEST['catnam'];
		if (isset($_REQUEST['seourl'])) $CatObj->seourl = $_REQUEST['seourl'];
		if (isset($_REQUEST['keywrd'])) $CatObj->keywrd = $_REQUEST['keywrd'];
		if (isset($_REQUEST['keydsc'])) $CatObj->keydsc = $_REQUEST['keydsc'];
		if (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) $CatObj->sta_id = $_REQUEST['sta_id'];
		
		$Cat_ID = $CatDao->update($CatObj);
		
		$throwJSON['id'] = $Cat_ID;
		$throwJSON['title'] = 'Category Updated';
		$throwJSON['description'] = 'Category '.$CatObj->catnam.' updated';
		$throwJSON['type'] = 'success';
		
	}

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') {
	
	$CatObj = $CatDao->select($Cat_ID, NULL, NULL, NULL, true);
	if ($CatObj) $CatDao->delete($CatObj->cat_id);
	

	$throwJSON['id'] = $CatObj->cat_id;
	$throwJSON['title'] = 'Category Deleted';
	$throwJSON['description'] = 'Category '.$CatObj->catnam.' deleted';
	$throwJSON['type'] = 'success';
	
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'select') {

	$CatObj = $CatDao->select($Cat_ID, NULL, NULL, NULL, true);
	$jsonArray = array();
	
	if (is_numeric($Cat_ID) && $CatObj) {
		
		$recordArray = array();
		$recordArray['cat_id'] = $CatObj->cat_id;
		$recordArray['tblnam'] = $CatObj->tblnam;
		$recordArray['tbl_id'] = $CatObj->tbl_id;
		$recordArray['catnam'] = $CatObj->catnam;
		$recordArray['seourl'] = $CatObj->seourl;
		$recordArray['keywrd'] = $CatObj->keywrd;
		$recordArray['keydsc'] = $CatObj->keydsc;
		$recordArray['sta_id'] = $CatObj->sta_id;
		$jsonArray[] = $recordArray;
	}
	
	die(json_encode($jsonArray));	

}

die(json_encode($throwJSON));

?>