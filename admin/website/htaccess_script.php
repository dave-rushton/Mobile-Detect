<?php

require_once("../../config/config.php");
require_once("../patchworks.php");
require_once("classes/htaccess.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);

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
	
	//header('lohtaion: ../login.php');
		
	$throwJSON['title'] = 'Authorisation';
	$throwJSON['description'] = 'You are not authorised for this action';
	$throwJSON['type'] = 'error';
}


$Hta_ID = (isset($_REQUEST['hta_id']) && is_numeric($_REQUEST['hta_id'])) ? $_REQUEST['hta_id'] : NULL;

if (is_null($Hta_ID)) {
	$throwJSON['title'] = 'Invalid htAccess';
	$throwJSON['description'] = 'htAccess not found';
	$throwJSON['type'] = 'error';
}

$HtaDao = new HtaDAO();

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update' && !is_null($Hta_ID)) {

	$HtaObj = $HtaDao->select($Hta_ID, true);
	
	if (!$HtaObj) {
		
		$HtaObj = new stdClass();
		
		$HtaObj->hta_id = 0;
		$HtaObj->frmurl = '';
		$HtaObj->to_url = '';
		$HtaObj->htaobj = '';
		$HtaObj->srtord = 0;
		
		if (isset($_REQUEST['frmurl'])) $HtaObj->frmurl = $_REQUEST['frmurl'];
		if (isset($_REQUEST['to_url'])) $HtaObj->to_url = $_REQUEST['to_url'];
        if (isset($_REQUEST['htaobj'])) $HtaObj->htaobj = $_REQUEST['htaobj'];
				
		$Hta_ID = $HtaDao->update($HtaObj);
		
		$throwJSON['id'] = $Hta_ID;
		$throwJSON['title'] = 'htAccess Created';
		$throwJSON['description'] = 'htAccess '.$HtaObj->htaobj.' created';
		$throwJSON['type'] = 'success';

		
	} else {
		
		if (isset($_REQUEST['frmurl'])) $HtaObj->frmurl = $_REQUEST['frmurl'];
		if (isset($_REQUEST['to_url'])) $HtaObj->to_url = $_REQUEST['to_url'];
		if (isset($_REQUEST['htaobj'])) $HtaObj->htaobj = $_REQUEST['htaobj'];
		
		$Hta_ID = $HtaDao->update($HtaObj);
		
		$throwJSON['id'] = $Hta_ID;
		$throwJSON['title'] = 'htAccess Updated';
		$throwJSON['description'] = 'htAccess '.$HtaObj->htaobj.' updated';
		$throwJSON['type'] = 'success';
		
	}

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') {
	
	$HtaObj = $HtaDao->select($Hta_ID, true);
	if ($HtaObj) $HtaDao->delete($HtaObj->hta_id);
	

	$throwJSON['id'] = $HtaObj->hta_id;
	$throwJSON['title'] = 'htAccess Deleted';
	$throwJSON['description'] = 'htAccess '.$HtaObj->htaobj.' deleted';
	$throwJSON['type'] = 'success';
	
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'select') {
	
	if (is_numeric($Hta_ID) && $HtaObj) {

        $HtaObj = $HtaDao->select($Hta_ID, true);
        $jsonArray = array();

		$recordArray = array();
		$recordArray['hta_id'] = $HtaObj->hta_id;
		$recordArray['frmurl'] = $HtaObj->frmurl;
		$recordArray['to_url'] = $HtaObj->to_url;
		$recordArray['htaobj'] = $HtaObj->htaobj;
		$recordArray['srtord'] = $HtaObj->srtord;
		$jsonArray[] = $recordArray;

	} else {

        $jsonArray = $HtaDao->select(NULL, false);

    }
	
	die(json_encode($jsonArray));	

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'resort') {

    $SrtOrd = (isset($_GET['srtord'])) ? $_GET['srtord'] : NULL;

    if (!is_null($SrtOrd)) {

        $SrtOrd = explode(",",$SrtOrd);

        for ($o=0; $o<count($SrtOrd); $o++) {

            $qryArray = array();
            $sql = 'UPDATE htaccess SET
					srtord = '.$o.'
					WHERE hta_id = '.$SrtOrd[$o];

            $upload = $patchworks->run($sql, $qryArray, false);

        }

    }

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'updateall') {

    $htaRecs = json_decode($_REQUEST['jsondata'], true);

    for ($i=0;$i<count($htaRecs);$i++) {

        $HtaObj = $HtaDao->select($htaRecs[$i]['hta_id'], true);
        $HtaObj->frmurl = $htaRecs[$i]['frmurl'];
        $HtaObj->to_url = $htaRecs[$i]['to_url'];
        $HtaObj->htaobj = '';
        $HtaObj->srtord = $i;
        $HtaDao->update($HtaObj);

    }

    $throwJSON['id'] = 0;
    $throwJSON['title'] = 'htAccess file updated';
    $throwJSON['description'] = 'htAccess updated';
    $throwJSON['type'] = 'success';

} if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'rebuild') {

    $htAccessFile = file_get_contents($patchworks->pwRoot.'website/build_files/htaccess_top.txt');

    $htAccess = $HtaDao->select(NULL, false);

    $htAccessFile .= '##################' . PHP_EOL;
    $htAccessFile .= '##### CUSTOM #####' . PHP_EOL;
    $htAccessFile .= '##################' . PHP_EOL . PHP_EOL;

    for ($i=0;$i<count($htAccess);$i++) {

        if (!empty($htAccess[$i]['to_url'])) {

            $htAccessFile .= 'RewriteRule ^'.$htAccess[$i]['frmurl'].'$ '.$htAccess[$i]['to_url'].' [L]' . PHP_EOL;

        }
    }

    $htAccessFile .= file_get_contents($patchworks->pwRoot.'website/build_files/htaccess_bottom.txt');

    $file = $patchworks->docRoot.'.htaccess';

    $f1 = fopen($file, "w");
    fwrite($f1, $htAccessFile);
    fclose($f1);


    $throwJSON['id'] = $Hta_ID;
    $throwJSON['title'] = 'htAccess file created';
    $throwJSON['description'] = 'htAccess created';
    $throwJSON['type'] = 'success';

}

die(json_encode($throwJSON));

?>