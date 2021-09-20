<?php

require_once("../../config/config.php");
require_once("../patchworks.php");
require_once("classes/subcategories.cls.php");

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
$Sub_ID = (isset($_REQUEST['sub_id']) && is_numeric($_REQUEST['sub_id'])) ? $_REQUEST['sub_id'] : NULL;
$Sta_ID = (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) ? $_REQUEST['sta_id'] : 0;

if (is_null($Cat_ID && is_null($Sub_ID))) {
    $throwJSON['title'] = 'Invalid Category';
    $throwJSON['description'] = 'Category not found';
    $throwJSON['type'] = 'error';

    die(json_encode($throwJSON));

}

$SubDao = new SubDAO();

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update' && !is_null($Sub_ID)) {

    $SubObj = $SubDao->select(NULL, $Sub_ID, NULL, NULL, true);

    if (!$SubObj) {

        $SubObj = new stdClass();

        $SubObj->sub_id = 0;
        $SubObj->cat_id = 0;
        $SubObj->tblnam = '';
        $SubObj->tbl_id = 0;
        $SubObj->subnam = '';
        $SubObj->seourl = '';
        $SubObj->keywrd = '';
        $SubObj->keydsc = '';
        $SubObj->sta_id = 0;
        $SubObj->subtxt = '';

        if (isset($_REQUEST['sub_id']) && is_numeric($_REQUEST['sub_id'])) $SubObj->sub_id = $_REQUEST['sub_id'];
        if (isset($_REQUEST['cat_id']) && is_numeric($_REQUEST['cat_id'])) $SubObj->cat_id = $_REQUEST['cat_id'];

        if (isset($_REQUEST['subnam'])) {
            $SubObj->subnam = $_REQUEST['subnam'];
            $SubObj->tblnam = seoUrl($_REQUEST['subnam']);
            $SubObj->seourl = seoUrl($_REQUEST['subnam']);
            $SubObj->keywrd = $_REQUEST['subnam'];
            $SubObj->keydsc = $_REQUEST['subnam'];
        }

        if (isset($_REQUEST['tblnam'])) $SubObj->tblnam = $_REQUEST['tblnam'];
        if (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) $SubObj->tbl_id = $_REQUEST['tbl_id'];

        if (isset($_REQUEST['seourl'])) $SubObj->seourl = $_REQUEST['seourl'];
        if (isset($_REQUEST['keywrd'])) $SubObj->keywrd = $_REQUEST['keywrd'];
        if (isset($_REQUEST['keydsc'])) $SubObj->keydsc = $_REQUEST['keydsc'];
        if (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) $SubObj->sta_id = $_REQUEST['sta_id'];
        if (isset($_REQUEST['subtxt'])) $SubObj->subtxt = $_REQUEST['subtxt'];

        $Sub_ID = $SubDao->update($SubObj);

        $throwJSON['id'] = $Sub_ID;
        $throwJSON['title'] = 'Sub Category Created';
        $throwJSON['description'] = 'Sub Category '.$SubObj->subnam.' created';
        $throwJSON['type'] = 'success';


    } else {

        if (isset($_REQUEST['sub_id']) && is_numeric($_REQUEST['sub_id'])) $SubObj->sub_id = $_REQUEST['sub_id'];
        if (isset($_REQUEST['cat_id']) && is_numeric($_REQUEST['cat_id'])) $SubObj->cat_id = $_REQUEST['cat_id'];
        if (isset($_REQUEST['tblnam'])) $SubObj->tblnam = $_REQUEST['tblnam'];
        if (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) $SubObj->tbl_id = $_REQUEST['tbl_id'];
        if (isset($_REQUEST['subnam'])) $SubObj->subnam = $_REQUEST['subnam'];
        if (isset($_REQUEST['seourl'])) $SubObj->seourl = $_REQUEST['seourl'];
        if (isset($_REQUEST['keywrd'])) $SubObj->keywrd = $_REQUEST['keywrd'];
        if (isset($_REQUEST['keydsc'])) $SubObj->keydsc = $_REQUEST['keydsc'];
        if (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) $SubObj->sta_id = $_REQUEST['sta_id'];
        if (isset($_REQUEST['subtxt'])) $SubObj->subtxt = $_REQUEST['subtxt'];

        $Sub_ID = $SubDao->update($SubObj);

        $throwJSON['id'] = $Sub_ID;
        $throwJSON['title'] = 'Sub Category Updated';
        $throwJSON['description'] = 'Sub Category '.$SubObj->subnam.' updated';
        $throwJSON['type'] = 'success';

    }

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') {

    $SubObj = $SubDao->select(NULL, $Sub_ID, NULL, NULL, true);
    if ($SubObj) $SubDao->delete($SubObj->sub_id);

    $throwJSON['id'] = $SubObj->sub_id;
    $throwJSON['title'] = 'SubCategory Deleted';
    $throwJSON['description'] = 'SubCategory '.$SubObj->subnam.' deleted';
    $throwJSON['type'] = 'success';

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'select') {

    $SubObj = $SubDao->select(NULL, $Sub_ID, NULL, NULL, true);
    $jsonArray = array();

    if (is_numeric($Sub_ID) && $SubObj) {

        $recordArray = array();
        $recordArray['cat_id'] = $SubObj->cat_id;
        $recordArray['tblnam'] = $SubObj->tblnam;
        $recordArray['tbl_id'] = $SubObj->tbl_id;
        $recordArray['subnam'] = $SubObj->subnam;
        $recordArray['seourl'] = $SubObj->seourl;
        $recordArray['keywrd'] = $SubObj->keywrd;
        $recordArray['keydsc'] = $SubObj->keydsc;
        $recordArray['sta_id'] = $SubObj->sta_id;
        $recordArray['subtxt'] = $SubObj->subtxt;
        $jsonArray[] = $recordArray;
    }

    die(json_encode($jsonArray));

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'resort') {

    $SrtOrd = (isset($_REQUEST['sub_id'])) ? $_REQUEST['sub_id'] : NULL;

    if (!is_null($SrtOrd)) {

        $SrtOrd = explode(",",$SrtOrd);

        for ($o=0; $o<count($SrtOrd); $o++) {

            $qryArray = array();
            $sql = 'UPDATE subcategories SET
				srtord = :srtord
				WHERE sub_id = :sub_id';
            $qryArray["srtord"] = $o;
            $qryArray["sub_id"] = $SrtOrd[$o];

            $recordSet = $patchworks->dbConn->prepare($sql);
            $recordSet->execute($qryArray);

        }

        $throwJSON['id'] = 0;
        $throwJSON['title'] = 'SubCategory Resorted';
        $throwJSON['description'] = 'SubCategory  resorted';
        $throwJSON['type'] = 'success';

    }

}

die(json_encode($throwJSON));

?>