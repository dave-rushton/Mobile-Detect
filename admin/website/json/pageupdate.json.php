<?php

require_once("../../../config/config.php");
require_once("../../patchworks.php");
require_once("../classes/pages.cls.php");
require_once("../classes/page.handler.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
//if ($loggedIn == 0) header('location: ../login.php');

$Pag_ID = (isset($_POST['pag_id']) && is_numeric($_POST['pag_id'])) ? $_POST['pag_id'] : die('FAIL');

$PagTtl = (isset($_POST['pagttl'])) ? $_POST['pagttl'] : NULL;
$TmpLte = (isset($_POST['tmplte'])) ? $_POST['tmplte'] : NULL;
$SeoUrl = (isset($_POST['seourl'])) ? $_POST['seourl'] : NULL;
$KeyWrd = (isset($_POST['keywrd'])) ? $_POST['keywrd'] : NULL;
$PagDsc = (isset($_POST['pagdsc'])) ? $_POST['pagdsc'] : NULL;
$LnkTyp = (isset($_POST['lnktyp']) && is_numeric($_POST['lnktyp'])) ? $_POST['lnktyp'] : NULL;
$TmpLte = (isset($_POST['tmplte'])) ? $_POST['tmplte'] : NULL;
$Sta_ID = (isset($_POST['sta_id']) && is_numeric($_POST['sta_id'])) ? $_POST['sta_id'] : 0;
$DefPag = (isset($_POST['defpag']) && is_numeric($_POST['defpag'])) ? $_POST['defpag'] : NULL;
$PagImg = (isset($_POST['pagimg'])) ? $_POST['pagimg'] : NULL;

$PagObj = (isset($_POST['pagobj'])) ? $_POST['pagobj'] : NULL;

$PagDao = new PagDAO();

$pageRec = $PagDao->select($Pag_ID, NULL, true);

$throwJSON = array();
$throwJSON['id'] = '0';
$throwJSON['title'] = 'noaction';
$throwJSON['description'] = 'no action taken';
$throwJSON['type'] = 'warning';

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

// set seourl
// set template

$jsonArray = array();

if ($pageRec) {

	if (!is_null($PagTtl)) $pageRec->pagttl = $PagTtl;
	if (!is_null($SeoUrl)) $pageRec->seourl = str_replace(" ", "", $SeoUrl);
	if (!is_null($KeyWrd)) $pageRec->keywrd = $KeyWrd;
	if (!is_null($PagDsc)) $pageRec->pagdsc = $PagDsc;
	if (!is_null($LnkTyp)) $pageRec->lnktyp = $LnkTyp;
	if (!is_null($TmpLte)) $pageRec->tmplte = $TmpLte;
	if (!is_null($Sta_ID)) $pageRec->sta_id = $Sta_ID;
	if (!is_null($DefPag)) $pageRec->defpag = $DefPag;
	if (!is_null($PagImg)) $pageRec->pagimg = $PagImg;
	if (!is_null($PagObj)) $pageRec->pagobj = $PagObj;

	$PagDao->update($pageRec);
		
	$throwJSON = array();
	$throwJSON['id'] = $Pag_ID;
	$throwJSON['title'] = 'Page Updated';
	$throwJSON['description'] = $pageRec->title.' page updated';
	$throwJSON['type'] = 'success';

} else {

		
	$throwJSON = array();
	$throwJSON['id'] = '0';
	$throwJSON['title'] = 'Page Not Found';
	$throwJSON['description'] = 'the page you were looking for could not be found';
	$throwJSON['type'] = 'error';
	
}


$file = 'robots.txt';
$current = "User-agent: *\n";
$current .= "Disallow: /cgi-bin/\n";
$current .= "Disallow: /admin/\n";
$current .= "Disallow: /config/\n";
$current .= "Disallow: /Facebook/\n";
$current .= "Disallow: /twitterapi/\n";

$pageHandler = new pageHandler();
$inactivePages = $pageHandler->getInactive();
for ($i=0;$i<count($inactivePages);$i++) {
    $current .= "Disallow: /".$inactivePages[$i]['seourl']."/\n";
}
file_put_contents($file, $current);

die(json_encode($throwJSON));

?>