<?php

require_once("../../config/config.php");
require_once("../patchworks.php");

require_once("classes/structure.cls.php");
require_once("../system/classes/related.cls.php");

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

    die(json_encode($throwJSON));

}

$Str_ID = (isset($_REQUEST['str_id']) && is_numeric($_REQUEST['str_id'])) ? $_REQUEST['str_id'] : die('fail');

$StrDao = new StrDAO();

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'select') {

    $structure = $StrDao->select($Str_ID, NULL, NULL, false);
    die(json_encode($structure));

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update') {


    $StrObj = $StrDao->select($Str_ID, NULL, NULL, true);

    if (!$StrObj) {

        $StrObj = new stdClass();
        $StrObj->str_id = 0;
        $StrObj->tblnam = 0;
        $StrObj->tbl_id  = 0;
        $StrObj->par_id = 0;
        $StrObj->strnam = '';
        $StrObj->seourl = '';
        $StrObj->srtord = 0;
        $StrObj->sta_id = 0;
        $StrObj->strobj = '';


        if (isset($_REQUEST['tblnam'])) $StrObj->tblnam = $_REQUEST['tblnam'];
        if (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) $StrObj->tbl_id = $_REQUEST['tbl_id'];
        if (isset($_REQUEST['par_id']) && is_numeric($_REQUEST['par_id'])) $StrObj->par_id = $_REQUEST['par_id'];
        if (isset($_REQUEST['strnam'])) $StrObj->strnam = $_REQUEST['strnam'];
        if (isset($_REQUEST['seourl'])) $StrObj->seourl = $_REQUEST['seourl'];
        if (isset($_REQUEST['srtord']) && is_numeric($_REQUEST['srtord'])) $StrObj->srtord = $_REQUEST['srtord'];
        if (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) $StrObj->sta_id = $_REQUEST['sta_id'];
        if (isset($_REQUEST['strobj'])) $StrObj->strobj = $_REQUEST['strobj'];

        $Str_ID = $StrDao->update($StrObj);

        $throwJSON['id'] = $Str_ID;
        $throwJSON['title'] = 'Structure Created';
        $throwJSON['description'] = 'Structure '.$StrObj->strnam.' created';
        $throwJSON['type'] = 'success';


    } else {

        if (isset($_REQUEST['tblnam'])) $StrObj->tblnam = $_REQUEST['tblnam'];
        if (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) $StrObj->tbl_id = $_REQUEST['tbl_id'];
        if (isset($_REQUEST['par_id']) && is_numeric($_REQUEST['par_id'])) $StrObj->par_id = $_REQUEST['par_id'];
        if (isset($_REQUEST['strnam'])) $StrObj->strnam = $_REQUEST['strnam'];
        if (isset($_REQUEST['seourl'])) $StrObj->seourl = $_REQUEST['seourl'];
        if (isset($_REQUEST['srtord']) && is_numeric($_REQUEST['srtord'])) $StrObj->srtord = $_REQUEST['srtord'];
        if (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) $StrObj->sta_id = $_REQUEST['sta_id'];
        if (isset($_REQUEST['strobj'])) $StrObj->strobj = $_REQUEST['strobj'];

        $Str_ID = $StrDao->update($StrObj);

        $throwJSON['id'] = $Str_ID;
        $throwJSON['title'] = 'Structure Record Updated';
        $throwJSON['description'] = 'Structure Record '.$StrObj->strnam.' updated';
        $throwJSON['type'] = 'success';

    }

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') {

    $StrObj = $StrDao->select($Str_ID, NULL, NULL, true);
    if ($StrObj) $StrDao->delete($StrObj->str_id);

    $throwJSON['id'] = $StrObj->str_id;
    $throwJSON['title'] = 'Structure Deleted';
    $throwJSON['description'] = 'Structure '.$StrObj->strnam.' deleted';
    $throwJSON['type'] = 'success';

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'deletestructure') {

    $StrObj = $StrDao->select($Str_ID, NULL, NULL, true);
    if ($StrObj) $StrDao->deleteStructure($StrObj->str_id);

    $throwJSON['id'] = $StrObj->str_id;
    $throwJSON['title'] = 'Structure Deleted';
    $throwJSON['description'] = 'Structure '.$StrObj->strnam.' deleted';
    $throwJSON['type'] = 'success';

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'structure') {

    $Par_ID = (isset($_REQUEST['par_id'])) ? $_REQUEST['par_id'] : NULL;
    $SeoUrl = (isset($_REQUEST['seourl'])) ? $_REQUEST['seourl'] : NULL;
    $Ele_ID = (isset($_REQUEST['ele_id'])) ? $_REQUEST['ele_id'] : NULL;
    $EleCls = (isset($_REQUEST['elecls'])) ? $_REQUEST['elecls'] : NULL;
    $EleAdm = (isset($_REQUEST['eleadm'])) ? $_REQUEST['eleadm'] : NULL;

    $StrDao->buildStructure($Par_ID, $SeoUrl, $Ele_ID, $EleCls, $EleAdm);
    die();

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'relatedproducts') {

    $TmpRel = new RelDAO();
    $related = $TmpRel->structureProducts(NULL, 'PRODUCT', NULL, 'STRUCTURE', $Str_ID, false, false);
    die(json_encode($related));

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'createcategory') {

    $StrObj = $StrDao->select($Str_ID, NULL, NULL, true);
    if ($StrObj) $StrDao->structureToCats($StrObj->str_id, 'product-category');

    $throwJSON['id'] = 0;
    $throwJSON['title'] = 'Categories Created';
    $throwJSON['description'] = 'Categories Created';
    $throwJSON['type'] = 'success';

}

die(json_encode($throwJSON));

?>