<?php

require_once("../../../config/config.php");
require_once("../../patchworks.php");
require_once("../../website/classes/pagecontent.cls.php");

//$userAuth = new AuthDAO();
//$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
//
//if ($loggedIn == 0) header('location: ../login.php');

//print_r($_POST);
//print_r($_GET);

$Pgc_ID = (isset($_POST['pgc_id'])) ? $_POST['pgc_id'] : die('FAIL');

$EleDao = new PgcDAO();
 
if (isset($_POST['action']) && $_POST['action'] == 'select') {
	
	
	
} else if (isset($_POST['action']) && $_POST['action'] == 'update') {
	
	$EleObj = $EleDao->select($Pgc_ID, NULL, NULL, true);
	
	if (!$EleObj) {
		
		$EleObj = new stdClass();
        $EleObj->pgcttl = '';
		$EleObj->pgc_id = 0;
		$EleObj->pgctxt = '';
		$EleObj->sta_id = 0;
        $EleObj->tblnam = '';
        $EleObj->tbl_id = 0;
        $EleObj->pgcobj = '';
        $EleObj->srtord = 0;

		$EleObj->pgcttl = (isset($_POST['pgcttl'])) ? $_POST['pgcttl'] : $EleObj->pgcttl;
		$EleObj->pgctxt = (isset($_POST['pgctxt'])) ? htmlspecialchars($_POST['pgctxt'], ENT_QUOTES) : $EleObj->pgctxt;
		$EleObj->sta_id = (isset($_POST['sta_id']) && is_numeric($_POST['sta_id'])) ? $_POST['sta_id'] : $EleObj->sta_id;

        $EleObj->tblnam = (isset($_POST['tblnam'])) ? htmlspecialchars($_POST['tblnam'], ENT_QUOTES) : $EleObj->tblnam;
        $EleObj->tbl_id = (isset($_POST['tbl_id']) && is_numeric($_POST['tbl_id'])) ? $_POST['tbl_id'] : $EleObj->tbl_id;

        $EleObj->srtord = (isset($_POST['srtord']) && is_numeric($_POST['srtord'])) ? $_POST['srtord'] : $EleObj->srtord;

        $EleObj->pgcobj = (isset($_POST['pgcobj'])) ? $_POST['pgcobj'] : $EleObj->pgcobj;

		$Pgc_ID = $EleDao->update($EleObj);
		
	} else {
		
		$EleObj->pgcttl = (isset($_POST['pgcttl'])) ? $_POST['pgcttl'] : $EleObj->pgcttl;
		$EleObj->pgctxt = (isset($_POST['pgctxt'])) ? htmlspecialchars($_POST['pgctxt'], ENT_QUOTES) : $EleObj->pgctxt;
		$EleObj->sta_id = (isset($_POST['sta_id']) && is_numeric($_POST['sta_id'])) ? $_POST['sta_id'] : $EleObj->sta_id;

        $EleObj->tblnam = (isset($_POST['tblnam'])) ? htmlspecialchars($_POST['tblnam'], ENT_QUOTES) : $EleObj->tblnam;
        $EleObj->tbl_id = (isset($_POST['tbl_id']) && is_numeric($_POST['tbl_id'])) ? $_POST['tbl_id'] : $EleObj->tbl_id;

        $EleObj->srtord = (isset($_POST['srtord']) && is_numeric($_POST['srtord'])) ? $_POST['srtord'] : $EleObj->srtord;

        $EleObj->pgcobj = (isset($_POST['pgcobj'])) ? $_POST['pgcobj'] : $EleObj->pgcobj;
		
		$Pgc_ID = $EleDao->update($EleObj);
	
	}

} else if (isset($_POST['action']) && $_POST['action'] == 'delete') {
	$EleObj = $EleDao->select($Pgc_ID, NULL, NULL, true);
	if ($EleObj) $EleDao->delete($EleObj->pgc_id);
	
} else if (isset($_POST['action']) && $_POST['action'] == 'resort') {

    $SrtOrd = (isset($_REQUEST['pgc_id'])) ? $_REQUEST['pgc_id'] : NULL;

    if (!is_null($SrtOrd)) {

        $SrtOrd = explode(",",$SrtOrd);

        for ($o=0; $o<count($SrtOrd); $o++) {

            $qryArray = array();
            $sql = 'UPDATE pagecontent SET
				srtord = :srtord
				WHERE pgc_id = :pgc_id';
            $qryArray["srtord"] = $o;
            $qryArray["pgc_id"] = $SrtOrd[$o];

            $recordSet = $patchworks->dbConn->prepare($sql);
            $recordSet->execute($qryArray);

        }

        $throwJSON['id'] = 0;
        $throwJSON['title'] = 'Resorted';
        $throwJSON['description'] = 'resorted';
        $throwJSON['type'] = 'success';

    }


}

die($Pgc_ID);

?>