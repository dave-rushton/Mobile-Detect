<?php

require_once("../../config/config.php");
require_once("../patchworks.php");
require_once("classes/related.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
//if ($loggedIn == 0) header('location: ../login.php');


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


$Rel_ID = (isset($_REQUEST['rel_id'])) ? $_REQUEST['rel_id'] : NULL;

if (is_null($Rel_ID)) {
	$throwJSON['title'] = 'Invalid Relation';
	$throwJSON['description'] = 'Relation not found';
	$throwJSON['type'] = 'error';
}

$RelDao = new RelDAO();


if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'getrelated') {
	
	$TblNam = (isset($_REQUEST['tblnam'])) ? $_REQUEST['tblnam'] : NULL;
	$Tbl_ID = (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) ? $_REQUEST['tbl_id'] : NULL;
	$RefNam = (isset($_REQUEST['refnam'])) ? $_REQUEST['refnam'] : NULL;
    $Ref_ID = (isset($_REQUEST['ref_id']) && is_numeric($_REQUEST['ref_id'])) ? $_REQUEST['ref_id'] : NULL;

	$relatedRecs = $RelDao->select(NULL,$TblNam,$Tbl_ID,$RefNam,$Ref_ID,false);
	die(json_encode($relatedRecs));

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'relatedproducts') {

    $TblNam = (isset($_REQUEST['tblnam'])) ? $_REQUEST['tblnam'] : '';
    $Tbl_ID = (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) ? $_REQUEST['tbl_id'] : 0;
    $RefNam = (isset($_REQUEST['refnam'])) ? $_REQUEST['refnam'] : '';

    $relatedRecs = $RelDao->relatedProducts(NULL,$TblNam,$Tbl_ID,$RefNam,NULL,false);
    die(json_encode($relatedRecs));

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'relatedproducttypes') {

    $TblNam = (isset($_REQUEST['tblnam'])) ? $_REQUEST['tblnam'] : '';
    $Tbl_ID = (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) ? $_REQUEST['tbl_id'] : 0;
    $RefNam = (isset($_REQUEST['refnam'])) ? $_REQUEST['refnam'] : '';
    $relatedRecs = $RelDao->relatedProductTypes(NULL,$TblNam,$Tbl_ID,$RefNam,NULL,false);
    die(json_encode($relatedRecs));

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'relate') {
	
	$relatedIDs = (isset($_REQUEST['ref_id'])) ? explode(',',$_REQUEST['ref_id']) : NULL;
	
	$RelDao->clear($_REQUEST['tblnam'], $_REQUEST['tbl_id'], $_REQUEST['refnam']);
	
	if (is_array($relatedIDs)) {
		for ($r=0;$r<count($relatedIDs);$r++) {
			
			$RelObj = new stdClass();
		
			$RelObj->rel_id = 0;
			$RelObj->tblnam = (isset($_REQUEST['tblnam'])) ? $_REQUEST['tblnam'] : '';
			$RelObj->tbl_id = (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) ? $_REQUEST['tbl_id'] : 0;
			$RelObj->refnam = (isset($_REQUEST['refnam'])) ? $_REQUEST['refnam'] : '';
			$RelObj->ref_id = $relatedIDs[$r];
			$RelObj->reltyp = (isset($_REQUEST['reltyp'])) ? $_REQUEST['reltyp'] : '';
			$RelObj->srtord = 9999;

			$RelDao->update($RelObj);
		
		}
		
	}
	
	$throwJSON['id'] = 0;
	$throwJSON['title'] = 'Relations Created';
	$throwJSON['description'] = 'Relations created';
	$throwJSON['type'] = 'success';

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update') {

	$RelObj = $RelDao->select($Rel_ID, NULL, NULL, NULL, true);
	
	if (!$RelObj) {
		
		$RelObj = new stdClass();
		
		$RelObj->rel_id = 0;
		$RelObj->tblnam = '';
		$RelObj->tbl_id = 0;
		$RelObj->refnam = '';
		$RelObj->ref_id = 0;
		$RelObj->reltyp = '';
		$RelObj->srtord = 0;

		if (isset($_REQUEST['tblnam'])) $RelObj->tblnam = $_REQUEST['tblnam'];
		if (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) $RelObj->tbl_id = $_REQUEST['tbl_id'];
		if (isset($_REQUEST['refnam'])) $RelObj->refnam = $_REQUEST['refnam'];
		if (isset($_REQUEST['ref_id']) && is_numeric($_REQUEST['ref_id'])) $RelObj->ref_id = $_REQUEST['ref_id'];
		if (isset($_REQUEST['reltyp'])) $RelObj->reltyp = $_REQUEST['reltyp'];
		if (isset($_REQUEST['srtord'])) $RelObj->srtord = $_REQUEST['srtord'];

		$Rel_ID = $RelDao->update($RelObj);
		
		$throwJSON['id'] = $Rel_ID;
		$throwJSON['title'] = 'Relation Created';
		$throwJSON['description'] = 'Relation '.$RelObj->relnam.' created';
		$throwJSON['type'] = 'success';

		
	} else {
		
		if (isset($_REQUEST['tblnam'])) $RelObj->tblnam = $_REQUEST['tblnam'];
		if (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) $RelObj->tbl_id = $_REQUEST['tbl_id'];
		if (isset($_REQUEST['comnam'])) $RelObj->refnam = $_REQUEST['refnam'];
		if (isset($_REQUEST['comnam']) && is_numeric($_REQUEST['ref_id'])) $RelObj->ref_id = $_REQUEST['ref_id'];
		if (isset($_REQUEST['comnam'])) $RelObj->reltyp = $_REQUEST['reltyp'];
        if (isset($_REQUEST['srtord'])) $RelObj->srtord = $_REQUEST['srtord'];
		
		$Rel_ID = $RelDao->update($RelObj);
		
		$throwJSON['id'] = $Rel_ID;
		$throwJSON['title'] = 'Relation Updated';
		$throwJSON['description'] = 'Relation '.$RelObj->relnam.' updated';
		$throwJSON['type'] = 'success';
		
	}

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') {
	
	$RelObj = $RelDao->select($Rel_ID, NULL, NULL, NULL, NULL, true);
	if ($RelObj) $RelDao->delete($RelObj->rel_id);
	

	$throwJSON['id'] = $Rel_ID;
	$throwJSON['title'] = 'Relation Deleted';
	$throwJSON['description'] = 'Relation  deleted';
	$throwJSON['type'] = 'success';

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'create') {

    $RelObj = new stdClass();

    $RelObj->rel_id = 0;
    $RelObj->tblnam = (isset($_REQUEST['tblnam'])) ? $_REQUEST['tblnam'] : '';
    $RelObj->tbl_id = (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) ? $_REQUEST['tbl_id'] : 0;
    $RelObj->refnam = (isset($_REQUEST['refnam'])) ? $_REQUEST['refnam'] : '';
    $RelObj->ref_id = (isset($_REQUEST['ref_id']) && is_numeric($_REQUEST['ref_id'])) ? $_REQUEST['ref_id'] : 0;
    $RelObj->reltyp = (isset($_REQUEST['reltyp'])) ? $_REQUEST['reltyp'] : '';
    $RelObj->srtord = 9999;
    $RelObj->rel_id = $RelDao->update($RelObj);

    $throwJSON['id'] = $RelObj->rel_id;
    $throwJSON['title'] = 'Relation Created';
    $throwJSON['description'] = 'Relation created';
    $throwJSON['type'] = 'success';

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'resort') {

    $SrtOrd = (isset($_REQUEST['rel_id'])) ? $_REQUEST['rel_id'] : NULL;

    if (!is_null($SrtOrd)) {

        $SrtOrd = explode(",",$SrtOrd);

        for ($o=0; $o<count($SrtOrd); $o++) {

            $qryArray = array();
            $sql = 'UPDATE related SET
				srtord = :srtord
				WHERE rel_id = :rel_id';
            $qryArray["srtord"] = $o;
            $qryArray["rel_id"] = $SrtOrd[$o];

            $recordSet = $patchworks->dbConn->prepare($sql);
            $recordSet->execute($qryArray);

        }

        $throwJSON['id'] = 0;
        $throwJSON['title'] = 'Resorted';
        $throwJSON['description'] = 'resorted';
        $throwJSON['type'] = 'success';

    }

}

die(json_encode($throwJSON));

?>